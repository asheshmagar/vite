<?php
/**
 * Entry elements.
 *
 * @package Vite
 */

namespace Vite\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * EntryElements.
 */
class EntryElements extends Elements {

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
		$this->action( 'entry-elements/start' );

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
		$this->action( 'entry-elements/end' );
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
		$this->action( 'entry-elements/meta/start' );
		?>
		<div class="vite-post__meta entry-meta">
			<?php
			$meta_elements = array_filter(
				$meta_elements,
				function( $meta_element ) {
					return $meta_element['visible'];
				}
			);
			foreach ( $meta_elements as $meta_element ) {
				get_template_part( 'template-parts/entry/entry', "meta-{$meta_element['id']}" );
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
		$this->action( 'entry-elements/meta/end' );
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
		$this->action( 'entry-elements/excerpt/start' );
		?>
		<div
		<?php
		$this->print_html_attributes(
			'entry-elements/excerpt',
			[
				'class' => [
					'vite-post__excerpt',
					'entry-summary',
				],
			]
		);
		?>
		>
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
		$this->action( 'entry-elements/excerpt/end' );
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
		$this->action( 'entry-elements/featured-image/start' );
		?>
		<div class="vite-post__thumbnail">
			<?php if ( is_singular( $post_type ) ) : ?>
				<?php the_post_thumbnail( 'full', $this->filter( 'html-attributes/entry-elements/featured-image', [] ) ); ?>
			<?php else : ?>
				<a class="vite-post__thumbnail-link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" aria-label="<?php esc_html( the_title() ); ?>">
					<div class="vite-post__thumbnail-inner vite-post__thumbnail-inner--3x2">
						<?php the_post_thumbnail( 'vite_thumbnail', $this->filter( 'html-attributes/entry-elements/featured-image', [] ) ); ?>
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
		$this->action( 'entry-elements/featured-image/end' );
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
		$this->action( 'entry-elements/meta/comment/start' );
		?>
		<div class="vite-post__meta__comment">
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
			<?php
			add_filter(
				'comments_popup_link_attributes',
				function( $attributes ) {
					return $attributes . $this->print_html_attributes( 'entry-elements/meta/comment', [], false );
				}
			);
			comments_popup_link( __( 'Leave a comment', 'vite' ), __( '1 Comment', 'vite' ), __( '% Comments', 'vite' ) );
			?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/meta/comments/end.
		 *
		 * Fires after the entry meta comments are displayed.
		 *
		 * @since x.x.x
		 */
		$this->action( 'entry-elements/meta/comment/end' );
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
		$this->action( 'entry-elements/title/start' );
		?>
		<div class="vite-post__title entry-title">
			<?php if ( is_singular() ) : ?>
			<h1
				<?php
				$this->print_html_attributes(
					'entry-elements/title',
					[]
				);
				?>
			>
				<?php the_title(); ?>
			</h1>
		<?php else : ?>
			<h2
			<?php
			$this->print_html_attributes(
				'entry-elements/title',
				[]
			);
			?>
			>
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>
			<?php endif; ?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/title/end.
		 *
		 * Fires after the entry title is displayed.
		 *
		 * @since x.x.x
		 */
		$this->action( 'entry-elements/title/end' );
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
		$this->action( 'entry-elements/content/start' );
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
		$this->action( 'entry-elements/content/end' );
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
		$this->action( 'entry-elements/button/start' );
		?>
		<a href="<?php the_permalink(); ?>" class="vite-post__btn">
			<span class="vite-post__btn-text">
				<?php esc_html_e( 'Read More', 'vite' ); ?>
				<span class="screen-reader-text"><?php the_title(); ?></span>
			</span>
		</a>
		<?php

		/**
		 * Action: vite/entry-elements/button/end.
		 *
		 * Fires after the entry button is displayed.
		 *
		 * @since x.x.x
		 */
		$this->action( 'entry-elements/button/end' );
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
			"vite-post__meta__$type-time" . ( 'published' === $type ? ' entry-date published' : ' updated' ),
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
		$this->action( 'entry-elements/meta/date/start' );
		?>
		<div
		<?php
		$this->print_html_attributes(
			'entry-elements/meta/date',
			[
				'class' => [ 'vite-post__meta__date' ],
			],
			true,
			$type
		);
		?>
		>
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
		$this->action( 'entry-elements/meta/date/end' );
	}

	/**
	 * Meta author.
	 *
	 * @param mixed $args Arguments.
	 * @return void
	 */
	public function meta_author( $args ) {
		$author_id = get_the_author_meta( 'ID' );

		if ( empty( $author_id ) ) {
			return;
		}

		/**
		 * Action: vite/entry-elements/meta/author/start.
		 *
		 * Fires before the entry meta author is displayed.
		 *
		 * @since x.x.x
		 */
		$this->action( 'entry-elements/meta/author/start' );
		?>
		<div
		<?php
		$this->print_html_attributes(
			'entry-elements/meta/author',
			[
				'class' => 'vite-post__meta__author',
			]
		);
		?>
		>
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
			</span>
			<span class="vite-post__author author vcard">
				<a
				<?php
				$this->print_html_attributes(
					'entry-elements/meta/author/url',
					[
						'class' => [ 'vite-post__author__url', 'url', 'fn', 'n' ],
						'href'  => esc_url( get_author_posts_url( $author_id ) ),
						'rel'   => 'author',
					]
				)
				?>
				>
					<span
					<?php
					$this->print_html_attributes(
						'entry-elements/meta/author/name',
						[
							'class' => 'vite-post__author__name',
						]
					)
					?>
					>
						<?php the_author(); ?>
					</span>
				</a>
			</span>
		</div>
		<?php
		$this->action( 'entry-elements/meta/author/end' );
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
		$this->action( "entry-elements/meta/$type/start" );
		?>
		<div
		<?php
		$this->print_html_attributes(
			'entry-elements/meta/tax',
			[
				'class' => [ "vite-post__meta__$type-links", "$type-links" ],
			],
			true,
			$type
		)
		?>
		>
			<?php
			echo wp_kses(
				$list,
				[
					'a' => [
						'href'  => true,
						'rel'   => true,
						'class' => true,
					],
				]
			)
			?>
		</div>
		<?php

		/**
		 * Action: vite/entry-elements/meta/$type/end.
		 *
		 * Fires after the entry meta tax is displayed.
		 *
		 * @since x.x.x
		 */
		$this->action( "entry-elements/meta/$type/end" );
	}
}
