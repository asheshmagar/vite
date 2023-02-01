<?php
/**
 * Builder Elements.
 *
 * @package Vite
 */

namespace Vite\Elements;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\{ HTMLAttrs, SmartTags };

/**
 * Builder Elements.
 */
class BuilderElements {

	use ElementsTrait , SmartTags {
		SmartTags::filter insteadof ElementsTrait;
		SmartTags::action insteadof ElementsTrait;
		SmartTags::add_action insteadof ElementsTrait;
		SmartTags::add_filter insteadof ElementsTrait;
		SmartTags::remove_action insteadof ElementsTrait;
		SmartTags::remove_filter insteadof ElementsTrait;
	}

	/**
	 * Site branding.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function logo( $args ) {
		$elements = $this->get_mod( 'header-site-branding-elements' );
		$layout   = $this->get_mod( 'header-site-branding-layout' );

		/**
		 * Action: vite/header/site-branding/start
		 *
		 * Fires before header site branding.
		 *
		 * @since 1.0.0
		 */
		$this->action( 'header/site-branding/start' );
		?>
		<div
		<?php
		$this->print_html_attributes(
			'header/site-branding',
			[
				'class' => [
					'vite-brand',
					"vite-brand--$layout",
				],
			]
		);
		?>
		>
			<?php the_custom_logo(); ?>
			<?php if ( in_array( $elements, [ 'logo-title', 'logo-title-description' ], true ) ) : ?>
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1
					<?php
					$this->print_html_attributes(
						'header/site-branding/title',
						[
							'class' => [
								'vite-brand__title',
							],
						]
					);
					?>
					>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</h1>
				<?php else : ?>
					<p
					<?php
					$this->print_html_attributes(
						'header/site-branding/title',
						[
							'class' => [
								'vite-brand__title',
							],
						]
					);
					?>
					>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( 'logo-title-description' === $elements ) : ?>
				<?php if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) : ?>
					<p
					<?php
					$this->print_html_attributes(
						'header/site-branding/description',
						[
							'class' => [
								'vite-brand__title',
							],
						]
					);
					?>
					><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php

		/**
		 * Action: vite/header/site-branding/end
		 *
		 * Fires after header site branding.
		 *
		 * @since 1.0.0
		 */
		$this->action( 'header/site-branding/end' );
	}

	/**
	 * Render header socials.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function socials( $args ) {
		$socials    = $this->get_mod( "{$args['context']}-social-links" );
		$size       = $this->get_mod( "{$args['context']}-social-icons-size" );
		$color_type = $this->get_mod( "{$args['context']}-social-icons-color-type" );

		if ( empty( $socials ) ) {
			return;
		}

		/**
		 * Action: vite/$args['context']/socials/start
		 *
		 * Fires before header socials.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/socials/start" );
		?>
		<div class="vite-social">
			<ul class="vite-social__list">
				<?php
				foreach ( $socials as $social ) {
					if ( ! $social['visible'] || ! isset( $social['id'] ) ) {
						continue;
					}
					?>
					<li class="vite-social__item">
						<a <?php print( esc_attr( 'brand' === $color_type && isset( $social['color'] ) ? 'style=--link-color:' . $social['color'] : '' ) ); ?>
							class="vite-social__link vite-social__link--<?php echo esc_attr( $social['id'] ); ?>"
							rel="noopener"
							href=<?php echo esc_url( $this->get_mod( "{$social['id']}-link", '#' ) ); ?>>
							<?php isset( $social['label'] ) && printf( '<span class="screen-reader-text">%s</span>', esc_html( $social['label'] ) ); ?>
							<span class="vite-social__icon">
						<?php
						vite( 'icon' )->get_icon(
							$social['id'],
							[
								'echo' => true,
								'size' => $size,
							]
						);
						?>
						</span>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php

		/**
		 * Action: vite/$args['context']/socials/end
		 *
		 * Fires after header socials.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/socials/end" );
	}

	/**
	 * Search form.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function search( $args ) {
		$context               = $args['context'];
		$label                 = $this->get_mod( "$context-search-label" );
		$label_visibility      = $this->get_mod( "$context-search-label-visibility" );
		$label_position        = $this->get_mod( "$context-search-label-position" );
		$visibility            = 'none';
		$in_mobile_menu_offset = $args['in_mobile_menu_offset'] ?? false;

		if ( count( $label_visibility ) === 3 ) {
			$visibility = 'all';
		} elseif ( count( $label_visibility ) === 2 ) {
			$visibility = implode( '-', $label_visibility );
		}

		/**
		 * Action: vite/$context/search/start
		 *
		 * Fires before header search.
		 *
		 * @since 1.0.0
		 */
		$this->action( "$context/search/start" );
		?>
		<div class="vite-search">
			<?php if ( ! $in_mobile_menu_offset ) : ?>
			<button class="vite-search__btn" aria-label="<?php esc_attr_e( 'Search modal open button', 'vite' ); ?>">
				<?php if ( ! empty( $label ) ) : ?>
					<span
						class="vite-search__label vite-search__label--pos-<?php esc_attr( $label_position ); ?> vite-search__label--visibility-<?php echo esc_attr( $visibility ); ?>"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>
				<span class="vite-search__icon">
					<?php
					vite( 'icon' )->get_icon(
						'vite-search',
						[
							'echo' => true,
							'size' => 20,
						]
					);
					?>
				</span>
			</button>
			<?php else : ?>
				<?php
				get_search_form(
					[
						'submit_icon'           => true,
						'context'               => 'modal',
						'icon_size'             => 15,
						'in_mobile_menu_offset' => $in_mobile_menu_offset,
					]
				);
				?>
			<?php endif; ?>
		</div>
		<?php

		/**
		 * Action: vite/$args['context']/search/end
		 *
		 * Fires after header search.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/search/end" );
		! $in_mobile_menu_offset && $this->add_action( 'vite/body/close', [ $this, 'search_modal' ], 11 );
	}

	/**
	 * Search modal.
	 *
	 * @return void
	 */
	public function search_modal() {
		/**
		 * Action: vite/header/search/modal/start
		 *
		 * Fires before header search modal.
		 *
		 * @since 1.0.0
		 */
		$this->action( 'header/search/modal/start' );
		?>
		<div class="vite-modal vite-modal--search">
			<div class="vite-modal__action">
				<button class="vite-modal__btn" <?php esc_attr_e( 'Search modal close button', 'vite' ); ?>>
					<?php
					vite( 'icon' )->get_icon(
						'xmark',
						[
							'echo' => true,
							'size' => 20,
						]
					)
					?>
				</button>
			</div>
			<div class="vite-modal__content vite-modal__content--slide-up">
				<?php
				get_search_form(
					[
						'submit_icon' => true,
						'context'     => 'modal',
					]
				);
				?>
			</div>
		</div>
		<?php

		/**
		 * Action: vite/header/search/modal/end
		 *
		 * Fires after header search modal.
		 *
		 * @since 1.0.0
		 */
		$this->action( 'header/search/modal/end' );
	}

	/**
	 * Render header html.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function html( $args ) {
		$type    = $args['type'] ?? '1';
		$content = $this->get_mod( "{$args['context']}-html-$type" );
		$content = $this->parse_smart_tags( (string) $content );

		/**
		 * Action: vite/$args['context']/html/start
		 *
		 * Fires before header html.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/html/start" );
		?>
		<div class="vite-html vite-html--<?php echo esc_attr( $type ); ?>">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php

		/**
		 * Action: vite/$args['context']/html/end
		 *
		 * Fires after header html.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/html/end" );
	}

	/**
	 * Render header button.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function button( $args ) {
		$type      = $args['type'] ?? '1';
		$text      = $this->get_mod( "{$args['context']}-button-$type-text" );
		$url       = $this->get_mod( "{$args['context']}-button-$type-link", '#' );
		$target    = $this->get_mod( "{$args['context']}-button-$type-target" );
		$download  = $this->get_mod( "{$args['context']}-button-$type-download" );
		$sponsored = $this->get_mod( "{$args['context']}-button-$type-sponsored" );
		$nofollow  = $this->get_mod( "{$args['context']}-button-$type-nofollow" );
		$style     = $this->get_mod( "{$args['context']}-button-$type-style" );

		$rel = [];

		if ( $nofollow ) {
			$rel[] = 'nofollow';
		}

		if ( $sponsored ) {
			$rel[] = 'sponsored';
		}

		/**
		 * Action: vite/$args['context']/button/start
		 *
		 * Fires before header button.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/button/start" );
		?>
		<div class="vite-button vite-button--<?php echo esc_attr( $type ); ?>">
			<a
			href="<?php echo esc_url( $url ); ?>"
			<?php
			$this->print_html_attributes(
				"builder/{$args['context']}/button/$type",
				[
					'role'     => 'button',
					'class'    => [
						'vite-button__link',
						"vite-button__link--$style",
					],
					'target'   => esc_attr( $target ? '_blank' : '_self' ),
					'rel'      => $rel,
					'download' => $download ? 'download' : null,
				]
			);
			?>
			>
				<?php echo esc_html( $text ); ?>
			</a>
		</div>
		<?php

		/**
		 * Action: vite/$args['context']/button/end
		 *
		 * Fires after header button.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/button/end" );
	}

	/**
	 * Render trigger.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function trigger( $args ) {

		/**
		 * Action: vite/$args['context']/mobile-menu-trigger/start
		 *
		 * Fires before header mobile menu trigger.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/mobile-menu-trigger/start" );
		?>
		<div class="vite-mobile-menu">
			<button class="vite-mobile-menu__btn"
					aria-label="<?php esc_attr_e( 'Mobile menu modal open button', 'vite' ); ?>">
				<span class="vite-mobile-menu__icon">
					<?php vite( 'icon' )->get_icon( 'bars', [ 'echo' => true ] ); ?>
				</span>
			</button>
		</div>
		<?php

		/**
		 * Action: vite/$args['context']/mobile-menu-trigger/end
		 *
		 * Fires after header mobile menu trigger.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$args['context']}/mobile-menu-trigger/end" );
		$this->add_action( 'vite/body/close', [ $this, 'mobile_menu_offset' ] );
	}

	/**
	 * Render mobile menu offset.
	 *
	 * @return void
	 */
	public function mobile_menu_offset() {
		$offset_config = $this->get_mod( 'header' )['offset'] ?? [];

		/**
		 * Filter: vite/header/mobile-menu-offset/elements.
		 *
		 * @param array $elements Elements.
		 */
		$offset_config = $this->filter( 'header/mobile-menu-offset/elements', $offset_config );

		/**
		 * Action: vite/$args['context']/mobile-menu-offset/start
		 *
		 * Fires before header mobile menu offset.
		 *
		 * @since 1.0.0
		 */
		$this->action( 'header/mobile-menu-offset/start' );
		?>
		<div class="vite-modal vite-modal--mobile-menu">
			<div class="vite-modal__inner">
				<div class="vite-modal__action">
					<button class="vite-modal__btn"
							aria-label="<?php esc_html_e( 'Close mobile menu modal', 'vite' ); ?>">
						<?php
						vite( 'icon' )->get_icon(
							'xmark',
							[
								'echo' => true,
								'size' => 20,
							]
						);
						?>
					</button>
				</div>
				<div class="vite-modal__content">
					<?php
					if ( ! empty( $offset_config ) ) {
						foreach ( $offset_config as $element ) {
							printf( '<div class="vite-element vite-element--%s">', esc_attr( $element['id'] ) );
							preg_match( '/\d+$/', $element['id'], $matches );
							$element_id = preg_replace( '/(_|-)\d+/', '', $element['id'] );
							$type       = $matches[0] ?? null;

							get_template_part(
								"template-parts/builder-elements/$element_id",
								'',
								[
									'type'    => (string) $type,
									'context' => 'header',
									'in_mobile_menu_offset' => true,
								]
							);
							echo '</div>';
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php

		/**
		 * Action: vite/$args['context']/mobile-menu-offset/end
		 *
		 * Fires after header mobile menu offset.
		 *
		 * @since 1.0.0
		 */
		$this->action( 'header/mobile-menu-offset/end' );
	}

	/**
	 * Render menu.
	 *
	 * @param mixed $args Arguments.
	 *
	 * @return void
	 */
	public function menu( $args ) {
		$type    = $args['type'] ?? '1';
		$context = $args['context'] ?? 'header';
		$menu    = null;

		if ( is_customize_preview() ) {
			$menu = $this->get_mod( "$context-menu-$type", '0' );
		}

		/**
		 * Filter: vite/$args['context']/$type/menu.
		 *
		 * Fires before menu.
		 *
		 * @since 1.0.0
		 */
		$menu = $this->filter( "$context/$type/menu", $menu );

		$this->action( "$context/$type/menu/start" );

		vite( 'nav-menu' )->render_menu( (string) $type, $menu, $context );

		/**
		 * Action: vite/$args['context']/$type/menu/end
		 *
		 * Fires after menu.
		 *
		 * @since 1.0.0
		 */
		$this->action( "$context/$type/menu/end" );
	}

	/**
	 * Widget area.
	 *
	 * @param array $args Arguments.
	 *
	 * @return void
	 */
	public function widget( array $args ) {
		$context = $args['context'] ?? 'header';
		$type    = $args['type'] ?? '1';

		/**
		 * Action: vite/$context/widget/$type/end
		 *
		 * Fires before widget.
		 *
		 * @since 1.0.0
		 */
		$this->action( "{$context}/widget/$type/start" );

		vite( 'sidebar' )->render_sidebar(
			[
				'id'          => "$context-widget-$type",
				'wrapper_id'  => "$context-widget-$type",
				'wrapper_tag' => 'div',
				'widget_tag'  => 'div',
			]
		);

		/**
		 * Action: vite/$context/widget/$type/end
		 *
		 * Fires after widget.
		 *
		 * @since 1.0.0
		 */
		$this->action( "$context/widget/$type/end" );
	}
}
