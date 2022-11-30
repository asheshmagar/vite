<?php
/**
 * Global social links options.
 *
 * @package Vite
 */

$options = array_reduce(
	vite( 'core' )->get_social_networks(),
	function( $acc, $curr ) {
		$acc[ "vite[{$curr['id']}-link]" ] = [
			'section' => 'vite[socials]',
			'type'    => 'vite-input',
			'title'   => $curr['label'],
			'default' => '',
		];
		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
