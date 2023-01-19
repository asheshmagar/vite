<?php
/**
 * SEO options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$options = [
	'vite[schema-markup]' => [
		'title'       => __( 'Schema Markup', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[seo]',
		'default'     => false,
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[og-meta-tags]'  => [
		'title'       => __( 'Open Graph Meta Tags', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[seo]',
		'default'     => false,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'input_attrs'         => [
		'allow_reset' => false,
	],
];

vite( 'customizer' )->add( 'settings', $options );
