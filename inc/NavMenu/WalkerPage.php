<?php

namespace Vite\NavMenu;

defined( 'ABSPATH' ) || exit;

use stdClass;
use Vite\Traits\Hook;
use Walker_Page;
use WP_Post;

/**
 * NavMenu.
 */
class WalkerPage extends Walker_Page {

	use Hook;

	/**
	 * {@inheritDoc}
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int    $depth  Optional. Depth of page. Used for padding. Default 0.
	 * @param array  $args   Optional. Arguments for outputting the next level.
	 *                       Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$n$indent<ul class='vite-nav__submenu'>$n";
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param string   $output            Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object       Menu item data object.
	 * @param int      $depth             Depth of menu item. Used for padding.
	 * @param stdClass $args              Not used.
	 * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = [], $current_object_id = 0 ) {
		$indent              = str_repeat( "\t", $depth );
		$css_class           = [ 'page_item', 'menu-item', 'page-item-' . $data_object->ID, 'vite-nav__item' ];
		$theme_location      = empty( $args['theme_location'] ) ? '' : $args['theme_location'];
		$link_wrap_open      = '';
		$link_wrap_close     = '';
		$li_attrs            = '';
		$submenu_icon        = '';
		$submenu_icon_button = '';

		if ( isset( $args['pages_with_children'][ $data_object->ID ] ) ) {
			if ( in_array( $theme_location, [ 'menu-1', 'menu-2', 'menu-3' ], true ) ) {
				$css_class[]         = 'vite-nav__item--parent';
				$icon                = $this->filter(
					'submenu/icon',
					vite( 'icon' )->get_icon( 'chevron-down', [ 'size' => 10 ] )
				);
				$submenu_icon        = sprintf( '<span class="vite-nav__submenu-icon" role="presentation">%s</span>', $icon );
				$submenu_icon_button = sprintf(
					'<button aria-expanded="false" aria-label="%s" class="vite-nav__submenu-toggle%s">%s</button>',
					esc_attr__( 'Open sub menu', 'vite' ),
					'menu-3' !== $theme_location ? ' vite-nav__submenu-toggle--hidden' : '',
					'menu-3' !== $theme_location ? '' : $icon
				);
				if ( 'menu-3' === $theme_location ) {
					$link_wrap_open  = '<div class="vite-nav__item-inner">';
					$link_wrap_close = '</div>';
					$submenu_icon    = '';
				} else {
					$li_attrs = ' aria-haspopup="true"';
				}
			}
		}

		if ( ! empty( $current_object_id ) ) {
			$_current_page = get_post( $current_object_id );
			if ( $_current_page && in_array( $data_object->ID, $_current_page->ancestors, true ) ) {
				$css_class[] = 'current_page_ancestor';
				$css_class[] = 'vite-nav__item--active';
			}
			if ( $data_object->ID === $current_object_id ) {
				$css_class[] = 'current_page_item';
				$css_class[] = 'vite-nav__item--active';
			} elseif ( $_current_page && $data_object->ID === $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
				$css_class[] = 'vite-nav__item--active';
			}
		} elseif ( get_option( 'page_for_posts' ) === $data_object->ID ) {
			$css_class[] = 'current_page_parent';
			$css_class[] = 'vite-nav__item--active';
		}

		/**
		 * Filters the list of CSS classes to include with each page item in the list.
		 *
		 * @see wp_list_pages()
		 *
		 * @param string[] $css_class    An array
		 * @param WP_Post  $data_object  Page data object.
		 * @param int      $depth        Depth of page, used for padding.
		 * @param array    $args         An array of arguments.
		 * @param int      $current_page ID of the current page.
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $data_object, $depth, $args, $current_object_id ) );
		if ( '' === $data_object->post_title ) {
			/* translators: %d: ID of a post. */
			$data_object->post_title = sprintf( __( '#%d (no title)' ), $data_object->ID );
		}
		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $data_object->post_title, $data_object->ID );

		/**
		 * Filters the HTML output of a page list item.
		 *
		 * @see wp_list_pages()
		 *
		 * @param string   $title        Page title.
		 * @param WP_Post  $data_object  Page data object.
		 * @param int      $depth        Depth of page, used for padding.
		 * @param array    $args         An array of arguments.
		 * @param int      $current_page ID of the current page.
		 */
		$output .= $indent . sprintf(
			'<li class="%s"%s>%s<a href="%s" class="vite-nav__link">%s%s</a>%s%s',
			$css_classes,
			$li_attrs,
			$link_wrap_open,
			get_permalink( $data_object->ID ),
			$title,
			$submenu_icon,
			$submenu_icon_button,
			$link_wrap_close
		);
	}
}
