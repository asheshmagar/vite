<?php
/**
 * SmartTags.
 *
 * @package Vite
 */

namespace Vite\Traits;

trait SmartTags {

	/**
	 * Get smart tags.
	 *
	 * @return mixed|null
	 */
	public function get_smart_tags() {
		$time_format = get_option( 'time_format', 'H:i:s' );
		$date_format = get_option( 'date_format', 'Y-m-d' );
		$hook_handle = 'vite/smart/tags';

		/**
		 * Filter smart tags.
		 *
		 * @param array $smart_tags Smart tags.
		 * @since 1.0.0
		 */
		return apply_filters(
			$hook_handle,
			[
				'{{site-title}}'   => get_bloginfo( 'name' ),
				'{{site-url}}'     => home_url(),
				'{{year}}'         => wp_date( 'Y' ),
				'{{date}}'         => wp_date( $date_format ),
				'{{time}}'         => wp_date( $time_format ),
				'{{datetime}}'     => wp_date( "$date_format $time_format" ),
				'{{copyright}}'    => 'Copyright &copy;',
				'{{theme-author}}' => sprintf(
					/* Translators: %s: Theme author. */
					__( 'Powered by %s' ),
					'<a href="https://wpvite.com" rel="nofollow noopener" target="_blank">Vite</a>'
				),
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
