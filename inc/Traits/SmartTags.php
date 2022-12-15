<?php

namespace VIte\Traits;

trait SmartTags {

	/**
	 * Parse smart tags.
	 *
	 * @param string $content Content.
	 * @return array|string|string[]
	 */
	public function parse_smart_tags( string $content = '' ) {
		$smart_tags = [
			'{{site-title}}'   => get_bloginfo( 'name' ),
			'{{site-url}}'     => home_url(),
			'{{year}}'         => date_i18n( 'Y' ),
			'{{date}}'         => gmdate( 'Y-m-d' ),
			'{{time}}'         => gmdate( 'H:i:s' ),
			'{{datetime}}'     => gmdate( 'Y-m-d H:i:s' ),
			'{{copyright}}'    => 'Copyright &copy;',
			/* Translators: %s: Theme author. */
			'{{theme-author}}' => sprintf( __( 'Powered by %s' ), '<a href="https://wpvite.com" rel="nofollow noopener" target="_blank">Vite</a>' ),
		];

		foreach ( $smart_tags as $tag => $value ) {
			$content = str_replace( $tag, $value, $content );
		}

		return $content;
	}
}
