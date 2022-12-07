<?php
/**
 * Builder Elements.
 *
 * @package Vite
 */

namespace Vite\Elements;

/**
 * Builder Elements.
 */
class BuilderElements extends Elements {

	/**
	 * Context.
	 *
	 * @var string|null
	 */
	protected ?string $context;

	/**
	 * {@inheritDoc}
	 *
	 * @param string $context Context.
	 * @return void
	 */
	public function set_context( string $context = '' ) {
		$this->context = $context;
	}

	/**
	 * Site branding.
	 *
	 * @return void
	 */
	public function logo() {
		$elements = $this->customizer->get_setting( 'header-site-branding-elements' );
		$layout   = $this->customizer->get_setting( 'header-site-branding-layout' );

		/**
		 * Action: vite/header/site-branding/start
		 *
		 * Fires before header site branding.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( 'header/site-branding/start' );
		?>
		<div class="vite-brand vite-brand--<?php echo esc_attr( $layout ); ?>"<?php vite( 'seo' )->print_schema_microdata( 'logo' ); ?>>
			<?php the_custom_logo(); ?>
			<?php if ( in_array( $elements, [ 'logo-title', 'logo-title-description' ], true ) ) : ?>
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="vite-brand__title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="vite-brand__title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( 'logo-title-description' === $elements ) : ?>
				<?php if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) : ?>
					<p class="vite-brand__description"><?php bloginfo( 'description' ); ?></p>
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
		$this->core->action( 'header/site-branding/end' );
	}

	/**
	 * Render header socials.
	 *
	 * @return void
	 */
	public function socials() {
		$socials    = $this->customizer->get_setting( "$this->context-social-links" );
		$size       = $this->customizer->get_setting( "$this->context-social-icons-size" );
		$color_type = $this->customizer->get_setting( "$this->context-social-icons-color-type" );

		if ( empty( $socials ) ) {
			return;
		}

		/**
		 * Action: vite/$this->context/socials/start
		 *
		 * Fires before header socials.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/socials/start" );
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
							<a <?php print( esc_attr( 'brand' === $color_type && isset( $social['color'] ) ? 'style=color:' . $social['color'] : '' ) ); ?> class="vite-social__link vite-social__link--<?php echo esc_attr( $social['id'] ); ?>" rel="noopener" href=<?php echo esc_url( $this->customizer->get_setting( "{$social['id']}-link", '#' ) ); ?>>
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
		 * Action: vite/$this->context/socials/end
		 *
		 * Fires after header socials.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/socials/end" );
	}

	/**
	 * Search form.
	 *
	 * @return void
	 */
	public function search() {
		$label            = $this->customizer->get_setting( "$this->context-search-label" );
		$label_visibility = $this->customizer->get_setting( "$this->context-search-label-visibility" );
		$label_position   = $this->customizer->get_setting( "$this->context-search-label-position" );
		$visibility       = 'none';

		if ( count( $label_visibility ) === 3 ) {
			$visibility = 'all';
		} elseif ( count( $label_visibility ) === 2 ) {
			$visibility = implode( '-', $label_visibility );
		}

		/**
		 * Action: vite/$this->context/search/start
		 *
		 * Fires before header search.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/search/start" );
		?>
		<div class="vite-search">
			<button class="vite-search__btn" aria-label="<?php esc_attr_e( 'Search modal open button', 'vite' ); ?>">
				<?php if ( ! empty( $label ) ) : ?>
					<span class="vite-search__label vite-search__label--pos-<?php esc_attr( $label_position ); ?> vite-search__label--visibility-<?php echo esc_attr( $visibility ); ?>"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>
				<span class="vite-search__icon">
					<?php vite( 'icon' )->get_icon( 'magnifying-glass', [ 'echo' => true ] ); ?>
				</span>
			</button>
		</div>
		<?php

		/**
		 * Action: vite/$this->context/search/end
		 *
		 * Fires after header search.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/search/end" );
		add_action( 'wp_footer', [ $this, 'search_modal' ], 11 );
	}

	/**
	 * Search modal.
	 *
	 * @return void
	 */
	public function search_modal() {
		/**
		 * Action: vite/$this->context/search/modal/start
		 *
		 * Fires before header search modal.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/search/modal/start" );
		?>
		<div class="vite-modal vite-modal--search">
			<div class="vite-modal__action">
				<button class="vite-modal__btn" <?php esc_attr_e( 'Search modal close button', 'vite' ); ?>>
					<?php
						vite( 'icon' )->get_icon(
							'xmark',
							[
								'echo' => true,
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
		 * Action: vite/$this->context/search/modal/end
		 *
		 * Fires after header search modal.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/search/modal/end" );
	}

	/**
	 * Render header html.
	 *
	 * @return void
	 */
	public function html() {
		$args    = func_get_args()[0] ?? [];
		$type    = $args['type'] ?? '1';
		$content = $this->customizer->get_setting( "$this->context-html-$type" );
		$content = vite( 'core' )->parse_smart_tags( $content );

		/**
		 * Action: vite/$this->context/html/start
		 *
		 * Fires before header html.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/html/start" );
		?>
		<div class="vite-html vite-html--<?php echo esc_attr( $type ); ?>">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php

		/**
		 * Action: vite/$this->context/html/end
		 *
		 * Fires after header html.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/html/end" );
	}

	/**
	 * Render header button.
	 *
	 * @return void
	 */
	public function button() {
		$args      = func_get_args()[0] ?? [];
		$type      = $args['type'] ?? '1';
		$text      = $this->customizer->get_setting( "$this->context-button-$type-text" );
		$url       = $this->customizer->get_setting( "$this->context-button-$type-url", '#' );
		$target    = $this->customizer->get_setting( "$this->context-button-$type-target" );
		$download  = $this->customizer->get_setting( "$this->context-button-$type-download" );
		$sponsored = $this->customizer->get_setting( "$this->context-button-$type-sponsored" );
		$nofollow  = $this->customizer->get_setting( "$this->context-button-$type-nofollow" );
		$style     = $this->customizer->get_setting( "$this->context-button-$type-style" );

		$rel = [];

		if ( $nofollow ) {
			$rel[] = 'nofollow';
		}

		if ( $sponsored ) {
			$rel[] = 'sponsored';
		}

		/**
		 * Action: vite/$this->context/button/start
		 *
		 * Fires before header button.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/button/start" );
		?>
		<div class="vite-button vite-button--<?php echo esc_attr( $type ); ?>">
			<?php
				printf(
					'<a href="%s" class="vite-button__link vite-button__link--%s" target="%s" %s%s>%s</a>',
					esc_url( $url ),
					esc_attr( $style ),
					esc_attr( $target ? '_blank' : '_self' ),
					esc_attr( ! empty( $rel ) ? 'rel=' . implode( ' ', $rel ) : '' ),
					esc_attr( $download ? ' download' : '' ),
					esc_html( $text )
				);
			?>
		</div>
		<?php

		/**
		 * Action: vite/$this->context/button/end
		 *
		 * Fires after header button.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/button/end" );
	}

	/**
	 * Render trigger.
	 *
	 * @return void
	 */
	public function trigger() {

		/**
		 * Action: vite/$this->context/mobile-menu-trigger/start
		 *
		 * Fires before header mobile menu trigger.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/mobile-menu-trigger/start" );
		?>
		<div class="vite-mobile-menu">
			<button class="vite-mobile-menu__btn" aria-label="<?php esc_attr_e( 'Mobile menu modal open button', 'vite' ); ?>">
				<span class="vite-mobile-menu__icon">
					<?php vite( 'icon' )->get_icon( 'bars', [ 'echo' => true ] ); ?>
				</span>
			</button>
		</div>
		<?php

		/**
		 * Action: vite/$this->context/mobile-menu-trigger/end
		 *
		 * Fires after header mobile menu trigger.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/mobile-menu-trigger/end" );
		add_action( 'wp_footer', [ $this, 'mobile_menu_offset' ] );
	}

	/**
	 * Render mobile menu offset.
	 *
	 * @return void
	 */
	public function mobile_menu_offset() {
		$offset_config = $this->customizer->get_setting( 'header' )['offset'] ?? [];

		/**
		 * Filter: vite/header/mobile-menu-offset/elements.
		 *
		 * @param array $elements Elements.
		 */
		$offset_config = $this->core->filter( 'header/mobile-menu-offset/elements', $offset_config );

		/**
		 * Action: vite/$this->context/mobile-menu-offset/start
		 *
		 * Fires before header mobile menu offset.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/mobile-menu-offset/start" );
		?>
		<div data-modal class="vite-modal vite-modal--mobile-menu">
			<div class="vite-modal__inner">
				<div class="vite-modal__action">
					<button class="vite-modal__btn" aria-label="<?php esc_html_e( 'Close mobile menu modal', 'vite' ); ?>">
						<?php vite( 'icon' )->get_icon( 'xmark', [ 'echo' => true ] ); ?>
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
								"template-parts/builder-components/$element_id",
								'',
								[
									'type'    => $type,
									'context' => 'header',
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
		 * Action: vite/$this->context/mobile-menu-offset/end
		 *
		 * Fires after header mobile menu offset.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/mobile-menu-offset/end" );
	}

	/**
	 * Render menu.
	 *
	 * @return void
	 */
	public function menu() {
		$args = func_get_args()[0] ?? [];
		$type = $args['type'] ?? '1';
		$menu = null;

		if ( is_customize_preview() ) {
			$menu = vite( 'customizer' )->get_setting( "menu-$type", '0' );
		}

		/**
		 * Filter: vite/$this->context/$type/menu.
		 *
		 * Fires before menu.
		 *
		 * @since 1.0.0
		 */
		$menu = $this->core->filter( "$this->context/$type/menu", $menu );

		vite( 'nav-menu' )->render_menu( $type, $menu, $this->context );

		/**
		 * Action: vite/$this->context/$type/menu/end
		 *
		 * Fires after menu.
		 *
		 * @since 1.0.0
		 */
		$this->core->action( "$this->context/$type/menu/end" );
	}
}
