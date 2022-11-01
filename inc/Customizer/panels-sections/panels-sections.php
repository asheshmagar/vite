<?php

vite( 'customizer' )->add(
	'panels',
	[
		'vite[global]'               => [
			'title' => __( 'Global', 'vite' ),
		],
		'vite[header-builder-panel]' => [
			'title' => __( 'Header', 'vite' ),
		],
	]
);

vite( 'customizer' )->add(
	'sections',
	[
		'vite[global-colors]'          => [
			'title' => __( 'Colors', 'vite' ),
			'panel' => 'vite[global]',
		],
		'vite[global-typography]'      => [
			'title' => __( 'Typography', 'vite' ),
			'panel' => 'vite[global]',
		],
		'vite[header-builder-section]' => [
			'title' => __( 'Header', 'vite' ),
			'panel' => 'vite[header-builder-panel]',
		],
		'vite[archive]'                => [
			'title' => __( 'Archive/Blog', 'vite' ),
		],
		'vite[single]'                 => [
			'title' => __( 'Single Post', 'vite' ),
		],
	]
);
