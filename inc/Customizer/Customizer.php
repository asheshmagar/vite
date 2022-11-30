<?php
/**
 * Customizer.
 */

namespace Vite\Customizer;

use Vite\Customizer\Type\Control;
use WP_Customize_Cropped_Image_Control;
use WP_Customize_Manager;
use Vite\Customizer\Type\Panel;
use Vite\Customizer\Type\Section;
use Vite\DynamicCSS;

/**
 * Customizer class.
 */
class Customizer {

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
	 * @var array
	 */
	private $google_fonts = [];

	/**
	 * Holds setting defaults.
	 *
	 * @var array
	 */
	private $defaults;

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
	protected $sanitize = null;

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
		add_action( 'customize_save_after', [ $this, 'save_dynamic_css' ] );
		add_filter( 'customize_render_partials_response', [ $this, 'partial_response' ] );
		add_action( 'wp_print_scripts', [ $this, 'print_dynamic_css' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_script' ] );
		add_action( 'customize_save_after', [ $this, 'sync_menus' ] );
	}

	/**
	 * Sync menus.
	 *
	 * Sync menu across default WP settings and custom settings
	 *
	 * @param WP_Customize_Manager $wp_customize Instance of WP_Customize_Manager.
	 * @return void
	 */
	public function sync_menus( WP_Customize_Manager $wp_customize ) {
		$action = 'save-customize_' . $wp_customize->get_stylesheet();
		if ( ! check_ajax_referer( $action, 'nonce', false ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		if ( ! isset( $_POST['customized'] ) ) {
			return;
		}

		$customized = json_decode( sanitize_textarea_field( wp_unslash( $_POST['customized'] ) ), true );

		if ( ! $customized ) {
			return;
		}

		$primary   = ( $customized['nav_menu_locations[primary]'] ?? ( $customized['vite[header-primary-menu]'] ?? null ) );
		$secondary = ( $customized['nav_menu_locations[secondary]'] ?? ( $customized['vite[header-secondary-menu]'] ?? null ) );
		$locations = get_theme_mod( 'nav_menu_locations' );
		$vite_mods = get_theme_mod( 'vite' );

		if ( isset( $primary ) || isset( $secondary ) ) {
			$primary   = absint( $primary );
			$secondary = absint( $secondary );

			if ( $primary ) {
				$locations['primary']             = $primary;
				$vite_mods['header-primary-menu'] = $primary;
			} else {
				unset( $locations['primary'] );
				unset( $vite_mods['header-primary-menu'] );
			}

			if ( $secondary ) {
				$locations['secondary']             = $secondary;
				$vite_mods['header-secondary-menu'] = $secondary;
			} else {
				unset( $locations['secondary'] );
				unset( $vite_mods['header-secondary-menu'] );
			}

			set_theme_mod( 'nav_menu_locations', $locations );
			set_theme_mod( 'vite', $vite_mods );
		}
	}

	/**
	 * After WP init.
	 *
	 * @return void
	 */
	public function after_wp_init() {
		$this->include();
		$this->dynamic_css->init_css_data( $this->settings );
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
		try {
			$response['viteDynamicCSS'] = vite( 'dynamic-css' )->make()->get();
		} catch ( \Exception $e ) {} // phpcs:ignore
		return $response;
	}

	/**
	 * Get settings defaults.
	 *
	 * @return mixed|void
	 */
	public function get_defaults() {
		if ( ! isset( $this->defaults ) ) {
			$this->defaults = require __DIR__ . '/defaults/defaults.php';
		}
		return vite( 'core' )->filter( 'customizer/defaults', $this->defaults );
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
		try {
			$css = vite( 'dynamic-css' )->make()->get();
			// echo '<style id="vite-dynamic-css">' . $css . '</style>'; // phpcs:ignore
		} catch ( \Exception $e ) {} // phpcs:ignore
	}

	/**
	 * Get setting.
	 *
	 * @param string $key Setting key.
	 * @param mixed  $default Default.
	 * @return mixed|string
	 */
	public function get_setting( string $key = '', $default = false ) {
		$settings = get_theme_mod( 'vite' );
		$defaults = $this->get_defaults();
		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		if ( isset( $defaults[ $key ] ) ) {
			return $defaults[ $key ];
		}

		return $default;
	}

	/**
	 * Get all settings.
	 *
	 * @return array
	 */
	public function get_settings(): array {
		return $this->settings;
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

		$control_custom_logo->priority = 1;
		$control_custom_logo->section  = 'vite[header-logo]';

		$control_blogname->type     = 'vite-input';
		$control_blogname->section  = 'vite[header-logo]';
		$control_blogname->priority = 3;

		$control_blogdescription->type     = 'vite-input';
		$control_blogdescription->section  = 'vite[header-logo]';
		$control_blogdescription->priority = 4;

		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'custom_logo' )->transport     = 'postMessage';

		$render_callback = function() {
			get_template_part( 'template-parts/header/header', 'logo' );
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
				'selector'            => '.site-branding',
				'settings'            => [ 'blogname' ],
				'container_inclusive' => true,
				'render_callback'     => $render_callback,
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			[
				'selector'            => '.site-branding',
				'settings'            => [ 'blogdescription' ],
				'container_inclusive' => true,
				'render_callback'     => $render_callback,
			]
		);
	}

	/**
	 * Save dynamic CSS.
	 *
	 * @return void
	 */
	public function save_dynamic_css() {
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
				'condition'  => vite( 'core' )->filter( 'customizer/condition', $this->condition ),
				'conditions' => vite( 'core' )->filter( 'customizer/condition', $this->conditions ),
				'publicPath' => vite( 'core' )->get_public_path(),
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
				'settings' => $this->settings,
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
		$panels = vite( 'core' )->filter( 'customizer/panels', $this->panels );
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
		$sections = vite( 'core' )->filter( 'customizer/sections', $this->sections );
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
		$settings           = vite( 'core' )->filter( 'customizer/settings', $this->settings );
		$sanitize_callbacks = vite( 'core' )->filter( 'customizer/sanitize/callbacks', $this->sanitize_callbacks() );
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

				$wp_customize->add_setting(
					$id,
					[
						'default'           => $config['default'] ?? '',
						'transport'         => $config['transport'] ?? static::TRANSPORT,
						'type'              => static::STORE,
						'sanitize_callback' => $sanitize_callbacks[ $config['type'] ] ?? null,
					]
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

				$wp_customize->add_control(
					new Control(
						$wp_customize,
						$id,
						[
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
							'selectors'   => $config['selectors'] ?? null,
							'properties'  => $config['properties'] ?? null,
						]
					)
				);
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
		$fonts_json = VITE_ASSETS_DIR . 'json/google-fonts.json';
		if ( file_exists( $fonts_json ) && empty( $this->google_fonts ) ) {
			ob_start();
			include_once $fonts_json;
			$this->google_fonts = array_merge(
				[
					[
						'family'     => 'Default',
						'variants'   => [
							'regular',
							'100',
							'200',
							'300',
							'400',
							'500',
							'600',
							'700',
							'800',
							'900',
						],
						'value'      => 'default',
						'defVariant' => 'regular',
						'id'         => 'default',
						'label'      => 'Default',
					],
					[
						'family'     => 'Inherit',
						'variants'   => [
							'regular',
							'100',
							'200',
							'300',
							'400',
							'500',
							'600',
							'700',
							'800',
							'900',
						],
						'value'      => 'inherit',
						'defVariant' => 'regular',
						'id'         => 'inherit',
						'label'      => 'Inherit',
					],
				],
				json_decode( ob_get_clean(), true )
			);
		}
		return vite( 'core' )->filter( 'customizer/google-fonts', $this->google_fonts );
	}

	/**
	 * Get control configs.
	 *
	 * @return array
	 */
	private function sanitize_callbacks(): array {
		return [
			'vite-background'     => [ $this->sanitize, 'sanitize_background' ],
			'vite-typography'     => [ $this->sanitize, 'sanitize_typography' ],
			'vite-dimensions'     => [ $this->sanitize, 'sanitize_dimensions' ],
			'vite-buttonset'      => [ $this->sanitize, 'sanitize_buttonset' ],
			'vite-sortable'       => [ $this->sanitize, 'sanitize_sortable' ],
			'vite-checkbox'       => [ $this->sanitize, 'sanitize_checkbox' ],
			'vite-toggle'         => [ $this->sanitize, 'sanitize_checkbox' ],
			'vite-slider'         => [ $this->sanitize, 'sanitize_slider' ],
			'vite-color'          => [ $this->sanitize, 'sanitize_color' ],
			'vite-radio-image'    => [ $this->sanitize, 'sanitize_radio' ],
			'vite-select'         => [ $this->sanitize, 'sanitize_radio' ],
			'select'              => [ $this->sanitize, 'sanitize_radio' ],
			'vite-radio'          => [ $this->sanitize, 'sanitize_radio' ],
			'radio'               => [ $this->sanitize, 'sanitize_radio' ],
			'vite-input'          => [ $this->sanitize, 'sanitize_input' ],
			'vite-border'         => [ $this->sanitize, 'sanitize_border' ],
			'number'              => [ $this->sanitize, 'sanitize_int' ],
			'vite-textarea'       => 'sanitize_textarea_field',
			'textarea'            => 'sanitize_textarea_field',
			'text'                => 'sanitize_text_field',
			'vite-gradient'       => 'sanitize_text_field',
			'email'               => 'sanitize_email',
			'url'                 => 'esc_url_raw',
			'vite-editor'         => 'wp_kses_post',
			'vite-hidden'         => null,
			'vite-group'          => null,
			'vite-title'          => null,
			'vite-builder'        => null,
			'vite-header-builder' => null,
			'vite-heading'        => null,
			'vite-navigate'       => null,
		];
	}
}
