<?php
	/**
	 * The module 3 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the global posts.
	global $posts;


	//Whether the global posts are not empty.
	if ( $posts ) {


		//Load the global posts.
		foreach ( $posts as $global_post ) {


			//Get the taxonomy.
			$taxonomy = get_field (

				'taxonomy'

			);


			//Whether the taxonomy is not empty.
			if ( $taxonomy ) {


				//Get the posts number.
				$posts_number = intval (

					get_field (

						'posts-number'

					)

				);


				//Whether the posts number is not empty.
				if ( $posts_number ) {


					//Get the related posts.
					$related_posts = get_related_posts (

						$taxonomy,
						
						$posts_number,

						$global_post->ID
						
					);


					//Whether the related posts are not empty.
					if ( $related_posts ) {


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
						echo '<div class="module-3 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


							//Display the header.
							get_template_part (

								'templates/modules/1'

							);


							//Display the container open tag.
							echo '<div class="container">';
					
					
								//Display the cards open tag.
								echo '<div class="' . esc_attr( get_post_type( $related_posts[ 0 ] ) . '-cards ' ) . 'cards flex stretch center">';

							
									//Load the related posts.
									foreach ( $related_posts AS $related_post ) {

								
										//Display the post card.
										the_post_card (
											
											$related_post->ID
										
										);
										
										
									}
									
									
								//Display the cards close tag.
								echo '</div>';


							//Display the container close tag.
							echo '</div>';


							//Display the edit module post link.
							the_edit_post_link();


						//Display the module close tag.
						echo '</div>';
					

					}


				}


			}


		}


	}


?>