<?php
/**
 * Init app.
 */

use League\Container\Container;
use League\Container\ReflectionContainer;

global $vite;

$vite = new Container();

$vite->delegate( new ReflectionContainer() );

$service_providers = [
	Vite\ServiceProvider\CoreServiceProvider::class,
	Vite\ServiceProvider\BreadcrumbsServiceProvider::class,
	Vite\ServiceProvider\SupportsServiceProvider::class,
	Vite\ServiceProvider\NavMenuServiceProvider::class,
	Vite\ServiceProvider\SidebarServiceProvider::class,
	Vite\ServiceProvider\HeaderServiceProvider::class,
	Vite\ServiceProvider\PageHeaderServiceProvider::class,
	Vite\ServiceProvider\CommentsServiceProvider::class,
	Vite\ServiceProvider\EntryElementsServiceProvider::class,
	Vite\ServiceProvider\TemplateHooksServiceProvider::class,
	Vite\ServiceProvider\ScriptsServiceProvider::class,
	Vite\ServiceProvider\StylesServiceProvider::class,
	Vite\ServiceProvider\DynamicCSSServiceProvider::class,
	Vite\ServiceProvider\CustomizerControlServiceProvider::class,
	Vite\ServiceProvider\CustomizerServiceProvider::class,
	Vite\ServiceProvider\ViteServiceProvider::class,
];

foreach ( $service_providers as $service_provider ) {
	$vite->addServiceProvider( new $service_provider() );
}

if ( ! function_exists( 'vite' ) ) {

	/**
	 * Get theme container.
	 *
	 * @param string $class_or_alias Class name or class name alias.
	 * @return array|mixed|object
	 */
	function vite( string $class_or_alias ) {
		global $vite;

		if ( ! empty( $class_or_alias ) && $vite->has( $class_or_alias ) ) {
			return $vite->get( $class_or_alias );
		}

		return $vite;
	}

	vite( 'theme' );
}