<?php
/**
 * Footer.
 *
 * @package vite
 * @since 1.0.0
 */

namespace Vite;

/**
 * Footer.
 */
class Footer {

	/**
	 * Render footer.
	 *
	 * @return void
	 */
	public function render_footer() {
		$footer_configs = apply_filters(
			'vite_footer_elements',
			[
				'top'    => [],
				'middle' => [],
				'bottom' => [
					'1' => [ 'html' ],
				],
			]
		);
		?>
		<footer id="colophon" class="site-footer">
			<?php
			foreach ( $footer_configs as $row => $cols ) {
				$is_row_empty = array_reduce(
					array_values( $cols ),
					function( $acc, $curr ) {
						return empty( $curr );
					},
					false
				);
				if ( $is_row_empty || empty( $cols ) ) {
					continue;
				}
				?>
				<div data-row="<?php echo esc_attr( $row ); ?>">
					<div class="container">
						<?php foreach ( $cols as $col => $elements ) : ?>
							<div data-col="<?php echo esc_attr( $col ); ?>">
								<?php foreach ( $elements as $element ) : ?>
									<?php preg_match_all( '!\d+!', $element, $matches ); ?>
									<div data-element="<?php echo esc_attr( $element ); ?>">
										<?php get_template_part( 'template-parts/footer/footer', $element ); ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
			}
			?>
		</footer>
		<?php
	}

	/**
	 * Print footer html.
	 *
	 * @return void
	 */
	public function render_footer_html() {
		$default = vite( 'customizer' )->get_defaults()['footer-html'];
		$content = vite( 'customizer' )->get_setting( 'footer-html', $default );
		$content = vite( 'core' )->parse_smart_tags( $content );
		?>
		<div class="footer-html">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php
	}
}
