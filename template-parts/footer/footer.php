<?php
/**
 * Template part for displaying footer.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

?>
<footer id="colophon" class="vite-footer"<?php vite( 'seo' )->print_schema_microdata( 'footer' ); ?>>
	<?php
	$configs = vite( 'core' )->get_theme_mod( 'footer' )['desktop'];

	foreach ( $configs as $row => $cols ) {
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
		$classes   = [
			'vite-footer__row',
			"vite-footer__row--$row",
		];

		$classes = vite( 'core' )->filter( 'footer/row/classes', $classes, $row );
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>">
			<div class="vite-container">
			<?php foreach ( $cols as $col => $elements ) : ?>
					<div class="vite-footer__col vite-footer__col--<?php echo esc_attr( $col ); ?>">
					<?php foreach ( $elements as $element ) : ?>
							<div class="vite-element vite-element--<?php echo esc_attr( $element['id'] ); ?>">
								<?php
									preg_match( '/\d+$/', $element['id'], $matches );
									$element_id   = preg_replace( '/(_|-)\d+/', '', $element['id'] );
									$element_type = $matches[0] ?? null;
									get_template_part(
										"template-parts/builder-elements/$element_id",
										'',
										[
											'type'    => $element_type,
											'context' => 'footer',
										]
									);
								?>
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
