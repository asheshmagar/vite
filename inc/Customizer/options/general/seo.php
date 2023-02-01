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
		'default'     => true,
		'input_attrs' => [
			'allow_reset' => false,
		],
		'transport'   => 'refresh',
	],
];

vite( 'customizer' )->add( 'settings', $options );
