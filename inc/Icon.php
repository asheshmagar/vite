<?php

namespace Vite;

use Vite\Traits\Hook;
use Vite\Traits\JSON;

/**
 * Class Icon.
 */
class Icon {

	use Hook, JSON;

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
		$this->icons                = $this->json_to_array( VITE_ASSETS_DIR . '/json/font-awesome.json' );
		$this->icons['vite-search'] = "<svg xmlns='http://www.w3.org/2000/svg' class='search %s' width='%d' height='%d' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='11' cy='11' r='8'></circle><line x1='21' y1='21' x2='16.65' y2='16.65'></line></svg>";
	}

	/**
	 * Get icons.
	 *
	 * @return mixed
	 */
	public function get_icons() {
		return $this->filter( 'svg/icons', $this->icons );
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

		$args = $this->filter( 'svg/icon/args', $args, $icon );

		if ( ! isset( $this->icons[ $icon ] ) ) {
			return;
		}

		$svg          = sprintf( $this->icons[ $icon ], $args['class'], $args['size'], $args['size'] );
		$allowed_html = $this->filter(
			'svg/allowed-html',
			[
				'svg'     => [
					'class'           => true,
					'xmlns'           => true,
					'width'           => true,
					'height'          => true,
					'viewbox'         => true,
					'aria-hidden'     => true,
					'role'            => true,
					'focusable'       => true,
					'fill'            => true,
					'stroke'          => true,
					'stroke-width'    => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
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
				'line'    => [
					'x1' => true,
					'y1' => true,
					'x2' => true,
					'y2' => true,
				],
			]
		);

		$svg = $this->filter( 'svg/icon', $svg, $this->icons );

		if ( ! $args['echo'] ) {
			return $svg;
		}

		echo wp_kses( $svg, $allowed_html );
	}
}

