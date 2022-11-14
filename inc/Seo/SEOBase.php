<?php
/**
 *
 */

namespace Vite\SEO;

/**
 * Class SEOBase.
 */
class SEOBase {

	/**
	 * Get title.
	 *
	 * @return string|void
	 */
	final public function get_title() {
		$title = get_the_title();

		if ( is_front_page() ) {
			$title = get_bloginfo( 'name' );
		}

		return $title;
	}

	/**
	 * Get description.
	 *
	 * @return string|void
	 */
	final public function get_description() {
		$description = '';

		if ( is_single() ) {
			$description = get_the_excerpt();
		} elseif ( is_author() ) {
			$description = get_the_author_meta( 'description' );
		} elseif ( is_search() ) {
			$description = get_bloginfo( 'description' );
		} elseif ( is_front_page() ) {
			$description = get_bloginfo( 'description' );
		}

		return $description;
	}

	/**
	 * Get URL.
	 *
	 * @return false|string|void
	 */
	final public function get_url() {
		$url = get_the_permalink();

		if ( is_front_page() ) {
			$url = home_url();
		}

		return $url;
	}

	/**
	 * Get site name.
	 *
	 * @return string|void
	 */
	final public function get_site_name() {
		return get_bloginfo( 'name' );
	}

	/**
	 * Get image.
	 *
	 * @return false|string
	 */
	final public function get_image() {
		$image = '';

		if ( is_single() ) {
			$image = get_the_post_thumbnail_url();
		} elseif ( is_author() ) {
			$image = get_avatar_url( get_the_author_meta( 'ID' ) );
		} elseif ( is_search() ) {
			$image = get_theme_file_uri( '/assets/images/logo.png' );
		} elseif ( is_front_page() ) {
			$image = get_theme_file_uri( '/assets/images/logo.png' );
		}

		return $image;
	}
}
