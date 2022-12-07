<?php
/**
 * Displays the post meta categories.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

vite( 'entry-elements' )->render( 'meta-tax', [ 'type' => 'cat' ] );
