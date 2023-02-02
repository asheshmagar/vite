<?php
/**
 * Class StarterContent.
 *
 * @package Vite
 */

namespace Vite\Compatibility\Plugin;

use Vite\Traits\JSON;
use WP_Post;

/**
 * Class StarterContent.
 */
class StarterContent extends Base {

	use JSON;

	/**
	 * {@inheritDoc}
	 *
	 * @param string $slug Plugin slug.
	 */
	public function __construct( string $slug ) {
		parent::__construct( $slug );
		$this->json_to_array( VITE_ASSETS_DIR . 'json/starter-post-content.json', 'post_content' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'setup' ], 99 );
		add_action( 'wp_insert_post', [ $this, 'update_post_meta' ], 10, 3 );
	}

	/**
	 * Setup starter content.
	 *
	 * @return void
	 */
	public function setup() {
		add_theme_support(
			'starter-content',
			$this->filter(
				'starter-content',
				[
					'nav_menus'  => [
						'menu-1' => [
							'items' => [
								'home'    => [
									'type'      => 'post_type',
									'object'    => 'page',
									'object_id' => '{{home}}',
								],
								'about'   => [
									'type'      => 'post_type',
									'object'    => 'page',
									'object_id' => '{{about}}',
								],
								'blog'    => [
									'type'      => 'post_type',
									'object'    => 'page',
									'object_id' => '{{blog}}',
								],
								'contact' => [
									'type'      => 'post_type',
									'object'    => 'page',
									'object_id' => '{{contact}}',
								],
							],
						],
					],
					'options'    => [
						'show_on_front'  => 'page',
						'page_on_front'  => '{{home}}',
						'page_for_posts' => '{{blog}}',
						'blogname'       => 'Vite Starter',
					],
					'theme_mods' => [],
					'posts'      => [
						'home'    => [
							'post_type'    => 'page',
							'post_title'   => 'Home',
							'post_content' => $this->post_content['home'] ?? '',
						],
						'about'   => [
							'post_type'    => 'page',
							'post_title'   => 'About',
							'post_content' => $this->post_content['about'] ?? '',
						],
						'blog'    => [
							'post_type'  => 'page',
							'post_title' => 'Blog',
							'post_name'  => 'blog',
						],
						'contact' => [
							'post_type'    => 'page',
							'post_title'   => 'Contact',
							'post_content' => $this->post_content['contact'] ?? '',
						],
					],
				]
			)
		);
	}

	/**
	 * Update post metas.
	 *
	 * @param int     $post_ID Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated.
	 */
	public function update_post_meta( int $post_ID, WP_Post $post, bool $update ) {
		if (
			$update ||
			empty( get_post_meta( $post_ID, '_customize_draft_post_name', true ) ) ||
			'page' !== $post->post_type
		) {
			return;
		}

		// Update post meta.
	}
}
