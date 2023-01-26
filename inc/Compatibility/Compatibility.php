<?php
/**
 * Main Compatibility class.
 *
 * @package Vite\Compatibility
 */

namespace Vite\Compatibility;

defined( 'ABSPATH' ) || exit;

use Vite\Compatibility\Plugin\{
	Plugin,
	BBPress,
	Edd,
	Elementor,
	JetPack,
	LifterLMS,
	PWA,
	StarterContent,
	TheEventsCalendar,
	WebStories,
	WooCommerce
};
use Vite\Traits\Hook;

/**
 * Main Compatibility class.
 */
class Compatibility {

	use Hook;

	/**
	 * Holds compatibility classes instance.
	 *
	 * @var array
	 */
	public $container = [];

	/**
	 * Initialize the theme compatibility with plugins.
	 *
	 * @return void
	 */
	public function init() {
		$plugins = $this->filter(
			'compatibility/plugins',
			[
				'starter-content'        => [
					'class'       => StarterContent::class,
					'should_load' => (bool) get_option( 'fresh_site' ),
				],
				'bbpress'                => [
					'class'       => BBPress::class,
					'should_load' => class_exists( 'bbPress' ),
				],
				'lifterlms'              => [
					'class'       => LifterLMS::class,
					'should_load' => class_exists( 'LifterLMS' ),
				],
				'easy-digital-downloads' => [
					'class'       => EDD::class,
					'should_load' => class_exists( 'Easy_Digital_Downloads' ),
				],
				'woocommerce'            => [
					'class'       => WooCommerce::class,
					'should_load' => class_exists( 'WooCommerce' ),
				],
				'web-stories'            => [
					'class'       => WebStories::class,
					'should_load' => class_exists( 'Google\Web_Stories\Plugin' ),
				],
				'elementor'              => [
					'class'       => Elementor::class,
					'should_load' => class_exists( 'Elementor\Plugin' ),
				],
				'pwa'                    => [
					'class'       => PWA::class,
					'should_load' => defined( 'PWA_VERSION' ),
				],
				'the-events-calendar'    => [
					'class'       => TheEventsCalendar::class,
					'should_load' => class_exists( 'Tribe__Events__Main' ),
				],
				'jetpack'                => [
					'class'       => JetPack::class,
					'should_load' => defined( 'JETPACK__VERSION' ),
				],
			]
		);

		foreach ( $plugins as $slug => $args ) {
			if (
				! $args['should_load'] ||
				! $args['class'] ||
				! is_subclass_of( $args['class'], Plugin::class )
			) {
				continue;
			}

			$this->container[ $slug ] = new $args['class']( $slug );
			$this->container[ $slug ]->init();
		}
	}
}
