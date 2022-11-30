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
			if ( $element['visible'] ) {
				if ( 'meta' === substr( $element['id'], 0, 4 ) ) {
					get_template_part( 'template-parts/entry/entry', 'meta', [ 'meta-elements' => $element['items'] ?? [] ] );
				} else {
					get_template_part( 'template-parts/entry/entry', $element['id'] );
				}
			}
		}
	}

	/**
	 * Entry meta.
	 *
	 * @param array $meta_elements Meta elements.
	 * @return void
	 */
	public function render_entry_meta( array $meta_elements = [] ) {
		$should_render = false;

		foreach ( $meta_elements as $meta_element ) {
			if ( $meta_element['visible'] ) {
				$should_render = true;
				break;
			}
		}

		if ( ! $should_render ) {
			return;
		}
		?>
		<div class="entry-meta">
			<?php
			$meta_elements = array_filter(
				$meta_elements,
				function( $meta_element ) {
					return $meta_element['visible'];
				}
			);
			$i             = 0;
			$meta_count    = count( $meta_elements );
			foreach ( $meta_elements as $meta_element ) {
				++$i;
				get_template_part( 'template-parts/entry/entry', "meta-{$meta_element['id']}" );
				if ( $i < $meta_count ) {
					echo '<span class="entry-meta-separator">/</span>';
				}
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
	public function render_entry_excerpt() {
		?>
		<div class="entry-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php
	}

	/**
	 * Entry featured image.
	 *
	 * @return void
	 */
	public function render_entry_featured_image() {
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
				<a class="entry-thumbnail-link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" aria-label="<?php esc_html( the_title() ); ?>">
					<div class="entry-thumbnail-wrap" data-aspect-ratio="3:2">
						<?php the_post_thumbnail( 'vite_thumbnail' ); ?>
					</div>
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
		<div class="entry-meta-comments">
			<a href="<?php echo esc_url( get_comments_link() ); ?>" tabindex="-1">
				<?php
				vite( 'icon' )->get_icon(
					'comment',
					[
						'echo' => true,
						'size' => 11,
					]
				);
				?>
			</a>
			<?php comments_popup_link( __( 'Leave a comment', 'vite' ), __( '1 Comment', 'vite' ), __( '% Comments', 'vite' ) ); ?>
		</div>
		<?php
	}

	/**
	 * Entry header.
	 *
	 * @return void
	 */
	public function render_entry_title() {
		?>
		<div class="entry-title">
			<?php
			if ( is_singular() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . get_the_permalink() . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</div>
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
	public function render_entry_button() {
		?>
		<a href="<?php the_permalink(); ?>" class="entry-button" >
			<span class="screen-reader-text"><?php the_title(); ?></span>
			<span class="entry-button-text"><?php esc_html_e( 'Read More', 'vite' ); ?></span>
			<span class="entry-button-icon">
				<?php
				vite( 'icon' )->get_icon(
					'arrow-right-long',
					[
						'echo' => true,
						'size' => 13,
					]
				);
				?>
			</span>
		</a>
		<?php
	}

	/**
	 * Render entry date.
	 *
	 * @param string $type Published or updated.
	 * @return void
	 */
	public function render_entry_date( string $type = 'published' ) {
		if ( ! in_array( $type, [ 'published', 'updated' ], true ) ) {
			return;
		}

		$time_string = '<time class="%1$s" datetime="%2$s">%3$s</time>';
		$data_func   = 'published' === $type ? 'get_the_date' : 'get_the_modified_date';

		$time_string = sprintf(
			$time_string,
			"entry-$type-time",
			esc_attr( call_user_func( $data_func, DATE_W3C ) ),
			esc_html( call_user_func( $data_func ) )
		);

		?>
		<div class="entry-meta-date">
			<a href="<?php echo esc_url( get_permalink() ); ?>" tabindex="-1">
				<?php
				vite( 'icon' )->get_icon(
					'calendar',
					[
						'echo' => true,
						'size' => 11,
					]
				);
				?>
			</a>
			<?php
			printf(
				/* translators: %1$s: post link. %2$s: Modified or Updated post date */
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
		</div>
		<?php
	}

	/**
	 * Display the author of the current post.
	 *
	 * @return void
	 */
	public function render_entry_meta_author() {
		?>
		<div class="entry-meta-author">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" tabindex="-1">
				<?php
				vite( 'icon' )->get_icon(
					'user',
					[
						'echo' => true,
						'size' => 11,
					]
				);
				?>
			<?php
			printf(
				/* translators: %1$s: post link. %2$s: post author */
				'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			);
			?>
		</div>
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
