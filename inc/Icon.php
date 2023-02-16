<?php

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\{ Hook, JSON };

/**
 * Class Icon.
 */
class Icon {

	use Hook, JSON;

	/**
	 * Holds icons.
	 *
	 * @var array|null
	 */
	private $icons;

	/**
	 * Icon constructor.
	 */
	public function __construct() {
		if ( ! isset( $this->icons ) ) {
			$this->icons = $this->json_to_array( VITE_ASSETS_DIR . '/json/font-awesome.json' );
		}
		$this->icons = array_merge( $this->icons, $this->get_extra_icons() ?? [] );
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
	 * @return string|bool
	 */
	public function get_icon( string $icon, array $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'class' => 'vite-icon',
				'size'  => 15,
				'echo'  => false,
			]
		);

		$args = $this->filter( 'svg/icon/args', $args, $icon );

		if ( ! $icon || ! isset( $this->icons[ $icon ] ) ) {
			return false;
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

		if ( $args['echo'] ) {
			echo wp_kses( $svg, $allowed_html );
			return true;
		}

		return $svg;
	}

	/**
	 * Get custom icons.
	 *
	 * @return string[]
	 */
	private function get_extra_icons(): array {
		return $this->filter(
			'svg/icons/extra',
			[
				'vite-search'  => '<svg xmlns="http://www.w3.org/2000/svg" class="search %s" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
				'vite-desktop' => '<svg xmlns="http://www.w3.org/2000/svg" class="desktop %s" width="%s" height="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
				'vite-tablet'  => '<svg xmlns="http://www.w3.org/2000/svg" class="tablet %s" width="%s" height="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>',
				'vite-mobile'  => '<svg xmlns="http://www.w3.org/2000/svg" class="mobile %s" width="%s" height="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>',
			]
		);
	}
}

