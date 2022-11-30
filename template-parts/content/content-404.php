<?php
/**
 * The template for displaying the 404 page.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

?>
<section class="error-404">
	<?php
	/**
	 * Action: vite/404/start.
	 *
	 * Fires before the 404 page.
	 *
	 * @since x.x.x
	 */
	$core->action( 'content/404/start' );
	?>
	<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.png' ); ?>" alt="<?php esc_attr_e( '404', 'vite' ); ?>">

	<div class="page-content">
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'vite' ); ?></p>
		<?php
		get_search_form();
		?>
	</div>
	<?php
	/**
	 * Action: vite/404/end.
	 *
	 * Fires after the 404 page.
	 *
	 * @since x.x.x
	 */
	$core->action( 'content/404/end' );
	?>
</section>
