<?php
/**
 * Panel class.
 *
 * @package vite
 * @since 1.0.0
 */

namespace Vite\Customizer\Type;

defined( 'ABSPATH' ) || exit;

use WP_Customize_Panel;

/**
 * Panel class.
 */
class Panel extends WP_Customize_Panel {

	/**
	 * Panel.
	 *
	 * @var $panel.
	 */
	public $panel;

	/**
	 * Panel type.
	 *
	 * @var string
	 */
	public $type = 'vite-panel';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @return array
	 */
	public function json(): array {
		$json                   = wp_array_slice_assoc(
			(array) $this,
			array(
				'id',
				'description',
				'priority',
				'type',
				'panel',
			)
		);
		$json['title']          = html_entity_decode(
			$this->title,
			ENT_QUOTES,
			get_bloginfo( 'charset' )
		);
		$json['content']        = $this->get_content();
		$json['active']         = $this->active();
		$json['instanceNumber'] = $this->instance_number;

		return $json;
	}
}
