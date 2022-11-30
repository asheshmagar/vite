
<?php
/**
 * Displays the post header.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
$core = vite( 'core' );

/**
 * Action: vite/entry-elements/title/start
 *
 * Fires before entry title.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/title/start' );

vite( 'entry-elements' )->render_entry_title();

/**
 * Action: vite/entry-elements/title/end
 *
 * Fires after entry title.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/title/end' );
