<?php
/**
 * SortableControl class.
 */

namespace Vite\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * SortableControl class.
 */
class SortableControl extends Control {

	/**
	 * Controls type.
	 *
	 * @var string
	 */
	public $type = 'customind-sortable';

	public $input_attrs = array(
		'unsortable' => array(),
	);

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();
		$choices                         = $this->choices;
		$this->json['choices']           = array_reduce(
			array_keys( $this->choices ),
			function( $acc, $curr ) use ( $choices ) {
				if ( ! in_array( $curr, $this->input_attrs['unsortable'], true ) ) {
					$acc[ $curr ] = $choices[ $curr ];
				}
				return $acc;
			},
			array()
		);
		$this->input_attrs['unsortable'] = array_reduce(
			$this->input_attrs['unsortable'],
			function( $acc, $curr ) use ( $choices ) {
				if ( in_array( $curr, $this->input_attrs['unsortable'], true ) ) {
					$acc[ $curr ] = $choices[ $curr ];
				}
				return $acc;
			},
			array()
		);
		$this->json['inputAttrs']        = $this->input_attrs;
	}
}
