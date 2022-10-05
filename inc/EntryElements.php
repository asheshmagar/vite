<?php

namespace Vite;

/**
 * Entry Elements.
 */
class EntryElements {

	/**
	 * Entry elements.
	 *
	 * @param array $elements Elements.
	 * @return void
	 */
	public function render_entry_elements( array $elements = [] ) {
		if ( empty( $elements ) ) {
			return;
		}

		foreach ( $elements as $element ) {
			get_template_part( 'template-parts/entry/entry', $element );
		}
	}

	/**
	 * Entry meta.
	 *
	 * @return void
	 */
	public function render_entry_meta() {
		$meta_elements = apply_filters(
			'theme_entry_meta_elements',
			[
				'author',
				'date',
				'comments',
				'categories',
				'tags',
			]
		);

		if ( is_archive() || is_home() ) {
			$meta_elements = [ 'categories' ];
		}
		?>
		<div class="entry-meta">
			<?php
			foreach ( $meta_elements as $meta_element ) {
				get_template_part( 'template-parts/entry/entry', "meta-$meta_element" );
			}
			?>
		</div>
		<?php
	}


	/**
	 * Entry summary.
	 *
	 * @return void
	 */
	public function render_entry_summary() {
		?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<?php
	}

	/**
	 * Entry thumbnail.
	 *
	 * @return void
	 */
	public function render_entry_thumbnail() {
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
					<?php the_post_thumbnail( 'Vite_thumbnail' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Entry meta comment.
	 *
	 * @return void
	 */
	public function render_entry_meta_comments() {
		if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
			return;
		}
		?>
		<div class="entry-comments">
			<?php comments_popup_link( '0', '1', '%' ); ?>
		</div>
		<?php
	}

	/**
	 * Entry header.
	 *
	 * @return void
	 */
	public function render_entry_header() {
		?>
		<header class="entry-header">
			<?php
			if ( is_singular() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . get_the_permalink() . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</header>
		<?php
	}

	/**
	 * Entry content.
	 *
	 * @return void
	 */
	public function render_entry_content() {
		?>
		<div class="entry-content">
			<?php
			if ( is_single() ) {
				$sr_text = sprintf(
					/* translators: %s: Post title. */
					__( 'Continue reading %s', 'vite' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				);
				the_content( $sr_text );
			} else {
				the_content();
			}

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vite' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>
		<?php
	}

	/**
	 * Entry footer.
	 *
	 * @return void
	 */
	public function render_entry_footer() {
		?>
		<footer class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="ws-entry-cta">
				<span class="read-more-text"><?php esc_html_e( 'Read More', 'vite' ); ?></span>
			</a>
		</footer>
		<?php
	}

	/**
	 * Entry meta date.
	 *
	 * @return void
	 */
	public function render_entry_meta_date() {
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

	/**
	 * Display the author of the current post.
	 *
	 * @return void
	 */
	public function render_entry_meta_author() {
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

	/**
	 * Entry meta cat or tags.
	 *
	 * @param string $type Type cat or tags.
	 * @return void
	 */
	private function render_entry_meta_categories_or_tags( string $type = 'cat' ) {
		$list = 'cat' === $type ? get_the_category_list( ', ' ) : get_the_tag_list( '', ', ' );

		if ( empty( $list ) ) {
			return;
		}
		?>
		<span class="<?php echo esc_attr( $type ); ?>-links">
			<?php
			printf(
				/* translators: %1$s: post link. %2$s: post categories */
				'<span class="%1$s-links">%2$s</span>',
				esc_attr( $type ),
				wp_kses(
					$list,
					[
						'a' => [
							'href'  => true,
							'rel'   => true,
							'class' => true,
						],
					]
				)
			);
			?>
		</span>
		<?php
	}

	/**
	 * Entry meta categories.
	 *
	 * @return void
	 */
	public function render_entry_meta_categories() {
		$this->render_entry_meta_categories_or_tags();
	}

	/**
	 * Entry meta tags.
	 *
	 * @return void
	 */
	public function render_entry_meta_tags() {
		$this->render_entry_meta_categories_or_tags( 'tag' );
	}
}
