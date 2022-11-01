<?php

namespace Vite;

/**
 * Init sidebar.
 */
class Sidebar {

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	/**
	 * Register sidebars.
	 */
	public function register_sidebars() {
		$sidebars = apply_filters(
			'vite_sidebars',
			[
				'sidebar-1' => __( 'Sidebar 1', 'vite' ),
				'sidebar-2' => __( 'Sidebar 2', 'vite' ),
				'footer-1'  => __( 'Footer 1', 'vite' ),
				'footer-2'  => __( 'Footer 2', 'vite' ),
				'footer-3'  => __( 'Footer 3', 'vite' ),
				'footer-4'  => __( 'Footer 4', 'vite' ),
				'footer-5'  => __( 'Footer 5', 'vite' ),
				'footer-6'  => __( 'Footer 6', 'vite' ),
			]
		);
		foreach ( $sidebars as $id => $name ) {
			register_sidebar(
				apply_filters(
					'vite_sidebar_args',
					[
						'name'          => $name,
						'id'            => $id,
						'description'   => __( 'Add widgets here..', 'vite' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => sprintf( '<%s class="widget-title">', apply_filters( 'vite_sidebar_title_tag', 'h2' ) ),
						'after_title'   => sprintf( '</%s>', apply_filters( 'vite_sidebar_title_tag', 'h2' ) ),
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

		printf(
			'<%1$s id="%2$s" class="%3$s">',
			esc_attr( $wrapper_tag ),
			esc_attr( $wrapper_id ),
			esc_attr( $wrapper_class )
		);
		?>
			<?php if ( is_active_sidebar( $id ) ) : ?>
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
