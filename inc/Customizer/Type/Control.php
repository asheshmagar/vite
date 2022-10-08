<?php
/**
 * Control class.
 *
 * @since x.x.x
 * @package Vite;
 */

namespace Vite\Customizer\Type;

defined( 'ABSPATH' ) || exit;

use WP_Customize_Control;

/**
 * Class Control.
 */
class Control extends WP_Customize_Control {

	/**
	 * {@inheritDoc}
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default']     = $this->default ?? $this->setting->default;
		$this->json['value']       = $this->value();
		$this->json['link']        = $this->get_link();
		$this->json['id']          = $this->id;
		$this->json['label']       = esc_html( $this->label );
		$this->json['description'] = $this->description;
		$this->json['inputAttrs']  = $this->input_attrs;
		$this->json['choices']     = $this->choices;
	}

	/**
	 * {@inheritDoc}
	 *
	 * Content will be rendered via JS.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	public function render_content() {}
}
