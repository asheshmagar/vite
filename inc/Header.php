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
		?>
		<header id="mast-head" class="site-header">
			<?php $this->render_desktop_header(); ?>
			<?php $this->render_mobile_header(); ?>
		</header>
		<?php
	}

	/**
	 * Render mobile header.
	 *
	 * @return void
	 */
	private function render_mobile_header() {
		$default        = vite( 'customizer' )->get_defaults()['header'];
		$header_configs = vite( 'customizer' )->get_setting( 'header', $default )['mobile'];

		do_action( 'vite_before_desktop_header' );
		?>
		<div data-device-mobile>
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

				do_action( 'vite_before_mobile_header_row', $row, $cols_with_content );
				?>
					<div data-cols="<?php echo esc_attr( $cols_attr ); ?>" data-row="<?php echo esc_attr( $row ); ?>">
						<div class="container">
						<?php foreach ( $cols as $col => $elements ) : ?>
								<?php
								if ( empty( $elements ) && (
										( 1 === $col_count ) ||
										( 2 === $col_count && ! in_array( 'center', $col_keys, true ) )
									)
								) {
									continue;
								}
								?>
								<div data-col="<?php echo esc_attr( $col ); ?>">
									<?php foreach ( $elements as $element ) : ?>
										<div data-element="header-<?php echo esc_attr( $element['id'] ); ?>">
											<?php get_template_part( 'template-parts/header/header', $element['id'] ); ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php
					do_action( 'vite_after_mobile_header_row', $row, $cols_with_content );
			}
			?>
		</div>
		<?php
		do_action( 'vite_after_mobile_header' );
	}

	/**
	 * Render desktop header.
	 *
	 * @return void
	 */
	private function render_desktop_header() {
		$header_configs = vite( 'customizer' )->get_setting( 'header' )['desktop'];

		do_action( 'vite_before_desktop_header' );
		?>
		<div data-device-desktop>
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

			$col_keys   = array_keys( $cols_with_content );
			$col_count  = count( $col_keys );
			$cols_attr  = $col_count . ':' . implode( ':', $col_keys );
			$row_layout = vite( 'customizer' )->get_setting( "header-$row-row-layout" );

			do_action( 'vite_before_desktop_header_row', $row, $cols_with_content );
			?>
			<div data-cols="<?php echo esc_attr( $cols_attr ); ?>" data-layout="<?php echo esc_attr( $row_layout ); ?>" data-row="<?php echo esc_attr( $row ); ?>">
				<div class="container">
					<?php foreach ( $cols as $col => $elements ) : ?>
						<?php
						if ( empty( $elements ) && (
								( 1 === $col_count ) ||
								( 2 === $col_count && ! in_array( 'center', $col_keys, true ) )
							)
						) {
							continue;
						}
						?>
						<div data-col="<?php echo esc_attr( $col ); ?>">
							<?php foreach ( $elements as $element ) : ?>
								<div data-element="header-<?php echo esc_attr( $element['id'] ); ?>">
									<?php get_template_part( 'template-parts/header/header', $element['id'] ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
			do_action( 'vite_after_desktop_header_row', $row, $cols_with_content );
		}
		?>
		</div>
		<?php
		do_action( 'vite_after_desktop_header' );
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
<!--			--><?php // if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) : ?>
<!--				<p class="site-description vite-site-description">--><?php // bloginfo( 'description' ); ?><!--</p>-->
<!--			--><?php // endif; ?>
		</div>
		<?php
	}

	/**
	 * Render header menu.
	 *
	 * @return void
	 */
	public function render_header_social() {
		?>
		<div class="header-social">
			Facebook
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
		<div class="search-modal-trigger">
			<a href="#" class="search-modal-open" data-modal-trigger>
				<?php vite( 'icon' )->get_icon( 'magnifying-glass', [ 'echo' => true ] ); ?>
			</a>
		</div>
		<?php
		add_action( 'wp_footer', [ $this, 'render_search_modal' ] );
	}

	/**
	 * Search modal.
	 *
	 * @return void
	 */
	public function render_search_modal() {
		?>
		<div data-modal class="search-modal">
			<div data-modal-actions class="search-modal-actions">
				<a href="#" data-modal-trigger class="search-modal-close">
					<?php
						vite( 'icon' )->get_icon(
							'xmark',
							[
								'echo' => true,
							]
						)
					?>
				</a>
			</div>
			<div data-modal-content class="search-modal-content">
				<?php get_template_part( 'template-parts/header/header', 'search-form' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render header html.
	 *
	 * @return void
	 */
	public function render_header_html() {
		$content = vite( 'customizer' )->get_setting( 'header-html' );
		$content = vite( 'core' )->parse_smart_tags( $content );
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

	/**
	 * Render header mobile menu trigger.
	 *
	 * @return void
	 */
	public function render_header_mobile_menu_trigger() {
		?>
		<div class="mobile-menu-trigger">
			<a href="#" data-modal-trigger class="mobile-menu-offset-open">
				<?php vite( 'icon' )->get_icon( 'bars', [ 'echo' => true ] ); ?>
			</a>
		</div>
		<?php
		add_action( 'wp_footer', [ $this, 'render_mobile_menu_offset' ] );
	}

	/**
	 * Render mobile menu offset.
	 *
	 * @return void
	 */
	public function render_mobile_menu_offset() {
		$offset_config = vite( 'customizer' )->get_setting( 'header' )['offset'] ?? [];
		?>
		<div data-modal class="mobile-menu-offset">
			<div class="mobile-menu-offset-inner">
				<div data-modal-actions class="mobile-menu-offset-actions">
					<a href="#" data-modal-trigger class="mobile-menu-offset-close" aria-label="<?php esc_html_e( 'Close search modal', 'vite' ); ?>">
						<?php vite( 'icon' )->get_icon( 'xmark', [ 'echo' => true ] ); ?>
					</a>
				</div>
				<div data-modal-content class="mobile-menu-offset-content">
					<?php
					if ( ! empty( $offset_config ) ) {
						foreach ( $offset_config as $element ) {
							echo '<div data-element="header-' . esc_attr( $element['id'] ) . '">';
							get_template_part( 'template-parts/header/header', $element['id'] );
							echo '</div>';
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
