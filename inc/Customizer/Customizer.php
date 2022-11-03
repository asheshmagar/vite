<?php
/**
 * Customizer.
 */

namespace Vite\Customizer;

use Vite\Customizer\Type\Control;
use WP_Customize_Manager;
use Vite\Customizer\Type\Panel;
use Vite\Customizer\Type\Section;

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
	}

	/**
	 * After WP init.
	 *
	 * @return void
	 */
	public function after_wp_init() {
		$this->include();
		do_action( 'vite_customizer_init' );
	}

	/**
	 * Include files.
	 *
	 * @return void
	 */
	private function include() {
		$options_dir       = __DIR__ . '/options/';
		$option_file_names = scandir( $options_dir );

		foreach ( $option_file_names as $option_file_name ) {
			if ( '.' === $option_file_name || '..' === $option_file_name ) {
				continue;
			}
			require $options_dir . $option_file_name;
		}
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
		return apply_filters( 'vite_settings_defaults', $this->defaults );
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

	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Override default controls.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function override_controls( WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_control( 'blogname' )->type        = 'vite-input';
		$wp_customize->get_control( 'blogdescription' )->type = 'vite-input';
		$wp_customize->get_control( 'custom_logo' )->section  = 'vite[header-logo]';
		$wp_customize->get_control( 'custom_logo' )->priority = 1;
	}

	/**
	 * Save dynamic CSS.
	 *
	 * @return void
	 */
	public function save_dynamic_css() {
	}

	/**
	 * Get asset from file.
	 *
	 * @param string $file_name Filename.
	 * @return array
	 */
	private function get_asset( string $file_name ): array {
		$file = VITE_ASSETS_DIR . "dist/$file_name.asset.php";
		return file_exists( $file ) ? require $file : [
			'dependencies' => [],
			'version'      => VITE_VERSION,
		];
	}

	/**
	 * Enqueue control script.
	 *
	 * @return void
	 */
	public function enqueue_control_script() {
		$asset = $this->get_asset( 'customizer' );

		wp_enqueue_media();
		wp_enqueue_editor();
		wp_enqueue_script( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.js', $asset['dependencies'], $asset['version'], true );
		wp_enqueue_style( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.css', [ 'wp-components' ], $asset['version'] );
		wp_localize_script(
			'vite-customizer',
			'_VITE_CUSTOMIZER_',
			[
				'icons'      => vite( 'icon' )->get_icons(),
				'condition'  => $this->condition,
				'conditions' => $this->conditions,
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
		$asset = $this->get_asset( 'customizer-preview' );

		wp_enqueue_script( 'vite-customizer-preview', VITE_ASSETS_URI . 'dist/customizer-preview.js', array_merge( $asset['dependencies'], [ 'customize-preview' ] ), $asset['version'], true );
		wp_localize_script(
			'vite-customizer-preview',
			'_VITE_CUSTOMIZER_PREVIEW_',
			[
				'settings' => $this->settings,
			]
		);

		wp_register_style( 'vite-customizer-preview', false, false, '1.0.0' );
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
		$panels = apply_filters( 'vite_customizer_panels', $this->panels );
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
		$sections = apply_filters( 'vite_customizer_sections', $this->sections );
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
		$settings           = apply_filters( 'vite_customizer_settings', $this->settings );
		$sanitize_callbacks = apply_filters( 'vite_customizer_sanitize_callbacks', $this->sanitize_callbacks() );
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
						'transport'         => static::TRANSPORT,
						'type'              => static::STORE,
						'sanitize_callback' => $sanitize_callbacks[ $config['type'] ] ?? null,
					]
				);

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
				array_map(
					function( $font ) {
						$font['label'] = $font['family'];
						$font['value'] = $font['family'];
						return $font;

					},
					json_decode( ob_get_clean(), true )
				)
			);
		}
		return apply_filters( 'vite_google_fonts', $this->google_fonts );
	}

	/**
	 * Get control configs.
	 *
	 * @return array
	 */
	private function sanitize_callbacks(): array {
		return [
			'vite-background'     => [ 'Vite\Customizer\Sanitize', 'sanitize_background' ],
			'vite-typography'     => [ 'Vite\Customizer\Sanitize', 'sanitize_typography' ],
			'vite-dimensions'     => [ 'Vite\Customizer\Sanitize', 'sanitize_dimensions' ],
			'vite-buttonset'      => [ 'Vite\Customizer\Sanitize', 'sanitize_buttonset' ],
			'vite-sortable'       => [ 'Vite\Customizer\Sanitize', 'sanitize_sortable' ],
			'vite-checkbox'       => [ 'Vite\Customizer\Sanitize', 'sanitize_checkbox' ],
			'vite-toggle'         => [ 'Vite\Customizer\Sanitize', 'sanitize_checkbox' ],
			'vite-slider'         => [ 'Vite\Customizer\Sanitize', 'sanitize_slider' ],
			'vite-color'          => [ 'Vite\Customizer\Sanitize', 'sanitize_color' ],
			'vite-radio-image'    => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'vite-select'         => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'select'              => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'vite-radio'          => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'radio'               => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'vite-input'          => [ 'Vite\Customizer\Sanitize', 'sanitize_input' ],
			'vite-border'         => [ 'Vite\Customizer\Sanitize', 'sanitize_border' ],
			'number'              => [ 'Vite\Customizer\Sanitize', 'sanitize_int' ],
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
