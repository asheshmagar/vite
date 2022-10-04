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

$sidebar = apply_filters( 'theme_sidebar', 'sidebar-1' );
?>

<aside id="secondary">
	<?php
	do_action( 'theme_before_sidebar' );
	if ( is_active_sidebar( $sidebar ) ) {
		dynamic_sidebar( $sidebar );
	} else {
		do_action( 'theme_no_sidebar' );
	}
	do_action( 'theme_after_sidebar' );
	?>
</aside>
