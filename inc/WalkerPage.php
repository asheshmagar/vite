<?php

namespace Vite;

use stdClass;
use Walker_Page;
use WP_Post;

/**
 * NavMenu.
 */
class WalkerPage extends Walker_Page {

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
		$output .= "$n$indent<ul class='children sub-menu'>$n";
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
		$indent                = str_repeat( "\t", $depth );
		$desktop_submenu_icon  = '';
		$mobile_submenu_toggle = '';
		$css_class             = [ 'page_item', 'menu-item', 'page-item-' . $data_object->ID ];
		$theme_location        = empty( $args['theme_location'] ) ? '' : $args['theme_location'];
		$link_wrap_open        = '';
		$link_wrap_close       = '';
		$li_attrs              = '';

		if ( isset( $args['pages_with_children'][ $data_object->ID ] ) ) {
			$css_class[] = 'page_item_has_children vite-has-sub-menu';

			if ( in_array( $theme_location, [ 'primary', 'secondary', 'mobile' ], true ) ) {
				$submenu_icon = apply_filters( 'vite_submenu_icon', vite( 'icon' )->get_icon( 'chevron-down', [ 'size' => 10 ] ) );
				if ( 'mobile' === $theme_location ) {
					$link_wrap_open        = '<div class="vite-sub-menu-toggle-wrap">';
					$link_wrap_close       = '</div>';
					$mobile_submenu_toggle = "<button aria-expanded='false' class='vite-sub-menu-toggle'><span class='vite-sub-menu-icon' role='presentation'>$submenu_icon</span></button>";
				} else {
					$li_attrs             = ' aria-haspopup="true"';
					$desktop_submenu_icon = "<span class='vite-sub-menu-icon' role='presentation'>$submenu_icon</span>";
				}
			}
		}

		if ( ! empty( $current_object_id ) ) {
			$_current_page = get_post( $current_object_id );
			if ( $_current_page && in_array( $data_object->ID, $_current_page->ancestors, true ) ) {
				$css_class[] = 'current_page_ancestor';
			}
			if ( $data_object->ID === $current_object_id ) {
				$css_class[] = 'current_page_item';
			} elseif ( $_current_page && $data_object->ID === $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
			}
		} elseif ( get_option( 'page_for_posts' ) === $data_object->ID ) {
			$css_class[] = 'current_page_parent';
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
			'<li class="%s">%s<a href="%s">%s%s</a>%s%s',
			$css_classes,
			$li_attrs,
			$link_wrap_open,
			get_permalink( $data_object->ID ),
			$title,
			$desktop_submenu_icon,
			$mobile_submenu_toggle,
			$link_wrap_close
		);
	}
}
