<?php
/**
 * UpgradeControl class.
 */

namespace Vite\Customizer\Control;

defined( 'ABSPATH' ) || exit;

/**
 * UpgradeControl class.
 */
class UpgradeControl extends Control {

	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'vite-update';

	/**
	 * URL.
	 *
	 * @var string
	 */
	public $url = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();
		$this->json['url'] = esc_url( $this->url );
	}
}
