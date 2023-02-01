<?php

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\Mods;

/**
 * Class Schema.
 */
class Schema {

	use Mods;

	/**
	 * Base URI.
	 *
	 * @var string
	 */
	private $base_uri = 'https://schema.org/';

	/**
	 * Hook suffix.
	 *
	 * @var string
	 */
	private $hook_suffix = 'vite/html-attributes';

	/**
	 * Init schema markup.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! $this->get_mod( 'schema-markup', true ) ) {
			return;
		}
		$suffix = $this->hook_suffix;
		$this->add_filter( "$suffix/body", [ $this, 'body_schema' ] );
		$this->add_filter( "$suffix/header", [ $this, 'header_schema' ] );
		$this->add_filter( "$suffix/header/site-branding", [ $this, 'site_branding_schema' ] );
		$this->add_filter( "$suffix/footer", [ $this, 'footer_schema' ] );
		$this->add_filter( "$suffix/nav", [ $this, 'site_navigation_schema' ] );
		$this->add_filter( "$suffix/article", [ $this, 'post_schema' ] );
		$this->add_action( "$suffix/comment", [ $this, 'comment_schema' ] );
		$this->add_filter( "$suffix/entry-elements/title", [ $this, 'title_schema' ] );
		$this->add_filter( "$suffix/entry-elements/excerpt", [ $this, 'excerpt_schema' ] );
		$this->add_filter( "$suffix/entry-elements/meta/author", [ $this, 'author_schema' ] );
		$this->add_filter( "$suffix/entry-elements/meta/date", [ $this, 'date_schema' ], 10, 2 );
		$this->add_filter( "$suffix/entry-elements/meta/tax", [ $this, 'tax_schema' ], 10, 2 );
		$this->add_filter( "$suffix/entry-elements/meta/comment", [ $this, 'meta_comment_schema' ] );
		$this->add_filter( "$suffix/entry-elements/featured-image", [ $this, 'thumbnail_schema' ] );
	}

	/**
	 * Comment schema.
	 *
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function comment_schema( array $attributes ): array {
		$this->add_filter(
			"$this->hook_suffix/comment/author",
			function( $attrs ) {
				return array_merge(
					$attrs,
					[
						'itemscope' => '',
						'itemtype'  => $this->base_uri . 'Person',
						'itemprop'  => 'author',
					]
				);
			}
		);
		$this->add_filter(
			"$this->hook_suffix/comment/author/name",
			function( $attrs ) {
				return array_merge(
					$attrs,
					[
						'itemprop' => 'name',
					]
				);
			}
		);
		$this->add_filter(
			"$this->hook_suffix/comment/time",
			function( $attrs ) {
				return array_merge(
					$attrs,
					[
						'itemprop' => 'datePublished',
					]
				);
			}
		);
		$this->add_filter(
			"$this->hook_suffix/comment/content",
			function( $attrs ) {
				return array_merge(
					$attrs,
					[
						'itemprop' => 'text',
					]
				);
			}
		);
		return array_merge(
			$attributes,
			[
				'itemscope' => '',
				'itemtype'  => $this->base_uri . 'Comment',
			]
		);
	}

	/**
	 * Add schema attr to thumbnail.
	 *
	 * @param array $attrs Attributes.
	 * @return array
	 */
	public function thumbnail_schema( array $attrs ): array {
		$attrs['itemprop'] = 'image';
		return $attrs;
	}

	/**
	 * Add schema to tax.
	 *
	 * @param array  $attributes HTML attributes.
	 * @param string $type Tax type cat or tag.
	 *
	 * @return array
	 */
	public function tax_schema( array $attributes, string $type ): array {
		if ( 'tag' === $type ) {
			$attributes['itemprop'] = 'keywords';
		}
		return $attributes;
	}

	/**
	 * Add schema attributes to comments.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function meta_comment_schema( array $attributes ): array {
		$attributes['itemprop'] = 'discussionUrl';
		return $attributes;
	}

	/**
	 * Add schema to date.
	 *
	 * @param array  $attributes HTML attributes.
	 * @param string $type Update or modified.
	 * @return array
	 */
	public function date_schema( array $attributes, string $type ): array {
		return array_merge(
			$attributes,
			[
				'itemprop' => 'published' === $type ? 'datePublished' : 'dateModified',
			]
		);
	}

	/**
	 * Add schema to author.
	 *
	 * @param array $attributes HML attributes.
	 *
	 * @return array
	 */
	public function author_schema( array $attributes ): array {
		$this->add_filter(
			"$this->hook_suffix/entry-elements/meta/author/url",
			function( $attributes ) {
				return array_merge( $attributes, [ 'itemprop' => 'url' ] );
			}
		);
		$this->add_filter(
			"$this->hook_suffix/entry-elements/meta/author/name",
			function( $attributes ) {
				return array_merge( $attributes, [ 'itemprop' => 'name' ] );
			}
		);
		return array_merge(
			$attributes,
			[
				'itemscope' => '',
				'itemtype'  => $this->base_uri . 'Person',
				'itemprop'  => 'author',
			]
		);
	}

	/**
	 * Add schema attributes to title.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function title_schema( array $attributes ): array {
		return array_merge(
			$attributes,
			[
				'itemprop' => 'headline',
			]
		);
	}

	/**
	 * Add schema attributes to title.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function excerpt_schema( array $attributes ): array {
		return array_merge(
			$attributes,
			[
				'itemprop' => 'text',
			]
		);
	}

	/**
	 * Add schema attributes to post.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function post_schema( array $attributes ): array {
		$this->add_filter(
			"$this->hook_suffix/article/content",
			function ( $attrs ) {
				return array_merge( $attrs, [ 'itemprop' => 'text' ] );
			}
		);
		return array_merge(
			$attributes,
			[
				'itemscope' => 'itemscope',
				'itemtype'  => $this->base_uri . 'CreativeWork',
			]
		);
	}

	/**
	 * Add schema attributes to navigation.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function site_navigation_schema( array $attributes ): array {
		return array_merge(
			$attributes,
			[
				'itemscope' => 'itemscope',
				'itemtype'  => $this->base_uri . 'SiteNavigationElement',
			]
		);
	}

	/**
	 * Add schema attributes to HTML.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function body_schema( array $attributes ): array {
		if ( is_single() || is_archive() || is_home() ) {
			$type = 'Blog';
		} elseif ( is_author() ) {
			$type = 'ProfilePage';
		} elseif ( is_search() ) {
			$type = 'SearchResultsPage';
		} else {
			$type = 'WebPage';
		}
		return array_merge(
			$attributes,
			[
				'itemscope' => 'itemscope',
				'itemtype'  => $this->base_uri . $type,
			]
		);
	}

	/**
	 * Add schema attributes to site branding.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function site_branding_schema( array $attributes ): array {
		$this->add_filter(
			"$this->hook_suffix/header/site-branding/title",
			function( array $attrs ) {
				return array_merge(
					$attrs,
					[
						'itemprop' => 'name',
					]
				);
			}
		);
		$this->add_filter(
			"$this->hook_suffix/header/site-branding/description",
			function( array $attrs ) {
				return array_merge(
					$attrs,
					[
						'itemprop' => 'description',
					]
				);
			}
		);
		return array_merge(
			$attributes,
			[
				'itemscope' => '',
				'itemtype'  => $this->base_uri . 'Organization',
			]
		);
	}

	/**
	 * Add schema attributes to header.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function header_schema( array $attributes ): array {
		return array_merge(
			$attributes,
			[
				'itemscope' => '',
				'itemtype'  => $this->base_uri . 'WPHeader',
				'itemid'    => '#mast-head',
			]
		);
	}

	/**
	 * Add schema attributes to footer.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return array
	 */
	public function footer_schema( array $attributes ): array {
		return array_merge(
			$attributes,
			[
				'itemscope' => '',
				'itemtype'  => $this->base_uri . 'WPFooter',
				'itemid'    => '#colophon',
			]
		);
	}
}
