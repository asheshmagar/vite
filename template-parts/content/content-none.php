<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vite
 * @since   1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/content-none/start.
 *
 * Fires before the content-none.
 *
 * @since x.x.x
 */
$core->action( 'content-none/start' );
?>
<section class="vite-post vite-post--empty">
	<div class="vite-post__content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
					$first_post_text = sprintf(
						/* translators: %s: link to new post */
						__( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'vite' ),
						esc_url( admin_url( 'post-new.php' ) )
					);

					echo wp_kses(
						$first_post_text,
						[
							'a' => [
								'href' => true,
							],
						]
					);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<h1><?php esc_html_e( 'Search:', 'vite' ); ?><span>"<?php echo esc_html( get_search_query() ); ?>"</span></h1>
			<p><?php esc_html_e( 'We could not find any results for your search. You can give it another try through the search form below.', 'vite' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
<?php
/**
 * Action: vite/content-none/end.
 *
 * Fires after the content-none.
 *
 * @since 1.0.0
 */
$core->action( 'content-none/end' );

