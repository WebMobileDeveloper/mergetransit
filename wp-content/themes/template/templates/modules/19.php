<?php
	/**
	 * The module 19 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the post types.
	$post_types = get_field (

		'post-types'

	);


	//Get the order by.
	$order_by = get_field (

		'order-by'

	);


	//Get the order.
	$order = get_field (

		'order'

	);


	//Get the posts ids.
	$posts_ids = get_field (

		'posts'

	);


	//Get the offset.
	$offset = intval (

		get_field (

			'offset'

		)

	);


	//Get the number posts.
	$numberposts = intval (

		get_field (

			'numberposts'

		)

	);


	//Set up the posts per page number.
	$posts_per_page = $numberposts;


	//Whether the number posts are empty.
	if ( !$numberposts ) {


		//Whether the pagination post id is defined.
		if ( defined( 'PAGINATION_POST_ID' ) ) {


			//Get the posts per page number from pagination post.
			$posts_per_page = intval (

				get_field (

					'posts-per-page',

					PAGINATION_POST_ID

				)

			);


		}


	}


	//Get the global posts.
	global $posts;


	//Whether the global posts are not empty.
	if ( $posts ) {


		//Load the global posts.
		foreach ( $posts as $global_post ) {


			//Add the global post to the restricted posts.
			$post__not_in[] = $global_post->ID;


		}


	}


	//Get the paged page naumber.
	$paged = get_paged();


	//The posts arguments.
	$args = array (

		'paged' => $paged,

		'post_type' => $post_types ? $post_types : 'any',

		'orderby' => $order_by ? $order_by : 'date',
		
		'order' => $order ? $order : 'DESC',

		'post__in' => $posts_ids,

		'post__not_in' => $post__not_in,

		'offset' => $offset + ( $paged - 1 ) * $posts_per_page,

		'posts_per_page' => $posts_per_page

	);


	//Get the terms ids.
	$terms_ids = get_field (

		'terms'

	);


	//Whether the terms ids are not empty.
	if ( $terms_ids ) {


		//Load the terms ids.
		foreach ( $terms_ids as $key => $term_id ) {


			//Get the term.
			$term = get_term (

				$term_id

			);


			//Whether the term is not empty.
			if ( $term ) {


				//Set up the taxonomy attribute.
				$args[ 'tax_query' ][ $key ][ 'taxonomy' ] = $term->taxonomy;


				//Set up the term id attribute.
				$args[ 'tax_query' ][ $key ][ 'terms' ] = $term->term_id;


			}


		}


	}


	//Get the no posts text.
	$no_posts_text = get_field (

		'no-posts'

	);


	//Get the posts.
	$articles = new WP_Query (

		$args

	);


	//Whether the posts or no posts text are not empty.
	if ( $articles->have_posts() || $no_posts_text ) {


		//Set up the module default hash attribute.
		$hash = '';


		//Get the module identifier.
		$identifier = get_field (

			'module-identifier'

		);


		//Whether the module identifier is not empty.
		if ( $identifier ) {


			//Set up the module hash attribute.
			$hash = ' data-hash="' . esc_attr( $identifier ) . '"';


		}


		//Get the module style attribute.
		$style = get_style (

			array (
			
				'background-image' => get_field (

					'background-image-url'

				),
			
				'background-color' => get_field (

					'background-color'

				)
			
			)
			
		);


		//Display the module open tag.
		echo '<div class="module-19 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Whether the posts are not empty.
				if ( $articles->have_posts() ) {
		
		
					//Display the cards open tag.
					echo '<div class="' . esc_attr( get_post_type( $articles->posts[ 0 ] ) . '-cards ' ) . 'cards flex stretch center">';


						//Load the posts.
						foreach ( $articles->posts as $article ) {


							//Display the post card.
							the_post_card (

								$article->ID

							);


						}


					//Display the cards close tag.
					echo '</div>';


					//Whether the number posts are not defined.
					if ( !$numberposts ) {


						//Display the pagination.
						the_pagination (

							$articles

						);


					}


				//The posts are empty.
				} else {


					//Whether the no posts text is not empty.
					if ( $no_posts_text ) {


						//Display the no posts text open tag.
						echo '<div class="no-posts content">';


							//Display the no posts text.
							echo merge_connectives (

								$no_posts_text

							);


						//Display the no posts text close tag.
						echo '</div>';


					}


				}
		
		
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';


	}


?>