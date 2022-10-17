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
?>
		<?php do_action( 'vite_after_content' ); ?>
			</div>
		<?php
		do_action( 'vite_before_footer' );
		do_action( 'vite_footer' );
		do_action( 'vite_after_footer' );
		?>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
