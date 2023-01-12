<?php
/**
 * SmartTags.
 *
 * @package Vite
 */

namespace VIte\Traits;

trait SmartTags {

	use Hook;

	/**
	 * Get smart tags.
	 *
	 * @return mixed|null
	 */
	public function get_smart_tags() {
		return $this->filter( 'smart/tags', [
			'{{site-title}}'   => get_bloginfo( 'name' ),
			'{{site-url}}'     => home_url(),
			'{{year}}'         => date_i18n( 'Y' ),
			'{{date}}'         => gmdate( 'Y-m-d' ),
			'{{time}}'         => gmdate( 'H:i:s' ),
			'{{datetime}}'     => gmdate( 'Y-m-d H:i:s' ),
			'{{copyright}}'    => 'Copyright &copy;',
			/* Translators: %s: Theme author. */
			'{{theme-author}}' => sprintf( __( 'Powered by %s' ), '<a href="https://wpvite.com" rel="nofollow noopener" target="_blank">Vite</a>' ),
		] );
	}

	/**
	 * Parse smart tags.
	 *
	 * @param string $content Content.
	 * @return string
	 */
	public function parse_smart_tags( string $content = '' ): string {
		return strtr( $content, $this->get_smart_tags() ?? [] );
	}
}
