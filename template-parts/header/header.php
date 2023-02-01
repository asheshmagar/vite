<?php
/**
 * Template part for displaying header.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );
?>
<header
<?php
$core->print_html_attributes(
	'header',
	[
		'id'    => 'mast-head',
		'class' => [
			'vite-header',
		],
	]
);
?>
>
	<?php
	foreach ( [ 'desktop', 'mobile' ] as $device ) {
		$configs = vite( 'core' )->get_mod( 'header' )[ $device ] ?? [];

		if ( empty( $configs ) ) {
			continue;
		}

		vite( 'core' )->action( "header/$device/start" );
		?>
			<div class="vite-header__<?php echo esc_attr( $device ); ?>">
			<?php
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
				$classes   = $core->filter(
					'header/mobile/row/classes',
					array(
						'vite-header__row',
						'vite-header__row--' . $row,
					),
					$row
				);

				$container_class = '';

				if ( $col_count > 1 ) {
					if ( 3 === $col_count || in_array( 'center', $col_keys, true ) ) {
						$container_class = 'lf-even';
					} elseif ( 2 === $col_count ) {
						$container_class = '2';
					}
				}
				?>
				<div class="<?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>">
					<div class="vite-container vite-container--grid<?php print( ! empty( $container_class ) ? esc_attr( ' vite-container--col-' . $container_class ) : '' ); ?>">
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
							<div class="vite-header__col vite-header__col--<?php echo esc_attr( $col ); ?><?php echo esc_attr( empty( $elements ) ? ' vite-header__col--empty' : '' ); ?>">
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
													'type' => $element_type,
													'context' => 'header',
													'device' => $device,
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
			</div>
			<?php
			vite( 'core' )->action( "header/$device/end" );
	}
	?>
</header>
