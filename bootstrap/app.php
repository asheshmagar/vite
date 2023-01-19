<?php
/**
 * Init app.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

use League\Container\{ Container, ReflectionContainer };

global $vite;

$vite = new Container();

// Auto-wire.
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
	 * @return object|mixed
	 */
	function vite( string $id = '' ) {
		global $vite;
		return empty( $id ) ? $vite : $vite->get( $id );
	}
}
