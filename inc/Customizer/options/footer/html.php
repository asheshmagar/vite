<?php
/**
 * Header HTML options.
 *
 * @package Vite
 */

$options = array_reduce(
	[ 1, 2 ],
	function( $acc, $curr ) {
		$acc[ "vite[footer-html-$curr]" ] = [
			'section' => "vite[footer-html-$curr]",
			'type'    => 'vite-editor',
			'title'   => __( 'HTML', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "footer-html-$curr" ],
			'partial' => [
				'selector'        => ".vite-footer .vite-html--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part(
						'template-parts/builder-elements/html',
						'',
						[
							'type'    => $curr,
							'context' => 'footer',
						]
					);
				},
			],
		];
		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
