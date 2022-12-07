<?php
/**
 *
 */

namespace Vite;

/**
 * Breadcrumbs.
 */
class Breadcrumbs {

	/**
	 * Array of items belonging to the current breadcrumb trail.
	 *
	 * @var    array
	 */
	private $items = [];

	/**
	 * Arguments used to build the breadcrumb trail.
	 *
	 * @var    array
	 */
	private $args;

	/**
	 * Array of text labels.
	 *
	 * @var    array
	 */
	private $labels = [];

	/**
	 * Array of post types (key) and taxonomies (value) to use for single post views.
	 *
	 * @var    array
	 */
	private $post_taxonomy = [];

	/**
	 * Magic method to use in case someone tries to output the layout object as a string.
	 * We'll just return the trail HTML.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->trail();
	}

	/**
	 * Sets up the breadcrumb trail properties.  Calls the `Breadcrumb_Trail::add_items()` method
	 * to creat the array of breadcrumb items.
	 *
	 * @param array $args {.
	 *
	 * @type string $container Container HTML element. nav|div
	 * @type string $before String to output before breadcrumb menu.
	 * @type string $after String to output after breadcrumb menu.
	 * @type string $browse_tag The HTML tag to use to wrap the "Browse" header text.
	 * @type string $list_tag The HTML tag to use for the list wrapper.
	 * @type string $item_tag The HTML tag to use for the item wrapper.
	 * @type bool $show_on_front Whether to show when `is_front_page()`.
	 * @type bool $network Whether to link to the network main site (multisite only).
	 * @type bool $show_title Whether to show the title (last item) in the trail.
	 * @type array $labels Text labels. @see Breadcrumb_Trail::set_labels()
	 * @type array $post_taxonomy Taxonomies to use for post types. @see Breadcrumb_Trail::set_post_taxonomy()
	 * @type bool $echo Whether to print or return the breadcrumbs.
	 * }
	 */
	public function breadcrumbs( array $args = [] ) {
		$defaults = [
			'container'         => 'nav',
			'before'            => '',
			'after'             => '',
			'browse_tag'        => 'h2',
			'list_tag'          => 'ul',
			'item_tag'          => 'li',
			'show_on_front'     => true,
			'network'           => false,
			'show_title'        => true,
			'link_current_item' => false,
			'labels'            => [],
			'post_taxonomy'     => [],
			'echo'              => true,
			'separator'         => '/',
		];

		/**
		 * Filter: vite/breadcrumbs/trail/args.
		 *
		 * Filter the breadcrumb trail default arguments.
		 *
		 * @since x.x.x
		 * @param array $defaults Default arguments.
		 * @param array $args Arguments passed to the breadcrumb trail.
		 */
		$this->args = vite( 'core' )->filter( 'breadcrumbs/trail/args', wp_parse_args( $args, $defaults ) );

		// Set the labels and post taxonomy properties.
		$this->set_labels();
		$this->set_post_taxonomy();

		// Let's find some items to add to the trail!
		$this->add_items();

		return $this->trail();
	}

	/**
	 * Formats the HTML output for the breadcrumb trail.
	 *
	 * @return string|void
	 */
	private function trail() {
		// Set up variables that we'll need.
		$breadcrumb    = '';
		$item_count    = count( $this->items );
		$item_position = 0;
		$schema_type   = vite( 'customizer' )->get_setting( 'schema-markup-implementation' );
		$has_schema    = vite( 'customizer' )->get_setting( 'schema-markup' );
		$microdata     = $has_schema && 'microdata' === $schema_type;
		$jsonld        = $has_schema && 'json-ld' === $schema_type;
		$json          = [];

		// Connect the breadcrumb trail if there are items in the trail.
		if ( 0 < $item_count ) {
			// Open the unordered list.
			$breadcrumb .= sprintf(
				'<%s class="vite-breadcrumb__list" %s>',
				tag_escape( $this->args['list_tag'] ),
				$microdata ? 'itemscope itemtype="https://schema.org/BreadcrumbList"' : ''
			);

			if ( $jsonld ) {
				$json = [
					'@context'        => 'https://schema.org',
					'@type'           => 'BreadcrumbList',
					'itemListElement' => [],
				];
			}

			if ( $microdata ) {
				// Add the number of items and item list order schema.
				$breadcrumb .= sprintf( '<meta name="numberOfItems" content="%d" />', absint( $item_count ) );
				$breadcrumb .= '<meta name="itemListOrder" content="Ascending" />';
			}

			// Loop through the items and add them to the list.
			foreach ( $this->items as $item ) {

				// Iterate the item position.
				++ $item_position;

				// Check if the item is linked.
				preg_match( '/(<a.*?>)(.*?)(<\/a>)/i', $item, $matches );

				if ( $jsonld ) {
					$json['itemListElement'][] = [
						'@type'    => 'ListItem',
						'position' => $item_position,
						'name'     => ! empty( $matches ) ? $matches[2] : $item,
						'item'     => ! empty( $matches ) ? preg_replace( '/.*href="([^"]+)".*/', '$1', $matches[1] ) : '',
					];
				}

				// Wrap the item text with appropriate itemprop.
				$item = ! empty( $matches ) ? sprintf( '%s<span%s>%s</span>%s', $matches[1], $microdata ? ' itemprop="name"' : '', $matches[2], $matches[3] ) : sprintf( '<span>%s</span>', $item );

				// Add list item classes.
				$item_class = 'vite-breadcrumb__item';

				// Create list item attributes.
				$attributes = sprintf( '%sclass="%s"', $microdata ? 'itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ' : '', $item_class );
				$span_item  = $microdata ? '<span itemprop="item">%s</span>' : '<span>%s</span>';
				$meta       = sprintf( '<meta itemprop="position" content="%s" />', absint( $item_position ) );

				if ( 1 === $item_position && 1 < $item_count ) {
					$item_class .= ' vite-breadcrumbs__item--first';
					// Build the meta position HTML.
				} elseif ( $item_count === $item_position ) {
					$item_class .= ' vite-breadcrumbs__item--last';

					if ( is_404() || false === $this->args['link_current_item'] ) {
						$attributes = 'class="' . $item_class . '"';
						$span_item  = '%s';
						$meta       = '';
					}
				}

				// Wrap the item with its itemprop.
				$item = ! empty( $matches )
					? ( $microdata ? preg_replace( '/(<a.*?)([\'"])>/i', '$1$2 itemprop=$2item$2>', $item ) : $item )
					: sprintf( $span_item, $item );

				// Build the list item.
				$breadcrumb .= sprintf( '<%1$s %2$s>%3$s%4$s</%1$s>', tag_escape( $this->args['item_tag'] ), $attributes, $item, $microdata ? $meta : '' );

				if ( $item_position < $item_count ) {
					$breadcrumb .= sprintf( '<span class="vite-breadcrumb__separator">%s</span>', $this->args['separator'] );
				}
			}

			// Close the unordered list.
			$breadcrumb .= sprintf( '</%s>', tag_escape( $this->args['list_tag'] ) );

			// Wrap the breadcrumb trail.
			$breadcrumb = sprintf(
				'<%1$s role="navigation" aria-label="%2$s" class="vite-breadcrumb"%3$s>%4$s%5$s%6$s</%1$s>',
				tag_escape( $this->args['container'] ),
				esc_attr( $this->labels['aria_label'] ),
				$microdata ? ' itemprop="breadcrumb"' : '',
				$this->args['before'],
				$breadcrumb,
				$this->args['after']
			);
		}

		if ( ! empty( $json ) ) {
			$breadcrumb = $breadcrumb . wp_print_inline_script_tag( wp_json_encode( $json ), [ 'type' => 'application/ld+json' ] ) . "\n";
		}

		/**
		 * Filter: vite/breadcrumbs/trail/
		 *
		 * Filter the breadcrumb trail HTML.
		 *
		 * @since x.x.x
		 * @param string $breadcrumb The HTML of the breadcrumb trail.
		 * @param array  $args       An array of arguments.
		 */
		$breadcrumb = vite( 'core' )->filter( 'breadcrumbs/trail', $breadcrumb, $this->args );

		if ( false === $this->args['echo'] ) {
			return $breadcrumb;
		}

		echo wp_kses(
			$breadcrumb,
			[
				'nav'    => [
					'role'       => true,
					'aria-label' => true,
					'class'      => true,
					'itemprop'   => true,
				],
				'ul'     => [
					'class'     => true,
					'itemscope' => true,
					'itemtype'  => true,
				],
				'meta'   => [
					'name'     => true,
					'content'  => true,
					'itemprop' => true,
				],
				'li'     => [
					'itemprop'  => true,
					'itemscope' => true,
					'itemtype'  => true,
					'class'     => true,
				],
				'a'      => [
					'itemprop' => true,
					'rel'      => true,
					'class'    => true,
					'href'     => true,
				],
				'span'   => [
					'itemprop' => true,
					'content'  => true,
					'class'    => true,
				],
				'script' => [
					'type' => [
						'application/ld+json' => true,
					],
				],
			]
		);
	}

	/**
	 * Sets the labels property.  Parses the inputted labels array with the defaults.
	 *
	 * @return void
	 */
	private function set_labels() {
		$defaults = [
			'browse'              => esc_html__( 'Browse:', 'vite' ),
			'aria_label'          => esc_attr_x( 'Breadcrumbs', 'breadcrumbs aria label', 'vite' ),
			'home'                => esc_html__( 'Home', 'vite' ),
			'error_404'           => esc_html__( '404 Not Found', 'vite' ),
			'archives'            => esc_html__( 'Archives', 'vite' ),
			// Translators: %s is the search query.
			'search'              => esc_html__( 'Search results for: %s', 'vite' ),
			// Translators: %s is the page number.
			'paged'               => esc_html__( 'Page %s', 'vite' ),
			// Translators: %s is the page number.
			'paged_comments'      => esc_html__( 'Comment Page %s', 'vite' ),
			// Translators: Minute archive title. %s is the minute time format.
			'archive_minute'      => esc_html__( 'Minute %s', 'vite' ),
			// Translators: Weekly archive title. %s is the week date format.
			'archive_week'        => esc_html__( 'Week %s', 'vite' ),

			// "%s" is replaced with the translated date/time format.
			'archive_minute_hour' => '%s',
			'archive_hour'        => '%s',
			'archive_day'         => '%s',
			'archive_month'       => '%s',
			'archive_year'        => '%s',
		];

		$this->labels = vite( 'core' )->filter( 'breadcrumbs/trail/labels', wp_parse_args( $this->args['labels'], $defaults ) );
	}

	/**
	 * Sets the `$post_taxonomy` property.  This is an array of post types (key) and taxonomies (value).
	 * The taxonomy's terms are shown on the singular post view if set.
	 *
	 * @return void
	 */
	private function set_post_taxonomy() {
		$defaults = [];

		// If post permalink is set to `%postname%`, use the `category` taxonomy.
		if ( '%postname%' === trim( get_option( 'permalink_structure' ), '/' ) ) {
			$defaults['post'] = 'category';
		}

		/**
		 * Filter: vite/breadcrumbs/trail/post-taxonomy.
		 *
		 * Filter the post taxonomy array.
		 *
		 * @since x.x.x
		 * @param array $defaults The default post taxonomy array.
		 */
		$this->post_taxonomy = vite( 'core' )->filter( 'breadcrumb/trail/post-taxonomy', wp_parse_args( $this->args['post_taxonomy'], $defaults ) );
	}

	/**
	 * Runs through the various WordPress conditional tags to check the current page being viewed.  Once
	 * a condition is met, a specific method is launched to add items to the `$items` array.
	 *
	 * @return void
	 */
	private function add_items() {
		// If viewing the front page.
		if ( is_front_page() ) {
			$this->add_front_page_items();
		} else { // If not viewing the front page.

			// Add the network and site home links.
			$this->add_network_home_link();
			$this->add_site_home_link();

			// If viewing the home/blog page.
			if ( is_home() ) {
				$this->add_blog_items();
			} elseif ( is_singular() ) { // If not viewing the front page.
				$this->add_singular_items();
			} elseif ( is_archive() ) { // If viewing an archive page.

				if ( is_post_type_archive() ) {
					$this->add_post_type_archive_items();
				} elseif ( is_category() || is_tag() || is_tax() ) {
					$this->add_term_archive_items();
				} elseif ( is_author() ) {
					$this->add_user_archive_items();
				} elseif ( get_query_var( 'minute' ) && get_query_var( 'hour' ) ) {
					$this->add_minute_hour_archive_items();
				} elseif ( get_query_var( 'minute' ) ) {
					$this->add_minute_archive_items();
				} elseif ( get_query_var( 'hour' ) ) {
					$this->add_hour_archive_items();
				} elseif ( is_day() ) {
					$this->add_day_archive_items();
				} elseif ( get_query_var( 'w' ) ) {
					$this->add_week_archive_items();
				} elseif ( is_month() ) {
					$this->add_month_archive_items();
				} elseif ( is_year() ) {
					$this->add_year_archive_items();
				} else {
					$this->add_default_archive_items();
				}
			} elseif ( is_search() ) { // If viewing a search results page.
				$this->add_search_items();
			} elseif ( is_404() ) { // If viewing the 404 page.
				$this->add_404_items();
			}
		}

		// Add paged items if they exist.
		$this->add_paged_items();

		/**
		 * Filter: vite/breadcrumbs/trail/items.
		 *
		 * Filter the breadcrumb trail items.
		 *
		 * @since x.x.x
		 * @param array $items The breadcrumb trail items.
		 * @param array $args The breadcrumb trail arguments.
		 */
		$this->items = array_unique( vite( 'core' )->filter( 'breadcrumbs/trail/items', $this->items, $this->args ) );
	}

	/**
	 * Gets front items based on $wp_rewrite->front.
	 *
	 * @return void
	 */
	private function add_rewrite_front_items() {
		global $wp_rewrite;

		if ( $wp_rewrite->front ) {
			$this->add_path_parents( $wp_rewrite->front );
		}
	}

	/**
	 * Adds the page/paged number to the items array.
	 *
	 * @return void
	 */
	private function add_paged_items() {
		// If viewing a paged singular post.
		if ( is_singular() && 1 < get_query_var( 'page' ) && true === $this->args['show_title'] ) {
			$this->items[] = sprintf( $this->labels['paged'], number_format_i18n( absint( get_query_var( 'page' ) ) ) );
		} elseif ( is_singular() && get_option( 'page_comments' ) && 1 < get_query_var( 'cpage' ) ) { // If viewing a singular post with paged comments.
			$this->items[] = sprintf( $this->labels['paged_comments'], number_format_i18n( absint( get_query_var( 'cpage' ) ) ) );
		} elseif ( is_paged() && true === $this->args['show_title'] ) { // If viewing a paged archive-type page.
			$this->items[] = sprintf( $this->labels['paged'], number_format_i18n( absint( get_query_var( 'paged' ) ) ) );
		}
	}

	/**
	 * Adds the network (all sites) home page link to the items array.
	 *
	 * @return void
	 */
	private function add_network_home_link() {
		if ( is_multisite() && ! is_main_site() && true === $this->args['network'] ) {
			$this->items[] = sprintf( '<a href="%s" rel="home">%s</a>', esc_url( network_home_url() ), $this->labels['home'] );
		}
	}

	/**
	 * Adds the current site's home page link to the items array.
	 *
	 * @return void
	 */
	private function add_site_home_link() {
		$network = is_multisite() && ! is_main_site() && true === $this->args['network'];
		$label   = $network ? get_bloginfo( 'name' ) : $this->labels['home'];
		$rel     = $network ? '' : ' rel="home"';

		$this->items[] = sprintf( '<a href="%s" %s>%s</a>', esc_url( user_trailingslashit( home_url() ) ), $rel, $label );
	}

	/**
	 * Adds items for the front page to the items array.
	 *
	 * @return void
	 */
	private function add_front_page_items() {
		// Only show front items if the 'show_on_front' argument is set to 'true'.
		if ( true === $this->args['show_on_front'] || is_paged() || ( is_singular() && 1 < get_query_var( 'page' ) ) ) {

			// Add network home link.
			$this->add_network_home_link();

			// If on a paged view, add the site home link.
			if ( is_paged() ) {
				$this->add_site_home_link();
			} elseif ( true === $this->args['show_title'] ) { // If on the main front page, add the network home title.
				$this->items[] = is_multisite() && true === $this->args['network'] ? get_bloginfo( 'name' ) : $this->labels['home'];
			}
		}
	}

	/**
	 * Adds items for the posts page (i.e., is_home()) to the items array.
	 *
	 * @return void
	 */
	private function add_blog_items() {
		// Get the post ID and post.
		$post_id = get_queried_object_id();
		$post    = get_post( $post_id );

		// If the post has parents, add them to the trail.
		if ( 0 < $post->post_parent ) {
			$this->add_post_parents( $post->post_parent );
		}
		// Get the page title.
		$title = get_the_title( $post_id );

		// Add the posts page item.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), $title );
		} elseif ( $title && true === $this->args['show_title'] ) {
			$this->items[] = $title;
		}
	}

	/**
	 * Adds singular post items to the items array.
	 *
	 * @return void
	 */
	private function add_singular_items() {
		// Get the queried post.
		$post       = get_queried_object();
		$post_id    = get_queried_object_id();
		$post_title = single_post_title( '', false );

		// If the post has a parent, follow the parent trail.
		if ( 0 < $post->post_parent ) {
			$this->add_post_parents( $post->post_parent );
		} else { // If the post doesn't have a parent, get its hierarchy based off the post type.
			$this->add_post_hierarchy( $post_id );
		}

		// Display terms for specific post type taxonomy if requested.
		if ( ! empty( $this->post_taxonomy[ $post->post_type ] ) ) {
			$this->add_post_terms( $post_id, $this->post_taxonomy[ $post->post_type ] );
		}

		// End with the post title.
		if ( $post_title ) {

			if ( true === $this->args['link_current_item'] || ( 1 < get_query_var( 'page' ) || is_paged() ) || ( get_option( 'page_comments' ) && 1 < absint( get_query_var( 'cpage' ) ) ) ) {
				$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), $post_title );
			} elseif ( true === $this->args['show_title'] ) {
				$this->items[] = $post_title;
			}
		}
	}

	/**
	 * Adds the items to the trail items array for taxonomy term archives.
	 *
	 * @return void
	 * @global object $wp_rewrite
	 */
	private function add_term_archive_items() {
		global $wp_rewrite;

		// Get some taxonomy and term variables.
		$term           = get_queried_object();
		$taxonomy       = get_taxonomy( $term->taxonomy );
		$done_post_type = false;

		// If there are rewrite rules for the taxonomy.
		if ( false !== $taxonomy->rewrite ) {

			// If 'with_front' is true, dd $wp_rewrite->front to the trail.
			if ( array_key_exists( 'with_front', $taxonomy->rewrite ) && $taxonomy->rewrite['with_front'] && $wp_rewrite->front ) {
				$this->add_rewrite_front_items();
			}

			// Get parent pages by path if they exist.
			$this->add_path_parents( $taxonomy->rewrite['slug'] );

			// Add post type archive if its 'has_archive' matches the taxonomy rewrite 'slug'.
			if ( $taxonomy->rewrite['slug'] ) {

				$slug = trim( $taxonomy->rewrite['slug'], '/' );

				// Deals with the situation if the slug has a '/' between multiple
				// strings. For example, "movies/genres" where "movies" is the post
				// type archive.
				$matches = explode( '/', $slug );

				// If matches are found for the path.
				if ( isset( $matches ) ) {

					// Reverse the array of matches to search for posts in the proper order.
					$matches = array_reverse( $matches );

					// Loop through each of the path matches.
					foreach ( $matches as $match ) {

						// If a match is found.
						$slug = $match;

						// Get private post types that match the rewrite slug.
						$post_types = $this->get_post_types_by_slug( $match );

						if ( ! empty( $post_types ) ) {

							$post_type_object = $post_types[0];

							// Add support for a non-standard label of 'archive_title' (special use case).
							$label = ! empty( $post_type_object->labels->archive_title ) ? $post_type_object->labels->archive_title : $post_type_object->labels->name;

							/**
							 * Filter: vite/breadcrumbs/post-type/archive/title
							 *
							 * Filters the post type archive title.
							 *
							 * @since x.x.x
							 * @param string $label The post type archive title.
							 * @param string $post_type The post type name.
							 */
							$label = vite( 'core' )->filter( 'breadcrumbs/post-type/archive/title', $label, $post_type_object->name );

							// Add the post type archive link to the trail.
							$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type_object->name ) ), $label );

							$done_post_type = true;

							// Break out of the loop.
							break;
						}
					}
				}
			}
		}

		// If there's a single post type for the taxonomy, use it.
		if ( false === $done_post_type && 1 === count( $taxonomy->object_type ) && post_type_exists( $taxonomy->object_type[0] ) ) {

			// If the post type is 'post'.
			if ( 'post' === $taxonomy->object_type[0] ) {
				$post_id = get_option( 'page_for_posts' );

				if ( 'posts' !== get_option( 'show_on_front' ) && 0 < $post_id ) {
					$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), get_the_title( $post_id ) );
				}

				// If the post type is not 'post'.
			} else {
				$post_type_object = get_post_type_object( $taxonomy->object_type[0] );

				$label = ! empty( $post_type_object->labels->archive_title ) ? $post_type_object->labels->archive_title : $post_type_object->labels->name;

				/**
				 * Filter: vite/breadcrumbs/post-type/archive/title
				 *
				 * Filters the post type archive title.
				 *
				 * @since x.x.x
				 * @param string $label The post type archive title.
				 * @param string $post_type The post type name.
				 */
				$label = vite( 'core' )->core( 'breadcrumbs/post-type/archive/title', $label, $post_type_object->name );

				$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type_object->name ) ), $label );
			}
		}

		// If the taxonomy is hierarchical, list its parent terms.
		if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) {
			$this->add_term_parents( $term->parent, $term->taxonomy );
		}

		// Add the term name to the trail end.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, $term->taxonomy ) ), single_term_title( '', false ) );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = single_term_title( '', false );
		}
	}

	/**
	 * Adds the items to the trail items array for post type archives.
	 *
	 * @return void
	 */
	private function add_post_type_archive_items() {
		// Get the post type object.
		$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

		if ( false !== $post_type_object->rewrite ) {

			// If 'with_front' is true, add $wp_rewrite->front to the trail.
			if ( $post_type_object->rewrite['with_front'] ) {
				$this->add_rewrite_front_items();
			}

			// If there's a rewrite slug, check for parents.
			if ( ! empty( $post_type_object->rewrite['slug'] ) ) {
				$this->add_path_parents( $post_type_object->rewrite['slug'] );
			}
		}

		// Add the post type [plural] name to the trail end.
		if ( is_paged() || is_author() ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type_object->name ) ), post_type_archive_title( '', false ) );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = post_type_archive_title( '', false );
		}

		// If viewing a post type archive by author.
		if ( is_author() ) {
			$this->add_user_archive_items();
		}
	}

	/**
	 * Adds the items to the trail items array for user (author) archives.
	 *
	 * @return void
	 * @global object $wp_rewrite
	 */
	private function add_user_archive_items() {
		global $wp_rewrite;

		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Get the user ID.
		$user_id = get_query_var( 'author' );

		// If $author_base exists, check for parent pages.
		if ( ! empty( $wp_rewrite->author_base ) && ! is_post_type_archive() ) {
			$this->add_path_parents( $wp_rewrite->author_base );
		}

		// Add the author's display name to the trail end.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( $user_id ) ), get_the_author_meta( 'display_name', $user_id ) );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = get_the_author_meta( 'display_name', $user_id );
		}
	}

	/**
	 * Adds the items to the trail items array for minute + hour archives.
	 *
	 * @return void
	 */
	private function add_minute_hour_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Add the minute + hour item.
		if ( true === $this->args['show_title'] ) {
			$this->items[] = sprintf( $this->labels['archive_minute_hour'], get_the_time( esc_html_x( 'g:i a', 'minute and hour archives time format', 'vite' ) ) );
		}
	}

	/**
	 * Adds the items to the trail items array for minute archives.
	 *
	 * @return void
	 */
	private function add_minute_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Add the minute item.
		if ( true === $this->args['show_title'] ) {
			$this->items[] = sprintf( $this->labels['archive_minute'], get_the_time( esc_html_x( 'i', 'minute archives time format', 'vite' ) ) );
		}
	}

	/**
	 * Adds the items to the trail items array for hour archives.
	 *
	 * @return void
	 */
	private function add_hour_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Add the hour item.
		if ( true === $this->args['show_title'] ) {
			$this->items[] = sprintf( $this->labels['archive_hour'], get_the_time( esc_html_x( 'g a', 'hour archives time format', 'vite' ) ) );
		}
	}

	/**
	 * Adds the items to the trail items array for day archives.
	 *
	 * @return void
	 */
	private function add_day_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Get year, month, and day.
		$year  = sprintf( $this->labels['archive_year'], get_the_time( esc_html_x( 'Y', 'yearly archives date format', 'vite' ) ) );
		$month = sprintf( $this->labels['archive_month'], get_the_time( esc_html_x( 'F', 'monthly archives date format', 'vite' ) ) );
		$day   = sprintf( $this->labels['archive_day'], get_the_time( esc_html_x( 'j', 'daily archives date format', 'vite' ) ) );

		// Add the year and month items.
		$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );
		$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ), $month );

		// Add the day item.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ), $day );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = $day;
		}
	}

	/**
	 * Adds the items to the trail items array for week archives.
	 *
	 * @return void
	 */
	private function add_week_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Get the year and week.
		$year = sprintf( $this->labels['archive_year'], get_the_time( esc_html_x( 'Y', 'yearly archives date format', 'vite' ) ) );
		$week = sprintf( $this->labels['archive_week'], get_the_time( esc_html_x( 'W', 'weekly archives date format', 'vite' ) ) );

		// Add the year item.
		$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );

		// Add the week item.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = esc_url(
				get_archives_link(
					add_query_arg(
						[
							'm' => get_the_time( 'Y' ),
							'w' => get_the_time( 'W' ),
						],
						home_url()
					),
					$week,
					false
				)
			);
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = $week;
		}
	}

	/**
	 * Adds the items to the trail items array for month archives.
	 *
	 * @return void
	 */
	private function add_month_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Get the year and month.
		$year  = sprintf( $this->labels['archive_year'], get_the_time( esc_html_x( 'Y', 'yearly archives date format', 'vite' ) ) );
		$month = sprintf( $this->labels['archive_month'], get_the_time( esc_html_x( 'F', 'monthly archives date format', 'vite' ) ) );

		// Add the year item.
		$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );

		// Add the month item.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ), $month );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = $month;
		}
	}

	/**
	 * Adds the items to the trail items array for year archives.
	 *
	 * @return void
	 */
	private function add_year_archive_items() {
		// Add $wp_rewrite->front to the trail.
		$this->add_rewrite_front_items();

		// Get the year.
		$year = sprintf( $this->labels['archive_year'], get_the_time( esc_html_x( 'Y', 'yearly archives date format', 'vite' ) ) );

		// Add the year item.
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = $year;
		}
	}

	/**
	 * Adds the items to the trail items array for archives that don't have a more specific method
	 * defined in this class.
	 *
	 * @return void
	 */
	private function add_default_archive_items() {
		// If this is a date-/time-based archive, add $wp_rewrite->front to the trail.
		if ( is_date() || is_time() ) {
			$this->add_rewrite_front_items();
		}
		if ( true === $this->args['show_title'] ) {
			$this->items[] = $this->labels['archives'];
		}
	}

	/**
	 * Adds the items to the trail items array for search results.
	 *
	 * @return void
	 */
	private function add_search_items() {
		if ( is_paged() || ( true === $this->args['link_current_item'] ) ) {
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_search_link() ), sprintf( $this->labels['search'], get_search_query() ) );
		} elseif ( true === $this->args['show_title'] ) {
			$this->items[] = sprintf( $this->labels['search'], get_search_query() );
		}
	}

	/**
	 * Adds the items to the trail items array for 404 pages.
	 *
	 * @return void
	 */
	private function add_404_items() {
		if ( true === $this->args['show_title'] ) {
			$this->items[] = $this->labels['error_404'];
		}
	}

	/**
	 * Adds a specific post's parents to the items array.
	 *
	 * @param int $post_id post id.
	 *
	 * @return void
	 */
	private function add_post_parents( int $post_id ) {
		$parents = [];

		while ( $post_id ) {

			// Get the post by ID.
			$post = get_post( $post_id );

			// If we hit a page that's set as the front page, bail.
			if ( 'page' === $post->post_type && 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) === $post_id ) {
				break;
			}

			// Add the formatted post link to the array of parents.
			$parents[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), get_the_title( $post_id ) );

			// If there's no longer a post parent, break out of the loop.
			if ( 0 >= $post->post_parent ) {
				break;
			}

			// Change the post ID to the parent post to continue looping.
			$post_id = $post->post_parent;
		}

		// Get the post hierarchy based off the final parent post.
		$this->add_post_hierarchy( $post_id );

		// Display terms for specific post type taxonomy if requested.
		if ( ! empty( $this->post_taxonomy[ $post->post_type ] ) ) {
			$this->add_post_terms( $post_id, $this->post_taxonomy[ $post->post_type ] );
		}

		// Merge the parent items into the items array.
		$this->items = array_merge( $this->items, array_reverse( $parents ) );
	}

	/**
	 * Adds a specific post's hierarchy to the items array.  The hierarchy is determined by post type's
	 * rewrite arguments and whether it has an archive page.
	 *
	 * @param int $post_id post id.
	 *
	 * @return void
	 */
	private function add_post_hierarchy( int $post_id ) {
		// Get the post type.
		$post_type        = get_post_type( $post_id );
		$post_type_object = get_post_type_object( $post_type );

		// If this is the 'post' post type, get the rewrite front items and map the rewrite tags.
		if ( 'post' === $post_type ) {

			// Add $wp_rewrite->front to the trail.
			$this->add_rewrite_front_items();

			// Map the rewrite tags.
			$this->map_rewrite_tags( $post_id, get_option( 'permalink_structure' ) );
		} elseif ( false !== $post_type_object->rewrite ) { // If the post type has rewrite rules.

			// If 'with_front' is true, add $wp_rewrite->front to the trail.
			if ( $post_type_object->rewrite['with_front'] ) {
				$this->add_rewrite_front_items();
			}

			// If there's a path, check for parents.
			if ( ! empty( $post_type_object->rewrite['slug'] ) ) {
				$this->add_path_parents( $post_type_object->rewrite['slug'] );
			}
		}

		// If there's an archive page, add it to the trail.
		if ( $post_type_object->has_archive ) {

			// Add support for a non-standard label of 'archive_title' (special use case).
			$label = ! empty( $post_type_object->labels->archive_title ) ? $post_type_object->labels->archive_title : $post_type_object->labels->name;

			/**
			 * Filter: vite/breadcrumbs/post-type/archive/title.
			 *
			 * @since x.x.x
			 * @param string $label Post type archive title.
			 * @param string $post_type Post type name.
			 * @param object $post_type_object Post type object.
			 */
			$label = vite( 'core' )->filter( 'breadcrumbs/post-type/archive/title', $label, $post_type_object->name );

			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type ) ), $label );
		}

		// Map the rewrite tags if there's a `%` in the slug.
		if ( 'post' !== $post_type && ! empty( $post_type_object->rewrite['slug'] ) && false !== strpos( $post_type_object->rewrite['slug'], '%' ) ) {
			$this->map_rewrite_tags( $post_id, $post_type_object->rewrite['slug'] );
		}
	}

	/**
	 * Gets post types by slug.  This is needed because the get_post_types() function doesn't exactly
	 * match the 'has_archive' argument when it's set as a string instead of a boolean.
	 *
	 * @param int|string $slug The post type archive slug to search for.
	 *
	 * @return array $return post type.
	 */
	private function get_post_types_by_slug( $slug ): array {
		$return = [];

		$post_types = get_post_types( [], 'objects' );

		foreach ( $post_types as $type ) {

			if ( $slug === $type->has_archive || ( true === $type->has_archive && $slug === $type->rewrite['slug'] ) ) {
				$return[] = $type;
			}
		}

		return $return;
	}

	/**
	 * Adds a post's terms from a specific taxonomy to the items array.
	 *
	 * @param int    $post_id The ID of the post to get the terms for.
	 * @param string $taxonomy The taxonomy to get the terms from.
	 *
	 * @return void
	 */
	private function add_post_terms( int $post_id, string $taxonomy ) {
		// Get the post type.
		$post_type = get_post_type( $post_id );

		// Get the post categories.
		$terms = get_the_terms( $post_id, $taxonomy );

		// Check that categories were returned.
		if ( $terms && ! is_wp_error( $terms ) ) {

			// Sort the terms by ID and get the first category.
			if ( function_exists( 'wp_list_sort' ) ) {
				$terms = wp_list_sort( $terms, 'term_id' );
			} else {
				usort( $terms, '_usort_terms_by_ID' );
			}
			$term = get_term( $terms[0], $taxonomy );

			// If the category has a parent, add the hierarchy to the trail.
			if ( 0 < $term->parent ) {
				$this->add_term_parents( $term->parent, $taxonomy );
			}

			// Add the category archive link to the trail.
			$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, $taxonomy ) ), $term->name );
		}
	}

	/**
	 * Get parent posts by path.  Currently, this method only supports getting parents of the 'page'
	 * post type.  The goal of this function is to create a clear path back to home given what would
	 * normally be a "ghost" directory.  If any page matches the given path, it'll be added.
	 *
	 * @param string $path The path (slug) to search for posts by.
	 *
	 * @return void
	 */
	private function add_path_parents( string $path ) {
		// Trim '/' off $path in case we just got a simple '/' instead of a real path.
		$path = trim( $path, '/' );

		// If there's no path, return.
		if ( empty( $path ) ) {
			return;
		}

		// Get parent post by the path.
		$post = get_page_by_path( $path );

		if ( ! empty( $post ) ) {
			$this->add_post_parents( $post->ID );
		} elseif ( is_null( $post ) ) {

			// Separate post names into separate paths by '/'.
			$path = trim( $path, '/' );
			preg_match_all( '/\/.*?\z/', $path, $matches );

			// If matches are found for the path.
			if ( isset( $matches ) ) {

				// Reverse the array of matches to search for posts in the proper order.
				$matches = array_reverse( $matches );

				// Loop through each of the path matches.
				foreach ( $matches as $match ) {

					// If a match is found.
					if ( isset( $match[0] ) ) {

						// Get the parent post by the given path.
						$path = str_replace( $match[0], '', $path );
						$post = get_page_by_path( trim( $path, '/' ) );

						// If a parent post is found, set the $post_id and break out of the loop.
						if ( ! empty( $post ) && 0 < $post->ID ) {
							$this->add_post_parents( $post->ID );
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * Searches for term parents of hierarchical taxonomies.  This function is similar to the WordPress
	 * function get_category_parents() but handles any type of taxonomy.
	 *
	 * @param int    $term_id ID of the term to get the parents of.
	 * @param string $taxonomy Name of the taxonomy for the given term.
	 *
	 * @return void
	 */
	private function add_term_parents( int $term_id, string $taxonomy ) {
		// Set up some default arrays.
		$parents = [];

		// While there is a parent ID, add the parent term link to the $parents array.
		while ( $term_id ) {

			// Get the parent term.
			$term = get_term( $term_id, $taxonomy );

			// Add the formatted term link to the array of parent terms.
			$parents[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, $taxonomy ) ), $term->name );

			// Set the parent term's parent as the parent ID.
			$term_id = $term->parent;
		}

		// If we have parent terms, reverse the array to put them in the proper order for the trail.
		if ( ! empty( $parents ) ) {
			$this->items = array_merge( $this->items, array_reverse( $parents ) );
		}
	}

	/**
	 * Turns %tag% from permalink structures into usable links for the breadcrumb trail.  This feels kind of
	 * hackish for now because we're checking for specific %tag% examples and only doing it for the 'post'
	 * post type.  In the future, maybe it'll handle a wider variety of possibilities, especially for custom post
	 * types.
	 *
	 * @param int    $post_id ID of the post whose parents we want.
	 * @param string $path Path of a potential parent page.
	 */
	private function map_rewrite_tags( int $post_id, string $path ) {

		$post = get_post( $post_id );

		// Trim '/' from both sides of the $path.
		$path = trim( $path, '/' );

		// Split the $path into an array of strings.
		$matches = explode( '/', $path );

		// If matches are found for the path.
		if ( is_array( $matches ) ) {

			// Loop through each of the matches, adding each to the $trail array.
			foreach ( $matches as $match ) {

				// Trim any '/' from the $match.
				$tag = trim( $match, '/' );

				// If using the %year% tag, add a link to the yearly archive.
				if ( '%year%' === $tag ) {
					$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y', $post_id ) ) ), sprintf( $this->labels['archive_year'], get_the_time( esc_html_x( 'Y', 'yearly archives date format', 'vite' ) ) ) );
				} elseif ( '%monthnum%' === $tag ) { // If using the %monthnum% tag, add a link to the monthly archive.
					$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_month_link( get_the_time( 'Y', $post_id ), get_the_time( 'm', $post_id ) ) ), sprintf( $this->labels['archive_month'], get_the_time( esc_html_x( 'F', 'monthly archives date format', 'vite' ) ) ) );
				} elseif ( '%day%' === $tag ) { // If using the %day% tag, add a link to the daily archive.
					$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_day_link( get_the_time( 'Y', $post_id ), get_the_time( 'm', $post_id ), get_the_time( 'd', $post_id ) ) ), sprintf( $this->labels['archive_day'], get_the_time( esc_html_x( 'j', 'daily archives date format', 'vite' ) ) ) );
				} elseif ( '%author%' === $tag ) { // If using the %author% tag, add a link to the post author archive.
					$this->items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( $post->post_author ) ), get_the_author_meta( 'display_name', $post->post_author ) );
				} elseif ( taxonomy_exists( trim( $tag, '%' ) ) ) { // If using the %category% tag, add a link to the first category archive to match permalinks.

					// Force override terms in this post type.
					$this->post_taxonomy[ $post->post_type ] = false;

					// Add the post categories.
					$this->add_post_terms( $post_id, trim( $tag, '%' ) );
				}
			}
		}
	}
}
