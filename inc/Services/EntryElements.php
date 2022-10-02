<?php

namespace Theme\Services;

class EntryElements extends Service {

	/**
	 * @inheritDoc
	 */
	public function init(): void {}

	public function services(): array {
		return [
			'render_entry_elements' => [ $this, 'render_entry_elements' ],
		];
	}

	/**
	 * Render entry elements.
	 *
	 * @return void
	 */
	public function render_entry_elements() {
		$elements = apply_filters(
			'theme_entry_elements',
			[
				'thumbnail',
				'header',
				'meta',
				'summary',
				'footer',
			]
		);

		foreach ( $elements as $element ) {
			get_template_part( 'template-parts/entry/entry', $element );
		}
	}

	public function entry_thumbnail() {
		$post_type = get_post_type();
		$type      = $post_type;
		if ( 'attachment' === $post_type ) {
			if ( wp_attachment_is( 'audio' ) ) {
				$type = "$type:audio";
			} elseif ( wp_attachment_is( 'video' ) ) {
				$type = "$type:video";
			}
		}
		if ( post_password_required() || ! post_type_supports( $type, 'thumbnail' ) || ! has_post_thumbnail() ) {
			return;
		}
		?>
		<div class="entry-thumbnail">
			<?php if ( is_singular( $post_type ) ) : ?>
				<?php the_post_thumbnail( 'full' ); ?>
			<?php else : ?>
				<a class="entry-thumbnail-link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail( 'full' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	private function entry_comments() {
		if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
			return;
		}
		?>
		<div class="entry-comments">
			<?php comments_popup_link( '0', '1', '%' ); ?>
		</div>
		<?php
	}

	public function entry_header() {
		?>
		<header class="entry-header">
			<?php
			if ( is_singular() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</header>
		<?php
	}

	public function entry_meta() {
		$meta_elements = apply_filters(
			'theme_entry_meta_elements',
			[
				'author',
				'date',
				'comments',
			]
		);
		$post_type     = get_post_type();
		?>
		<div class="entry-meta">
			<?php
			if ( 'post' === $post_type ) {
				foreach ( $meta_elements as $meta_element ) {
					switch ( $meta_element ) {
						case 'author':
							$this->entry_author();
							break;
						case 'date':
							$this->entry_date();
							break;
						case 'comments':
							$this->entry_comments();
							break;
					}
				}
			}
			?>
		</div>
		<?php
	}

	private function entry_date() {
		?>
		<span class="posted-on">
			<?php
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}
			$time_string = sprintf(
				$time_string,
				esc_attr( get_the_date( DATE_W3C ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( DATE_W3C ) ),
				esc_html( get_the_modified_date() )
			);
			printf(
			/* translators: %1$s: post link. %2$s: Modified or Updated post time */
				'<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				wp_kses(
					$time_string,
					[
						'time' => [
							'datetime' => true,
							'class'    => true,
						],
					]
				)
			);
			?>
		</span>
		<?php
	}

	private function entry_author() {
		?>
		<span class="byline">
			<?php
			printf(
			/* translators: %1$s: post link. %2$s: post author */
				'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			);
			?>
		</span>
		<?php
	}
}
