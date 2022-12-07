<?php
/**
 * Template hooks.
 *
 * @package Vite
 * @since   1.0.0
 */

namespace Vite;

/**
 * Template hooks.
 */
class TemplateHooks {

	/**
	 * Holds the instance of this class.
	 *
	 * @var null|TemplateHooks
	 */
	private static ?TemplateHooks $instance = null;

	/**
	 * Init.
	 *
	 * @return TemplateHooks|null
	 */
	public static function init(): ?TemplateHooks {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$core = vite( 'core' );

		$core->add_action( 'vite/the-loop', [ $this, 'content' ] );
		$core->add_action( 'vite/the-loop/no-posts', [ $this, 'content_none' ] );
		$core->add_action( 'vite/header', [ $this, 'header' ] );
		$core->add_action( 'vite/footer', [ $this, 'footer' ] );
		$core->add_action( 'vite/archive/content', [ $this, 'archive_content' ] );
		$core->add_action( 'vite/header/end', [ $this, 'archive_page_header' ] );
		$core->add_action( 'vite/archive/start', [ $this, 'archive_wrapper_open' ] );
		$core->add_action( 'vite/archive/end', [ $this, 'archive_wrapper_close' ] );
		$core->add_action( 'vite/archive/end', [ $this, 'pagination_template' ], 11 );
		$core->add_action( 'vite/single/end', [ $this, 'navigation_template' ] );
		$core->add_action( 'vite/single/end', [ $this, 'comments_template' ], 11 );
		$core->add_action( 'vite/page/end', [ $this, 'comments_template' ] );
		$core->add_action(
			'vite/header/mobile/start',
			function() {
				add_filter( 'theme_mod_custom_logo', [ $this, 'change_logo' ] );
			}
		);
		$core->add_action(
			'vite/header/mobile/end',
			function() {
				remove_filter( 'theme_mod_custom_logo', [ $this, 'change_logo' ] );
			}
		);
		$core->add_action(
			'vite/404',
			function() {
				get_template_part( 'template-parts/content/content', '404' );
			}
		);

		add_filter( 'post_class', [ $this, 'post_class' ], 10, 3 );
		add_filter( 'body_class', [ $this, 'body_class' ] );
	}

	/**
	 * Archive content.
	 *
	 * @param mixed $elements Elements.
	 * @return void
	 */
	public function archive_content( $elements ) {
		get_template_part( 'template-parts/entry/entry', '', [ 'elements' => $elements ] );
	}

	/**
	 * Content none template.
	 *
	 * @return void
	 */
	public function content_none() {
		get_template_part( 'template-parts/content/content', 'none' );
	}

	/**
	 * Add body classes.
	 *
	 * @param string[] $classes An array of body class names.
	 * @return string[]
	 */
	public function body_class( array $classes ): array {
		$classes[] = 'vite';

		if ( is_archive() ) {
			$archive_layout = vite( 'customizer' )->get_setting( 'archive-layout' );
			$classes[]      = 'archive-layout-' . $archive_layout;
		}

		return $classes;
	}

	/**
	 * Change logo id.
	 *
	 * @param mixed $attachment_id Attachment ID.
	 * @return mixed
	 */
	public function change_logo( $attachment_id ) {
		$mobile_logo = vite( 'customizer' )->get_setting( 'mobile-logo' );
		if ( $mobile_logo ) {
			return $mobile_logo;
		}

		return $attachment_id;
	}

	/**
	 * Add navigation template.
	 *
	 * @return void
	 */
	public function navigation_template() {
		get_template_part( 'template-parts/navigation/navigation', '' );
	}

	/**
	 * Pagination template.
	 *
	 * @return void
	 */
	public function pagination_template() {
		get_template_part( 'template-parts/pagination/pagination', '' );
	}

	/**
	 * Comments template.
	 *
	 * @return void
	 */
	public function comments_template() {
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}

	/**
	 * Wrapper open.
	 *
	 * @return void
	 */
	public function archive_wrapper_open() {
		$archive_style           = vite( 'customizer' )->get_setting( 'archive-style' );
		$archive_columns         = vite( 'customizer' )->get_setting( 'archive-columns' );
		$is_masonry              = 'grid' === $archive_style && vite( 'customizer' )->get_setting( 'archive-style-masonry' );
		$archive_wrapper_classes = [
			'vite-posts',
			'vite-posts--' . $archive_style,
		];

		if ( 'grid' === $archive_style ) {
			$archive_wrapper_classes[] = 'vite-posts--col-' . $archive_columns;
		}

		if ( $is_masonry ) {
			$archive_wrapper_classes[] = 'vite-posts--masonry';
		}

		if ( ! have_posts() ) {
			$archive_wrapper_classes = [ 'vite-posts' ];
		}

		printf(
			'<div class="%s">',
			esc_attr( implode( ' ', array_unique( $archive_wrapper_classes ) ) )
		);
	}

	/**
	 * Wrapper close.
	 *
	 * @return void
	 */
	public function archive_wrapper_close() {
		echo '</div>';
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
		$elements         = vite( 'customizer' )->get_setting( 'archive-elements' );
		$visible_elements = array_filter(
			$elements,
			function( $element ) {
				return $element['visible'];
			}
		);
		$first_element    = reset( $visible_elements );
		$last_element     = end( $visible_elements );

		if ( is_single() ) {
			$classes[] = 'vite-single';
		}

		$classes[] = 'vite-post';

		if ( has_post_thumbnail( $post_id ) ) {
			$classes[] = 'vite-post--has-thumbnail';

			if ( is_archive() ) {
				if ( isset( $first_element['id'] ) && 'featured-image' === $first_element['id'] ) {
					$classes[] = 'vite-post--thumbnail-first';
				}

				if ( isset( $last_element['id'] ) && 'featured-image' === $last_element['id'] ) {
					$classes[] = 'vite-post--thumbnail-last';
				}
			}
		}

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
	public function archive_page_header() {
		if ( ! is_archive() ) {
			return;
		}
		$archive_title_position = vite( 'customizer' )->get_setting( 'archive-title-position' );
		$archive_title_elements = vite( 'customizer' )->get_setting( 'archive-title-elements' );

		if ( 'inside' === $archive_title_position ) {
			vite( 'core' )->add_action(
				'vite/archive/start',
				function() use ( $archive_title_elements ) {
					get_template_part( 'template-parts/page-header/page-header', '', [ 'elements' => $archive_title_elements ] );
				},
				9
			);
			return;
		}
		get_template_part( 'template-parts/page-header/page-header', '', [ 'elements' => $archive_title_elements ] );
	}

	/**
	 * Content.
	 *
	 * @return void
	 */
	public function content() {
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
