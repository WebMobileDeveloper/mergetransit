<?php
	/**
	 * The module 18 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the global posts.
	global $posts;


	//Whether the global posts are not empty.
	if ( $posts ) {


		//Get the taxonomy.
		$taxonomy = get_field (

			'taxonomy'

		);


		//Whether the taxonomy is not empty.
		if ( $taxonomy ) {


			//Get the terms.
			$terms = get_the_terms (

				$posts[ 0 ]->ID,

				$taxonomy

			);

			
			//Whether the terms are not empty.
			if ( $terms ) {


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
				echo '<div class="module-18 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


					//Display the header.
					get_template_part (

						'templates/modules/1'

					);


					//Display the container open tag.
					echo '<div class="container">';


						//Display the terms open tag.
						echo '<div class="terms flex middle">';


							//Get the label.
							$label = get_field (

								'label'

							);


							//Whether the label is not empty.
							if ( $label ) {


								//Display the label open tag.
								echo '<p class="label">';


									//Display the label.
									echo merge_connectives (
									
										$label
										
									);
								

								//Display the label close tag.
								echo '</p>';


							}
							
							
							//Load the terms.
							foreach ( $terms AS $term ) {


								//Display the term open tag.
								echo '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '" class="term btn black small">';


									//Display the term name.
									echo merge_connectives (
									
										$term->name
										
									);
									
									
								//Display the term close tag.
								echo '</a>';
								
								
							}
						
						
						//Display the terms close tag.
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
	
	
?>