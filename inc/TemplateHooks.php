<?php
/**
 * Template hooks.
 */

namespace Vite;

/**
 * Template hooks.
 */
class TemplateHooks {

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'vite_the_loop', [ $this, 'content' ] );
		add_action( 'vite_header', [ $this, 'header' ] );
		add_action( 'vite_after_header', [ $this, 'page_header' ] );
		add_action( 'vite_footer', [ $this, 'footer' ] );
		add_filter( 'post_class', [ $this, 'post_class' ], 10, 3 );
		add_action( 'vite_before_the_loop', [ $this, 'archive_wrapper_open' ] );
		add_action( 'vite_after_the_loop', [ $this, 'archive_wrapper_close' ] );
		add_action( 'vite_after_the_loop', [ $this, 'comments_template' ] );
	}

	/**
	 * Comments template.
	 *
	 * @return void
	 */
	public function comments_template() {
		if ( is_single() || is_page() ) {
			comments_template();
		}
	}

	/**
	 * Wrapper open.
	 *
	 * @return void
	 */
	public function archive_wrapper_open() {
		if ( is_archive() || is_search() || is_home() ) {
			echo '<div class="vite-posts">';
		}
	}

	/**
	 * Wrapper close.
	 *
	 * @return void
	 */
	public function archive_wrapper_close() {
		if ( is_archive() || is_search() || is_home() ) {
			echo '</div>';
		}
	}

	/**
	 * Add classes to post.
	 *
	 * @param string[] $classes An array of post class names.
	 * @param string[] $class   An array of additional class names added to the post.
	 * @param int      $post_id The post ID.
	 * @return string[]
	 */
	public function post_class( array $classes, array $class, int $post_id ): array {
		if ( has_post_thumbnail( $post_id ) ) {
			$classes[] = 'vite-has-post-thumbnail';
		}

		if ( is_single() ) {
			$classes[] = 'vite-single';
		}

		$classes[] = 'vite-post';
		return $classes;
	}

	/**
	 * Render header.
	 *
	 * @return void
	 */
	public function header() {
		get_template_part( 'template-parts/header/header', '' );
	}

	/**
	 * Render footer.
	 *
	 * @return void
	 */
	public function footer() {
		get_template_part( 'template-parts/footer/footer', '' );
	}

	/**
	 * Render page header.
	 *
	 * @return void
	 */
	public function page_header() {
		if ( is_front_page() || is_singular() ) {
			return;
		}
		get_template_part( 'template-parts/page-header/page-header', '' );
	}

	/**
	 * Content.
	 *
	 * @return void
	 */
	public function content(): void {
		if ( is_archive() || is_home() || is_front_page() || is_search() ) {
			get_template_part( 'template-parts/content/content', '' );
		} elseif ( is_page() ) {
			get_template_part( 'template-parts/content/content', 'page' );
		} elseif ( is_single() ) {
			get_template_part( 'template-parts/content/content', 'single' );
		} else {
			get_template_part( 'template-parts/content/content', 'none' );
		}
	}
}
