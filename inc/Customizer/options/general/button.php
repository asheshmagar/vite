<?php
/**
 * General options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;


$selector = '.button, .vite-button__link, button, input[type=submit], input[type=button], input[type=reset], .comment-reply-link, #cancel-comment-reply-link, .wp-block-button .wp-block-button__link';

$options = [
	'vite[buttons-padding]'       => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Padding', 'vite' ),
		'default'     => vite( 'core' )->get_mod_defaults()['buttons-padding'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
		],
		'css'         => [
			'selector' => ':root',
			'property' => '--button--padding',
		],
	],
	'vite[buttons-border]'        => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-border',
		'title'       => __( 'Border', 'vite' ),
		'default'     => vite( 'core' )->get_mod_defaults()['buttons-border'],
		'input_attrs' => [
			'separator' => true,
		],
		'css'         => [
			'selector' => $selector,
			'property' => 'border',
		],
	],
	'vite[buttons-border-radius]' => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Border Radius', 'vite' ),
		'default'     => vite( 'core' )->get_mod_defaults()['buttons-border-radius'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
			'separator'  => true,
		],
		'css'         => [
			'selector' => ':root',
			'property' => '--button--border--radius',
		],
	],
	'vite[buttons-typography]'    => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-typography',
		'title'       => __( 'Typography', 'vite' ),
		'input_attrs' => [
			'separator' => true,
		],
		'css'         => [
			'selector' => $selector,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
