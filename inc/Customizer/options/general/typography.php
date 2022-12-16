<?php
/**
 * Global typography options.
 *
 * @package Vite
 */

$elements = [
	'base'     => __( 'Base', 'vite' ),
	'headings' => __( 'Headings', 'vite' ),
	'h1'       => __( 'H1', 'vite' ),
	'h2'       => __( 'H2', 'vite' ),
	'h3'       => __( 'H3', 'vite' ),
	'h4'       => __( 'H4', 'vite' ),
	'h5'       => __( 'H5', 'vite' ),
	'h6'       => __( 'H6', 'vite' ),
];

$options = array_reduce(
	array_keys( $elements ),
	function( $acc, $curr ) use ( $elements ) {
		$acc[ "vite[$curr-typography]" ] = [
			'section'   => 'vite[global-typography]',
			'type'      => 'vite-typography',
			'title'     => $elements[ $curr ],
			'selectors' => [ 'body' ],
		];

		if ( 'base' !== $curr ) {
			$acc[ "vite[$curr-typography]" ]['input_attrs'] = [
				'separator' => true,
			];
		}
		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
