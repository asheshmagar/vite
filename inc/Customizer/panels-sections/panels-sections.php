<?php
/**
 * Add or register panels and sections.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

// Add panels.
vite( 'customizer' )->add(
	'panels',
	[
		'vite[general]'        => [
			'title' => __( 'General', 'vite' ),
		],
		'vite[header-builder]' => [
			'title' => __( 'Header', 'vite' ),
		],
		'vite[blog]'           => [
			'title' => __( 'Blog', 'vite' ),
		],
		'vite[footer-builder]' => [
			'title' => __( 'Footer', 'vite' ),
		],
	]
);

// Add sections.
vite( 'customizer' )->add(
	'sections',
	[
		'vite[global-colors]'           => [
			'title' => __( 'Colors', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[global-typography]'       => [
			'title' => __( 'Typography', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[global-layout]'           => [
			'title' => __( 'Layout', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[global-buttons]'          => [
			'title' => __( 'Button', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[scroll-to-top]'           => [
			'title' => __( 'Scroll To Top', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[socials]'                 => [
			'title' => __( 'Social Accounts Links', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[seo]'                     => [
			'title' => __( 'SEO', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[performance]'             => [
			'title' => __( 'Performance', 'vite' ),
			'panel' => 'vite[general]',
		],
		'vite[header-builder]'          => [
			'title' => __( 'Header', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-builder-settings]' => [
			'title' => __( 'Header builder settings', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-logo]'             => [
			'title' => __( 'Logo', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-html-1]'           => [
			'title' => __( 'HTML 1', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-html-2]'           => [
			'title' => __( 'HTML 2', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-top-row]'          => [
			'title' => __( 'Top row', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-main-row]'         => [
			'title' => __( 'Main row', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-bottom-row]'       => [
			'title' => __( 'Bottom', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-menu-1]'           => [
			'title' => __( 'Primary Menu', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-menu-2]'           => [
			'title' => __( 'Secondary Menu', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-button-1]'         => [
			'title' => __( 'Button 1', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-button-2]'         => [
			'title' => __( 'Button 2', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-menu-3]'           => [
			'title' => __( 'Mobile Menu', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-search]'           => [
			'title' => __( 'Search', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-socials]'          => [
			'title' => __( 'Socials', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[archive]'                 => [
			'title' => __( 'Blog/Archive', 'vite' ),
			'panel' => 'vite[blog]',
		],
		'vite[single]'                  => [
			'title' => __( 'Single Post', 'vite' ),
			'panel' => 'vite[blog]',
		],
		'vite[footer-builder]'          => [
			'title' => __( 'Footer', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-builder-settings]' => [
			'title' => __( 'Footer builder settings', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-html-1]'           => [
			'title' => __( 'HTML 1', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-html-2]'           => [
			'title' => __( 'HTML 2', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-top-row]'          => [
			'title' => __( 'Top row', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-main-row]'         => [
			'title' => __( 'Main row', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-bottom-row]'       => [
			'title' => __( 'Bottom', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-socials]'          => [
			'title' => __( 'Socials', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
		'vite[footer-menu-4]'           => [
			'title' => __( 'Footer Menu', 'vite' ),
			'panel' => 'vite[footer-builder]',
		],
	]
);
