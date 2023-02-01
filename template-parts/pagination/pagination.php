<?php
/**
 * Template part for displaying a pagination
 *
 * @package Vite
 */

$core = vite( 'core' );

/**
 * Filter: vite/pagination/args.
 *
 * Filters pagination arguments.
 *
 * @since x.x.x
 */
$pagination_args    = $core->filter(
	'pagination/args',
	[
		'mid_size'  => 3,
		'prev_text' => sprintf(
			'<span class="screen-reader-text">%s</span>%s',
			__( 'Previous Page', 'vite' ),
			vite( 'icon' )->get_icon( is_rtl() ? 'chevron-right' : 'chevron-left', [ 'size' => 12 ] )
		),
		'next_text' => sprintf(
			'<span class="screen-reader-text">%s</span>%s',
			__( 'Next Page', 'vite' ),
			vite( 'icon' )->get_icon( is_rtl() ? 'chevron-left' : 'chevron-right', [ 'size' => 12 ] )
		),
		'class'     => 'vite-pagination',
	]
);
$is_infinite_scroll = 'infinite-scroll' === $core->get_mod( 'archive-pagination', 'numbered' );

/**
 * Action: vite/pagination/start
 *
 * Fires before pagination.
 *
 * @since x.x.x
 */
$core->action( 'pagination/start' );

$pagination = paginate_links( $pagination_args );

if ( $pagination ) {
	$pagination = str_replace( 'class="page-numbers"', 'class="vite-pagination__link"', $pagination );
	$pagination = str_replace( 'class="page-numbers current"', 'class="vite-pagination__link vite-pagination__link--current"', $pagination );
	$pagination = str_replace( 'class="next page-numbers"', 'class="vite-pagination__link vite-pagination__link--next"', $pagination );
	$pagination = str_replace( 'class="prev page-numbers"', 'class="vite-pagination__link vite-pagination__link--prev"', $pagination );

	?>
	<nav
	<?php
	$core->print_html_attributes(
		'archive/posts-pagination',
		[
			'class'      => [
				'vite-pagination',
				$is_infinite_scroll ? 'vite-pagination--infinite-scroll' : '',
			],
			'aria-label' => __( 'Posts', 'vite' ),
		]
	);
	?>
	>
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'vite' ); ?></h2>
		<div class="vite-pagination__links"<?php echo( $is_infinite_scroll ? ' style=display:none' : '' ); ?>>
			<?php
				echo wp_kses(
					$pagination,
					[
						'span' => [
							'class'        => true,
							'aria-current' => true,
						],
						'a'    => [
							'class' => true,
							'href'  => true,
						],
						'svg'  => [
							'class'       => true,
							'aria-hidden' => true,
							'role'        => true,
							'xmlns'       => true,
							'width'       => true,
							'height'      => true,
							'viewbox'     => true,
							'fill'        => true,
						],
						'path' => [
							'd'    => true,
							'fill' => true,
						],
					]
				);
			?>
		</div>
		<?php if ( $is_infinite_scroll ) : ?>
			<div class="vite-pagination__status" style="display: none">
				<div class="infinite-scroll-request">
					<?php
					vite( 'icon' )->get_icon(
						'spinner',
						[
							'size' => 20,
							'echo' => true,
						]
					);
					?>
				</div>
				<p class="infinite-scroll-last"><?php esc_html_e( 'No more posts to load!', 'vite' ); ?></p>
				<p class="infinite-scroll-error"><?php esc_html_e( 'Error loading posts!', 'vite' ); ?></p>
			</div>
		<?php endif; ?>
	</nav>
	<?php
}

/**
 * Action: vite/pagination/end
 *
 * Fires after pagination.
 *
 * @since x.x.x
 */
$core->action( 'pagination/end' );
