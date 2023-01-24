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
	$core    = vite( 'core' );
	$configs = $core->get_mod( 'footer' )['desktop'];

	foreach ( $configs as $row => $cols ) {
		$cols_with_content = array_filter(
			$cols,
			function ( $col ) {
				return ! empty( $col );
			}
		);

		if ( empty( $cols_with_content ) ) {
			continue;
		}

		$classes = [
			'vite-footer__row',
			"vite-footer__row--$row",
		];

		$classes    = vite( 'core' )->filter( 'footer/row/classes', $classes, $row );
		$row_cols   = $core->get_mod( "footer-$row-row-cols" );
		$row_layout = $core->get_mod( "footer-$row-row-col-layout" )[ $row_cols ] ?? null;

		$container_classes = [
			'vite-container',
			'vite-container--grid',
		];

		if ( is_array( $row_layout ) ) {
			foreach ( $row_layout as $k => $v ) {
				if ( 'desktop' === $k ) {
					$container_classes[] = "vite-container--$v";
				} else {
					$k                   = str_replace( 'tablet', 'md', $k );
					$k                   = str_replace( 'mobile', 'sm', $k );
					$container_classes[] = "vite-container--$k-$v";
				}
			}
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>">
			<div class="<?php echo esc_attr( implode( ' ', array_unique( $container_classes ) ) ); ?>">
				<?php
				foreach ( $cols as $col => $elements ) :
					if ( ! in_array( (int) $col, range( 1, (int) $row_cols ), true ) ) {
						continue;
					}
					?>
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
