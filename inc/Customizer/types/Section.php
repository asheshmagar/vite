<?php
/**
 * Section class.
 */

namespace Vite\Customizer\Types;

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
	public $type = 'customind-section';

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
				'panel',
				'type',
				'description_hidden',
				'section',
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

		if ( $this->panel ) {
			$array['customizeAction'] = sprintf(
			/* Translators: 1: Panel Title. */
				esc_html__( 'Customizing &#9656; %s' ),
				esc_html( $this->manager->get_panel( $this->panel )->title )
			);
		} else {
			$array['customizeAction'] = esc_html__( 'Customizing' );
		}

		return $array;
	}
}
