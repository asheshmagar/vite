<?php
/**
 * Class PWA.
 *
 * @package Vite
 */

namespace Vite\Compatibility;

defined( 'ABSPATH' ) || exit;

/**
 * Class PWA.
 */
class PWA extends AbstractCompatibility {

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		$this->add_action( 'vite/pwa/offline', [ $this, 'offline' ] );
		$this->add_action( 'vite/pwa/500', [ $this, 'error' ] );
	}

	/**
	 * Offline template.
	 *
	 * @return void
	 */
	public function offline() {
		?>
		<main id="main" class="vite-main">
			<h1><?php esc_html_e( 'Oops! It looks like you&#8217;re offline.', 'vite' ); ?></h1>
			<?php wp_service_worker_error_message_placeholder(); ?>
		</main>
		<?php
	}

	/**
	 * Load server error template.
	 *
	 * @return void
	 */
	public function error() {
		?>
		<main id="main" class="vite-main">
			<h1><?php esc_html_e( 'Oops! Something went wrong.', 'vite' ); ?></h1>
			<?php wp_service_worker_error_message_placeholder(); ?>
			<?php wp_service_worker_error_details_template(); ?>
		</main>
		<?php
	}
}
