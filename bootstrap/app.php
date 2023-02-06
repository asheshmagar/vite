<?php
/**
 * Init app.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

use League\Container\{ Container, ReflectionContainer };
use Psr\Container\ContainerExceptionInterface;

global $vite;

$vite = new Container();

// Auto-wire, resolve dependencies and cache resolutions.
$vite->delegate( ( new ReflectionContainer() )->cacheResolutions() );

$configs = require_once __DIR__ . '/config.php';

foreach ( $configs as $key => $config ) {
	$vite
		->add( $key, $config['concrete'], $config['shared'] ?? true )
		->addArguments( $config['arguments'] ?? [] );
}

if ( ! function_exists( 'vite' ) ) {

	/**
	 * Get theme container.
	 *
	 * @param string $id Identifier of the entry to look for in the container.
	 * @return object|void
	 */
	function vite( string $id = '' ) {
		global $vite;
		try {
			return empty( $id ) ? $vite : $vite->get( $id );
		} catch ( ContainerExceptionInterface $e ) {
			_doing_it_wrong( __FUNCTION__, esc_html( $e->getMessage() ), '1.0.0' );
		}
	}
}
