<?php
/**
 * Template file for single post navigation.
 *
 * @package vite
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );
$prev = get_previous_post();
$next = get_next_post();

/**
 * Action: vite/single/post-navigation/start
 *
 * Fires before single post navigation.
 *
 * @since 1.0.0
 */
$core->action( 'single/post-navigation/start' );
?>
<div
<?php
$core->print_html_attributes(
	'single/post-navigation',
	[
		'class' => 'vite-navigation',
	]
);
?>
>
	<?php if ( $prev ) : ?>
		<a class="vite-navigation__link vite-navigation__link--prev" href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Previous Post', 'vite' ); ?></span>
			<span class="vite-navigation__icon">
			<?php
			vite( 'icon' )->get_icon(
				'chevron-left',
				[
					'size' => 10,
					'echo' => true,
				]
			);
			?>
			</span>
			<span class="vite-navigation__title"><?php echo esc_html( get_the_title( $prev->ID ) ); ?></span>
		</a>
	<?php endif; ?>
	<?php if ( $next ) : ?>
		<a class="vite-navigation__link vite-navigation__link--next" href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Next Post', 'vite' ); ?></span>
			<span class="vite-navigation__title"><?php echo esc_html( get_the_title( $next->ID ) ); ?></span>
			<span class="vite-navigation__icon">
			<?php
			vite( 'icon' )->get_icon(
				'chevron-right',
				[
					'size' => 10,
					'echo' => true,
				]
			);
			?>
			</span>
		</a>
	<?php endif; ?>
</div>
<?php

/**
 * Action: vite/single/post-navigation/end
 *
 * Fires after single post navigation.
 *
 * @since 1.0.0
 */
$core->action( 'single/post-navigation/end' );
