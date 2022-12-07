<?php
/**
 * Displays the post meta tags.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

vite( 'entry-elements' )->render( 'meta-tax', [ 'type' => 'tag' ] );
