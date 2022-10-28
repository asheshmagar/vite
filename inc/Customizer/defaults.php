<?php
/**
 * Customizer defaults.
 *
 * @package Vite
 */

return [
	'header'                 => [
		'desktop' => [
			'top'    => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
			'main'   => [
				'left'   => [ 'logo' ],
				'center' => [],
				'right'  => [ 'primary-menu', 'search' ],
			],
			'bottom' => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
		],
		'mobile'  => [
			'top'    => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
			'main'   => [
				'left'   => [ 'logo' ],
				'center' => [],
				'right'  => [ 'mobile-menu-trigger' ],
			],
			'bottom' => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
		],
	],
	'header-html'            => __( 'Enter HTML.', 'vite' ),
	'header-button-text'     => __( 'Button', 'vite' ),
	'header-button-url'      => '#',
	'footer'                 => [
		'top'    => [],
		'middle' => [],
		'bottom' => [
			'1' => [ 'html' ],
		],
	],
	'footer-html'            => __( '{{copyright}} {{year}} {{site-title}}', 'vite' ),
	'archive-elements'       => [
		[
			'id'      => 'meta-1',
			'visible' => true,
			'items'   => [
				[
					'id'      => 'categories',
					'visible' => true,
				],
			],
		],
		[
			'id'      => 'featured-image',
			'visible' => true,
		],
		[
			'id'      => 'title',
			'visible' => true,
		],
		[
			'id'      => 'meta-2',
			'visible' => true,
			'items'   => [
				[
					'id'      => 'author',
					'visible' => true,
				],
				[
					'id'      => 'published-date',
					'visible' => true,
				],
				[
					'id'      => 'comments',
					'visible' => true,
				],
			],
		],
		[
			'id'      => 'excerpt',
			'visible' => true,
		],
		[
			'id'      => 'read-more',
			'visible' => true,
		],
	],
	'single-header-elements' => [
		[
			'id'      => 'title',
			'visible' => true,
		],
		[
			'id'      => 'meta-2',
			'visible' => true,
			'items'   => [
				[
					'id'      => 'author',
					'visible' => true,
				],
				[
					'id'      => 'published-date',
					'visible' => true,
				],
				[
					'id'      => 'comments',
					'visible' => true,
				],
			],
		],
		[
			'id'      => 'breadcrumbs',
			'visible' => false,
		],
	],
	'page-header-elements'   => [
		[
			'id'      => 'title',
			'visible' => true,
		],
		[
			'id'      => 'breadcrumbs',
			'visible' => false,
		],
	],
];
