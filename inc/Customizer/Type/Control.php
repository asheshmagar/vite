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

	/**
	 * Holds the Google fonts.
	 *
	 * @var null|array
	 */
	public $fonts;

	/**
	 * Holds the CSS selectors and properties.
	 *
	 * @var null|array
	 */
	public $css;

	/**
	 * {@inheritdoc}
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Args.
	 */
	public function __construct( $manager, $id, $args = [] ) {
		parent::__construct( $manager, $id, $args );

		if ( isset( $args['fonts'] ) ) {
			$this->fonts = $args['fonts'];
		}
		if ( isset( $args['css'] ) ) {
			$this->css = $args['css'];
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function json(): array {
		$json            = wp_array_slice_assoc(
			(array) $this,
			array(
				'type',
				'priority',
				'section',
				'label',
				'type',
				'description',
				'instance_number',
				'id',
				'choices',
				'css',
				'fonts',
			)
		);
		$json['active']  = $this->active();
		$json['content'] = $this->get_content();

		foreach ( $this->settings as $key => $setting ) {
			$json['settings'][ $key ] = $setting->id;
		}

		if ( 'dropdown-pages' === $this->type ) {
			$json['allow_addition'] = $this->allow_addition;
		}

		$json['default']    = $this->default ?? $this->setting->default;
		$json['inputAttrs'] = $this->input_attrs;
		$json['value']      = $this->value();
		$json['link']       = $this->get_link();

		return $json;
	}

	/**
	 * {@inheritDoc}
	 *
	 * Content will be rendered via JS - React.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	public function render_content() {}
}
