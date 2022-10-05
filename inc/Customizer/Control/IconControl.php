<?php
namespace Vite\Customizer\Control;

class IconControl extends Control {

	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'vite-icon';

	/**
	 * Control's nav info.
	 *
	 * @var string
	 */
	public $icons = array();

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();
		$this->json['icons'] = $this->get_icons();
	}

	private function get_icons() {
		$file  = dirname( __DIR__ ) . '/json/icons.json';
		$icons = array();

		if ( file_exists( $file ) ) {
			ob_start();
			include_once $file;
			$icons = json_decode( ob_get_clean(), true );

		}

		return apply_filters( 'vite_icons', $icons );
	}
}
