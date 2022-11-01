<?php

vite( 'customizer' )->add(
	'settings',
	[
		'vite[header]' => [
			'section'     => 'vite[header-builder-section]',
			'type'        => 'vite-header-builder',
			'title'       => __( 'Header', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['header'],
			'choices'     => [
				'logo'           => [
					'name'    => __( 'Logo', 'vite' ),
					'section' => 'label_tagline',
				],
				'primary-menu'   => [
					'name'    => __( 'Primary menu', 'vite' ),
					'section' => 'vite[header][primary-menu]',
				],
				'secondary-menu' => [
					'name'    => __( 'Secondary menu', 'vite' ),
					'section' => 'vite[header][secondary-menu]',
				],
				'search'         => [
					'name'    => __( 'Search', 'vite' ),
					'section' => 'vite[header][search]',
				],
				'button'         => [
					'name'    => __( 'Button', 'vite' ),
					'section' => 'vite[header][button]',
				],
				'social'         => [
					'name'    => __( 'Social', 'vite' ),
					'section' => 'vite[header][social]',
				],
				'html'           => [
					'name'    => __( 'HTML', 'vite' ),
					'section' => 'vite[header][html]',
				],
			],
			'input_attrs' => [
				'areas' => [
					'top'    => [
						'left'   => 'Top Left',
						'center' => 'Top Center',
						'right'  => 'Top Right',
					],
					'main'   => [
						'left'   => 'Main Left',
						'center' => 'Main center',
						'right'  => 'Main Right',
					],
					'bottom' => [
						'left'   => 'Bottom Left',
						'center' => 'Bottom Center',
						'right'  => 'Bottom Right',
					],
				],
			],
			'partial'     => [
				'selector'            => '.site-header',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', '' );
				},
			],
		],
	]
);
