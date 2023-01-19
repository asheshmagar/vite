<?php
/**
 * Class SEO.
 *
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\Mods;

defined( 'ABSPATH' ) || exit;

/**
 * SEO
 */
class SEO {

	use Mods;

	const SCHEMA_ORG = 'https://schema.org/';

	/**
	 * Schema markup.
	 *
	 * @var bool
	 */
	public $schema_markup = false;

	/**
	 * Open Graph Meta Tags
	 *
	 * @var bool
	 */
	public $og_meta_tags = false;

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'init_props' ] );
		$this->add_action( 'vite/head', [ $this, 'print_og_meta_tags' ] );
	}

	/**
	 * Init props.
	 *
	 * @return void
	 */
	public function init_props() {
		$this->schema_markup = $this->get_theme_mod( 'schema-markup', false );
		$this->og_meta_tags  = $this->get_theme_mod( 'og-meta-tags', false );
	}

	/**
	 * Print Open Graph meta tags.
	 *
	 * @return void
	 */
	public function print_og_meta_tags() {
		if ( ! $this->og_meta_tags ) {
			return;
		}

		if ( is_single() ) {
			$type = 'article';
		} elseif ( is_author() ) {
			$type = 'profile';
		} elseif ( is_search() ) {
			$type = 'website';
		} else {
			$type = 'website';
		}

		$og_tags = [
			'og:type'        => $type,
			'og:title'       => $this->get_title(),
			'og:description' => $this->get_description(),
			'og:url'         => $this->get_url(),
			'og:site_name'   => $this->get_site_name(),
			'og:image'       => $this->get_image(),
		];

		foreach ( $og_tags as $property => $content ) {
			if ( ! empty( $content ) ) {
				printf(
					'<meta property="%s" content="%s" />%s',
					esc_attr( $property ),
					esc_attr( $content ),
					"\n"
				);
			}
		}
	}

	/**
	 * Get title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		$title = get_the_title();

		if ( is_front_page() ) {
			$title = get_bloginfo( 'name' );
		}

		return $title;
	}

	/**
	 * Get description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		$description = '';

		if ( is_single() ) {
			$description = get_the_excerpt();
		} elseif ( is_author() ) {
			$description = get_the_author_meta( 'description' );
		} elseif ( is_search() ) {
			$description = get_bloginfo( 'description' );
		} elseif ( is_front_page() ) {
			$description = get_bloginfo( 'description' );
		}

		return $description;
	}

	/**
	 * Get URL.
	 *
	 * @return false|string
	 */
	public function get_url() {
		$url = get_the_permalink();

		if ( is_front_page() ) {
			$url = home_url();
		}

		return $url;
	}

	/**
	 * Get site name.
	 *
	 * @return string
	 */
	public function get_site_name(): string {
		return get_bloginfo( 'name' );
	}

	/**
	 * Get image.
	 *
	 * @return false|string
	 */
	public function get_image() {
		$image = '';

		if ( is_single() ) {
			$image = get_the_post_thumbnail_url();
		} elseif ( is_author() ) {
			$image = get_avatar_url( get_the_author_meta( 'ID' ) );
		} elseif ( is_search() ) {
			$image = $this->get_custom_logo_url();
		} elseif ( is_front_page() ) {
			$image = $this->get_custom_logo_url();
		}

		return $image;
	}

	/**
	 * Get custom logo data.
	 *
	 * @return mixed|string|boolean
	 */
	private function get_custom_logo_url() {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo           = wp_get_attachment_image_src( $custom_logo_id, 'full' );

		if ( $logo ) {
			return $logo[0];
		}

		return false;
	}

	/**
	 * Print schema microdata.
	 *
	 * @param string $context Context of the microdata.
	 * @return void
	 */
	public function print_schema_microdata( string $context = '' ) {
		if ( ! $this->schema_markup ) {
			return;
		}

		switch ( $context ) {
			case 'html':
				$type = 'WebPage';
				if ( is_single() ) {
					$type = 'BlogPosting';
				} elseif ( is_author() ) {
					$type = 'ProfilePage';
				} elseif ( is_search() ) {
					$type = 'SearchResultsPage';
				}
				echo ' itemscope itemtype="' . esc_url( static::SCHEMA_ORG ) . esc_attr( "/$type" ) . '"';
				break;
			case 'header':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'WPHeader" itemscope';
				break;
			case 'footer':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'WPFooter" itemscope';
				break;
			case 'navigation':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'SiteNavigationElement" itemscope';
				break;
			case 'article':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'Article" itemscope';
				break;
			case 'author':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'Person" itemscope';
				break;
			case 'image':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'ImageObject" itemscope';
				break;
			case 'comment':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'Comment" itemscope';
				break;
			case 'logo':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'Organization" itemscope';
				break;
			case 'sidebar':
				echo ' itemtype="' . esc_url( static::SCHEMA_ORG ) . 'WPSideBar" itemscope';
				break;
			default:
				break;
		}
	}
}
