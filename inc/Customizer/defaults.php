<?php
/**
 * Customizer defaults.
 *
 * @package Vite
 */

return [
	'header'             => [
		'top'    => [
			'left'   => [],
			'center' => [],
			'right'  => [],
		],
		'main'   => [
			'left'   => [ 'logo' ],
			'center' => [],
			'right'  => [ 'primary-navigation', 'search' ],
		],
		'bottom' => [
			'left'   => [],
			'center' => [],
			'right'  => [],
		],
	],
	'header-html'        => __( 'Enter HTML.', 'vite' ),
	'header-button-text' => __( 'Button', 'vite' ),
	'header-button-url'  => '#',
	'footer'             => [
		'top'    => [],
		'middle' => [],
		'bottom' => [
			'1' => [ 'html' ],
		],
	],
	'footer-html'        => __( '{{copyright}} {{year}} {{site-title}}', 'vite' ),
];
