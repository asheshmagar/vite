<?php
/**
 * Section class.
 */

namespace Vite\Customizer\Type;

defined( 'ABSPATH' ) || exit;

use WP_Customize_Section;

/**
 * Section class.
 */
class Section extends WP_Customize_Section {

	/**
	 * Section.
	 *
	 * @var $section
	 */
	public $section;

	/**
	 * Section type.
	 *
	 * @var string $type;
	 */
	public $type = 'vite-section';

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
				'panel',
				'type',
				'description_hidden',
				'section',
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

		if ( $this->panel ) {
			$json['customizeAction'] = sprintf(
			/* Translators: 1: Panel Title. */
				esc_html__( 'Customizing &#9656; %s' ),
				esc_html( $this->manager->get_panel( $this->panel )->title )
			);
		} else {
			$json['customizeAction'] = esc_html__( 'Customizing' );
		}

		return $json;
	}
}
