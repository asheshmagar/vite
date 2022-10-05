<?php
/**
 * GroupControl class.
 */

namespace Vite\Customizer\Control;

defined( 'ABSPATH' ) || exit;

/**
 * GroupControl class.
 */
class GroupControl extends Control {

	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'vite-group';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();
		$config = array();
		$id     = $this->id;

		if ( isset( vite( 'customizer' )->group[ $id ]['tabs'] ) ) {
			$tabs = array_keys( vite( 'customizer' )->group[ $id ]['tabs'] );
			foreach ( $tabs as $tab ) {
				$config['tabs'][ $tab ] = wp_list_sort( vite( 'customizer' )->group[ $id ]['tabs'][ $tab ], 'priority' );
			}
		} else {
			if ( isset( vite( 'customizer' )->group[ $id ] ) ) {
				$config = wp_list_sort( vite( 'customizer' )->group[ $id ], 'priority' );
			}
		}

		$this->input_attrs['fields'] = $config;
		$this->json['inputAttrs']    = $this->input_attrs;
	}
}
