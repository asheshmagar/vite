<?php
/**
 * Mods trait.
 *
 * @package Vite
 */

namespace Vite\Traits;

/**
 * Mods trait.
 */
trait Mods {

	use Hook;

	/**
	 * Get the theme mod.
	 *
	 * @param string $key Theme mod key.
	 * @param mixed  $default Default.
	 *
	 * @return false|mixed|null
	 */
	public function get_mod( string $key, $default = false ) {
		$mods = get_theme_mod( 'vite' );

		if ( isset( $mods[ $key ] ) ) {
			return $this->filter( "mod/$key", $mods[ $key ] );
		}

		return $this->get_mod_defaults()[ $key ] ?? $default;
	}

	/**
	 * Get all theme mod defaults.
	 *
	 * @return mixed|null
	 */
	public function get_mod_defaults() {
		$header_button_defaults = array_reduce(
			[ 1, 2 ],
			function ( $acc, $curr ) {
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
				'--color-0' => '#2271b1',
				'--color-1' => '#135e96',
				'--color-2' => '#100c08',
				'--color-3' => '#353f4a',
				'--color-4' => '#e7ebfd',
				'--color-6' => '#f2f5f7',
				'--color-5' => '#eaf0f6',
				'--color-7' => '#ffffff',
			],
			'link-colors'                    => [
				'--link-color'       => 'var(--color-2)',
				'--link-hover-color' => 'var(--color-0)',
			],
			'heading-color'                  => [
				'--heading--color' => 'var(--color-2)',
			],
			'text-color'                     => [
				'--text--color' => 'var(--color-3)',
			],
			'accent-color'                   => [
				'--accent--color' => 'var(--color-0)',
			],
			'button-colors'                  => [
				'--button-color'          => 'var(--color-7)',
				'--button-hover-color'    => 'var(--color-7)',
				'--button-bg-color'       => 'var(--color-0)',
				'--button-hover-bg-color' => 'var(--color-1)',
			],
			'border-color'                   => [
				'--border-color' => 'var(--color-6)',
			],
			'header-background-color'        => [
				'--header-bg-color' => 'var(--color-7)',
			],
			'site-background-color'          => [
				'--site-bg-color' => 'var(--color-4)',
			],
			'footer-background-color'        => [
				'--footer-bg-color' => 'var(--color-7)',
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
						'id' => 'menu-3',
					],
				],
			],
			'header-html-1'                  => __( 'Enter HTML.', 'vite' ),
			'header-html-2'                  => __( 'Enter HTML.', 'vite' ),
			'header-html-1-alignment'        => [
				'desktop' => 'left',
				'mobile'  => 'left',
				'tablet'  => 'left',
			],
			'header-html-2-alignment'        => [
				'desktop' => 'left',
				'mobile'  => 'left',
				'tablet'  => 'left',
			],
			'header-html-1-display'          => [ 'desktop', 'mobile', 'tablet' ],
			'header-html-2-display'          => [ 'desktop', 'mobile', 'tablet' ],
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
				'color' => 'var(--color-7)',
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
			'container-wide-width'           => [
				'value' => 1200,
				'unit'  => 'px',
			],
			'container-narrow-width'         => [
				'value' => 728,
				'unit'  => 'px',
			],
			'content-spacing'                => [
				'desktop' => [
					'top'    => 4,
					'right'  => 'auto',
					'bottom' => 4,
					'left'   => 'auto',
					'unit'   => 'rem',
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
			'buttons-colors'                 => [
				'--button-color'       => 'var(--color-7)',
				'--button-hover-color' => 'var(--color-7)',
			],
			'buttons-bg-colors'              => [
				'--button-bg-color'       => 'var(--color-0)',
				'--button-hover-bg-color' => 'var(--color-1)',
			],
			'buttons-border'                 => [],
			'buttons-border-radius'          => [
				'desktop' => [
					'top'    => 2,
					'right'  => 2,
					'bottom' => 2,
					'left'   => 2,
					'unit'   => 'px',
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
				'--link-color'       => 'var(--color-2)',
				'--link-hover-color' => 'var(--color-0)',
			],
			'header-button-1-font-colors'    => [
				'--button-color'       => 'var(--color-7)',
				'--button-hover-color' => 'var(--button-color)',
			],
			'header-button-2-font-colors'    => [
				'--button-color'       => 'var(--color-0)',
				'--button-hover-color' => 'var(--button-color)',
			],
			'header-button-1-button-colors'  => [
				'--button-bg-color'       => 'var(--color-0)',
				'--button-hover-bg-color' => 'var(--color-1)',
			],
			'header-button-2-button-colors'  => [
				'--button-bg-color'       => 'var(--color-0)',
				'--button-hover-bg-color' => 'var(--color-1)',
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
				'--link-color'          => 'var(--color-2)',
				'--link-hover-color'    => 'var(--color-0)',
				'--link--active--color' => 'var(--color-0)',
			],
			'header-menu-2-colors'           => [
				'--link-color'          => 'var(--color-2)',
				'--link-hover-color'    => 'var(--color-0)',
				'--link--active--color' => 'var(--color-0)',
			],
			'header-menu-3-colors'           => [
				'--link-color'          => 'var(--color-2)',
				'--link-hover-color'    => 'var(--color-0)',
				'--link--active--color' => 'var(--color-0)',
			],
			'footer'                         => [
				'desktop' => [
					'top'    => [
						'1' => [],
						'2' => [],
						'3' => [],
					],
					'main'   => [
						'1' => [],
						'2' => [],
						'3' => [],
					],
					'bottom' => [
						'1' => [],
						'2' => [ [ 'id' => 'html-1' ] ],
						'3' => [],
					],
				],
			],
			'footer-html-1'                  => '{{copyright}} {{year}} {{site-title}} | {{theme-author}}',
			'footer-html-2'                  => __( 'Enter HTML.', 'vite' ),
			'footer-html-1-alignment'        => [
				'desktop' => 'center',
				'mobile'  => 'center',
				'tablet'  => 'center',
			],
			'footer-html-2-alignment'        => [
				'desktop' => 'center',
				'mobile'  => 'center',
				'tablet'  => 'center',
			],
			'footer-html-1-display'          => [ 'desktop', 'mobile', 'tablet' ],
			'footer-html-2-display'          => [ 'desktop', 'mobile', 'tablet' ],
			'footer-top-row-layout'          => 'contained',
			'footer-top-row-cols'            => 3,
			'footer-main-row-layout'         => 'contained',
			'footer-main-row-cols'           => 3,
			'footer-bottom-row-layout'       => 'contained',
			'footer-bottom-row-cols'         => 3,
			'footer-social-links'            => [
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
			'footer-social-icons-size'       => 20,
			'footer-social-icons-color-type' => 'custom',
			'footer-social-icons-colors'     => [
				'--link-color'       => 'var(--color-2)',
				'--link-hover-color' => 'var(--color-0)',
			],
			'footer-menu-4-items-spacing'    => [
				'value' => 14,
				'unit'  => 'px',
			],
			'footer-menu-4-colors'           => [
				'--link-color'          => 'var(--color-2)',
				'--link-hover-color'    => 'var(--color-0)',
				'--link--active--color' => 'var(--color-0)',
			],
			'footer-top-row-height'          => [
				'desktop' => [
					'value' => 70,
					'unit'  => 'px',
				],
			],
			'footer-main-row-height'         => [
				'desktop' => [
					'value' => 70,
					'unit'  => 'px',
				],
			],
			'footer-bottom-row-height'       => [
				'desktop' => [
					'value' => 70,
					'unit'  => 'px',
				],
			],
			'scroll-to-top'                  => true,
			'scroll-to-top-position'         => 'right',
			'scroll-to-top-edge-offset'      => [
				'desktop' => [
					'value' => 30,
					'unit'  => 'px',
				],
			],
			'scroll-to-top-bottom-offset'    => [
				'desktop' => [
					'value' => 30,
					'unit'  => 'px',
				],
			],
			'scroll-to-top-icon-size'        => 13,
			'scroll-to-top-button-size'      => [
				'desktop' => [
					'value' => 40,
					'unit'  => 'px',
				],
			],
			'scroll-to-top-border'           => [],
			'scroll-to-top-radius'           => [
				'top-left'     => 2,
				'top-right'    => 2,
				'bottom-left'  => 2,
				'bottom-right' => 2,
				'unit'         => 'px',
			],
		];

		$footer_row_layout_defaults = array_reduce(
			[ 'top', 'main', 'bottom' ],
			function ( $acc, $curr ) {
				$acc[ "footer-$curr-row-col-layout" ] = [
					'1' => [
						'desktop' => '100',
						'tablet'  => 'stacked',
						'mobile'  => 'stacked',
					],
					'2' => [
						'desktop' => '50-50',
						'tablet'  => 'stacked',
						'mobile'  => 'stacked',
					],
					'3' => [
						'desktop' => '33-33-33',
						'tablet'  => 'stacked',
						'mobile'  => 'stacked',
					],
					'4' => [
						'desktop' => '25-25-25-25',
						'tablet'  => 'stacked',
						'mobile'  => 'stacked',
					],
					'5' => [
						'desktop' => '20-20-20-20-20',
						'tablet'  => 'stacked',
						'mobile'  => 'stacked',
					],
					'6' => [
						'desktop' => '16-16-16-16-16-16',
						'tablet'  => 'stacked',
						'mobile'  => 'stacked',
					],
				];

				return $acc;
			},
			[]
		);

		return $this->filter( 'mod/defaults', array_merge( $defaults, $header_button_defaults, $footer_row_layout_defaults ) );
	}

	/**
	 * Get theme mod default.
	 *
	 * @param string $key Theme mod key.
	 *
	 * @return mixed|null
	 */
	public function get_mod_default( string $key ) {
		return $this->get_mod_defaults()[ $key ] ?? null;
	}

	/**
	 * Set theme mod.
	 *
	 * @param string $key Theme mod key.
	 * @param mixed  $value Theme mod value.
	 *
	 * @return void
	 */
	public function set_mod( string $key, $value ) {
		$mods         = get_theme_mod( 'vite' );
		$mods[ $key ] = $value;

		set_theme_mod( 'vite', $mods );
	}

	/**
	 * Remove theme mod.
	 *
	 * @param string      $key Theme mod key.
	 * @param bool|string $migrate Migration key.
	 * @return void
	 */
	public function remove_mod( string $key, $migrate = false ) {
		$mods = get_theme_mod( 'vite' );

		if ( $migrate && is_string( $migrate ) ) {
			$mods[ $migrate ] = $mods[ $key ];
		}

		unset( $mods[ $key ] );

		set_theme_mod( 'vite', $mods );
	}
}
