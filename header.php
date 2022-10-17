<?php
/**
 * Header file for the theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Vite
 * @since x.x.x
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
		<div id="page" class="site wp-site-blocks">
			<?php
			do_action( 'vite_before_header' );
			do_action( 'vite_header' );
			do_action( 'vite_after_header' );
			?>
			<div id="content" class="site-content">
				<div class=vite-container>
				<?php do_action( 'vite_before_content' ); ?>
