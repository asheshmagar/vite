<?php
/**
 * Class SEO.
 *
 * @package Vite
 */

namespace Vite\SEO;

/**
 * SEO
 */
class SEO extends Base {

	const SCHEMA_ORG = 'https://schema.org/';

	/**
	 * {@inheritdoc}
	 */
	public function in_head() {
		parent::in_head();
		$this->print_og_meta_tags();
		$this->print_schema_json_ld();
	}

	/**
	 * Schema markup JSON-LD.
	 *
	 * @return void
	 */
	public function print_schema_json_ld() {
		if ( is_single() ) {
			$type = 'BlogPosting';
		} elseif ( is_author() ) {
			$type = 'ProfilePage';
		} elseif ( is_search() ) {
			$type = 'SearchResultsPage';
		} else {
			$type = 'WebPage';
		}

		$schema_markup = [
			'@context'         => static::SCHEMA_ORG,
			'@type'            => $type,
			'headline'         => $this->get_title(),
			'description'      => $this->get_description(),
			'url'              => $this->get_url(),
			'mainEntityOfPage' => [
				'@type' => 'WebPage',
				'@id'   => $this->get_url(),
			],
			'publisher'        => [
				'@type' => 'Organization',
				'name'  => $this->get_site_name(),
				'logo'  => [
					'@type' => 'ImageObject',
					'url'   => $this->get_image(),
				],
			],
			'image'            => $this->get_image(),
		];

		wp_print_inline_script_tag( wp_json_encode( $schema_markup ), [ 'type' => 'application/ld+json' ] );
	}

	/**
	 * Print Open Graph meta tags.
	 *
	 * @return void
	 */
	public function print_og_meta_tags() {
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
}
