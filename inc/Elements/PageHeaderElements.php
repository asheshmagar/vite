<?php
/**
 * Page header elements.
 *
 * @package Vite
 */

namespace Vite\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Page header elements.
 */
class PageHeaderElements {

	use ElementsTrait;

	/**
	 * Page header elements.
	 *
	 * @param array $args Template args.
	 *
	 * @return void
	 */
	public function page_header( array $args ) {
		$elements = $args['elements'] ?? [];

		if ( empty( $elements ) ) {
			return;
		}

		$should_render = array_filter(
			$elements,
			function( $element ) {
				return ! empty( $element['visible'] );
			}
		);
		if ( empty( $should_render ) ) {
			return;
		}

		$elements = $this->filter( 'page-header/elements', $elements );

		/**
		 * Action: vite/page-header/start
		 *
		 * Fires before page header.
		 *
		 * @since x.x.x
		 */
		$this->action( 'page-header/start' );
		?>
		<div class="vite-page-header">
			<div class="vite-container">
				<?php
				foreach ( $elements as $element ) {
					if ( ! $element['visible'] || ! isset( $element['id'] ) ) {
						continue;
					}
					get_template_part( 'template-parts/page-header/page-header', $element['id'] );
				}
				?>
			</div>
		</div>
		<?php

		/**
		 * Action: vite/page-header/start
		 *
		 * Fires after page header.
		 *
		 * @since x.x.x
		 */
		$this->action( 'page-header/end' );
	}

	/**
	 * Page header title.
	 *
	 * @return void
	 */
	public function title() {

		/**
		 * Action: vite/page-header/title/start
		 *
		 * Fires before page header title.
		 *
		 * @since x.x.x
		 */
		$this->action( 'page-header/title/start' );
		?>
		<h1 class="vite-page-header__title">
			<?php echo wp_kses_post( $this->get_title() ); ?>
		</h1>
		<?php

		/**
		 * Action: vite/page-header/title/end
		 *
		 * Fires after page header title.
		 *
		 * @since x.x.x
		 */
		$this->action( 'page-header/title/end' );
	}

	/**
	 * Page header breadcrumbs.
	 *
	 * @return void
	 */
	public function breadcrumbs() {
		/**
		 * Action: vite/page-header/breadcrumbs/start
		 *
		 * Fires before page header breadcrumbs.
		 *
		 * @since x.x.x
		 */
		$this->action( 'page-header/breadcrumbs/start' );

		vite( 'breadcrumbs' )->breadcrumbs(
			[
				'before' => '<div class="page-header-breadcrumbs">',
				'after'  => '</div>',
			]
		);

		/**
		 * Action: vite/page-header/breadcrumbs/end
		 *
		 * Fires after page header breadcrumbs.
		 *
		 * @since x.x.x
		 */
		$this->action( 'page-header/breadcrumbs/end' );
	}

	/**
	 * Page header description.
	 *
	 * @return void
	 */
	public function description() {
		if ( is_archive() && get_the_archive_description() ) {

			/**
			 * Action: vite/page-header/description/start
			 *
			 * Fires before page header description.
			 *
			 * @since x.x.x
			 */
			$this->action( 'page-header/description/start' );
			?>
			<div class="vite-page-header__archive-desc">
				<?php the_archive_description(); ?>
			</div>
			<?php

			/**
			 * Action: vite/page-header/description/start
			 *
			 * Fires after page header description.
			 *
			 * @since x.x.x
			 */
			$this->action( 'page-header/description/end' );
		}
	}

	/**
	 * Get title.
	 *
	 * @return mixed|void
	 */
	public function get_title() {
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
			$title = sprintf( __( 'Search Results for: %s', 'vite' ), get_search_query() );
		} elseif ( is_404() ) {
			$title = __( 'Oops! That page can&rsquo;t be found.', 'vite' );
		} elseif ( is_archive() ) {
			$title = get_the_archive_title();
		}

		return $this->filter( 'page/title', $title );
	}
}
