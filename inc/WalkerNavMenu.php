<?php

namespace Vite;

use stdClass;
use Walker_Nav_Menu;
use WP_Post;

/**
 * NavMenu.
 */
class WalkerNavMenu extends Walker_Nav_Menu {

	/**
	 * {@inheritDoc}
	 *
	 * @param string   $output            Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object       Menu item data object.
	 * @param int      $depth             Depth of menu item. Used for padding.
	 * @param stdClass $args              An object of wp_nav_menu() arguments.
	 * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$menu_item = $data_object;
		$indent    = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = $menu_item->classes ?? [];
		$classes   = (array) $classes;
		$classes[] = 'menu-item-' . $menu_item->ID;

		$submenu_icon        = '';
		$submenu_icon_button = '';
		$wrap_open           = '';
		$wrap_close          = '';

		if (
			in_array( $args->menu_id, [ 'primary-menu', 'secondary-menu', 'mobile-menu' ], true ) &&
			(
				in_array( 'menu-item-has-children', (array) $menu_item->classes, true ) ||
				in_array( 'page_item_has_children', (array) $menu_item->classes, true )
			)
		) {
			$icon                = apply_filters( 'vite_submenu_icon', vite( 'icon' )->get_icon( 'chevron-down', [ 'size' => 10 ] ) );
			$submenu_icon        = sprintf( '<span class="vite-sub-menu-icon" role="presentation">%s</span>', $icon );
			$submenu_icon_button = sprintf(
				'<button aria-expanded="false" aria-label="%s" class="vite-sub-menu-toggle%s">%s</button>',
				esc_attr__( 'Open sub menu', 'vite' ),
				'mobile-menu' !== $args->menu_id ? ' vite-sub-menu-toggle-hidden' : '',
				'mobile-menu' !== $args->menu_id ? '' : $icon
			);
			$classes[]           = 'vite-has-sub-menu';
			if ( 'mobile-menu' === $args->menu_id ) {
				$wrap_open    = '<div class="vite-sub-menu-toggle-wrap">';
				$wrap_close   = '</div>';
				$submenu_icon = '';
			}
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param WP_Post  $menu_item Menu item data object.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $menu_item The current menu item object.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $menu_item The current menu item.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
		$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
		if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $menu_item->xfn;
		}
		$atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
		$atts['aria-current'] = $menu_item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria-current The aria-current attribute.
		 * }
		 * @param WP_Post  $menu_item The current menu item object.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title     The menu item's title.
		 * @param WP_Post  $menu_item The current menu item object.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= $wrap_open;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		// if ( $menu_item->description && 0 !== $depth ) {
		// $item_output .= '<span class="menu-item-description">' . $menu_item->description . '</span>';
		// }
		$item_output .= $submenu_icon;
		$item_output .= '</a>';
		$item_output .= $submenu_icon_button;
		$item_output .= $wrap_close;
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $menu_item   Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}
}
