<?php
/**
 * Performance options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

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
			'separator'   => true,
			'cols'        => 2,
		],
	],
	'vite[cache-dynamic-css]'  => [
		'title'       => __( 'Cache dynamic CSS', 'vite' ),
		'type'        => 'vite-toggle',
		'default'     => true,
		'section'     => 'vite[performance]',
		'input_attrs' => [
			'allow_reset' => false,
			'separator'   => true,
			'cols'        => 2,
		],
		'description' => __( 'Cache inline CSS. Improves performance if enabled.', 'vite' ),
		'condition'   => [
			'vite[dynamic-css-output]' => 'inline',
		],
	],
	'vite[local-google-fonts]' => [
		'title'       => __( 'Local Google Fonts', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[performance]',
		'default'     => false,
		'input_attrs' => [
			'allow_reset' => false,
			'separator'   => true,
		],
	],
	'vite[css-preload]'        => [
		'title'       => __( 'CSS Preload', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[performance]',
		'default'     => false,
		'input_attrs' => [
			'allow_reset' => false,
			'separator'   => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
