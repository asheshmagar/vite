<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Vite
 * @since x.x.x
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );
?>
					</div>
				<?php
					/**
					 * Action: vite/content/end.
					 *
					 * Fires after the content.
					 *
					 * @since x.x.x
					 */
					$core->action( 'content/end' );
				?>
			</div>
		<?php
		/**
		 * Action: vite/footer/start.
		 *
		 * Fires before the footer.
		 *
		 * @since x.x.x
		 */
		$core->action( 'footer/start' );

		/**
		 * Action: vite/footer.
		 *
		 * Fires in the footer.
		 *
		 * @since x.x.x
		 */
		$core->action( 'footer' );

		/**
		 * Action: vite/footer/end.
		 *
		 * Fires after the footer.
		 *
		 * @since x.x.x
		 */
		$core->action( 'footer/end' );
		?>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
