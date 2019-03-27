<?php
	/**
	 * The module template.
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
	echo '<div class="module-31 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		//Display the header.
		get_template_part (

			'templates/modules/1'

		);


		//Display the container open tag.
		echo '<div class="container">';


			$image_post_id = get_field (

				'image-post-id'

			);


			if ( $image_post_id ) {


				the_image (

					$image_post_id

				);


			}


			$text1 = get_field (

				'text-1'

			);


			if ( $text1 ) {


				echo '<p class="text-1">';
				

					echo merge_connectives (

						$text1

					);


				echo '</p>';


			}


			$text2 = get_field (

				'text-2'

			);


			if ( $text2 ) {


				echo '<div class="text-2 content">';
				

					echo merge_connectives (

						$text2,

						true

					);


				echo '</div>';


			}


			$text3 = get_field (

				'text-3'

			);


			if ( $text3 ) {


				echo '<p class="text-3">';
				

					echo merge_connectives (

						$text3

					);


				echo '</p>';


			}


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
	
	
		//Display the container close tag.
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();
	
	
	//Display the module close tag.
	echo '</div>';


?>