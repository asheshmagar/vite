<?php
/**
 *
 */

namespace Vite\SEO;

/**
 * Class SchemaMarkup.
 */
class SchemaMarkup extends SEOBase {

	const BASE_URL = 'https://schema.org/';

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_head', [ $this, 'add_schema_markup' ] );
	}

	/**
	 * Add Schema markup.
	 *
	 * @return void
	 */
	public function add_schema_markup() {
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
			'@context'         => static::BASE_URL,
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

		if ( is_single() ) {
			$schema_markup['datePublished'] = get_the_date( 'c' );
			$schema_markup['dateModified']  = get_the_modified_date( 'c' );
			$schema_markup['author']        = [
				'@type' => 'Person',
				'name'  => get_the_author(),
			];
		}

//		if ( class_exists( 'WooCommerce' ) ) {
//			if ( is_product() ) {
//				$schema_markup['@type']       = 'Product';
//				$schema_markup['name']        = $this->get_title();
//				$schema_markup['image']       = $this->get_image();
//				$schema_markup['description'] = $this->get_description();
//				$schema_markup['offers']      = [
//					'@type'         => 'Offer',
//					'priceCurrency' => get_woocommerce_currency(),
//					'price'         => get_post_meta( get_the_ID(), '_regular_price', true ),
//					'itemCondition' => 'https://schema.org/NewCondition',
//					'availability'  => 'https://schema.org/InStock',
//					'seller'        => [
//						'@type' => 'Organization',
//						'name'  => get_bloginfo( 'name' ),
//					],
//				];
//				$schema_markup['brand']       = [
//					'@type' => 'Brand',
//					'name'  => get_bloginfo( 'name' ),
//				];
//				$schema_markup['sku']         = get_post_meta( get_the_ID(), '_sku', true );
//			}
//		}
		?>
		<script type="application/ld+json">
			<?php echo wp_json_encode( $schema_markup ); ?>
		</script>
		<?php
	}
}
