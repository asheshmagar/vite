<?php
/**
 * Displays the post footer.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/footer/start
 *
 * Fires before entry footer.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/footer/start' );

vite( 'entry-elements' )->render_entry_button();

/**
 * Action: vite/entry-elements/footer/end
 *
 * Fires after entry footer.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/footer/end' );
