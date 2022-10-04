<?php
/**
 * UpsellSection class.
 */

namespace Vite\Customizer\Types;

defined( 'ABSPATH' ) || exit;

use WP_Customize_Section;

/**
 * SeparatorSection class.
 */
class UpsellSection extends WP_Customize_Section {

	/**
	 * Type.
	 *
	 * @var string $type.
	 */
	public $type = 'customind-upsell-section';

	/**
	 * URL.
	 *
	 * @var string $url.
	 */
	public $url = '';

	/**
	 * ID.
	 *
	 * @var string $id.
	 */
	public $id = '';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @return array
	 */
	public function json(): array {
		$json        = parent::json();
		$json['url'] = esc_url( $this->url );
		$json['id']  = $this->id;
		return $json;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render_template() {}
}
