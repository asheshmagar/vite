<?php
/**
 * Theme functions and definitions.
 *
 * @since x.x.x
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define constants.
 */
! defined( 'VITE_VERSION' ) && define( 'VITE_VERSION', '1.0.0' );
! defined( 'VITE_PHP_MIN_VERSION' ) && define( 'VITE_PHP_MIN_VERSION', '7.4' );
! defined( 'VITE_WP_MIN_VERSION' ) && define( 'VITE_WP_MIN_VERSION', '5.5' );
! defined( 'VITE_ASSETS_DIR' ) && define( 'VITE_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'VITE_ASSETS_URI' ) && define( 'VITE_ASSETS_URI', get_theme_file_uri( '/assets/' ) );

/**
 * Make sure theme is compatible with the minimum PHP and WordPress versions.
 *
 * @return mixed
 */
function vite_maybe_load_theme() {
	$invalid_wp_version  = version_compare( $GLOBALS['wp_version'], VITE_WP_MIN_VERSION, '<' );
	$invalid_php_version = version_compare( phpversion(), VITE_PHP_MIN_VERSION, '<' );

	if ( $invalid_wp_version && $invalid_php_version ) {
		return [
			'load'   => false,
			'notice' => sprintf(
				/* translators: 1: WordPress version, 2: PHP version */
				__( 'Vite requires at least WordPress version %1$s and PHP version %2$s. You are running WordPress version %3$s and PHP version %4$s.', 'vite' ),
				VITE_WP_MIN_VERSION,
				VITE_PHP_MIN_VERSION,
				$GLOBALS['wp_version'],
				phpversion()
			),
		];
	}

	if ( $invalid_php_version ) {
		return [
			'load'   => false,
			'notice' => sprintf(
				/* translators: 1: PHP version */
				__( 'Vite requires at least PHP version %1$s. You are running PHP version %2$s.', 'vite' ),
				VITE_PHP_MIN_VERSION,
				phpversion()
			),
		];
	}

	if ( $invalid_wp_version ) {
		return [
			'load'   => false,
			'notice' => sprintf(
				/* translators: 1: WordPress version */
				__( 'Vite requires at least WordPress version %1$s. You are running WordPress version %2$s.', 'vite' ),
				VITE_WP_MIN_VERSION,
				$GLOBALS['wp_version']
			),
		];
	}

	return [ 'load' => true ];
}

$maybe_load_theme = vite_maybe_load_theme();

if ( ! $maybe_load_theme['load'] ) {
	add_action(
		'after_switch_theme',
		function() use ( $maybe_load_theme ) {
			switch_theme( WP_DEFAULT_THEME );
			unset( $_GET['activated'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_action(
				'admin_notices',
				function() use ( $maybe_load_theme ) {
					?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo esc_html( $maybe_load_theme['notice'] ); ?></p>
					</div>
					<?php
				}
			);
		}
	);

	add_action(
		'load-customize.php',
		function() use ( $maybe_load_theme ) {
			wp_die(
				esc_html( $maybe_load_theme['notice'] ),
				'',
				array(
					'back_link' => true,
				)
			);
		}
	);

	add_action(
		'template_redirect',
		function() use ( $maybe_load_theme ) {
			if ( isset( $_GET['preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_die( esc_html( $maybe_load_theme['notice'] ) );
			}
		}
	);

	return;
}

// Load composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';
