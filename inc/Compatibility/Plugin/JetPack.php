<?php
/**
 * JetPack compatibility.
 *
 * @package Vite
 */

namespace Vite\Compatibility\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * JetPack compatibility.
 */
class JetPack extends Base {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	/**
	 * Setup JetPack.
	 *
	 * @return void
	 */
	public function setup() {
		add_theme_support(
			'infinite-scroll',
			[
				'container' => 'main',
				'render'    => function() {
					vite( 'core' )->the_loop();
				},
				'footer'    => 'page',
			]
		);
		add_theme_support( 'jetpack-responsive-videos' );

		add_theme_support(
			'jetpack-content-options',
			array(
				'post-details' => array(
					'stylesheet' => 'vite-style',
					'date'       => '.vite-post__meta__date',
					'categories' => '.vite-post__meta__cat-links',
					'tags'       => '.vite-post__meta__tag-links',
					'author'     => '.vite-post__meta__author',
					'comment'    => '.vite-post__meta__comments',
				),
			)
		);
	}
}
