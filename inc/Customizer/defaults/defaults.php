<?php
/**
 * Customizer defaults.
 *
 * @package Vite
 */

$header_button_defaults = array_reduce(
	[ 1, 2 ],
	function( $acc, $curr ) {
		$acc[ "header-button-$curr-text" ]      = __( 'Button', 'vite' );
		$acc[ "header-button-$curr-link" ]      = '#';
		$acc[ "header-button-$curr-target" ]    = false;
		$acc[ "header-button-$curr-nofollow" ]  = false;
		$acc[ "header-button-$curr-sponsored" ] = false;
		$acc[ "header-button-$curr-download" ]  = false;

		return $acc;
	},
	[]
);

$defaults = [
	'global-palette'                 => [
		'--global--color--1' => '#2271b1',
		'--global--color--2' => '#135e96',
		'--global--color--3' => '#100c08',
		'--global--color--4' => '#353f4a',
		'--global--color--5' => '#e7ebfd',
		'--global--color--6' => '#f2f5f7',
		'--global--color--7' => '#eaf0f6',
		'--global--color--8' => '#ffffff',
	],
	'link-colors'                    => [
		'--link--color'        => 'var(--global--color--3)',
		'--link--hover--color' => 'var(--global--color--1)',
	],
	'heading-color'                  => [
		'--heading--color' => 'var(--global--color--3)',
	],
	'text-color'                     => [
		'--text--color' => 'var(--global--color--4)',
	],
	'accent-color'                   => [
		'--accent--color' => 'var(--global--color--1)',
	],
	'button-colors'                  => [
		'--button--color'            => 'var(--global--color--8)',
		'--button--hover--color'     => 'var(--global--color--8)',
		'--button--bg--color'        => 'var(--global--color--1)',
		'--button--hover--bg--color' => 'var(--global--color--2)',
	],
	'border-color'                   => [
		'--border--color' => 'var(--global--color--6)',
	],
	'header-background-color'        => [
		'--header--background--color' => 'var(--global--color--8)',
	],
	'site-background-color'          => [
		'--site--background--color' => 'var(--global--color--5)',
	],
	'footer-background-color'        => [
		'--footer--background--color' => 'var(--global--color--8)',
	],
	'header'                         => [
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
					[ 'id' => 'menu-1' ],
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
					[ 'id' => 'trigger' ],
				],
			],
			'bottom' => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
		],
		'offset'  => [
			[
				'id'       => 'menu-3',
				'filtered' => true,
			],
		],
	],
	'header-html-1'                  => __( 'Enter HTML.', 'vite' ),
	'header-html-2'                  => __( 'Enter HTML.', 'vite' ),
	'footer'                         => [
		'top'    => [],
		'middle' => [],
		'bottom' => [
			[ 'id' => 'html-1' ],
		],
	],
	'footer-html'                    => __( '{{copyright}} {{year}} {{site-title}}', 'vite' ),
	'archive-elements'               => [
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
	'archive-title-elements'         => [
		[
			'id'      => 'title',
			'visible' => true,
		],
		[
			'id'      => 'description',
			'visible' => true,
		],
		[
			'id'      => 'breadcrumbs',
			'visible' => false,
		],
	],
	'archive-layout'                 => 'wide',
	'archive-style'                  => 'grid',
	'archive-columns'                => '3',
	'archive-style-masonry'          => false,
	'archive-title-position'         => 'outside',
	'single-header-elements'         => [
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
	'page-header-elements'           => [
		[
			'id'      => 'title',
			'visible' => true,
		],
		[
			'id'      => 'breadcrumbs',
			'visible' => false,
		],
	],
	'header-top-row-height'          => [
		'desktop' => [
			'value' => 50,
			'unit'  => 'px',
		],
	],
	'header-main-row-height'         => [
		'desktop' => [
			'value' => 70,
			'unit'  => 'px',
		],
	],
	'header-bottom-row-height'       => [
		'desktop' => [
			'value' => 50,
			'unit'  => 'px',
		],
	],
	'header-top-row-layout'          => 'contained',
	'header-main-row-layout'         => 'contained',
	'header-bottom-row-layout'       => 'contained',
	'header-background'              => [
		'color' => 'var(--global--color--8)',
		'type'  => 'color',
	],
	'header-sticky'                  => false,
	'header-sticky-row'              => 'main',
	'header-sticky-style'            => 'default',
	'header-sticky-enable'           => [
		'desktop',
		'tablet',
		'mobile',
	],
	'header-transparent'             => false,
	'header-transparent-enable'      => [
		'desktop',
		'tablet',
		'mobile',
	],
	'container-wide-width'           => 1200,
	'container-narrow-width'         => 728,
	'content-spacing'                => [
		'desktop' => [
			'value' => 4,
			'unit'  => 'rem',
		],
	],
	'buttons-padding'                => [
		'desktop' => [
			'top'    => 10,
			'right'  => 20,
			'bottom' => 10,
			'left'   => 20,
			'unit'   => 'px',
		],
	],
	'buttons-border'                 => [
		'radius' => [
			'desktop' => [
				'value' => 2,
				'unit'  => 'px',
			],
		],
	],
	'header-site-branding-elements'  => 'logo-title',
	'header-site-branding-layout'    => 'inline',
	'header-site-title-typography'   => [
		'size'   => [
			'desktop' => [
				'value' => 24,
				'unit'  => 'px',
			],
		],
		'weight' => '700',
	],
	'header-button-1-style'          => 'filled',
	'header-button-2-style'          => 'outlined',
	'header-search-label'            => '',
	'header-search-label-position'   => 'left',
	'header-search-label-visibility' => [ 'desktop' ],
	'header-search-placeholder'      => __( 'Search', 'vite' ),
	'header-social-links'            => [
		[
			'id'      => 'facebook',
			'value'   => 'facebook',
			'visible' => true,
			'label'   => __( 'Facebook', 'vite' ),
			'color'   => '#3b5998',
		],
		[
			'id'      => 'twitter',
			'value'   => 'twitter',
			'visible' => true,
			'label'   => __( 'Twitter', 'vite' ),
			'color'   => '#1da1f2',
		],
		[
			'id'      => 'instagram',
			'value'   => 'instagram',
			'visible' => true,
			'label'   => __( 'Instagram', 'vite' ),
			'color'   => '#517fa4',
		],
	],
	'header-social-icons-size'       => 20,
	'header-social-icons-color-type' => 'custom',
	'header-social-icons-colors'     => [
		'--link--color'        => 'var(--global--color--3)',
		'--link--hover--color' => 'var(--global--color--1)',
	],
	'header-button-1-font-colors'    => [
		'--button--color'        => 'var(--global--color--8)',
		'--button--hover--color' => 'var(--button--color)',
	],
	'header-button-2-font-colors'    => [
		'--button--color'        => 'var(--global--color--1)',
		'--button--hover--color' => 'var(--button--color)',
	],
	'header-button-1-button-colors'  => [
		'--button--bg--color'        => 'var(--global--color--1)',
		'--button--hover--bg--color' => 'var(--global--color--2)',
	],
	'header-button-2-button-colors'  => [
		'--button--bg--color'        => 'var(--global--color--1)',
		'--button--hover--bg--color' => 'var(--global--color--2)',
	],
	'header-button-1-radius'         => [
		'desktop' => [
			'value' => 2,
			'unit'  => 'px',
		],
	],
	'header-button-2-radius'         => [
		'desktop' => [
			'value' => 2,
			'unit'  => 'px',
		],
	],
	'header-menu-1-items-spacing'    => [
		'value' => 14,
		'unit'  => 'px',
	],
	'header-menu-2-items-spacing'    => [
		'value' => 14,
		'unit'  => 'px',
	],
	'header-menu-1-colors'           => [
		'--link--color'         => 'var(--global--color--3)',
		'--link--hover--color'  => 'var(--global--color--1)',
		'--link--active--color' => 'var(--global--color--1)',
	],
	'header-menu-2-colors'           => [
		'--link--color'         => 'var(--global--color--3)',
		'--link--hover--color'  => 'var(--global--color--1)',
		'--link--active--color' => 'var(--global--color--1)',
	],
	'header-menu-3-colors'           => [
		'--link--color'         => 'var(--global--color--3)',
		'--link--hover--color'  => 'var(--global--color--1)',
		'--link--active--color' => 'var(--global--color--1)',
	],
];

return array_merge( $defaults, $header_button_defaults );
