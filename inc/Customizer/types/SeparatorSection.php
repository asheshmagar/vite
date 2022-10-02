<?php
/**
 * SeparatorSection class.
 */

namespace Theme\Customizer\Types;

defined( 'ABSPATH' ) || exit;

use WP_Customize_Section;

/**
 * SeparatorSection class.
 */
class SeparatorSection extends WP_Customize_Section {

	/**
	 * Type.
	 *
	 * @var string $type.
	 */
	public $type = 'customind-separator';

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render_template() {}
}
