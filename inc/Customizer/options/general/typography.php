<?php
/**
 * Global typography options.
 *
 * @package Vite
 */

$options = [
	'vite[base-typography]' => [
		'section'   => 'vite[global-typography]',
		'type'      => 'vite-typography',
		'title'     => __( 'Base', 'vite' ),
		'selectors' => [ 'body' ],
	],
	'vite[h1-typography]'   => [
		'section'     => 'vite[global-typography]',
		'type'        => 'vite-typography',
		'title'       => __( 'Heading 1 (h1)', 'vite' ),
		'selectors'   => [ 'body' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[h2-typography]'   => [
		'section'     => 'vite[global-typography]',
		'type'        => 'vite-typography',
		'title'       => __( 'Heading 2 (h2)', 'vite' ),
		'selectors'   => [ 'body' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[h3-typography]'   => [
		'section'     => 'vite[global-typography]',
		'type'        => 'vite-typography',
		'title'       => __( 'Heading 3 (h3)', 'vite' ),
		'selectors'   => [ 'body' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[h4-typography]'   => [
		'section'     => 'vite[global-typography]',
		'type'        => 'vite-typography',
		'title'       => __( 'Heading 4 (h4)', 'vite' ),
		'selectors'   => [ 'body' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[h5-typography]'   => [
		'section'     => 'vite[global-typography]',
		'type'        => 'vite-typography',
		'title'       => __( 'Heading 5 (h5)', 'vite' ),
		'selectors'   => [ 'body' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[h6-typography]'   => [
		'section'     => 'vite[global-typography]',
		'type'        => 'vite-typography',
		'title'       => __( 'Heading 6 (h6)', 'vite' ),
		'selectors'   => [ 'body' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
