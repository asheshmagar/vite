<?php
/**
 * Template part for displaying footer.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$configs = vite( 'customizer' )->get_setting( 'footer' );
?>
<footer id="colophon" class="vite-footer"<?php vite( 'seo' )->print_schema_microdata( 'footer' ); ?>>
	<?php
	foreach ( $configs as $row => $cols ) {
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
		$classes = [
			'vite-footer__row',
			"vite-footer__row--$row",
		];
		$classes = vite( 'core' )->filter( 'footer/row/classes', $classes, $row );
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>">
			<div class="vite-container">
			<?php foreach ( $cols as $col => $elements ) : ?>
					<div class="vite-header__col vite-header__col--<?php echo esc_attr( $col ); ?>">
						<?php foreach ( $elements as $element ) : ?>
<!--							<div class="vite-element vite-element----><?php //echo esc_attr( $element['id'] ); ?><!--">-->
<!--								--><?php
//									preg_match( '/\d+$/', $element['id'], $matches );
//									$element_id   = preg_replace( '/(_|-)\d+/', '', $element['id'] );
//									$element_type = $matches[0] ?? null;
//									get_template_part(
//										"template-parts/builder-components/$element_id",
//										'',
//										[
//											'type'    => $element_type,
//											'context' => 'footer',
//										]
//									);
//								?>
<!--							</div>-->
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
	?>
</footer>
