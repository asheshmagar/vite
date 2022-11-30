<?php
/**
 * SEO options.
 *
 * @package Vite
 */

$options = [
	'vite[schema-markup]'                => [
		'title'       => __( 'Schema Markup', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[seo]',
		'default'     => false,
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[schema-markup-implementation]' => [
		'title'       => __( 'Schema Markup Implementation', 'vite' ),
		'type'        => 'vite-buttonset',
		'choices'     => [
			'json-ld'   => __( 'JSON-LD', 'vite' ),
			'microdata' => __( 'Microdata', 'vite' ),
		],
		'section'     => 'vite[seo]',
		'default'     => 'json-ld',
		'input_attrs' => [
			'allow_reset' => false,
		],
		'condition'   => [
			'vite[schema-markup]' => true,
		],
	],
	'vite[og-meta-tags]'                 => [
		'title'       => __( 'Open Graph Meta Tags', 'vite' ),
		'type'        => 'vite-toggle',
		'section'     => 'vite[seo]',
		'default'     => false,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'input_attrs'                        => [
		'allow_reset' => false,
	],
];

vite( 'customizer' )->add( 'settings', $options );
