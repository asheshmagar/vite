<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Theme
 */

namespace Theme;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<aside id="secondary">
	<?php
		do_action( 'theme_before_sidebar' );
		do_action( 'theme_sidebar' );
		do_action( 'theme_after_sidebar' );
	?>
</aside>
