<?php

vite( 'customizer' )->add(
	'settings',
	[
		'vite[single-tabs]'                => [
			'type'    => 'vite-tabs',
			'section' => 'vite[single]',
		],
		'vite[single-header-elements]'     => [
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
			'default'     => vite( 'customizer' )->get_defaults()['single-header-elements'],
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
			'transport'   => 'postMessage',
			'partial'     => [
				'selector'            => '.vite-single',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/content/content', 'single' );
				},
			],
			'condition'   => [
				'vite-tab!' => 'general',
			],
		],
		'vite[single-related-posts]'       => [
			'type'      => 'vite-toggle',
			'title'     => __( 'Related Posts', 'vite' ),
			'default'   => false,
			'condition' => [
				'vite-tab' => 'general',
			],
			'section'   => 'vite[single]',
		],
		'vite[single-related-posts-input]' => [
			'type'      => 'vite-input',
			'title'     => __( 'Related Posts Count', 'vite' ),
			'default'   => 3,
			'condition' => [
				'vite-tab'                   => 'general',
				'vite[single-related-posts]' => true,
			],
			'section'   => 'vite[single]',
		],
		'vite[single-related-posts-test]'  => [
			'type'       => 'vite-input',
			'title'      => __( 'Related Posts Count', 'vite' ),
			'default'    => 3,
			'conditions' => [
				'terms' => [
					[
						'name'     => 'vite-tab',
						'value'    => 'design',
						'operator' => '==',
					],
				],
			],
			'section'    => 'vite[single]',
		],
	]
);
