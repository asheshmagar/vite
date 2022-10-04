<?php
/**
 * TypographyControl class.
 */

namespace Vite\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * TypographyControl class.
 */
class TypographyControl extends Control {

	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'customind-typography';

	public function to_json() {
		parent::to_json();
		$this->input_attrs['fonts'] = $this->get_google_fonts();
		$this->json['inputAttrs']   = $this->input_attrs;
	}

	private function get_google_fonts() {
		ob_start();
		include_once dirname( __DIR__ ) . '/json/google-fonts.json';
		return json_decode( ob_get_clean(), true );
	}
}
