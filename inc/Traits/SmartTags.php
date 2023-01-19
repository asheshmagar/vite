<?php
/**
 * SmartTags.
 *
 * @package Vite
 */

namespace Vite\Traits;

trait SmartTags {

	use Hook;

	/**
	 * Get smart tags.
	 *
	 * @return mixed|null
	 */
	public function get_smart_tags() {
		return $this->filter(
			'smart/tags',
			[
				'{{site-title}}'   => get_bloginfo( 'name' ),
				'{{site-url}}'     => home_url(),
				'{{year}}'         => date_i18n( 'Y' ),
				'{{date}}'         => gmdate( 'Y-m-d' ),
				'{{time}}'         => gmdate( 'H:i:s' ),
				'{{datetime}}'     => gmdate( 'Y-m-d H:i:s' ),
				'{{copyright}}'    => 'Copyright &copy;',
				/* Translators: %s: Theme author. */
				'{{theme-author}}' => sprintf( __( 'Powered by %s' ), '<a href="https://wpvite.com" rel="nofollow noopener" target="_blank">Vite</a>' ),
			]
		);
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

	/**
	 * Description of smart tags.
	 *
	 * @return string
	 */
	public function description_smart_tags(): string {
		$smart_tags = $this->get_smart_tags();
		array_walk(
			$smart_tags,
			function ( &$value, $key ) {
				$value = "<code>$key</code> : $value";
			}
		);
		return sprintf(
			'<details><summary>%s</summary><ul>%s</ul></details>',
			__( 'Available smart tags:' ),
			'<li>' . implode( '</li><li>', array_values( $smart_tags ) ) . '</li>'
		);
	}
}
