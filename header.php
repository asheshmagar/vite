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

$core = vite( 'core' );
?>
<!doctype html>
<html
<?php
language_attributes();
$core->print_html_attributes(
	'html',
	[
		'class' => 'no-js',
	]
);
?>
>
	<head>
		<?php
		/**
		 * Action: vite/head/start.
		 *
		 * Fires before the head.
		 *
		 * @since x.x.x
		 */
		$core->action( 'head/start' );
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php
		/**
		 * Action: vite/head.
		 *
		 * Fires in the head.
		 *
		 * @see Vite\SEO::print();
		 * @since x.x.x
		 */
		$core->action( 'head' );

		wp_head();

		/**
		 * Action: vite/head/end.
		 *
		 * Fires after the head.
		 */
		$core->action( 'head/end' );
		?>
	</head>
	<body<?php $core->print_html_attributes( 'body', [ 'class' => get_body_class() ] ); ?>>
		<a
			class="skip-link screen-reader-text"
			href="#content"
			role="link"
			title="<?php esc_html_e( 'Skip to content', 'vite' ); ?>">
			<?php esc_html_e( 'Skip to content', 'vite' ); ?>
		</a>
		<?php wp_body_open(); ?>
		<?php $core->action( 'body/open' ); ?>
		<div
		<?php
		$core->print_html_attributes(
			'body',
			[
				'id'    => 'page',
				'class' => [
					'wp-site-blocks',
					'vite-site',
					'hfeed'
				],
			]
		);
		?>
		>
			<?php
			/**
			 * Action: vite/header/start.
			 *
			 * Fires before the header.
			 *
			 * @since x.x.x
			 */
			$core->action( 'header/start' );

			/**
			 * Action: vite/header.
			 *
			 * Fires in the header.
			 *
			 * @since x.x.x
			 */
			$core->action( 'header' );

			/**
			 * Action: vite/header/end.
			 *
			 * Fires after the header.
			 *
			 * @since x.x.x
			 */
			$core->action( 'header/end' );
			?>
			<div
			<?php
			$core->print_html_attributes(
				'content',
				[
					'id'    => 'content',
					'class' => [
						'vite-content',
					],
				]
			);
			?>
			>
				<div class="vite-container">
					<div id="primary" class="vite-content__area">
						<?php
						/**
						 * Action: vite/content/start.
						 *
						 * Fires before the content.
						 *
						 * @since x.x.x
						 */
						$core->action( 'content/start' );
