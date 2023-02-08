<?php
/**
 * Back compat functionality.
 *
 * Prevents Vite from running on WordPress versions prior to 5.5 and PHP versions prior to 7.0,
 *
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

/**
 * Make sure theme is compatible with the minimum PHP and WordPress versions.
 *
 * @return bool
 */
function maybe_load_theme(): bool {
	$invalid_wp_version  = version_compare( $GLOBALS['wp_version'], VITE_WP_MIN_VERSION, '<' );
	$invalid_php_version = version_compare( phpversion(), VITE_PHP_MIN_VERSION, '<' );

	switch ( true ) {
		case $invalid_wp_version && $invalid_php_version:
			$message = sprintf(
				/* translators: 1: PHP version, 2: WordPress version */
				__( 'Vite requires PHP version %1$s or higher and WordPress version %2$s or higher.', 'vite' ),
				VITE_PHP_MIN_VERSION,
				VITE_WP_MIN_VERSION
			);
			break;
		case $invalid_php_version && ! $invalid_wp_version:
			$message = sprintf(
				/* translators: 1: PHP version */
				__( 'Vite requires at least PHP version %1$s. You are running PHP version %2$s.', 'vite' ),
				VITE_PHP_MIN_VERSION,
				phpversion()
			);
			break;
		case $invalid_wp_version && ! $invalid_php_version:
			$message = sprintf(
				/* translators: 1: WordPress version */
				__( 'Vite requires at least WordPress version %1$s. You are running WordPress version %2$s.', 'vite' ),
				VITE_WP_MIN_VERSION,
				$GLOBALS['wp_version']
			);
			break;
		default:
			$message = null;
	}

	if ( ! isset( $message ) ) {
		return true;
	}

	add_action(
		'after_switch_theme',
		function () use ( $message ) {
			switch_theme( WP_DEFAULT_THEME );
			unset( $_GET['activated'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_action(
				'admin_notices',
				function () use ( $message ) {
					?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo esc_html( $message ); ?></p>
					</div>
					<?php
				}
			);
		}
	);

	add_action(
		'load-customize.php',
		function () use ( $message ) {
			wp_die(
				esc_html( $message ),
				'',
				[
					'back_link' => true,
				]
			);
		}
	);

	add_action(
		'template_redirect',
		function () use ( $message ) {
			if ( isset( $_GET['preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_die( esc_html( $message ) );
			}
		}
	);

	return false;
}
