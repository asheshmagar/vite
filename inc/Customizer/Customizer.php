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

	/**
	 * Holds all settings.
	 *
	 * @var array Settings.
	 */
	public $settings = [];

	/**
	 * Control group.
	 *
	 * @var array Control group.
	 */
	public $group = [];

	/**
	 * Context.
	 *
	 * @var array
	 */
	public $context = [];

	/**
	 * Holds instance of dynamic CSS.
	 *
	 * @var DynamicCSS $css
	 */
	public $css;

	private $print_css = false;

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'customize_register', [ $this, 'customize_register' ], PHP_INT_MAX - 1 );
		add_action( 'customize_register', [ $this, 'override_controls' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_control_script' ] );
		add_action( 'customize_save_after', [ $this, 'save_dynamic_css' ] );
		add_filter( 'customize_render_partials_response', [ $this, 'partial_response' ] );
		add_action( 'wp_print_scripts', [ $this, 'print_dynamic_css' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_script' ] );
	}

	/**
	 * Register customizer settings.
	 *
	 * @param array $response Response.
	 * @return array
	 */
	public function partial_response( array $response ): array {
		if ( ! empty( $this->css->css_data ) ) {
			try {
				$response['viteDynamicCSS'] = $this->css->make()->get();
			} catch ( \Exception $e ) {} // phpcs:ignore
		}
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

		if ( ! empty( $this->css->css_data ) ) {
			try {
				$css = $this->css->make()->get();
//				echo '<style id="vite-dynamic-css">' . $css . '</style>'; // phpcs:ignore
			} catch ( \Exception $e ) {} // phpcs:ignore
		}
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
		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}
		return $default;
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
	}

	/**
	 * Save dynamic CSS.
	 *
	 * @return void
	 */
	public function save_dynamic_css() {
		if ( ! empty( $this->css->css_data ) ) {
			try {
				$this->css->make()->save();
			} catch ( \Exception $e ) {} // phpcs:ignore
		}
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
	}

	/**
	 * Customizer register.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		$this->css = new DynamicCSS();

		require_once __DIR__ . '/settings.php';
		$this->register( $wp_customize );
		$this->add( $wp_customize );
	}

	/**
	 * Add settings.
	 *
	 * @param array[] $settings Settings array.
	 * @return void
	 */
	public function add_settings( array $settings ) {
		if ( is_array( $settings[ array_key_first( $settings ) ] ) ) {
			foreach ( $settings as $setting ) {
				$this->settings[] = $setting;
			}
		} else {
			$this->settings[] = $settings;
		}
	}

	/**
	 * Register custom panel, section and control.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function register( WP_Customize_Manager $wp_customize ) {
		$wp_customize->register_panel_type( Panel::class );
		$wp_customize->register_section_type( Section::class );
		$wp_customize->register_control_type( Control::class );
	}

	/**
	 * Add custom section, panel, control and sub-control.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function add( WP_Customize_Manager $wp_customize ) {
		$configs = $this->settings;

		if ( empty( $configs ) ) {
			return;
		}

		foreach ( $configs as $config ) {
			$config = wp_parse_args(
				$config,
				apply_filters(
					'vite_customizer_default_configs',
					[
						'priority'             => null,
						'title'                => null,
						'label'                => null,
						'name'                 => null,
						'type'                 => null,
						'description'          => null,
						'capability'           => 'edit_theme_options',
						'datastore_type'       => 'theme_mod',
						'settings'             => null,
						'active_callback'      => null,
						'sanitize_callback'    => null,
						'sanitize_js_callback' => null,
						'theme_supports'       => null,
						'transport'            => null,
						'default'              => null,
						'selector'             => null,
						'fields'               => [],
						'css'                  => null,
						'input_attrs'          => [],
					]
				)
			);

			if ( ! isset( $config['type'] ) || ! isset( $config['name'] ) ) {
				return;
			}

			if ( isset( $config['css'] ) ) {
				vite( 'dynamic-css' )->add( $config );
			}

			switch ( $config['type'] ) {
				case 'panel':
				case 'vite-builder-panel':
					if ( 'panel' === $config['type'] ) {
						unset( $config['type'] );
					}
					$wp_customize->add_panel( new Panel( $wp_customize, $config['name'], $config ) );
					break;
				case 'section':
				case 'vite-builder-section':
					if ( 'section' === $config['type'] ) {
						unset( $config['type'] );
					}
					$wp_customize->add_section( new Section( $wp_customize, $config['name'], $config ) );
					break;
				case 'sub-control':
					unset( $config['type'] );
					$this->add_sub_control( $config, $wp_customize );
					break;
				case 'control':
					unset( $config['type'] );
					$this->add_control( $config, $wp_customize );
					break;
			}
		}
	}

	/**
	 * Add control.
	 *
	 * @param array                $config Configs.
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function add_control( array $config, WP_Customize_Manager $wp_customize ) {
		$name              = $config['name'];
		$control_configs   = $this->get_control_configs();
		$transport         = $config['transport'] ?? 'refresh';
		$sanitize_callback = $config['sanitize_callback'] ?? $control_configs[ $config['control'] ];

		if ( 'vite-group' === $config['control'] ) {
			$sanitize_callback = false;
		}

		$wp_customize->add_setting(
			$name,
			array(
				'default'           => $config['default'],
				'type'              => $config['datastore_type'],
				'transport'         => $transport,
				'sanitize_callback' => $sanitize_callback,
			)
		);
		$config['type'] = $config['control'];
		$config         = $this->additional_config( $config );

		if ( isset( $config['selectors'] ) ) {
			$this->css->add( $config );
		}

		$wp_customize->add_control( new Control( $wp_customize, $name, $config ) );

		$selective_refresh = isset( $config['partial'] );
		$render_callback   = $config['partial']['render_callback'] ?? '';

		if ( $selective_refresh ) {
			if ( isset( $wp_customize->selective_refresh ) ) {
				$wp_customize->selective_refresh->add_partial(
					$name,
					[
						'selector'        => $config['partial']['selector'],
						'render_callback' => $render_callback,
					]
				);
			}
		}
	}

	/**
	 * Add sub control.
	 *
	 * @param array                $config Configs.
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	private function add_sub_control( array $config, WP_Customize_Manager $wp_customize ) {
		$name            = $config['name'];
		$control_configs = $this->get_control_configs();

		if ( isset( $wp_customize->get_control( $name )->id ) ) {
			return;
		}

		$parent = $config['input_attrs']['parent'] ?? '';
		$tab    = $config['input_attrs']['tab'] ?? '';

		if ( empty( $this->group[ $parent ] ) ) {
			$this->group[ $parent ] = array();
		}

		if ( array_key_exists( 'tab', $config['input_attrs'] ) ) {
			$this->group[ $parent ]['tabs'][ $tab ][] = $config;
		} else {
			$this->group[ $parent ][] = $config;
		}

		$sanitize_callback = $config['sanitize_callback'] ?? $control_configs[ $config['control'] ];
		$transport         = $config['transport'] ?? 'refresh';
		$config            = wp_parse_args(
			[
				'name'              => $name,
				'datastore_type'    => apply_filters( 'vite_customize_datastore_type', 'theme_mod' ),
				'control'           => 'customind-hidden',
				'section'           => $config['section'],
				'default'           => $config['default'],
				'transport'         => $transport,
				'sanitize_callback' => $sanitize_callback,
			],
			$config
		);
		$config            = $this->additional_config( $config );

		$wp_customize->add_setting( $name, $config );

		$config['type'] = $config['control'];

		if ( isset( $config['selectors'] ) ) {
			$this->css->add( $config );
		}

		$wp_customize->add_control( new Control( $wp_customize, $name, $config ) );
	}

	/**
	 * Add additional config.
	 *
	 * @param array $config Config.
	 * @return array
	 */
	private function additional_config( array &$config ): array {
		$name = $config['name'];

		switch ( $config['type'] ) {
			case 'vite-group':
				$group = [];

				if ( isset( $this->group[ $name ]['tabs'] ) ) {
					$tabs = array_keys( $this->group[ $name ]['tabs'] );
					foreach ( $tabs as $tab ) {
						$group['tabs'][ $tab ] = wp_list_sort( $this->group[ $name ]['tabs'][ $tab ], 'priority' );
					}
				} else {
					if ( isset( $this->group[ $name ] ) ) {
						$group = wp_list_sort( $this->group[ $name ], 'priority' );
					}
				}

				$config['field'] = $group;
				break;
			case 'vite-typography':
				$fonts_json = VITE_ASSETS_DIR . 'json/google-fonts.json';
				if ( file_exists( $fonts_json ) ) {
					ob_start();
					include_once $fonts_json;
					$config['fonts'] = json_decode( ob_get_clean(), true );
				}
				break;
		}

		return $config;
	}

	/**
	 * Get control configs.
	 *
	 * @return array
	 */
	private function get_control_configs(): array {
		return [
			'vite-background'  => [ 'Vite\Customizer\Sanitize', 'sanitize_background' ],
			'vite-typography'  => [ 'Vite\Customizer\Sanitize', 'sanitize_typography' ],
			'vite-dimensions'  => [ 'Vite\Customizer\Sanitize', 'sanitize_dimensions' ],
			'vite-buttonset'   => [ 'Vite\Customizer\Sanitize', 'sanitize_buttonset' ],
			'vite-sortable'    => [ 'Vite\Customizer\Sanitize', 'sanitize_sortable' ],
			'vite-checkbox'    => [ 'Vite\Customizer\Sanitize', 'sanitize_checkbox' ],
			'vite-toggle'      => [ 'Vite\Customizer\Sanitize', 'sanitize_checkbox' ],
			'vite-slider'      => [ 'Vite\Customizer\Sanitize', 'sanitize_slider' ],
			'vite-color'       => [ 'Vite\Customizer\Sanitize', 'sanitize_color' ],
			'vite-radio-image' => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'vite-select'      => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'select'           => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'vite-radio'       => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'radio'            => [ 'Vite\Customizer\Sanitize', 'sanitize_radio' ],
			'vite-input'       => [ 'Vite\Customizer\Sanitize', 'sanitize_input' ],
			'vite-border'      => [ 'Vite\Customizer\Sanitize', 'sanitize_border' ],
			'number'           => [ 'Vite\Customizer\Sanitize', 'sanitize_int' ],
			'vite-textarea'    => 'sanitize_textarea_field',
			'textarea'         => 'sanitize_textarea_field',
			'text'             => 'sanitize_text_field',
			'vite-gradient'    => 'sanitize_text_field',
			'email'            => 'sanitize_email',
			'url'              => 'esc_url_raw',
			'vite-editor'      => 'wp_kses_post',
			'vite-hidden'      => null,
			'vite-group'       => null,
			'vite-title'       => null,
			'vite-builder'     => null,
			'vite-heading'     => null,
			'vite-navigate'    => null,
		];
	}
}
