<?php

namespace Vite;

/**
 * Class Icon.
 */
class Icon {

	/**
	 * Icons list.
	 *
	 * @var mixed $icon
	 */
	public $icons;

	/**
	 * Icon constructor.
	 */
	public function __construct() {
		$file = VITE_ASSETS_DIR . '/json/font-awesome.json';
		if ( file_exists( $file ) ) {
			ob_start();
			include $file;
			$this->icons = json_decode( ob_get_clean(), true );
		}
	}

	/**
	 * Get icons.
	 *
	 * @return mixed
	 */
	public function get_icons() {
		return $this->icons;
	}

	/**
	 * Get icon.
	 *
	 * @param string $icon Icon name.
	 * @param array  $args Icon args.
	 * @return string|void
	 */
	public function get_icon( string $icon, array $args = [] ) {
		if ( ! $icon ) {
			return '';
		}
		$args = wp_parse_args(
			$args,
			[
				'class' => 'vite-icon',
				'size'  => 15,
				'echo'  => false,
			]
		);

		if ( ! isset( $this->icons[ $icon ] ) ) {
			return;
		}

		$svg          = sprintf( $this->icons[ $icon ], $args['class'], $args['size'], $args['size'] );
		$allowed_html = [
			'svg'     => [
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
			],
			'path'    => [
				'fill'      => true,
				'fill-rule' => true,
				'd'         => true,
				'transform' => true,
			],
			'circle'  => [
				'cx' => true,
				'cy' => true,
				'r'  => true,
			],
			'polygon' => [
				'fill'      => true,
				'fill-rule' => true,
				'points'    => true,
				'transform' => true,
				'focusable' => true,
			],
		];

		if ( ! $args['echo'] ) {
			return $svg;
		}

		echo wp_kses( $svg, $allowed_html );
	}
}

