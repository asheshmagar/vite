<?php
/**
 * Page header setup.
 *
 * @package Theme
 * @since 1.0.0
 */

namespace Theme;

/**
 * Page header setup.
 */
class PageHeader {

	/**
	 * Page header markup.
	 *
	 * @return void
	 */
	public function render_page_header() {
		$page_header_elements = apply_filters(
			'theme_page_header_elements',
			[
				'title',
				'breadcrumbs',
			]
		);
		?>
		<div class="page-header-wrap">
			<header class="page-header">
				<div class="container">
					<?php
					foreach ( $page_header_elements as $page_header_element ) {
						get_template_part( 'template-parts/page-header/page-header', $page_header_element );
					}
					?>
				</div>
			</header>
		</div>
		<?php
	}

	/**
	 * Page header title.
	 *
	 * @return void
	 */
	public function render_page_header_title() {
		?>
		<div class="page-title-wrap">
			<h1 class="page-title">
				<?php $this->page_title(); ?>
			</h1>
		</div>
		<?php
	}

	/**
	 * Page title.
	 *
	 * @param bool $should_return Should return or echo.
	 * @return string|void|null
	 */
	public function page_title( bool $should_return = false ) {
		$title = '';

		if ( is_singular() ) {
			$title = get_the_title();
		} elseif ( is_home() ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$title = single_term_title( '', false );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_search() ) {
			/* Translators: %s: Search query */
			$title = sprintf( __( 'Search Results for: %s', 'theme' ), get_search_query() );
		} elseif ( is_404() ) {
			$title = __( 'Oops! That page can&rsquo;t be found.', 'theme' );
		} elseif ( is_archive() ) {
			$title = get_the_archive_title();
		}

		if ( $should_return ) {
			return $title;
		}

		echo wp_kses_post( $title );
	}
}
