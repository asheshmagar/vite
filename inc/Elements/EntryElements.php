<?php
/**
 * Entry elements.
 *
 * @package Vite
 */

namespace Vite\Elements;

/**
 * EntryElements.
 */
class EntryElements {

	use ElementTrait;

	/**
	 * Elements.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function entry( $args ) {
		$elements = $args['elements'] ?? [];

		if ( empty( $elements ) ) {
			return;
		}

		/**
		 * Action: vite/entry-elements/start.
		 *
		 * Fires before the entry elements are displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/start' );

		foreach ( $elements as $element ) {
			if ( $element['visible'] ) {
				if ( 'meta' === substr( $element['id'], 0, 4 ) ) {
					get_template_part( 'template-parts/entry/entry', 'meta', [ 'meta-elements' => $element['items'] ?? [] ] );
				} else {
					get_template_part( 'template-parts/entry/entry', $element['id'] );
				}
			}
		}

		/**
		 * Action: vite/entry-elements/end.
		 *
		 * Fires after the entry elements are displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/end' );
	}

	/**
	 * Meta.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function meta( $args ) {
		$meta_elements = $args['meta-elements'] ?? [];
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

		/**
		 * Action: vite/entry-elements/meta/start.
		 *
		 * Fires before the entry meta is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/start' );
		?>
		<div class="vite-post__meta">
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
					echo '<span class="vite-post__meta__separator">/</span>';
				}
			}
			?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/meta/end.
		 *
		 * Fires after the entry meta is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/end' );
	}

	/**
	 * Excerpt.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function excerpt( $args ) {

		/**
		 * Action: vite/entry-elements/excerpt/start.
		 *
		 * Fires before the entry excerpt is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/excerpt/start' );
		?>
		<div class="vite-post__excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php
		/**
		 * Action: vite/entry-elements/excerpt/end.
		 *
		 * Fires after the entry excerpt is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/excerpt/end' );
	}

	/**
	 * Featured Image.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function featured_image( $args ) {
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

		/**
		 * Action: vite/entry-elements/featured-image/start.
		 *
		 * Fires before the entry featured image is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/featured-image/start' );
		?>
		<div class="vite-post__thumbnail">
			<?php if ( is_singular( $post_type ) ) : ?>
				<?php the_post_thumbnail( 'full' ); ?>
			<?php else : ?>
				<a class="vite-post__thumbnail-link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" aria-label="<?php esc_html( the_title() ); ?>">
					<div class="vite-post__thumbnail-inner vite-post__thumbnail-inner--3x2">
						<?php the_post_thumbnail( 'vite_thumbnail' ); ?>
					</div>
				</a>
			<?php endif; ?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/featured-image/end.
		 *
		 * Fires after the entry featured image is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/featured-image/end' );
	}

	/**
	 * Meta comments.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function meta_comments( $args ) {
		if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
			return;
		}

		/**
		 * Action: vite/entry-elements/meta/comments/start.
		 *
		 * Fires before the entry meta comments are displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/comments/start' );
		?>
		<div class="vite-post__meta__comments">
			<span class="vite-post__meta__icon">
				<?php
					vite( 'icon' )->get_icon(
						'comment',
						[
							'echo' => true,
							'size' => 11,
						]
					);
				?>
			</span>
			<?php comments_popup_link( __( 'Leave a comment', 'vite' ), __( '1 Comment', 'vite' ), __( '% Comments', 'vite' ) ); ?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/meta/comments/end.
		 *
		 * Fires after the entry meta comments are displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/comments/end' );
	}

	/**
	 * Title.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function title( $args ) {

		/**
		 * Action: vite/entry-elements/title/start.
		 *
		 * Fires before the entry title is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/title/start' );
		?>
		<div class="vite-post__title">
			<?php
			if ( is_singular() ) {
				the_title( '<h1>', '</h1>' );
			} else {
				the_title( '<h2><a href="' . get_the_permalink() . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/title/end.
		 *
		 * Fires after the entry title is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/title/end' );
	}

	/**
	 * Content.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function content( $args ) {
		/**
		 * Action: vite/entry-elements/content/start.
		 *
		 * Fires before the entry content is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/content/start' );
		?>
		<div class="vite-post__content">
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
						'before' => '<div class="vite-post__page-links">' . esc_html__( 'Pages:', 'vite' ),
						'after'  => '</div>',
					)
				);
			?>
		</div>
		<?php
		/**
		 * Action: vite/entry-elements/content/end.
		 *
		 * Fires after the entry content is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/content/end' );
	}

	/**
	 * Meta.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function button( $args ) {

		/**
		 * Action: vite/entry-elements/button/start.
		 *
		 * Fires before the entry button is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/button/start' );
		?>
		<a href="<?php the_permalink(); ?>" class="vite-post__btn" >
			<span class="screen-reader-text"><?php the_title(); ?></span>
			<span class="vite-post__btn-text"><?php esc_html_e( 'Read More', 'vite' ); ?></span>
		</a>
		<?php

		/**
		 * Action: vite/entry-elements/button/end.
		 *
		 * Fires after the entry button is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/button/end' );
	}

	/**
	 * Meta date.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function meta_date( $args ) {
		$type = $args['type'] ?? 'published';
		if ( ! in_array( $type, [ 'published', 'updated' ], true ) ) {
			return;
		}

		$time_string = '<time class="%1$s" datetime="%2$s">%3$s</time>';
		$data_func   = 'published' === $type ? 'get_the_date' : 'get_the_modified_date';

		$time_string = sprintf(
			$time_string,
			"vite-post__meta__$type-time",
			esc_attr( call_user_func( $data_func, DATE_W3C ) ),
			esc_html( call_user_func( $data_func ) )
		);

		/**
		 * Action: vite/entry-elements/meta/date/start.
		 *
		 * Fires before the entry meta date is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/date/start' );
		?>
		<div class="vite-post__meta__date">
			<span class="vite-post__meta__icon">
				<?php
					vite( 'icon' )->get_icon(
						'calendar',
						[
							'echo' => true,
							'size' => 11,
						]
					);
				?>
			</span>
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

		/**
		 * Action: vite/entry-elements/meta/date/end.
		 *
		 * Fires after the entry meta date is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/date/end' );
	}

	/**
	 * Meta author.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function meta_author( $args ) {

		/**
		 * Action: vite/entry-elements/meta/author/start.
		 *
		 * Fires before the entry meta author is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( 'entry-elements/meta/author/start' );
		?>
		<div class="vite-post__meta__author">
			<span class="vite-post__meta__icon">
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
						'<span class="vite-post__author"><a href="%1$s">%2$s</a></span>',
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						esc_html( get_the_author() )
					);
				?>
		</div>
		<?php
		$args['core']->action( 'entry-elements/meta/author/end' );
	}

	/**
	 * Meta tax.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function meta_tax( $args ) {
		$type = $args['type'] ?? 'cat';
		$list = 'cat' === $type ? get_the_category_list( ' ' ) : get_the_tag_list( '', ', ' );

		if ( empty( $list ) ) {
			return;
		}

		/**
		 * Action: vite/entry-elements/meta/$type/start.
		 *
		 * Fires before the entry meta tax is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( "entry-elements/meta/$type/start" );
		printf(
		/* translators: %1$s: post link. %2$s: post categories */
			'<span class="vite-post__meta__%1$s-links">%2$s</span>',
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

		/**
		 * Action: vite/entry-elements/meta/$type/end.
		 *
		 * Fires after the entry meta tax is displayed.
		 *
		 * @since x.x.x
		 */
		$args['core']->action( "entry-elements/meta/$type/end" );
	}
}
