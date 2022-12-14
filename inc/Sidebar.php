<?php

namespace Vite;

use Vite\Traits\Mods;

/**
 * Init sidebar.
 */
class Sidebar {

	use Mods;

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	/**
	 * Register sidebars.
	 */
	public function register_sidebars() {
		$sidebars  = $this->filter(
			'sidebars',
			[
				'sidebar-1'       => __( 'Sidebar 1', 'vite' ),
				'sidebar-2'       => __( 'Sidebar 2', 'vite' ),
				'header-widget-1' => __( 'Header Widget 1', 'vite' ),
				'header-widget-2' => __( 'Header Widget 2', 'vite' ),
				'footer-widget-1' => __( 'Footer Widget 1', 'vite' ),
				'footer-widget-2' => __( 'Footer Widget 2', 'vite' ),
				'footer-widget-3' => __( 'Footer Widget 3', 'vite' ),
				'footer-widget-4' => __( 'Footer Widget 4', 'vite' ),
				'footer-widget-5' => __( 'Footer Widget 5', 'vite' ),
				'footer-widget-6' => __( 'Footer Widget 6', 'vite' ),
			]
		);
		$title_tag = $this->filter( 'sidebars/title/tag', 'h2' );

		foreach ( $sidebars as $id => $name ) {
			register_sidebar(
				$this->filter(
					'sidebar/args',
					[
						'name'          => $name,
						'id'            => $id,
						'description'   => __( 'Add widgets here..', 'vite' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => sprintf( '<%s class="widget-title">', $this->filter( "sidebar/$id/title/tag", $title_tag ) ),
						'after_title'   => sprintf( '</%s>', $this->filter( "sidebar/$id/title/tag", $title_tag ) ),
					]
				)
			);
		}
	}

	/**
	 * Render sidebar.
	 *
	 * @param array $args Sidebar arguments.
	 * @return void
	 */
	public function render_sidebar( array $args = [] ) {
		$args          = wp_parse_args(
			$args,
			[
				'id'            => 'sidebar-1',
				'wrapper_id'    => 'secondary',
				'wrapper_class' => 'widget-area',
				'should_render' => true,
				'wrapper_tag'   => 'aside',
			]
		);
		$id            = $args['id'] ?? '';
		$wrapper_id    = $args['wrapper_id'] ?? 'secondary';
		$wrapper_class = $args['wrapper_class'] ?? 'widget-area';
		$should_render = $args['should_render'] ?? true;
		$wrapper_tag   = $args['wrapper_tag'] ?? 'aside';

		if ( ! $should_render ) {
			return;
		}

		ob_start();
		vite( 'seo' )->print_schema_microdata( 'sidebar' );
		$schema_microdata = ob_get_clean();

		printf(
			'<%1$s id="%2$s" class="%3$s"' . esc_attr( $schema_microdata ) . '>',
			esc_attr( $wrapper_tag ),
			esc_attr( $wrapper_id ),
			esc_attr( $wrapper_class )
		);

		$is_active = ! in_array( $id, [ 'sidebar-1', 'sidebar-2' ], true ) || is_active_sidebar( $id );
		?>
			<?php if ( $is_active ) : ?>
				<?php dynamic_sidebar( $id ); ?>
			<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
				<section class="widget">
					<h2 class="widget-title"><?php echo esc_html( $this->get_sidebar_title( $id ) ); ?></h2>
					<a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Click here to add widgets for this area', 'vite' ); ?></a>
				</section>
			<?php endif; ?>
		<?php
		printf( '</%s>', esc_attr( $wrapper_tag ) );
	}

	/**
	 * Sidebar title.
	 *
	 * @param string $id Sidebar id.
	 * @return string|void
	 */
	public function get_sidebar_title( string $id = '' ) {
		global $wp_registered_sidebars;
		return $wp_registered_sidebars[ $id ]['name'] ?? '';
	}
}
