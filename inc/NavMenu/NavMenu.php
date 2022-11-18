<?php
/**
 *
 */

namespace Vite\NavMenu;

/**
 * NavMenu.
 */
class NavMenu {

	/**
	 * Walker nav menu.
	 *
	 * @var WalkerNavMenu|null
	 */
	protected $walker_nav_menu = null;

	/**
	 * Walker Page.
	 *
	 * @var WalkerPage|null
	 */
	protected $walker_page = null;

	public const PRIMARY_MENU   = 'primary';
	public const SECONDARY_MENU = 'secondary';
	public const MOBILE_MENU    = 'mobile';
	public const FOOTER_MENU    = 'footer';

	/**
	 * Constructor.
	 *
	 * @param WalkerNavMenu $walker_nav_menu WalkerNavMenu instance.
	 * @param WalkerPage    $walker_page WalkerPage instance.
	 */
	public function __construct( WalkerNavMenu $walker_nav_menu, WalkerPage $walker_page ) {
		$this->walker_nav_menu = $walker_nav_menu;
		$this->walker_page     = $walker_page;
	}

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
	 * @param mixed  $menu Menu id or string or WP_Term.
	 * @param string $context Context header or footer.
	 * @return void
	 */
	public function render_menu( string $type = 'primary', $menu = null, string $context = 'header' ) {
		$args = [
			'theme_location'  => $type,
			'menu_id'         => "$type-menu",
			'menu_class'      => "$type-menu menu",
			'container'       => 'nav',
			'container_id'    => "$context-$type-menu",
			'container_class' => "$context-$type-menu",
			'fallback_cb'     => function() use ( $type, $context ) {
				$this->fallback_menu( $type, $context );
			},
			'walker'          => $this->walker_nav_menu,
		];
		if ( isset( $menu ) ) {
			$args['menu'] = $menu;
		}
		wp_nav_menu( $args );
	}

	/**
	 * Fallback menu.
	 *
	 * @param string $type Menu type.
	 * @param string $context Context header or footer.
	 * @return void
	 */
	private function fallback_menu( string $type = 'primary', string $context = 'header' ) {
		if ( 'mobile' === $type && $this->is_primary_menu_active() ) {
			wp_nav_menu(
				[
					'theme_location'  => 'primary',
					'menu_id'         => 'mobile-menu',
					'menu_class'      => 'mobile-menu menu',
					'container'       => 'nav',
					'container_id'    => 'header-mobile-menu',
					'container_class' => 'header-mobile-menu',
					'walker'          => $this->walker_nav_menu,
				]
			);
			return;
		}
		?>
		<nav id="<?php echo esc_attr( "$context-$type" ); ?>-menu" class="<?php echo esc_attr( "$context-$type" ); ?>-menu">
			<ul class="<?php echo esc_attr( $type ); ?>-menu menu">
				<?php
					wp_list_pages(
						[
							'echo'           => true,
							'title_li'       => false,
							'theme_location' => $type,
							'walker'         => $this->walker_page,
						]
					);
				?>
			</ul>
		</nav>
		<?php
	}
}
