<?php
/**
 * Class Meta.
 *
 * @package Vite\Meta
 */

namespace Vite\Meta;

use Vite\DynamicCSS;

/**
 * Class Meta.
 */
class Meta {

	/**
	 * Holds all settings.
	 *
	 * @var array
	 */
	public $settings = [];

	/**
	 * Dynamic CSS instance.
	 *
	 * @var null|DynamicCSS
	 */
	public $dynamic_css = null;

	/**
	 * Meta constructor.
	 *
	 * @param DynamicCSS $dynamic_css Instance of DynamicCSS.
	 */
	public function __construct( DynamicCSS $dynamic_css ) {
		$this->dynamic_css          = $dynamic_css;
		$this->dynamic_css->context = 'meta';
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'register' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue' ] );
		if ( is_admin() ) {
			add_action( 'load-post.php', array( $this, 'init_meta' ) );
			add_action( 'load-post-new.php', array( $this, 'init_meta' ) );
		}
	}

	public function init_meta() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	public function add_meta_boxes() {}

	public function save_meta(){}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {}

	/**
	 * Add settings.
	 *
	 * @param array[] $settings Settings.
	 * @return void
	 */
	public function add( array $settings ) {
		foreach ( $settings as $key => $option ) {
			$this->settings[ $key ] = $option;
		}
	}

	/**
	 * Register meta.
	 *
	 * @return void
	 */
	public function register() {
		$settings = apply_filters( 'vite_customizer_settings', $this->settings );
		if ( empty( $settings ) ) {
			return;
		}

		$default_configs = [
			'show_in_rest'  => true,
			'single'        => true,
			'auth_callback' => '__return_true',
		];

		foreach ( $settings as $key => $option ) {
			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			if ( in_array( $option['type'], array( 'array', 'object' ), true ) ) {
				if ( ! isset( $option['schema'] ) ) {
					trigger_error( "Meta: `$key` with type {$option['type']} has missing schema!", E_USER_WARNING ); // phpcs:ignore
					continue;
				}

				$default_configs['show_in_rest'] = [
					'schema' => $option['schema'],
				];

				unset( $option['schema'] );
			}

			register_post_meta(
				'',
				$key,
				wp_parse_args(
					$option,
					$default_configs
				)
			);
		}
	}

	public function field( $key, $args = [] ) {
		if ( isset( $args['type'] ) ) {
			return;
		}

		switch ( $args['type'] ) {
			case 'select':
				$this->select_field( $key, $args );
				break;
			case 'checkbox':
				$this->checkbox_field( $key, $args );
				break;
		}
	}

	private function select_field( $key, $args = [] ) {
		$default = $args['default'] ?? '';
		$value   = get_post_meta( get_the_ID(), $key, true );
		$value   = $value ? $value : $default;
		$options = $args['options'] ?? [];

		if ( empty( $options ) ) {
			return;
		}
		?>
		<div class="field select-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $args['title'] ?? '' ); ?></label>
			<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">
				<?php foreach ( $options as $option_key => $option_value ) : ?>
					<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $value, $option_key ); ?>><?php echo esc_html( $option_value ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}

	private function checkbox_field( $key, $args = [] ) {
		$default = $args['default'] ?? '';
		$value   = get_post_meta( get_the_ID(), $key, true );
		$value   = $value ? $value : $default;
		$choices = $args['choices'] ?? [];
		?>
		<div class="field checkbox-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $args['title'] ?? '' ); ?></label>
			<?php foreach ( $choices as $choice_key => $choice_value ) : ?>
				<label for="<?php echo esc_attr( $key ); ?>-<?php echo esc_attr( $choice_key ); ?>">
					<input type="checkbox" name="<?php echo esc_attr( $key ); ?>[]" id="<?php echo esc_attr( $key ); ?>-<?php echo esc_attr( $choice_key ); ?>" value="<?php echo esc_attr( $choice_key ); ?>" <?php checked( in_array( $choice_key, $value, true ) ); ?>>
					<?php echo esc_html( $choice_value ); ?>
				</label>
			<?php endforeach; ?>
		<?php
	}
}
