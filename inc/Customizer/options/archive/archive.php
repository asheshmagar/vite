<?php
/**
 * Archive options.
 *
 * @package Vite
 */

$posts_render_callback = function() {
	$archive_style           = vite( 'core' )->get_theme_mod( 'archive-style' );
	$archive_columns         = vite( 'core' )->get_theme_mod( 'archive-columns' );
	$is_masonry              = 'grid' === $archive_style && vite( 'core' )->get_theme_mod( 'archive-style-masonry' );
	$archive_wrapper_classes = [
		'vite-posts',
		'vite-posts--' . $archive_style,
	];

	if ( 'grid' === $archive_style ) {
		$archive_wrapper_classes[] = 'vite-posts--col-' . $archive_columns;
	}

	if ( $is_masonry ) {
		$archive_wrapper_classes[] = 'vite-posts--masonry';
	}

	$archive_wrapper_classes = vite( 'core' )->filter( 'archive/wrapper/classes', $archive_wrapper_classes );

	printf(
		'<div class="%s">',
		esc_attr( implode( ' ', array_unique( $archive_wrapper_classes ) ) )
	);

	vite( 'core' )->the_loop();

	echo '</div>';
};

vite( 'customizer' )->add(
	'settings',
	[
		'vite[archive-title-position]' => [
			'type'      => 'vite-select',
			'title'     => __( 'Title Position', 'vite' ),
			'section'   => 'vite[archive]',
			'choices'   => [
				'outside' => __( 'Outside content', 'vite' ),
				'inside'  => __( 'Inside content', 'vite' ),
			],
			'default'   => vite( 'core' )->get_theme_mod_defaults()['archive-title-position'],
			'transport' => 'refresh',
		],
		'vite[archive-title-elements]' => [
			'type'    => 'vite-sortable',
			'title'   => __( 'Title Elements', 'vite' ),
			'section' => 'vite[archive]',
			'choices' => [
				[
					'id'    => 'title',
					'label' => __( 'Title', 'vite' ),
				],
				[
					'id'    => 'description',
					'label' => __( 'Description', 'vite' ),
				],
				[
					'id'    => 'breadcrumbs',
					'label' => __( 'Breadcrumbs', 'vite' ),
				],
			],
			'default' => vite( 'core' )->get_theme_mod_defaults()['archive-title-elements'],
			'partial' => [
				'selector'            => '.vite-page-header',
				'container_inclusive' => true,
				'render_callback'     => function() {
					$archive_title_elements = vite( 'core' )->get_theme_mod( 'archive-title-elements' );
					get_template_part( 'template-parts/page-header/page-header', '', [ 'elements' => $archive_title_elements ] );
				},
			],
		],
		'vite[archive-layout]'         => [
			'section'     => 'vite[archive]',
			'type'        => 'vite-select',
			'title'       => __( 'Layout', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()['archive-layout'],
			'choices'     => [
				'wide'          => __( 'Wide', 'vite' ),
				'narrow'        => __( 'Narrow', 'vite' ),
				'fullwidth'     => __( 'Fullwidth', 'vite' ),
				'left-sidebar'  => __( 'Left Sidebar', 'vite' ),
				'right-sidebar' => __( 'Right Sidebar', 'vite' ),
			],
			'transport'   => 'refresh',
			'input_attrs' => [
				'separator' => true,
			],
		],
		'vite[archive-style]'          => [
			'section'     => 'vite[archive]',
			'type'        => 'vite-select',
			'title'       => __( 'Style', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()['archive-style'],
			'choices'     => [
				'grid' => __( 'Grid', 'vite' ),
				'list' => __( 'List', 'vite' ),
			],
			'partial'     => [
				'selector'            => '.vite-posts',
				'container_inclusive' => true,
				'render_callback'     => $posts_render_callback,
			],
			'input_attrs' => [
				'separator' => true,
			],
		],
		'vite[archive-columns]'        => [
			'section'   => 'vite[archive]',
			'type'      => 'vite-buttonset',
			'title'     => __( 'Columns', 'vite' ),
			'default'   => vite( 'core' )->get_theme_mod_defaults()['archive-columns'],
			'choices'   => [
				'1' => __( '1', 'vite' ),
				'2' => __( '2', 'vite' ),
				'3' => __( '3', 'vite' ),
				'4' => __( '4', 'vite' ),
			],
			'condition' => [
				'vite[archive-style]' => 'grid',
			],
			'partial'   => [
				'selector'            => '.vite-posts',
				'container_inclusive' => true,
				'render_callback'     => $posts_render_callback,
			],
		],
		'vite[archive-style-masonry]'  => [
			'section'   => 'vite[archive]',
			'type'      => 'vite-toggle',
			'title'     => __( 'Masonry', 'vite' ),
			'default'   => vite( 'core' )->get_theme_mod_defaults()['archive-style-masonry'],
			'condition' => [
				'vite[archive-style]'    => 'grid',
				'vite[archive-columns]!' => '1',
			],
			'partial'   => [
				'selector'            => '.vite-posts',
				'container_inclusive' => true,
				'render_callback'     => $posts_render_callback,
			],
		],
		'vite[archive-elements]'       => [
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
			'default'     => vite( 'core' )->get_theme_mod_defaults()['archive-elements'],
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
				'separator'        => true,
			],
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
