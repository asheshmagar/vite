<?php
/**
 * Performance options.
 *
 * @package Vite
 */

$options = [
	'vite[emoji-script]'       => [
		'title'       => __( 'Emoji script', 'vite' ),
		'description' => __( 'Enable/Disable the emoji script.', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[performance]',
		'default'     => true,
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[dynamic-css-output]' => [
		'title'       => __( 'Dynamic CSS Output', 'vite' ),
		'type'        => 'vite-buttonset',
		'choices'     => [
			'inline' => __( 'Inline', 'vite' ),
			'file'   => __( 'File', 'vite' ),
		],
		'section'     => 'vite[performance]',
		'default'     => 'inline',
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[local-google-fonts]' => [
		'title'       => __( 'Local Google Fonts', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[performance]',
		'default'     => false,
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[css-preload]'        => [
		'title'       => __( 'CSS Preload', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[performance]',
		'default'     => false,
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
