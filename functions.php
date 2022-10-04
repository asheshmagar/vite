<?php
/**
 * Theme functions and definitions.
 *
 * @since x.x.x
 * @package Theme
 */

use League\Container\Container;
use League\Container\ReflectionContainer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define constants.
 */
! defined( 'THEME_VERSION' ) && define( 'THEME_VERSION', 'x.x.x' );
! defined( 'THEME_ASSETS_DIR' ) && define( 'THEME_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'THEME_ASSETS_URI' ) && define( 'THEME_ASSETS_URI', get_theme_file_uri( '/assets/' ) );

/**
 * Load composer autoloader.
 */
require_once __DIR__ . '/vendor/autoload.php';

global $custom_theme;

$custom_theme = new Container();

$custom_theme->delegate( new ReflectionContainer() );

$service_providers = [
	Theme\ServiceProvider\CoreServiceProvider::class,
	Theme\ServiceProvider\BreadcrumbsServiceProvider::class,
	Theme\ServiceProvider\SupportsServiceProvider::class,
	Theme\ServiceProvider\NavMenuServiceProvider::class,
	Theme\ServiceProvider\HeaderServiceProvider::class,
	Theme\ServiceProvider\PageHeaderServiceProvider::class,
	Theme\ServiceProvider\CommentsServiceProvider::class,
	Theme\ServiceProvider\EntryElementsServiceProvider::class,
	Theme\ServiceProvider\TemplateHooksServiceProvider::class,
	Theme\ServiceProvider\ScriptsServiceProvider::class,
	Theme\ServiceProvider\StylesServiceProvider::class,
	Theme\ServiceProvider\ThemeServiceProvider::class,
];

foreach ( $service_providers as $service_provider ) {
	$custom_theme->addServiceProvider( new $service_provider() );
}

if ( ! function_exists( 'theme' ) ) {

	/**
	 * Get theme container.
	 *
	 * @param string $class_or_alias Class name or class name alias.
	 * @return array|mixed|object
	 */
	function theme( string $class_or_alias ) {
		global $custom_theme;

		if ( ! empty( $class_or_alias ) && $custom_theme->has( $class_or_alias ) ) {
			return $custom_theme->get( $class_or_alias );
		}

		return $custom_theme;
	}

	theme( 'theme' );
}
