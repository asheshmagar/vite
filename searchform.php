<?php
/**
 * The template for displaying search form.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$submit_icon = (bool) ( $args['submit_icon'] ?? false );
$context     = $args['context'] ?? 'normal';
$icon_size   = $args['icon_size'] ?? 24;
$core        = vite( 'core' );

$core->action( 'search-form/start' );
?>
<form role="search" method="get" class="vite-search-form vite-search-form--<?php echo esc_attr( $context ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'vite' ); ?></span>
		<input type="search" class="vite-search-form__input" placeholder="<?php esc_html_e( 'Search &hellip;', 'vite' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="vite-search-form__submit" aria-label="<?php echo esc_attr__( 'Search Submit', 'vite' ); ?>">
		<span<?php echo esc_attr( $submit_icon ? ' class=screen-reader-text' : '' ); ?>><?php echo esc_html__( 'Search', 'vite' ); ?></span>
		<?php
		if ( $submit_icon ) {
			vite( 'icon' )->get_icon(
				'vite-search',
				[
					'echo' => true,
					'size' => $icon_size,
				]
			);
		}
		?>
	</button>
</form>
<?php
$core->action( 'search-form/end' );
