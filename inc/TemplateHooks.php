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
		add_action( 'theme_the_loop', [ $this, 'content' ] );
		add_action( 'theme_header', [ $this, 'header' ] );
		add_action( 'theme_after_header', [ $this, 'page_header' ] );
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
