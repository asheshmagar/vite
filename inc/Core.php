<?php
/**
 * The loop.
 *
 * @since x.x.x
 * @package Vite
 */

namespace Vite;

use WP_Post_Type;

/**
 * Core.
 */
class Core {

	/**
	 * Holds current post types.
	 *
	 * @var string[]|WP_Post_Type[]
	 */
	public $post_types;

	/**
	 * Adds a callback function to an action hook.
	 *
	 * @param string                $hook_name     The name of the filter to add the callback to.
	 * @param callable|string|array $callback      The callback to be run when the filter is applied.
	 * @param int                   $priority      Optional. Used to specify the order in which the functions
	 *                                                  associated with a particular filter are executed.
	 *                                                  Lower numbers correspond with earlier execution,
	 *                                                  and functions with the same priority are executed
	 *                                                  in the order in which they were added to the filter. Default 10.
	 * @param int                   $accepted_args Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @return true Always returns true.
	 */
	public function add_action( string $hook_name, $callback, int $priority = 10, int $accepted_args = 1 ) {
		return add_action( $hook_name, $callback, $priority, $accepted_args );
	}

	/**
	 * Adds a callback function to a filter hook.
	 *
	 * @param string                $hook_name     The name of the filter to add the callback to.
	 * @param callable|string|array $callback      The callback to be run when the filter is applied.
	 * @param int                   $priority      Optional. Used to specify the order in which the functions
	 *                                                  associated with a particular filter are executed.
	 *                                                  Lower numbers correspond with earlier execution,
	 *                                                  and functions with the same priority are executed
	 *                                                  in the order in which they were added to the filter. Default 10.
	 * @param int                   $accepted_args Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @return true Always returns true.
	 */
	public function add_filter( string $hook_name, $callback, int $priority = 10, int $accepted_args = 1 ) {
		return add_filter( $hook_name, $callback, $priority, $accepted_args );
	}

	/**
	 * Removes a callback function from an action hook.
	 *
	 * This can be used to remove default functions attached to a specific filter
	 * hook and possibly replace them with a substitute.
	 *
	 * To remove a hook, the `$callback` and `$priority` arguments must match
	 * when the hook was added. This goes for both filters and actions. No warning
	 * will be given on removal failure.
	 *
	 * @param string                $hook_name The filter hook to which the function to be removed is hooked.
	 * @param callable|string|array $callback  The callback to be removed from running when the filter is applied.
	 *                                         This function can be called unconditionally to speculatively remove
	 *                                         a callback that may or may not exist.
	 * @param int                   $priority  Optional. The exact priority used when adding the original
	 *                                                           filter callback. Default 10.
	 * @return bool Whether the function existed before it was removed.
	 */
	public function remove_action( string $hook_name, $callback, int $priority = 10 ): bool {
		return $this->remove_filter( $hook_name, $callback, $priority );
	}

	/**
	 * Removes a callback function from a filter hook.
	 *
	 * This can be used to remove default functions attached to a specific filter
	 * hook and possibly replace them with a substitute.
	 *
	 * To remove a hook, the `$callback` and `$priority` arguments must match
	 * when the hook was added. This goes for both filters and actions. No warning
	 * will be given on removal failure.
	 *
	 * @param string                $hook_name The filter hook to which the function to be removed is hooked.
	 * @param callable|string|array $callback  The callback to be removed from running when the filter is applied.
	 *                                         This function can be called unconditionally to speculatively remove
	 *                                         a callback that may or may not exist.
	 * @param int                   $priority  Optional. The exact priority used when adding the original
	 *                                         filter callback. Default 10.
	 * @return bool Whether the function existed before it was removed.
	 */
	public function remove_filter( string $hook_name, $callback, int $priority = 10 ): bool {
		return remove_filter( $hook_name, $callback, $priority );
	}

	/**
	 * Do action with vite as prefix.
	 *
	 * @param mixed ...$args Arguments.
	 * @return void
	 */
	public function action( ...$args ) {
		$handle = array_shift( $args );

		if ( is_null( $handle ) ) {
			return;
		}

		$handle = "vite/$handle";

		do_action_ref_array( $handle, array_merge( [], $args ) );
	}

	/**
	 * Apply filters with vite as prefix.
	 *
	 * @param mixed ...$args Arguments.
	 * @return mixed|void
	 */
	public function filter( ...$args ) {
		$handle = array_shift( $args );

		if ( is_null( $handle ) ) {
			return;
		}

		$action = "vite/$handle";

		return apply_filters_ref_array( $action, array_merge( [], $args ) );
	}

	/**
	 * The loop.
	 *
	 * @return void
	 */
	public function the_loop() {
		/**
		 * Action: vite/the-loop/start.
		 *
		 * Fires before the loop.
		 *
		 * @since x.x.x
		 */
		$this->action( 'the-loop/start' );

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Action: vite/the-loop.
				 *
				 * Fires once the post is loaded.
				 *
				 * @since x.x.x
				 * @see TemplateHooks::content()
				 * @param \WP_Post $post The post object.
				 */
				$this->action( 'the-loop', get_post() );
			}
		} else {

			/**
			 * Action: vite/the-loop/end.
			 *
			 * Fires when no posts are found.
			 *
			 * @since x.x.x
			 */
			$this->action( 'the-loop/no-posts' );
		}

		/**
		 * Fires after the loop.
		 *
		 * @since x.x.x
		 */
		$this->action( 'the-loop/end' );
	}

	/**
	 * Get the current post ID.
	 *
	 * @return mixed
	 */
	public function get_the_id(): int {
		if ( is_home() && 'page' === get_option( 'show_on_front' ) ) {
			$post_id = (int) get_option( 'page_for_posts' );
		} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
			$post_id = (int) get_option( 'page_on_front' );
		} elseif ( is_singular() ) {
			$post_id = (int) get_the_ID();
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$post_id = get_queried_object_id();
		} else {
			$post_id = 0;
		}

		/**
		 * Filter: vite/the-id.
		 *
		 * @since x.x.x
		 * @param int $post_id The post ID.
		 */
		return $this->filter( 'the-id', $post_id );
	}

	/**
	 * Static theme string.
	 *
	 * @param string $id String id.
	 * @param bool   $should_return Whether to return or echo string.
	 * @return string|void
	 */
	public function static_strings( string $id, bool $should_return = false ) {
		$strings = [
			'no-posts'        => __( 'No posts found.', 'vite' ),
			'skip-to-content' => __( 'Skip to content', 'vite' ),
			'go-to-top'       => __( 'Go to top', 'vite' ),
			'leave-a-comment' => __( 'Leave a comment', 'vite' ),
			'primary-menu'    => __( 'Primary Menu', 'vite' ),
			'secondary-menu'  => __( 'Secondary Menu', 'vite' ),
			'footer-menu'     => __( 'Footer Menu', 'vite' ),

		];

		if ( ! isset( $id ) || ! isset( $strings[ $id ] ) ) {
			return '';
		}

		if ( $should_return ) {
			return $strings[ $id ];
		}

		echo esc_html( $strings[ $id ] );
	}

	/**
	 * Get default options.
	 *
	 * @param string $id Id.
	 * @return string|string[]
	 */
	public function get_default_options( string $id ) {

		$defaults = [
			'post_page_header' => 'style-1',
			'page_page_header' => 'style-1',
		];

		if ( ! isset( $id ) || ! isset( $defaults[ $id ] ) ) {
			return $defaults;
		}

		return $defaults[ $id ];
	}

	/**
	 * Parse smart tags.
	 *
	 * @param string $content Content.
	 * @return array|string|string[]
	 */
	public function parse_smart_tags( string $content = '' ) {
		$smart_tags = [
			'{{site-title}}' => get_bloginfo( 'name' ),
			'{{site-url}}'   => home_url(),
			'{{year}}'       => date_i18n( 'Y' ),
			'{{date}}'       => gmdate( 'Y-m-d' ),
			'{{time}}'       => gmdate( 'H:i:s' ),
			'{{datetime}}'   => gmdate( 'Y-m-d H:i:s' ),
			'{{copyright}}'  => '&copy;',
		];

		foreach ( $smart_tags as $tag => $value ) {
			$content = str_replace( $tag, $value, $content );
		}

		return $content;
	}

	/**
	 * Get the current post types.
	 *
	 * @return string[]|WP_Post_Type[]
	 */
	public function get_current_post_types(): array {
		$post_types = get_post_types(
			[
				'public'   => true,
				'_builtin' => false,
			],
			'objects'
		);
		$exclude    = $this->filter(
			'vite_ignored_post_types',
			[
				'attachment',
				'elementor_library',
				'elementor-hf',
			]
		);

		$this->post_types = array_diff( $post_types, $exclude );

		return $this->post_types;
	}

	/**
	 * Check if block editor is active.
	 *
	 * @return bool
	 */
	public function is_block_editor_active(): bool {
		$gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );

		$block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

		if ( ! $gutenberg && ! $block_editor ) {
			return false;
		}
		if ( $this->is_classic_editor_plugin_active() ) {
			$editor_option       = get_option( 'classic-editor-replace' );
			$block_editor_active = array( 'no-replace', 'block' );

			return in_array( $editor_option, $block_editor_active, true );
		}

		return true;
	}

	/**
	 * Check if classic editor plugin is active.
	 *
	 * @return bool
	 */
	private function is_classic_editor_plugin_active(): bool {
		! function_exists( 'is_plugin_active' ) && include_once ABSPATH . 'wp-admin/includes/plugin.php';
		return is_plugin_active( 'classic-editor/classic-editor.php' );
	}

	/**
	 * Get menus.
	 *
	 * @return array
	 */
	public function get_menus(): array {
		return array_reduce(
			get_terms(
				[
					'taxonomy'   => 'nav_menu',
					'hide_empty' => true,
				]
			),
			function ( $items, $menu ) {
				$items[ "$menu->term_id" ] = $menu->name;
				return $items;
			},
			[]
		);
	}

	/**
	 * Get social networks.
	 *
	 * @return array|mixed
	 */
	public function get_social_networks() {
		$json            = VITE_ASSETS_DIR . 'json/social-networks.json';
		$social_networks = [];
		if ( file_exists( $json ) ) {
			ob_start();
			require $json;
			$social_networks = json_decode( ob_get_clean(), true );
		}
		return $social_networks;
	}

	/**
	 * Get public path.
	 *
	 * @return string
	 */
	public function get_public_path(): string {
		return VITE_ASSETS_URI . 'dist/';
	}
}
