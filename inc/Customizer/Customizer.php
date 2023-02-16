<?php
/**
 * Customizer.
 */

namespace Vite\Customizer;

defined( 'ABSPATH' ) || exit;

use Vite\DynamicCSS;
use Vite\Traits\{JSON, Mods, Hook};
use Vite\Customizer\Type\{Panel, Section, Control};
use WP_Customize_Cropped_Image_Control;
use WP_Customize_Manager;

/**
 * Customizer class.
 */
class Customizer {

	use Mods, JSON, Hook;

	const STORE      = 'theme_mod';
	const CAPABILITY = 'edit_theme_options';
	const PRIORITY   = 10;
	const TRANSPORT  = 'postMessage';

	/**
	 * Holds all settings.
	 *
	 * @var array Settings.
	 */
	private $settings = [];

	/**
	 * Holds all sections.
	 *
	 * @var array Sections.
	 */
	private $sections = [];

	/**
	 * Holds all panels.
	 *
	 * @var array Panels.
	 */
	private $panels = [];

	/**
	 * Print css.
	 *
	 * @var bool $print_css
	 */
	private $print_css = false;

	/**
	 * Holds google fonts.
	 *
	 * @var array|null
	 */
	private $google_fonts;

	/**
	 * Holds control condition.
	 *
	 * @var array
	 */
	private $condition = [];

	/**
	 * Hold control conditions.
	 *
	 * @var array
	 */
	private $conditions = [];

	/**
	 * DynamicCSS.
	 *
	 * @var null|DynamicCSS
	 */
	public $dynamic_css = null;

	/**
	 * Sanitize.
	 *
	 * @var null|Sanitize
	 */
	private $sanitize;

	/**
	 * Constructor.
	 *
	 * @param DynamicCSS $dynamic_css Instance of DynamicCSS.
	 * @param Sanitize   $sanitize Instance of DynamicCSS.
	 */
	public function __construct( DynamicCSS $dynamic_css, Sanitize $sanitize ) {
		$this->dynamic_css = $dynamic_css;
		$this->sanitize    = $sanitize;
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'after_wp_init' ], PHP_INT_MAX );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_register', [ $this, 'override_controls' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_control_script' ] );
		add_filter( 'customize_render_partials_response', [ $this, 'partial_response' ] );
		add_action( 'wp_print_scripts', [ $this, 'print_dynamic_css' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_script' ] );
		add_action( 'customize_save_after', [ $this, 'after_save' ] );
		add_filter( 'customizer_widgets_section_args', [ $this, 'modify_widgets_panel' ], 10, 3 );
		add_filter( 'customize_section_active', array( $this, 'modify_widgets_section_state' ), 100, 2 );
		add_action( 'wp_ajax_vite-reset-customizer', array( $this, 'reset_settings' ) );
	}

	/**
	 * After customizer save.
	 *
	 * @param WP_Customize_Manager $wp_customize Instance of WP_Customize_Manager.
	 * @return void
	 */
	public function after_save( WP_Customize_Manager $wp_customize ) {
		$this->dynamic_css->save();
		$this->sync( $wp_customize );
	}

	/**
	 * Reset settings.
	 *
	 * @return void
	 */
	public function reset_settings() {
		check_ajax_referer( 'vite-reset', 'nonce' );
		global $wp_customize;

		if ( ! $wp_customize || ! $wp_customize->is_preview() ) {
			wp_send_json_error();
		}

		$settings = $wp_customize->settings();

		$this->action( 'customizer-reset/before' );

		foreach ( $settings as $setting ) {
			remove_theme_mod( $setting->id );
		}

		remove_theme_mod( 'vite' );

		$this->dynamic_css->save(); // Save dynamic css.
		$this->action( 'local-fonts/cleanup' ); // Trigger action to clean up local fonts.
		$this->action( 'customizer-reset/after' );

		wp_send_json_success();
	}

	/**
	 * Filters response of WP_Customize_Section::active().
	 *
	 * @param bool  $active Whether the Customizer section is active.
	 * @param mixed $section WP_Customize_Section instance.
	 * @return bool
	 */
	public function modify_widgets_section_state( bool $active, $section ): bool {
		if (
			str_contains( $section->id, 'header-widget-' ) ||
			str_contains( $section->id, 'footer-widget-' )
		) {
			$active = true;
		}
		return $active;
	}

	/**
	 * Modify widgets panel.
	 *
	 * @param array      $section_args Array of Customizer widget section arguments.
	 * @param string     $section_id   Customizer section ID.
	 * @param int|string $sidebar_id   Sidebar ID.
	 */
	public function modify_widgets_panel( array $section_args, string $section_id, $sidebar_id ): array {
		$footer_widgets = [
			'footer-widget-1',
			'footer-widget-2',
			'footer-widget-3',
			'footer-widget-4',
			'footer-widget-5',
			'footer-widget-6',
		];
		$header_widgets = [
			'header-widget-1',
			'header-widget-2',
		];

		if ( in_array( $sidebar_id, $footer_widgets, true ) ) {
			$section_args['panel'] = 'vite[footer-builder]';
		}

		if ( in_array( $sidebar_id, $header_widgets, true ) ) {
			$section_args['panel'] = 'vite[header-builder]';
		}

		return $section_args;
	}

	/**
	 * Sync menus.
	 *
	 * Sync menu across default WP settings and custom settings
	 *
	 * @param WP_Customize_Manager $wp_customize Instance of WP_Customize_Manager.
	 * @return void
	 */
	private function sync( WP_Customize_Manager $wp_customize ) {
		$action = 'save-customize_' . $wp_customize->get_stylesheet();
		if (
			! check_ajax_referer( $action, 'nonce', false ) ||
			! isset( $_POST['customized'] )
		) {
			wp_send_json_error( 'invalid_nonce' );
		}

		$customized = json_decode( sanitize_textarea_field( wp_unslash( $_POST['customized'] ) ), true );

		if ( ! $customized ) {
			return;
		}

		// Sync menus.
		$menu1     = ( $customized['nav_menu_locations[menu-1]'] ?? ( $customized['vite[header-menu-1]'] ?? null ) );
		$menu2     = ( $customized['nav_menu_locations[menu-2]'] ?? ( $customized['vite[header-menu-2]'] ?? null ) );
		$menu3     = ( $customized['nav_menu_locations[menu-3]'] ?? ( $customized['vite[header-menu-3]'] ?? null ) );
		$locations = get_theme_mod( 'nav_menu_locations' );
		$vite_mods = get_theme_mod( 'vite' );

		if ( ! empty( array_filter( [ $menu1, $menu2, $menu3 ] ) ) ) {
			foreach ( range( 1, 3 ) as $i ) {
				$menu = absint( ${"menu$i"} );
				if ( $menu ) {
					$locations[ "menu-$i" ]        = $menu;
					$vite_mods[ "header-menu-$i" ] = $menu;
				} else {
					unset( $locations[ "menu-$i" ] );
					unset( $vite_mods[ "header-menu-$i" ] );
				}
				set_theme_mod( 'nav_menu_locations', $locations );
				set_theme_mod( 'vite', $vite_mods );
			}
		}

		$this->sync_fonts( $customized );
	}

	/**
	 * Sync fonts.
	 *
	 * @param mixed $customized Customized data.
	 * @return void
	 */
	private function sync_fonts( $customized ) {
		if ( $this->get_mod( 'local-google-fonts' ) ) {
			$fonts_url = $this->get_mod( 'google-fonts-url' );
			foreach ( array_keys( $customized ) as $key ) {
				if ( str_contains( $key, 'vite' ) && str_contains( $key, 'typography' ) ) {
					$this->action( 'local-fonts/cleanup' ); // Delete fonts folder on customize save.
					vite( 'performance' )->local_font->get( $fonts_url ); // Download fonts locally.
					break;
				}
			}
		}
	}

	/**
	 * After WP init.
	 *
	 * @return void
	 */
	public function after_wp_init() {
		$this->include();
		$this->dynamic_css->init( $this->settings );
	}

	/**
	 * Include files.
	 *
	 * @return void
	 */
	private function include() {
		require __DIR__ . '/options/options.php';
		require __DIR__ . '/panels-sections/panels-sections.php';
	}

	/**
	 * Register customizer settings.
	 *
	 * @param array $response Response.
	 * @return array
	 */
	public function partial_response( array $response ): array {
		$response['_VITE_DYNAMIC_CSS_'] = $this->dynamic_css->get( false );
		return $response;
	}

	/**
	 * Print dynamic CSS.
	 *
	 * @return void
	 */
	public function print_dynamic_css() {
		if ( ! is_admin() || $this->print_css ) {
			return;
		}

		$this->print_css = true;
	}

	/**
	 * Override default controls.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function override_controls( WP_Customize_Manager $wp_customize ) {
		$control_blogname        = $wp_customize->get_control( 'blogname' );
		$control_blogdescription = $wp_customize->get_control( 'blogdescription' );
		$control_custom_logo     = $wp_customize->get_control( 'custom_logo' );
		$logo_section            = 'vite[header-logo]';

		$control_custom_logo->priority = 1;
		$control_custom_logo->section  = $logo_section;

		$control_blogname->type     = 'vite-input';
		$control_blogname->section  = $logo_section;
		$control_blogname->priority = 3;

		$control_blogdescription->type     = 'vite-input';
		$control_blogdescription->section  = $logo_section;
		$control_blogdescription->priority = 4;

		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'custom_logo' )->transport     = 'postMessage';

		$render_callback = function() {
			get_template_part( 'template-parts/builder-elements/logo', '' );
		};

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			[
				'selector'            => '.site-branding',
				'settings'            => [ 'custom_logo' ],
				'container_inclusive' => true,
				'render_callback'     => $render_callback,
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			[
				'selector'            => '.vite-brand',
				'settings'            => [ 'blogname' ],
				'container_inclusive' => true,
				'render_callback'     => $render_callback,
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			[
				'selector'            => '.vite-brand',
				'settings'            => [ 'blogdescription' ],
				'container_inclusive' => true,
				'render_callback'     => $render_callback,
			]
		);
	}

	/**
	 * Enqueue control script.
	 *
	 * @return void
	 */
	public function enqueue_control_script() {
		wp_enqueue_media();
		wp_enqueue_editor();
		wp_enqueue_script( 'vite-customizer' );
		wp_enqueue_style( 'vite-customizer' );

		$this->condition['blogdescription'] = [
			'vite[header-site-branding-elements]' => 'logo-title-description',
		];

		$this->condition['blogname'] = [
			'vite[header-site-branding-elements]!' => 'logo',
		];

		wp_localize_script(
			'vite-customizer',
			'_VITE_CUSTOMIZER_',
			[
				'icons'      => vite( 'icon' )->get_icons(),
				'condition'  => $this->filter( 'customizer/condition', $this->condition ),
				'conditions' => $this->filter( 'customizer/conditions', $this->conditions ),
				'publicPath' => VITE_ASSETS_URI . 'dist/',
				'resetNonce' => wp_create_nonce( 'vite-reset' ),
				'ajaxURL'    => admin_url( 'admin-ajax.php' ),
			]
		);
		wp_set_script_translations( 'vite-customizer', 'vite', get_template_directory() . '/languages' );
	}

	/**
	 * Enqueue preview script.
	 *
	 * @return void
	 */
	public function enqueue_preview_script() {
		wp_enqueue_script( 'vite-customizer-preview' );
		wp_localize_script(
			'vite-customizer-preview',
			'_VITE_CUSTOMIZER_PREVIEW_',
			[
				'configs' => $this->dynamic_css->get_config(),
			]
		);
		wp_enqueue_style( 'vite-customizer-preview' );
		wp_add_inline_style( 'vite-customizer-preview', 'html, body, .site { height: 100% } ' );
	}

	/**
	 * Customizer register.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		$this->register_type( $wp_customize );
		$this->add_panels( $wp_customize );
		$this->add_sections( $wp_customize );
		$this->add_settings( $wp_customize );
	}

	/**
	 * Add panels.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function add_panels( WP_Customize_Manager $wp_customize ) {
		$panels = $this->filter( 'customizer/panels', $this->panels );
		if ( ! empty( $panels ) ) {
			foreach ( $panels as $id => $config ) {
				$wp_customize->add_panel(
					new Panel(
						$wp_customize,
						$id,
						wp_parse_args(
							$config,
							[
								'type'       => 'vite-panel',
								'priority'   => static::PRIORITY,
								'capability' => static::CAPABILITY,
							]
						)
					)
				);
			}
		}
	}

	/**
	 * Add sections.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function add_sections( WP_Customize_Manager $wp_customize ) {
		$sections = $this->filter( 'customizer/sections', $this->sections );
		if ( ! empty( $sections ) ) {
			foreach ( $sections as $id => $config ) {
				$wp_customize->add_section(
					new Section(
						$wp_customize,
						$id,
						wp_parse_args(
							$config,
							[
								'type'       => 'vite-section',
								'priority'   => static::PRIORITY,
								'capability' => static::CAPABILITY,
							]
						)
					)
				);
			}
		}
	}

	/**
	 * Add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function add_settings( WP_Customize_Manager $wp_customize ) {
		$settings = $this->filter( 'customizer/settings', $this->settings );

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $id => $config ) {
				if ( ! isset( $config['type'] ) ) {
					continue;
				}

				$selective_refresh   = isset( $config['partial'] );
				$render_callback     = $config['partial']['render_callback'] ?? null;
				$container_inclusive = $config['partial']['container_inclusive'] ?? false;
				$selector            = $config['partial']['selector'] ?? '';

				if ( $selective_refresh ) {
					if ( isset( $wp_customize->selective_refresh ) ) {
						$wp_customize->selective_refresh->add_partial(
							$id,
							[
								'selector'            => $selector,
								'render_callback'     => $render_callback,
								'container_inclusive' => $container_inclusive,
							]
						);
					}
				}

				if (
					! array_diff( [ 'condition', 'conditions' ], array_keys( $config ) ) ||
					in_array( 'conditions', array_keys( $config ), true )
				) {
					$this->conditions[ $id ] = $config['conditions'];
				} elseif ( in_array( 'condition', array_keys( $config ), true ) ) {
					$this->condition[ $id ] = $config['condition'];
				}

				$control_setting_config = [
					'default'           => $config['default'] ?? '',
					'transport'         => $config['transport'] ?? static::TRANSPORT,
					'type'              => static::STORE,
					'sanitize_callback' => $this->get_sanitize_callback( (string) $config['type'] ),
				];

				$wp_customize->add_setting(
					$id,
					$control_setting_config
				);

				if ( 'image' === $config['type'] ) {
					$wp_customize->add_control(
						new WP_Customize_Cropped_Image_Control(
							$wp_customize,
							$id,
							[
								'label'         => $config['title'] ?? '',
								'section'       => $config['section'] ?? '',
								'priority'      => $config['priority'] ?? static::PRIORITY,
								'height'        => $config['height'] ?? null,
								'width'         => $config['width'] ?? null,
								'flex_height'   => true,
								'flex_width'    => true,
								'button_labels' => array(
									'select'       => __( 'Select logo', 'vite' ),
									'change'       => __( 'Change logo', 'vite' ),
									'remove'       => __( 'Remove', 'vite' ),
									'default'      => __( 'Default', 'vite' ),
									'placeholder'  => __( 'No logo selected', 'vite' ),
									'frame_title'  => __( 'Select logo', 'vite' ),
									'frame_button' => __( 'Choose logo', 'vite' ),
								),
							]
						)
					);
					continue;
				}

				$control_config = [
					'label'       => $config['title'] ?? '',
					'title'       => $config['title'] ?? '',
					'description' => $config['description'] ?? '',
					'section'     => $config['section'] ?? '',
					'settings'    => $id,
					'type'        => $config['type'],
					'choices'     => $config['choices'] ?? [],
					'priority'    => $config['priority'] ?? static::PRIORITY,
					'capability'  => static::CAPABILITY,
					'input_attrs' => $config['input_attrs'] ?? [],
					'fonts'       => 'vite-typography' === $config['type'] ? $this->get_google_fonts() : null,
				];

				$wp_customize->add_control(
					new Control(
						$wp_customize,
						$id,
						$control_config
					)
				);

				$sub_controls = $config['input_attrs']['sub_controls'] ?? null;
				if ( isset( $sub_controls ) ) {
					foreach ( $sub_controls as $sub_id => $sub_control_config ) {
						$wp_customize->add_setting(
							$sub_id,
							$control_setting_config
						);
						$wp_customize->add_control(
							new Control(
								$wp_customize,
								$sub_id,
								wp_parse_args(
									[
										'type' => 'vite-hidden',
									],
									$control_config
								)
							)
						);
					}
				}
			}
		}
	}

	/**
	 * Add sections, panels, settings.
	 *
	 * @param string $type Type.
	 * @param array  $options Options.
	 * @return void
	 */
	public function add( string $type, array $options ) {
		if ( ! in_array( $type, [ 'panels', 'sections', 'settings' ], true ) ) {
			return;
		}

		foreach ( $options as $key => $option ) {
			$this->{$type}[ $key ] = $option;
		}
	}

	/**
	 * Register custom panel, section and control.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function register_type( WP_Customize_Manager $wp_customize ) {
		$wp_customize->register_panel_type( Panel::class );
		$wp_customize->register_section_type( Section::class );
		$wp_customize->register_control_type( Control::class );
	}

	/**
	 * Get google fonts.
	 *
	 * @return array
	 */
	private function get_google_fonts(): array {
		if ( ! isset( $this->google_fonts ) ) {
			$this->google_fonts = $this->json_to_array( VITE_ASSETS_DIR . 'json/google-fonts.json' );
		}
		return $this->filter( 'customizer/google-fonts', $this->google_fonts );
	}

	/**
	 * Get control configs.
	 *
	 * @param string $type Type.
	 *
	 * @return callable|null
	 */
	private function get_sanitize_callback( string $type ) {
		switch ( $type ) {
			case 'vite-background':
				return [ $this->sanitize, 'sanitize_background' ];
			case 'vite-typography':
				return [ $this->sanitize, 'sanitize_typography' ];
			case 'vite-dimensions':
				return [ $this->sanitize, 'sanitize_dimensions' ];
			case 'vite-buttonset':
				return [ $this->sanitize, 'sanitize_buttonset' ];
			case 'vite-sortable':
				return [ $this->sanitize, 'sanitize_sortable' ];
			case 'vite-checkbox':
			case 'vite-toggle':
				return [ $this->sanitize, 'sanitize_checkbox' ];
			case 'vite-slider':
				return [ $this->sanitize, 'sanitize_slider' ];
			case 'vite-color':
				return [ $this->sanitize, 'sanitize_color' ];
			case 'vite-radio-image':
			case 'vite-select':
			case 'select':
			case 'vite-radio':
			case 'radio':
				return [ $this->sanitize, 'sanitize_radio' ];
			case 'vite-input':
			case 'vite-text':
			case 'vite-gradient':
			case 'text':
				return [ $this->sanitize, 'sanitize_input' ];
			case 'vite-textarea':
			case 'textarea':
				return 'sanitize_textarea_field';
			case 'url':
				return 'esc_url_raw';
			case 'vite-editor':
				return 'wp_kses_post';
			case 'email':
				return 'sanitize_email';
			default:
				return null;
		}
	}
}
