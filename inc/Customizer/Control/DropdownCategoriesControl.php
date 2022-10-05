<?php
/**
 * DropdownCategoriesControl class.
 */

namespace Vite\Customizer\Control;

defined( 'ABSPATH' ) || exit;

/**
 * DropdownCategoriesControl class.
 */
class DropdownCategoriesControl extends Control {

	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'vite-dropdown-categories';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();
		$categories                    = get_categories();
		$this->input_attrs['dropdown'] = array_reduce(
			$categories,
			function( $acc, $curr ) {
				$acc[ $curr->term_id ] = $curr->cat_name;
				return $acc;
			},
			array()
		);
		$this->json['inputAttrs']      = $this->input_attrs;
	}
}
