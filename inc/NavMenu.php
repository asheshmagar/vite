<?php
/**
 *
 */

namespace Vite;

use WP_Post;
use stdClass;

/**
 * NavMenu.
 */
class NavMenu {

	public const PRIMARY_MENU   = 'primary';
	public const SECONDARY_MENU = 'secondary';
	public const MOBILE_MENU    = 'mobile';
	public const FOOTER_MENU    = 'footer';

	/**
	 * Init.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ] );
		add_filter( 'walker_nav_menu_start_el', [ $this, 'add_submenu_icon' ], 10, 4 );
		add_filter( 'wp_nav_menu_objects', [ $this, 'add_submenu_class' ] );
		add_filter( 'wp_list_pages', [ $this, 'add_class' ] );
	}

	/**
	 * Register nav menus.
	 */
	public function register_nav_menus() {
		$menu_locations = apply_filters(
			'vite_menu_locations',
			[
				static::PRIMARY_MENU   => __( 'Primary Menu', 'vite' ),
				static::SECONDARY_MENU => __( 'Secondary Menu', 'vite' ),
				static::MOBILE_MENU    => __( 'Mobile Menu', 'vite' ),
				static::FOOTER_MENU    => __( 'Footer Menu', 'vite' ),
			]
		);
		register_nav_menus( $menu_locations );
	}

	/**
	 * Add submenu icon.
	 *
	 * The menu item's starting output only includes $args->before, the opening <a>,
	 * the menu item's title, the closing </a>, and $args->after. Currently, there is
	 * no filter for modifying the opening and closing <li> for a menu item.
	 *
	 * @param string   $item_output The menu item's starting HTML output.
	 * @param WP_Post  $menu_item   Menu item data object.
	 * @param int      $depth       Depth of menu item. Used for padding.
	 * @param stdClass $args        An object of wp_nav_menu() arguments.
	 * @return string
	 */
	public function add_submenu_icon( string $item_output, WP_Post $menu_item, int $depth, stdClass $args ): string {
		if (
			in_array( $args->theme_location, [ 'primary', 'secondary' ], true ) &&
			(
				in_array( 'menu-item-has-children', (array) $menu_item->classes, true ) ||
				in_array( 'page_item_has_children', (array) $menu_item->classes, true )
			)
		) {
			$icon        = apply_filters( 'vite_submenu_icon', vite( 'icon' )->get_icon( 'chevron-down', [ 'size' => 12 ] ) );
			$item_output = str_replace(
				"$args->link_after</a>",
				"$args->link_after<span class='submenu-icon'>$icon</span></a>",
				$item_output
			);
		}
		return $item_output;
	}

	/**
	 * Add submenu class.
	 *
	 * @param array $items Menu items.
	 * @return array
	 */
	public function add_submenu_class( array $items ): array {
		$parent_menu_items = array_reduce(
			$items,
			function ( $acc, $curr ) {
				if ( $curr->menu_item_parent ) {
					$acc[] = (int) $curr->menu_item_parent;
				}
				return $acc;
			},
			[]
		);
		foreach ( $items as $item ) {
			if ( ! in_array( $item->ID, $parent_menu_items, true ) ) {
				continue;
			}
			$item->classes[] = 'vite-has-submenu';
		}
		return $items;
	}

	/**
	 * Is primary menu active.
	 *
	 * @return bool
	 */
	public function is_primary_menu_active(): bool {
		return has_nav_menu( static::PRIMARY_MENU );
	}

	/**
	 * Is mobile menu active.
	 *
	 * @return bool
	 */
	public function is_mobile_menu_active(): bool {
		return has_nav_menu( static::MOBILE_MENU );
	}

	/**
	 * Is secondary menu active.
	 *
	 * @return bool
	 */
	public function is_secondary_menu_active(): bool {
		return has_nav_menu( static::SECONDARY_MENU );
	}

	/**
	 * Is footer menu active.
	 *
	 * @return bool
	 */
	public function is_footer_menu_active(): bool {
		return has_nav_menu( static::FOOTER_MENU );
	}

	/**
	 * Render menu.
	 *
	 * @param string $type Menu type.
	 * @param string $container_prefix Container prefix.
	 * @return void
	 */
	public function render_menu( string $type = 'primary', string $container_prefix = 'header' ) {
		$args = [
			'theme_location'  => $type,
			'menu_id'         => "$type-menu",
			'menu_class'      => "$type-menu menu",
			'container'       => 'nav',
			'container_id'    => "$container_prefix-$type-menu",
			'container_class' => "$container_prefix-$type-menu",
			'fallback_cb'     => function() use ( $type, $container_prefix ) {
				$this->fallback_menu( $type, $container_prefix );
			},
		];
		wp_nav_menu( $args );
	}

	/**
	 * Fallback menu.
	 *
	 * @param string $type Menu type.
	 * @param string $container_prefix Container prefix.
	 * @return void
	 */
	private function fallback_menu( string $type = 'primary', string $container_prefix = 'header' ) {
		?>
		<nav id="<?php echo esc_attr( "$container_prefix-$type" ); ?>-menu" class="<?php echo esc_attr( "$container_prefix-$type" ); ?>-menu">
			<ul class="<?php echo esc_attr( $type ); ?>-menu menu">
				<?php
					wp_list_pages(
						[
							'echo'     => true,
							'title_li' => false,
						]
					);
				?>
			</ul>
		</nav>
		<?php
	}

	/**
	 * Add class to fallback menu.
	 *
	 * @param string $output Menu output.
	 * @return array|string|string[]
	 */
	public function add_class( string $output ) {
		return str_replace( 'page_item', 'page_item menu-item', $output );
	}
}
