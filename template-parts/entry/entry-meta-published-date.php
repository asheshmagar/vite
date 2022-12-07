<?php
/**
 * Displays the post published date.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

vite( 'entry-elements' )->render( 'meta-date', [ 'type' => 'published' ] );
