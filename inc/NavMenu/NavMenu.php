<?php
/**
 *
 */

namespace Vite\NavMenu;

use Vite\Traits\Hook;

/**
 * NavMenu.
 */
class NavMenu {

	use Hook;

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

	public const PRIMARY_MENU   = 'menu-1';
	public const SECONDARY_MENU = 'menu-2';
	public const MOBILE_MENU    = 'menu-3';
	public const FOOTER_MENU    = 'menu-4';

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
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ] );
	}

	/**
	 * Register nav menus.
	 */
	public function register_nav_menus() {
		$menu_locations = $this->filter(
			'menu/locations',
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
		return $this->filter( has_nav_menu( static::MOBILE_MENU ) );
	}

	/**
	 * Is secondary menu active.
	 *
	 * @return bool
	 */
	public function is_secondary_menu_active(): bool {
		return $this->filter( has_nav_menu( static::SECONDARY_MENU ) );
	}

	/**
	 * Is footer menu active.
	 *
	 * @return bool
	 */
	public function is_footer_menu_active(): bool {
		return $this->filter( has_nav_menu( static::FOOTER_MENU ) );
	}

	/**
	 * Render menu.
	 *
	 * @param string|int $type Menu type.
	 * @param mixed      $menu Menu id or string or WP_Term.
	 * @param string     $context Context header or footer.
	 * @return void
	 */
	public function render_menu( $type = '1', $menu = null, string $context = 'header' ) {
		$args = [
			'theme_location'  => "menu-$type",
			'menu_id'         => "menu-$type",
			'menu_class'      => 'vite-nav__list',
			'container'       => 'nav',
			'container_id'    => "$context-$type-menu",
			'container_class' => "vite-nav vite-nav--$type",
			'fallback_cb'     => function() use ( $type, $context ) {
				$this->fallback_menu( $type, $context );
			},
			'walker'          => $this->walker_nav_menu,
		];

		if ( isset( $menu ) ) {
			$args['menu'] = $menu;
		}

		if ( '4' === (string) $type ) {
			$args['depth'] = 1;
		}

		$args = $this->filter( "menu/$type/args", $args );

		wp_nav_menu( $args );
	}

	/**
	 * Fallback menu.
	 *
	 * @param string|int $type Menu type.
	 * @param string     $context Context header or footer.
	 * @return void
	 */
	private function fallback_menu( $type = '1', string $context = 'header' ) {
		if ( '3' === (string) $type && $this->is_primary_menu_active() ) {
			wp_nav_menu(
				[
					'theme_location'  => 'menu-1',
					'menu_id'         => 'menu-3',
					'menu_class'      => 'vite-nav__list',
					'container'       => 'nav',
					'container_id'    => 'header-menu-3',
					'container_class' => 'vite-nav vite-nav--3',
					'walker'          => $this->walker_nav_menu,
				]
			);
			return;
		}
		?>
		<nav id="<?php echo esc_attr( "menu-$type" ); ?>-menu" class="vite-nav vite-nav--<?php echo esc_attr( $type ); ?>"<?php vite( 'seo' )->print_schema_microdata( 'navigation' ); ?>>
			<ul class="vite-nav__list">
				<?php
					wp_list_pages(
						[
							'echo'           => true,
							'title_li'       => false,
							'theme_location' => "menu-$type",
							'walker'         => $this->walker_page,
							'depth'          => 'header' === $context ? 0 : 1,
						]
					);
				?>
			</ul>
		</nav>
		<?php
	}
}
