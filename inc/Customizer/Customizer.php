<?php
/**
 * Customizer.
 */

namespace Vite\Customizer;

use Vite\Customizer\Control\BuilderControl;
use Vite\Customizer\Control\ButtonsetControl;
use Vite\Customizer\Control\ColorControl;
use Vite\Customizer\Control\CustomControl;
use Vite\Customizer\Control\DimensionsControl;
use Vite\Customizer\Control\HiddenControl;
use Vite\Customizer\Control\DividerControl;
use Vite\Customizer\Control\DropdownCategoriesControl;
use Vite\Customizer\Control\EditorControl;
use Vite\Customizer\Control\GroupControl;
use Vite\Customizer\Control\HeadingControl;
use Vite\Customizer\Control\GradientControl;
use Vite\Customizer\Control\NavigateControl;
use Vite\Customizer\Control\RadioImageControl;
use Vite\Customizer\Control\SliderControl;
use Vite\Customizer\Control\SortableControl;
use Vite\Customizer\Control\TabControl;
use Vite\Customizer\Control\TitleControl;
use Vite\Customizer\Control\ToggleControl;
use Vite\Customizer\Control\TypographyControl;
use WP_Customize_Color_Control;
use WP_Customize_Image_Control;
use WP_Customize_Manager;
use Vite\Customizer\Type\Panel;
use Vite\Customizer\Type\Section;
use Vite\Customizer\Control\BackgroundControl;

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
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'customize_register', array( $this, 'customize_register' ), PHP_INT_MAX - 1 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_script' ) );
		add_action( 'customize_save_after', array( $this, 'save_dynamic_css' ) );
	}

	/**
	 * Save dynamic CSS.
	 *
	 * @return void
	 */
	public function save_dynamic_css() {
		vite( 'dynamic-css' )->make()->save();
	}

	/**
	 * Enqueue control script.
	 *
	 * @return void
	 */
	public function enqueue_control_script() {
		$asset_file = VITE_ASSETS_DIR . 'dist/customizer.asset.php';
		$asset      = file_exists( $asset_file ) ? require $asset_file : [
			'dependencies' => [],
			'version'      => VITE_VERSION,
		];

		wp_enqueue_media();
		wp_enqueue_editor();
		wp_enqueue_script( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.js', $asset['dependencies'], $asset['version'], true );
		wp_enqueue_style( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.css', [ 'wp-components' ], $asset['version'] );
		wp_set_script_translations( 'vite-customizer', 'vite', get_template_directory() . '/languages' );
	}

	/**
	 * Customizer register.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		require_once __DIR__ . '/settings.php';
		$this->register_section_and_panel( $wp_customize );
		$this->register_control();
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
	 * Register custom section and panel.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function register_section_and_panel( WP_Customize_Manager $wp_customize ) {
		$wp_customize->register_panel_type( Panel::class );
		$wp_customize->register_section_type( Section::class );
	}

	/**
	 * Register control.
	 *
	 * @return void
	 */
	private function register_control() {
		$configs = $this->get_control_configs();

		foreach ( $configs as $name => $props ) {
			vite( 'customizer-control' )->set( $name, $props );
		}
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
	public function add_control( array $config, WP_Customize_Manager $wp_customize ) {
		$name              = $config['name'];
		$sanitize_callback = $config['sanitize_callback'] ?? vite( 'customizer-control' )->get( $config['control'], 'sanitize_callback' );
		$transport         = $config['transport'] ?? 'refresh';

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
		$control        = vite( 'customizer-control' )->get( $config['control'], 'callback' );
		$config['type'] = $config['control'];

		if ( isset( $control ) ) {
			$wp_customize->add_control( new $control( $wp_customize, $name, $config ) );
		} else {
			$wp_customize->add_control( $name, $config );
		}

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
		$name = $config['name'];

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

		$sanitize_callback = $config['sanitize_callback'] ?? vite( 'customizer-control' )->get( $config['control'], 'sanitize_callback' );
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

		$wp_customize->add_setting( $name, $config );

		$control = vite( 'customizer-control' )->get( $config['control'], 'callback' );

		if ( isset( $control ) ) {
			$wp_customize->add_control( new $control( $wp_customize, $name, $config ) );
		} else {
			$wp_customize->add_control( $name, $config );
		}

		if ( isset( $config['context'] ) ) {
			$this->context[ $name ] = $config['context'];
		}
	}

	/**
	 * Get control configs.
	 *
	 * @return array
	 */
	private function get_control_configs(): array {
		$sanitize = new Sanitize();
		return [
			'checkbox'                 => [
				'sanitize_callback' => [ $sanitize, 'sanitize_checkbox' ],
			],
			'radio'                    => [
				'sanitize_callback' => [ $sanitize, 'sanitize_radio_select' ],
			],
			'select'                   => [
				'sanitize_callback' => [ $sanitize, 'sanitize_radio_select' ],
			],
			'text'                     => [
				'sanitize_callback' => [ $sanitize, 'sanitize_nohtml' ],
			],
			'number'                   => [
				'sanitize_callback' => [ $sanitize, 'sanitize_number' ],
			],
			'email'                    => [
				'sanitize_callback' => [ $sanitize, 'sanitize_email' ],
			],
			'url'                      => [
				'sanitize_callback' => [ $sanitize, 'sanitize_url' ],
			],
			'textarea'                 => [
				'sanitize_callback' => [ $sanitize, 'sanitize_html' ],
			],
			'dropdown-pages'           => [
				'sanitize_callback' => [ $sanitize, 'sanitize_dropdown_pages' ],
			],
			'color'                    => [
				'callback'          => WP_Customize_Color_Control::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_hex_color' ],
			],
			'image'                    => [
				'callback'          => WP_Customize_Image_Control::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_image_upload' ],
			],
			'vite-radio-image'         => [
				'callback'          => RadioImageControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_radio_select' ],
			],
			'vite-heading'             => [
				'callback'          => HeadingControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_false_values' ],
			],
			'vite-navigate'            => [
				'callback'          => NavigateControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_false_values' ],
			],
			'vite-editor'              => [
				'callback'          => EditorControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_html' ],
			],
			'vite-color'               => [
				'callback'          => ColorControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_alpha_color' ],
			],
			'vite-buttonset'           => [
				'callback'          => ButtonsetControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_radio_select' ],
			],
			'vite-toggle'              => [
				'callback'          => ToggleControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_checkbox' ],
			],
			'vite-slider'              => [
				'callback'          => SliderControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_number' ],
			],
			'vite-divider'             => [
				'callback'          => DividerControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_false_values' ],
			],
			'vite-dropdown-categories' => [
				'callback'          => DropdownCategoriesControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_dropdown_categories' ],
			],
			'vite-custom'              => [
				'callback'          => CustomControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_false_values' ],
			],
			'vite-background'          => [
				'callback'          => BackgroundControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_background' ],
			],
			'vite-typography'          => [
				'callback'          => TypographyControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_typography' ],
			],
			'vite-hidden'              => [
				'callback' => HiddenControl::class,
			],
			'vite-sortable'            => [
				'callback'          => SortableControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_sortable' ],
			],
			'vite-group'               => [
				'callback' => GroupControl::class,
			],
			'vite-title'               => [
				'callback' => TitleControl::class,
			],
			'vite-dimensions'          => [
				'callback' => DimensionsControl::class,
			],
			'vite-builder'             => [
				'callback' => BuilderControl::class,
			],
			'vite-gradient'            => [
				'callback' => GradientControl::class,
			],
			'vite-tab'                 => [
				'callback'          => TabControl::class,
				'sanitize_callback' => [ $sanitize, 'sanitize_text_field' ],
			],
		];

	}
}
