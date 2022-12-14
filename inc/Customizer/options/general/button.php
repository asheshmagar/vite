<?php
/**
 * General options.
 *
 * @package Vite
 */

$options = [
	'vite[buttons-padding]'       => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Padding', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['buttons-padding'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
		],
	],
	'vite[buttons-border]'        => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-border',
		'title'       => __( 'Border', 'vite' ),
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[buttons-border-radius]' => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Border Radius', 'vite' ),
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
			'separator'  => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
