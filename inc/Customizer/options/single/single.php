<?php
/**
 * Single post options.
 *
 * @package Vite
 */

vite( 'customizer' )->add(
	'settings',
	[
		'vite[single-header-elements]' => [
			'type'        => 'vite-sortable',
			'title'       => __( 'Header/Title elements', 'vite' ),
			'section'     => 'vite[single]',
			'choices'     => [
				[
					'id'    => 'title',
					'label' => __( 'Title', 'vite' ),
				],
				[
					'id'    => 'meta',
					'label' => __( 'Meta', 'vite' ),
				],
				[
					'id'    => 'breadcrumbs',
					'label' => __( 'Breadcrumbs', 'vite' ),
				],
			],
			'default'     => vite( 'core' )->get_theme_mod_defaults()['single-header-elements'],
			'input_attrs' => [
				'idWithInnerItems' => 'meta',
				'innerItems'       => [
					[
						'id'    => 'author',
						'label' => __( 'Author', 'vite' ),
					],
					[
						'id'    => 'published-date',
						'label' => __( 'Published Date', 'vite' ),
					],
					[
						'id'    => 'updated-date',
						'label' => __( 'Updated Date', 'vite' ),
					],
					[
						'id'    => 'categories',
						'label' => __( 'Categories', 'vite' ),
					],
					[
						'id'    => 'tags',
						'label' => __( 'Tags', 'vite' ),
					],
				],
			],
			'partial'     => [
				'selector'            => '.vite-single',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/content/content', 'single' );
				},
			],
		],
	]
);
