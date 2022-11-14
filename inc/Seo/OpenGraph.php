<?php
/**
 *
 */

namespace Vite\SEO;

/**
 * OpenGraph.
 */
class OpenGraph extends SEOBase {

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_head', [ $this, 'add_og_tags' ] );
	}

	/**
	 * Add OpenGraph tags.
	 *
	 * @return void
	 */
	public function add_og_tags() {
		if ( is_single() ) {
			$type = 'article';
		} elseif ( is_author() ) {
			$type = 'profile';
		} elseif ( is_search() ) {
			$type = 'website';
		} else {
			$type = 'website';
		}

		$og_tags = [
			'og:type'        => $type,
			'og:title'       => $this->get_title(),
			'og:description' => $this->get_description(),
			'og:url'         => $this->get_url(),
			'og:site_name'   => $this->get_site_name(),
			'og:image'       => $this->get_image(),
		];

		foreach ( $og_tags as $property => $content ) {
			if ( ! empty( $content ) ) {
				echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
			}
		}
	}
}
