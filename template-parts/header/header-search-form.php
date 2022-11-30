<?php
/**
 * The template for displaying the header search form.
 *
 * @package Vite
 * @since x.x.x
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Action: vite/header/search-form/start
 *
 * Fires before header search form.
 *
 * @since x.x.x
 */
vite( 'core' )->action( 'header/search-form/start' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'vite' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_html_e( 'Search &hellip;', 'vite' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
		<button class="search-submit" aria-label="<?php echo esc_attr__( 'Search Submit', 'vite' ); ?>">
			<span hidden><?php echo esc_html__( 'Search', 'vite' ); ?></span>
			<?php
			vite( 'icon' )->get_icon(
				'magnifying-glass',
				[
					'echo' => true,
				]
			);
			?>
		</button>
	</label>
</form>
<?php
/**
 * Action: vite/header/search-form/end
 *
 * Fires after header search form.
 *
 * @since x.x.x
 */
vite( 'core' )->action( 'header/search-form/end' );
