<?php
/**
 * Header mobile menu options.
 *
 * @package Vite
 */

$options = [
	'vite[header-mobile-menu]' => [
		'section'     => 'vite[header-mobile-menu]',
		'type'        => 'vite-select',
		'title'       => __( 'Menu', 'vite' ),
		'default'     => get_theme_mod( 'nav_menu_locations' )['mobile'] ?? '0',
		'choices'     => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
		'partial'     => [
			'selector'            => '.header-mobile-menu',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', 'mobile-menu' );
			},
		],
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
