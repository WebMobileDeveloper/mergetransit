<?php
	/**
	 * The module template.
	 */


	$text2 = get_field (

		'text-2'

	);


	if ( $text2 ) {


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
		echo '<div class="module-28 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container flex middle">';


				echo '<div class="texts">';


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


					echo '<p class="text-2 h1">';
					

						echo merge_connectives (

							$text2

						);


					echo '</p>';


					$text3 = get_field (

						'text-3'

					);


					if ( $text3 ) {


						echo '<div class="text-3 content">';
						

							echo merge_connectives (

								$text3,

								true

							);


						echo '</div>';


					}


				echo '</div>';


				$image_post_id = get_field (

					'image-post-id'

				);


				if ( $image_post_id ) {


					echo '<div class="images">';
					

						the_image (

							$image_post_id

						);


					echo '</div>';


				}

		
		
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';	


	}


?>