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
		'vite[global-buttons]'           => [
			'title' => __( 'button', 'vite' ),
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
		'vite[header-html]'             => [
			'title' => __( 'HTML', 'vite' ),
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
		'vite[archive]'                 => [
			'title' => __( 'Archive/Blog', 'vite' ),
		],
		'vite[single]'                  => [
			'title' => __( 'Single Post', 'vite' ),
		],
	]
);
