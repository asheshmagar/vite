<?php

vite( 'customizer' )->add(
	'settings',
	[
		'vite[archive-elements]' => [
			'type'        => 'vite-sortable',
			'control'     => __( 'Archive elements', 'vite' ),
			'section'     => 'vite[archive]',
			'title'       => __( 'Archive elements', 'vite' ),
			'choices'     => [
				[
					'id'    => 'title',
					'label' => __( 'Title', 'vite' ),
				],
				[
					'id'    => 'featured-image',
					'label' => __( 'Featured Image', 'vite' ),
				],
				[
					'id'    => 'meta',
					'label' => __( 'Meta', 'vite' ),
				],
				[
					'id'    => 'excerpt',
					'label' => __( 'Excerpt', 'vite' ),
				],
				[
					'id'    => 'read-more',
					'label' => __( 'Read more', 'vite' ),
				],
			],
			'default'     => vite( 'customizer' )->get_defaults()['archive-elements'],
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
					[
						'id'    => 'comments',
						'label' => __( 'Comments', 'vite' ),
					],
				],
			],
			'transport'   => 'postMessage',
			'partial'     => [
				'selector'            => '.vite-posts',
				'container_inclusive' => false,
				'render_callback'     => function() {
					vite( 'core' )->the_loop();
				},
			],
		],
	]
);
