<?php
/**
 * WooCommerce's compatibility.
 *
 * @package Vite
 */

namespace Vite\Compatibility\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce's compatibility.
 */
class WooCommerce extends Base {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_filter( 'woocommerce_show_page_title', '__return_false' );
	}

	/**
	 * Setup WooCommerce.
	 *
	 * @return void
	 */
	public function setup() {
		add_theme_support( 'woocommerce' );

		$features = $this->filter(
			'wc/features',
			[
				'wc-product-gallery-zoom',
				'wc-product-gallery-lightbox',
				'wc-product-gallery-slider',
			]
		);

		foreach ( $features as $feature ) {
			add_theme_support( $feature );
		}
	}
}
