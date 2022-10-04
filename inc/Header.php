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
		$header_configs = apply_filters(
			'theme_header_elements',
			[
				'top'    => [
					'left'   => [],
					'center' => [],
					'right'  => [],
				],
				'main'   => [
					'left'   => [ 'logo' ],
					'center' => [],
					'right'  => [ 'primary-navigation' ],
				],
				'bottom' => [
					'left'   => [],
					'center' => [],
					'right'  => [],
				],
			]
		);

		?>
		<header id="mast-head" class="site-header">
			<div class="container">
			<?php
			foreach ( $header_configs as $row => $cols ) {
				$is_row_empty = array_reduce(
					array_values( $cols ),
					function( $acc, $curr ) {
						return empty( $curr );
					},
					false
				);
				if ( $is_row_empty ) {
					continue;
				}
				?>
				<div class="site-header-row" data-row="<?php echo esc_attr( $row ); ?>">
					<?php foreach ( $cols as $col => $elements ) : ?>
						<?php if ( ! empty( $elements ) ) : ?>
							<div class="site-header-col" data-col="<?php echo esc_attr( $col ); ?>">
								<?php foreach ( $elements as $element ) : ?>
									<?php get_template_part( 'template-parts/header/header', $element ); ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php
			}
			?>
			</div>
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
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>
			<?php if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) : ?>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
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
			'theme_location'  => 'primary',
			'menu_id'         => 'primary-menu',
			'menu_class'      => 'primary-menu menu',
			'container_class' => 'primary-menu-container',
			'fallback_cb'     => [ $this, 'primary_navigation_fallback_cb' ],
		];
		?>
		<nav id="site-navigation" class="main-navigation">
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
}
