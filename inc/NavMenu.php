<?php
/**
 *
 */

namespace Theme;

/**
 * NavMenu.
 */
class NavMenu {

	public const PRIMARY_MENU   = 'primary';
	public const SECONDARY_MENU = 'secondary';
	public const FOOTER_MENU    = 'footer';

	/**
	 * Init.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ] );
	}

	/**
	 * Register nav menus.
	 */
	public function register_nav_menus() {
		$menu_locations = apply_filters(
			'theme_menu_locations',
			[
				static::PRIMARY_MENU   => __( 'Primary Menu', 'theme' ),
				static::SECONDARY_MENU => __( 'Secondary Menu', 'theme' ),
				static::FOOTER_MENU    => __( 'Footer Menu', 'theme' ),
			]
		);
		register_nav_menus( $menu_locations );
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
}
