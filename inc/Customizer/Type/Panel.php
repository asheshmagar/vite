<?php
/**
 * Panel class.
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
	public $type = 'customind-panel';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @return array
	 */
	public function json(): array {
		$array                   = wp_array_slice_assoc(
			(array) $this,
			array(
				'id',
				'description',
				'priority',
				'type',
				'panel',
			)
		);
		$array['title']          = html_entity_decode(
			$this->title,
			ENT_QUOTES,
			get_bloginfo( 'charset' )
		);
		$array['content']        = $this->get_content();
		$array['active']         = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		return $array;
	}
}
