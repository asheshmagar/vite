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
use WP_Customize_Manager;

/**
 * Class Control.
 */
class Control extends WP_Customize_Control {

	const ADDITIONAL_PROP_KEYS = [
		'selectors',
		'properties',
		'fonts',
		'field',
	];

	/**
	 * {@inheritdoc}
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Args.
	 */
	public function __construct( $manager, $id, $args = [] ) {
		parent::__construct( $manager, $id, $args );

		foreach ( static::ADDITIONAL_PROP_KEYS as $prop_key ) {
			if ( isset( $args[ $prop_key ] ) ) {
				$this->{$prop_key} = $args[ $prop_key ];
			}
		}
	}

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

		foreach ( static::ADDITIONAL_PROP_KEYS as $prop_key ) {
			if ( isset( $this->{$prop_key} ) ) {
				$this->json[ $prop_key ] = $this->{$prop_key};
			}
		}
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
