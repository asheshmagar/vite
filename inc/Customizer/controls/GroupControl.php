<?php
/**
 * GroupControl class.
 */

namespace Vite\Customizer\Controls;

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
	public $type = 'customind-group';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		global $customind;

		parent::to_json();
		$config = array();
		$id     = $this->id;

		if ( isset( $customind->group_configs[ $id ]['tabs'] ) ) {
			$tabs = array_keys( $customind->group_configs[ $id ]['tabs'] );
			foreach ( $tabs as $tab ) {
				$config['tabs'][ $tab ] = wp_list_sort( $customind->group_configs[ $id ]['tabs'][ $tab ], 'priority' );
			}
		} else {
			if ( isset( $customind->group_configs[ $id ] ) ) {
				$config = wp_list_sort( $customind->group_configs[ $id ], 'priority' );
			}
		}

		$this->input_attrs['fields'] = $config;
		$this->json['inputAttrs']    = $this->input_attrs;
	}
}
