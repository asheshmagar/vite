<?php

namespace Vite\NavMenu;

defined( 'ABSPATH' ) || exit;

use stdClass;
use Vite\Traits\Hook;
use Walker_Nav_Menu;
use WP_Post;

/**
 * NavMenu.
 */
class WalkerNavMenu extends Walker_Nav_Menu {

	use Hook;

	/**
	 * {@inheritDoc}
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$classes = [ 'vite-nav__submenu' ];

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

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
		$classes[] = 'vite-nav__item';

		$submenu_icon        = '';
		$submenu_icon_button = '';
		$wrap_open           = '';
		$wrap_close          = '';

		if (
			in_array( $args->menu_id, [ 'menu-1', 'menu-2', 'menu-3' ], true )
		) {
			if (
				in_array( 'menu-item-has-children', (array) $menu_item->classes, true ) ||
				in_array( 'page_item_has_children', (array) $menu_item->classes, true )
			) {
				$classes[]           = 'vite-nav__item--parent';
				$icon                = $this->filter(
					'submenu/icon',
					vite( 'icon' )->get_icon( 'chevron-down', [ 'size' => 10 ] )
				);
				$submenu_icon        = sprintf(
					'<span class="vite-nav__submenu-icon" role="presentation">%s</span>',
					$icon
				);
				$submenu_icon_button = sprintf(
					'<button aria-expanded="false" aria-label="%s" class="vite-nav__submenu-toggle%s">%s</button>',
					esc_attr__( 'Open sub menu', 'vite' ),
					'menu-3' !== $args->menu_id ? ' vite-nav__submenu-toggle--hidden' : '',
					'menu-3' !== $args->menu_id ? '' : $icon
				);
				if ( 'menu-3' === $args->menu_id ) {
					$wrap_open    = '<div class="vite-nav__item-inner">';
					$wrap_close   = '</div>';
					$submenu_icon = '';
				}
			}

			if ( in_array( 'current-menu-item', (array) $menu_item->classes, true ) ) {
				$classes[] = 'vite-nav__item--active';
			}
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param WP_Post  $menu_item Menu item data object.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 * @since 1.0.0
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		$class_names = implode(
			' ',
			/**
			 * Filters the CSS classes applied to a menu item's list item element.
			 *
			 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 * @since 1.0.0
			 */
			apply_filters(
				'nav_menu_css_class',
				array_filter( $classes ),
				$menu_item,
				$args,
				$depth
			)
		);
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $menu_item The current menu item.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 * @since 1.0.0
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
		$atts['class']        = 'vite-nav__link';

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
		 * @since 1.0.0
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/**
		 * Filter the title of a menu item.
		 *
		 * @param string   $title     The menu item's title.
		 * @param int      $menu_item The ID of the menu item.
		 * @since 1.0.0
		 */
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
		 * @since 1.0.0
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}
}
