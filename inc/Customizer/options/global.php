<?php
/**
 * Global options.
 */

vite( 'customizer' )->add(
	'settings',
	[
		'vite[global-palette]'         => [
			'title'       => __( 'Global', 'vite' ),
			'type'        => 'vite-color',
			'default'     => vite( 'customizer' )->get_defaults()['global-palette'],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--global--color--1',
						'label' => __( 'Color 1', 'vite' ),
					],
					[
						'id'    => '--global--color--2',
						'label' => __( 'Color 2', 'vite' ),
					],
					[
						'id'    => '--global--color--3',
						'label' => __( 'Color 3', 'vite' ),
					],
					[
						'id'    => '--global--color--4',
						'label' => __( 'Color 4', 'vite' ),
					],
					[
						'id'    => '--global--color--5',
						'label' => __( 'Color 6', 'vite' ),
					],
					[
						'id'    => '--global--color--6',
						'label' => __( 'Color 6', 'vite' ),
					],
					[
						'id'    => '--global--color--7',
						'label' => __( 'Color 7', 'vite' ),
					],
					[
						'id'    => '--global--color--8',
						'label' => __( 'Color 8', 'vite' ),
					],
				],
			],
			'section'     => 'vite[global-colors]',
			'selectors'   => [ ':root' ],
			'properties'  => [ '' ],
		],
		'vite[text-color]'             => [
			'section'     => 'vite[global-colors]',
			'type'        => 'vite-color',
			'title'       => __( 'Text color', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['text-color'],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--text--color',
						'label' => __( 'Normal', 'vite' ),
					],
				],
			],
			'transport'   => 'postMessage',
			'selectors'   => [ ':root' ],
			'properties'  => [ '' ],
		],
		'vite[link-colors]'            => [
			'section'     => 'vite[global-colors]',
			'type'        => 'vite-color',
			'title'       => __( 'Link colors', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['text-color'],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--link--color',
						'label' => __( 'Normal', 'vite' ),
					],
					[
						'id'    => '--link--hover--color',
						'label' => __( 'Hover', 'vite' ),
					],
				],
			],
			'transport'   => 'postMessage',
			'selectors'   => [ ':root' ],
			'properties'  => [ '' ],
		],
		'vite[heading-color]'          => [
			'section'     => 'vite[global-colors]',
			'type'        => 'vite-color',
			'title'       => __( 'Heading color', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['heading-color'],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--heading--color',
						'label' => __( 'Normal', 'vite' ),
					],
				],
			],
			'transport'   => 'postMessage',
			'selectors'   => [ ':root' ],
			'properties'  => [ '' ],
		],
		'vite[button-colors]'          => [
			'section'     => 'vite[global-colors]',
			'type'        => 'vite-color',
			'title'       => __( 'Button colors', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['button-colors'],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--button--color',
						'label' => __( 'Normal', 'vite' ),
					],
					[
						'id'    => '--button--hover--color',
						'label' => __( 'Hover', 'vite' ),
					],
					[
						'id'    => '--button--bg--color',
						'label' => __( 'Normal background', 'vite' ),
					],
					[
						'id'    => '--button--hover--bg--color',
						'label' => __( 'Hover background', 'vite' ),
					],
				],
			],
			'transport'   => 'postMessage',
			'selectors'   => [ ':root' ],
			'properties'  => [ '' ],
		],
		'vite[border-color]'           => [
			'section'     => 'vite[global-colors]',
			'type'        => 'vite-color',
			'title'       => __( 'Border color', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['border-color'],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--border--color',
						'label' => __( 'Border color', 'vite' ),
					],
				],
			],
			'transport'   => 'postMessage',
			'selectors'   => [ ':root' ],
			'properties'  => [ '' ],
		],
		'vite[base-typography]'        => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Base', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[h1-typography]'          => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Heading 1 (h1)', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[h2-typography]'          => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Heading 2 (h2)', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[h3-typography]'          => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Heading 3 (h3)', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[h4-typography]'          => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Heading 4 (h4)', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[h5-typography]'          => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Heading 5 (h5)', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[h6-typography]'          => [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => __( 'Heading 6 (h6)', 'vite' ),
			'selectors' => [ 'body' ],
		],
		'vite[container-wide-width]'   => [
			'section'     => 'vite[global-layout]',
			'type'        => 'vite-slider',
			'title'       => __( 'Container max width (px)', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['container-wide-width'],
			'input_attrs' => [
				'min'  => 728,
				'max'  => 2000,
				'step' => 1,
				'unit' => 'px',
			],
		],
		'vite[container-narrow-width]' => [
			'section'     => 'vite[global-layout]',
			'type'        => 'vite-slider',
			'title'       => __( 'Container narrow width (px)', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['container-narrow-width'],
			'input_attrs' => [
				'min'  => 428,
				'max'  => 1028,
				'step' => 1,
				'unit' => 'px',
			],
		],
		'vite[content-spacing]'        => [
			'section'     => 'vite[global-layout]',
			'type'        => 'vite-slider',
			'title'       => __( 'Content spacing', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['content-spacing'],
			'input_attrs' => [
				'noUnits'    => false,
				'units'      => [ 'px', 'rem', 'em' ],
				'responsive' => true,
			],
			'selectors'   => [ '.site-content' ],
			'properties'  => [ 'padding-block' ],
		],
		'vite[buttons-padding]'        => [
			'section'     => 'vite[global-buttons]',
			'type'        => 'vite-dimensions',
			'title'       => __( 'Padding', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['buttons-padding'],
			'input_attrs' => [
				'noUnits'    => false,
				'units'      => [ 'px', 'rem', 'em' ],
				'responsive' => true,
			],
		],
		'vite[buttons-border]'         => [
			'section' => 'vite[global-buttons]',
			'type'    => 'vite-border',
			'title'   => __( 'Border', 'vite' ),
		],
	]
);

$social_medias = require __DIR__ . '/social-medias.php';

$social_media_links_settings = array_reduce(
	array_keys( $social_medias ),
	function( $acc, $curr ) use ( $social_medias ) {
		$acc[ "vite[$curr-link]" ] = [
			'section' => 'vite[socials]',
			'type'    => 'text',
			'title'   => $social_medias[ $curr ]['label'],
			'default' => '',
		];
		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $social_media_links_settings );

