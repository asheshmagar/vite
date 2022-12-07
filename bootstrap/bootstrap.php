<?php
/**
 * Init app.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

global $vite;

$vite = new Container();

$vite->delegate( new ReflectionContainer() );

$service_providers = [
	Vite\ServiceProvider\CoreServiceProvider::class,
	Vite\ServiceProvider\SEOServiceProvider::class,
	Vite\ServiceProvider\BuilderElementsServiceProvider::class,
	Vite\ServiceProvider\EntryElementsServiceProvider::class,
	Vite\ServiceProvider\BreadcrumbsServiceProvider::class,
	Vite\ServiceProvider\NavMenuServiceProvider::class,
	Vite\ServiceProvider\SidebarServiceProvider::class,
	Vite\ServiceProvider\IconServiceProvider::class,
	Vite\ServiceProvider\PageHeaderElementsServiceProvider::class,
	Vite\ServiceProvider\CommentsServiceProvider::class,
	Vite\ServiceProvider\WebFontLoaderServerProvider::class,
	Vite\ServiceProvider\PerformanceServiceProvider::class,
	Vite\ServiceProvider\DynamicCSSServiceProvider::class,
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
			try {
				return $vite->get( $class_or_alias );
			} catch ( NotFoundExceptionInterface | ContainerExceptionInterface $e ) {
				return (object) [];
			}
		}

		return $vite;
	}
}

vite( 'theme' );
