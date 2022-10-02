<?php
/**
 * Class Theme.
 *
 * @since x.x.x
 * @package Theme
 */

namespace Theme;

use BadMethodCallException;

defined( 'ABSPATH' ) || exit;

/**
 * Class Theme.
 *
 * @method Services\Comments comments()
 * @method Services\Breadcrumbs breadcrumbs()
 * @method Services\NavMenu is_primary_menu_active()
 * @method Services\NavMenu is_secondary_menu_active()
 * @method Services\NavMenu is_footer_menu_active()
 * @since x.x.x
 */
class Theme {

	/**
	 * Services.
	 *
	 * @var array
	 */
	private $services = [];

	/**
	 * Singleton instance.
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Un-serializing instances of the class is forbidden.
	 */
	public function __wakeUp() {}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {}

	/**
	 * Initialize theme.
	 *
	 * @return Theme|null;
	 */
	public static function init(): ?Theme {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->init_services();
	}

	/**
	 * Initialize services.
	 *
	 * @return void
	 */
	private function init_services() {
		$services = [
			'Theme\Services\Localization',
			'Theme\Services\Support',
			'Theme\Services\NavMenu',
			'Theme\Services\Breadcrumbs',
		];

		$services = apply_filters( 'theme_services', $services );

		if ( ! empty( $services ) ) {
			foreach ( $services as $service ) {
				if ( class_exists( $service ) ) {
					$service_provider = new $service();
					$service_provider->init();
					$service_callbacks = $service_provider->services();
					if ( ! empty( $service_callbacks ) ) {
						foreach ( $service_callbacks  as $method => $cb ) {
							if ( method_exists( $service_provider, $method ) && ! array_key_exists( $method, $this->services ) ) {
								$this->services[ $method ] = $cb;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Magic method to call methods.
	 *
	 * Will call methods available from services.
	 *
	 * @param string $method Method name.
	 * @param array  $args Arguments.
	 * @return mixed
	 * @throws BadMethodCallException Thrown if the method does not exist.
	 */
	public function __call( string $method, array $args ) {
		if ( array_key_exists( $method, $this->services ) ) {
			return call_user_func_array( $this->services[ $method ], $args );
		}

		/* Translators: 1: Method name. */
		throw new BadMethodCallException( sprintf( __( 'Method %s does not exist.', 'theme' ), $method ) );
	}
}
