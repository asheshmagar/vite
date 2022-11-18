<?php

vite( 'customizer' )->add(
	'panels',
	[
		'vite[global]'         => [
			'title' => __( 'Global', 'vite' ),
		],
		'vite[header-builder]' => [
			'title' => __( 'Header', 'vite' ),
		],
	]
);

vite( 'customizer' )->add(
	'sections',
	[
		'vite[global-colors]'           => [
			'title' => __( 'Colors', 'vite' ),
			'panel' => 'vite[global]',
		],
		'vite[global-typography]'       => [
			'title' => __( 'Typography', 'vite' ),
			'panel' => 'vite[global]',
		],
		'vite[global-layout]'           => [
			'title' => __( 'Layout', 'vite' ),
			'panel' => 'vite[global]',
		],
		'vite[global-buttons]'          => [
			'title' => __( 'Button', 'vite' ),
			'panel' => 'vite[global]',
		],
		'vite[socials]'                 => [
			'title' => __( 'Social Accounts Links', 'vite' ),
			'panel' => 'vite[global]',
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
		'vite[header-primary-menu]'     => [
			'title' => __( 'Primary Menu', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-secondary-menu]'   => [
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
		'vite[header-mobile-menu]'      => [
			'title' => __( 'Mobile Menu', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[header-search]'           => [
			'title' => __( 'Search', 'vite' ),
			'panel' => 'vite[header-builder]',
		],
		'vite[archive]'                 => [
			'title' => __( 'Archive/Blog', 'vite' ),
		],
		'vite[single]'                  => [
			'title' => __( 'Single Post', 'vite' ),
		],
	]
);
