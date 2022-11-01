<?php
/**
 * Customizer defaults.
 *
 * @package Vite
 */

return [
	'global-palette'          => [
		'--global--color--1' => '#2271b1',
		'--global--color--2' => '#135e96',
		'--global--color--3' => '#100c08',
		'--global--color--4' => '#353f4a',
		'--global--color--5' => '#e7ebfd',
		'--global--color--6' => '#f2f5f7',
		'--global--color--7' => '#eaf0f6',
		'--global--color--8' => '#ffffff',
	],
	'link-colors'             => [
		'--link--color'        => 'var(--global--color--3)',
		'--link--hover--color' => 'var(--global--color--1)',
	],
	'heading-color'           => [
		'--heading--color' => 'var(--global--color--3)',
	],
	'text-color'              => [
		'--text--color' => 'var(--global--color--4)',
	],
	'accent-color'            => [
		'--accent--color' => 'var(--global--color--1)',
	],
	'button-colors'           => [
		'--button--color'            => 'var(--global--color--8)',
		'--button--hover--color'     => 'var(--global--color--8)',
		'--button--bg--color'        => 'var(--global--color--1)',
		'--button--hover--bg--color' => 'var(--global--color--2)',
	],
	'border-color'            => [
		'--border--color' => 'var(--global--color--6)',
	],
	'header-background-color' => [
		'--header--background--color' => 'var(--global--color--8)',
	],
	'site-background-color'   => [
		'--site--background--color' => 'var(--global--color--5)',
	],
	'footer-background-color' => [
		'--footer--background--color' => 'var(--global--color--8)',
	],
	'header'                  => [
		'desktop' => [
			'top'    => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
			'main'   => [
				'left'   => [
					[ 'id' => 'logo' ],
				],
				'center' => [],
				'right'  => [
					[ 'id' => 'primary-menu' ],
					[ 'id' => 'search' ],
				],
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
				'left'   => [
					[ 'id' => 'logo' ],
				],
				'center' => [],
				'right'  => [
					[ 'id' => 'mobile-menu-trigger' ],
				],
			],
			'bottom' => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
		],
	],
	'header-html'             => __( 'Enter HTML.', 'vite' ),
	'header-button-text'      => __( 'Button', 'vite' ),
	'header-button-url'       => '#',
	'footer'                  => [
		'top'    => [],
		'middle' => [],
		'bottom' => [
			'1' => [ 'html' ],
		],
	],
	'footer-html'             => __( '{{copyright}} {{year}} {{site-title}}', 'vite' ),
	'archive-elements'        => [
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
	'single-header-elements'  => [
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
	'page-header-elements'    => [
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
