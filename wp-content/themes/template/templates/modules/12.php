<?php
	/**
	 * The module 12 template.
	 *
	 * [Last update: 2018-09-14]
	 */


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
	echo '<div class="module-12 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		echo '<div class="module-top">';


			echo '<div class="container flex top space nowrap">';


				echo '<div class="part-left">';


					echo '<div class="texts">';


						//Get the image post id.
						$image_post_id = get_field (
						
							'image-post-id'
							
						);


						//Whether the image post id is not empty.
						if ( $image_post_id ) {


							//Display the image.
							the_image (

								$image_post_id

							);


						}


						$text1 = get_field (
						
							'text-1'
							
						);


						if ( $text1 ) {


							echo '<div class="text-1 content">';


								echo merge_connectives (

									$text1,

									true

								);


							echo '</div>';


						}


					echo '</div>';


				echo '</div>';


				echo '<div class="part-right flex top right nowrap">';


					$contacts = get_field (
					
						'contacts'
						
					);


					if ( $contacts ) {


						echo '<div class="contacts">';


							foreach ( $contacts as $contact ) {


								echo '<div class="contact">';


									echo '<p class="title">';


										echo merge_connectives (

											$contact[ 'title' ]

										);


									echo '</p>';


									echo '<div class="text content">';


										echo merge_connectives (

											$contact[ 'text' ],

											true

										);


									echo '</div>';


								echo '</div>';


							}


						echo '</div>';


					}


					//Get the navigations modules posts ids.
					$navigations_modules_posts_ids = get_field (
					
						'navigations-modules-posts-ids'
						
					);


					//Whether the navigations modules posts ids are not empty.
					if ( $navigations_modules_posts_ids ) {


						//Display the navigations open tag.
						echo '<div class="navigations">';


							//Load the navigations modules posts ids.
							foreach ( $navigations_modules_posts_ids as $navigation_module_post_id ) {


								//Display the navigation open tag.
								echo '<div class="navigation">';


									//Display the navigation module.
									the_module (

										$navigation_module_post_id

									);


								//Display the navigation close tag.
								echo '</div>';


							}


						//Display the navigations close tag.
						echo '</div>';


					}
				

				echo '</div>';


			echo '</div>';


		echo '</div>';


		echo '<div class="module-bottom">';


			echo '<div class="container flex space middle nowrap">';


				//Get the socials module post id.
				$socials_module_post_id = get_field (
				
					'socials-module-post-id'
					
				);


				//Whether the socials module post id is not empty.
				if ( $socials_module_post_id ) {


					//Display the socials module.
					the_module (

						$socials_module_post_id

					);


				}


				//Get the caption.
				$caption = get_field (
				
					'caption'
					
				);
				
				
				//Whether the caption is not empty.
				if ( $caption ) {


					//Display the caption open tag.
					echo '<p class="caption">';
					
					
						//Display the caption.
						echo merge_connectives (

							$caption

						);
					
					
					//Display the caption close tag.
					echo '</p>';
				
				
				}
			

			echo '</div>';
	
	
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();
	
	
	//Display the module close tag.
	echo '</div>';


?>