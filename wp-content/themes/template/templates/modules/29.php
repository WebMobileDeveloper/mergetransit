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
	echo '<div class="module-29 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		//Display the header.
		get_template_part (

			'templates/modules/1'

		);


		//Display the container open tag.
		echo '<div class="container flex top nowrap">';


			echo '<div class="texts">';


				$text1 = get_field (

					'text-1'

				);


				if ( $text1 ) {


					echo '<p class="text-1 h1">';
					

						echo merge_connectives (

							$text1

						);


					echo '</p>';


				}



				$buttons = get_field (

					'buttons'

				);


				if ( $buttons ) {


					echo '<div class="buttons flex middle nowrap">';


						foreach ( $buttons as $button ) {


							$image = get_image (

								$button[ 'image-post-id' ]

							);


							$a = new A (

								$image,

								$button[ 'url' ]

							);


							$a->set_target( 

								'_blank'

							);


							echo $a->display();


						}


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


?>