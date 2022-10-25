<?php
/**
 * The loop.
 *
 * @since 1.0.0
 * @package Vite
 */

namespace Vite;

use WP_Post_Type;

/**
 * Core.
 */
class Core {

	/**
	 * Holds current post types.
	 *
	 * @var string[]|WP_Post_Type[]
	 */
	public $post_types;

	/**
	 * The loop.
	 *
	 * @return void
	 */
	public function the_loop() {
		do_action( 'vite_before_the_loop' );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				do_action( 'vite_the_loop' );
			}
		} else {
			do_action( 'vite_no_posts' );
		}
		do_action( 'vite_after_the_loop' );
	}

	/**
	 * Get the current post ID.
	 *
	 * @return int
	 */
	public function get_the_id(): int {
		if ( is_home() && 'page' === get_option( 'show_on_front' ) ) {
			$post_id = (int) get_option( 'page_for_posts' );
		} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
			$post_id = (int) get_option( 'page_on_front' );
		} elseif ( is_singular() ) {
			$post_id = (int) get_the_ID();
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$post_id = get_queried_object_id();
		} else {
			$post_id = 0;
		}

		return $post_id;
	}

	/**
	 * Static theme string.
	 *
	 * @param string $id String id.
	 * @param bool   $should_return Whether to return or echo string.
	 * @return string|void
	 */
	public function static_strings( string $id, bool $should_return = false ) {
		$strings = [
			'no-posts'        => __( 'No posts found.', 'vite' ),
			'skip-to-content' => __( 'Skip to content', 'vite' ),
			'go-to-top'       => __( 'Go to top', 'vite' ),
			'leave-a-comment' => __( 'Leave a comment', 'vite' ),
			'primary-menu'    => __( 'Primary Menu', 'vite' ),
			'secondary-menu'  => __( 'Secondary Menu', 'vite' ),
			'footer-menu'     => __( 'Footer Menu', 'vite' ),

		];

		if ( ! isset( $id ) || ! isset( $strings[ $id ] ) ) {
			return '';
		}

		if ( $should_return ) {
			return $strings[ $id ];
		}

		echo esc_html( $strings[ $id ] );
	}

	/**
	 * Get default options.
	 *
	 * @param string $id Id.
	 * @return string|string[]
	 */
	public function get_default_options( string $id ) {

		$defaults = [
			'post_page_header' => 'style-1',
			'page_page_header' => 'style-1',
		];

		if ( ! isset( $id ) || ! isset( $defaults[ $id ] ) ) {
			return $defaults;
		}

		return $defaults[ $id ];
	}

	/**
	 * Parse smart tags.
	 *
	 * @param string $content Content.
	 * @return array|string|string[]
	 */
	public function parse_smart_tags( string $content = '' ) {
		$smart_tags = [
			'{{site-title}}' => get_bloginfo( 'name' ),
			'{{site-url}}'   => home_url(),
			'{{year}}'       => date_i18n( 'Y' ),
			'{{date}}'       => gmdate( 'Y-m-d' ),
			'{{time}}'       => gmdate( 'H:i:s' ),
			'{{datetime}}'   => gmdate( 'Y-m-d H:i:s' ),
			'{{copyright}}'  => '&copy;',
		];

		foreach ( $smart_tags as $tag => $value ) {
			$content = str_replace( $tag, $value, $content );
		}

		return $content;
	}

	/**
	 * Get the current post types.
	 *
	 * @return string[]|WP_Post_Type[]
	 */
	public function get_current_post_types(): array {
		$post_types = get_post_types(
			[
				'public'   => true,
				'_builtin' => false,
			],
			'objects'
		);
		$exclude    = apply_filters(
			'vite_ignored_post_types',
			[
				'attachment',
				'elementor_library',
				'elementor-hf',
			]
		);

		$this->post_types = array_diff( $post_types, $exclude );

		return $this->post_types;
	}
}
