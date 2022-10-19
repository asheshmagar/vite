<?php
/**
 * Class Header.
 *
 * @since x.x.x
 * @package Vite
 */

namespace Vite;

/**
 * Class Header.
 */
class Header {

	/**
	 * Header markup.
	 *
	 * @return void
	 */
	public function render_header() {
		$default        = vite( 'customizer' )->get_defaults()['header'];
		$header_configs = vite( 'customizer' )->get_setting( 'header', $default );

		?>
		<header id="mast-head" class="site-header">
			<?php
			foreach ( $header_configs as $row => $cols ) {
				$cols_with_content = array_filter(
					$cols,
					function( $col ) {
						return ! empty( $col );
					}
				);

				if ( empty( $cols_with_content ) ) {
					continue;
				}

				$col_keys  = array_keys( $cols_with_content );
				$col_count = count( $col_keys );
				$cols_attr = $col_count . ':' . implode( ':', $col_keys );

				do_action( 'vite_before_header_row', $row, $cols_with_content );
				?>
					<div data-cols="<?php echo esc_attr( $cols_attr ); ?>" data-row="<?php echo esc_attr( $row ); ?>">
						<div class="container">
							<?php foreach ( $cols as $col => $elements ) : ?>
								<?php
								if ( empty( $elements ) && (
										( 1 === $col_count && in_array( 'center', $col_keys, true ) ) ||
										( 2 === $col_count && ! in_array( 'center', $col_keys, true ) )
									)
								) {
									continue;
								}
								?>
								<div data-col="<?php echo esc_attr( $col ); ?>">
									<?php foreach ( $elements as $element ) : ?>
										<div data-element="header-<?php echo esc_attr( $element ); ?>">
											<?php get_template_part( 'template-parts/header/header', $element ); ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php
					do_action( 'vite_after_header_row', $row, $cols_with_content );
			}
			?>
		</header>
		<?php
	}

	/**
	 * Site branding.
	 *
	 * @return void
	 */
	public function render_header_logo() {
		?>
		<div class="site-branding">
			<?php the_custom_logo(); ?>
			<?php if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title vite-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title vite-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>
			<?php if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) : ?>
				<p class="site-description vite-site-description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Primary Menu.
	 *
	 * @return void
	 */
	public function render_header_primary_navigation() {
		$args = [
			'vite_location'   => 'primary',
			'menu_id'         => 'primary-menu',
			'menu_class'      => 'primary-menu menu',
			'container_class' => 'primary-menu-container',
			'fallback_cb'     => [ $this, 'primary_navigation_fallback_cb' ],
		];
		?>
		<nav data-element="header-primary-navigation" id="site-navigation" class="main-navigation">
			<?php wp_nav_menu( $args ); ?>
		</nav>
		<?php
	}

	/**
	 * Fallback for primary menu.
	 *
	 * @return void
	 */
	public function primary_navigation_fallback_cb() {
		?>
		<div id="primary-menu" class="primary-menu-container">
			<ul class="primary-menu menu">
				<?php
				wp_list_pages(
					[
						'echo'     => true,
						'title_li' => false,
					]
				);
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Search form.
	 *
	 * @return void
	 */
	public function render_header_search() {
		?>
		<div class="header-search">
			<?php vite( 'icon' )->get_icon( 'magnifying-glass', [ 'echo' => true ] ); ?>
		</div>
		<?php
	}

	/**
	 * Render header html.
	 *
	 * @return void
	 */
	public function render_header_html() {
		$default = vite( 'customizer' )->get_defaults()['header-html'];
		$content = vite( 'customizer' )->get_setting( 'header-html', $default );
		?>
		<div class="header-html">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php
	}

	/**
	 * Render header button.
	 *
	 * @return void
	 */
	public function render_header_button() {
		$defaults    = vite( 'customizer' )->get_defaults();
		$button_text = vite( 'customizer' )->get_setting( 'header-button-text', $defaults['header-button-text'] );
		$button_url  = vite( 'customizer' )->get_setting( 'header-button-url', $defaults['header-button-url'] );
		?>
		<div class="header-button">
			<a href="<?php echo esc_url( $button_url ); ?>" class="button"><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php
	}
}
