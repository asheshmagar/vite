<?php
/**
 * Template part for displaying scroll to top button.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core          = vite( 'core' );
$scroll_to_top = $core->get_mod( 'scroll-to-top', true );

if ( ! $scroll_to_top ) {
	return;
}

$position  = $core->get_mod( 'scroll-to-top-position', 'right' );
$icon      = $core->get_mod( 'scroll-to-top-icon', 'arrow-up' );
$icon_size = $core->get_mod( 'scroll-to-top-icon-size', 13 );
?>
<div
<?php
$core->print_html_attributes(
	'archive/posts-pagination',
	[
		'class'      => [
			'vite-modal',
			'vite-modal--stt',
			'vite-modal--stt--pos-' . $position,
		],
		'aria-label' => __( 'Posts', 'vite' ),
	]
);
?>
>
	<button class="vite-modal--stt__btn" aria-label="<?php esc_html_e( 'Scroll to top', 'vite' ); ?>">
		<?php
		vite( 'icon' )->get_icon(
			$icon,
			[
				'echo' => true,
				'size' => (int) $icon_size,
			]
		);
		?>
	</button>
</div>
