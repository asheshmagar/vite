<?php
/**
 * Theme core methods.
 *
 * @since x.x.x
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\{ JSON, Mods, SmartTags };

/**
 * Core.
 */
class Core {

	use Mods , JSON, SmartTags {
		SmartTags::filter insteadof Mods;
		SmartTags::action insteadof Mods;
		SmartTags::add_action insteadof Mods;
		SmartTags::add_filter insteadof Mods;
		SmartTags::remove_action insteadof Mods;
		SmartTags::remove_filter insteadof Mods;
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
		$social_networks = $this->json_to_array( VITE_ASSETS_DIR . 'json/social-networks.json' );

		/**
		 * Filter: vite/social-networks.
		 *
		 * @param array $social_networks The social networks.
		 *
		 * @since x.x.x
		 */
		return $this->filter( 'social-networks', $social_networks );
	}
}
