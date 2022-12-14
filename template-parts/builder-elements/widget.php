<?php
/**
 * Template part for displaying widget.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

vite( 'builder-elements' )->render( 'widget', $args ?? [] );
