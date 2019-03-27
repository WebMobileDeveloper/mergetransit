<?php
	/**
	 * The module 1 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the title.
	$title = get_field (

		'title'

	);


	//Get the subtitle.
	$subtitle = get_field (

		'subtitle'

	);


	//Get the description.
	$description = get_field (

		'description'

	);


	//Whether the title or subtitle or description are not empty.
	if ( $title || $subtitle || $description ) {


		//Set up the module default hash attribute.
		$hash = '';


		//Set up the module default style attribute.
		$style = '';


		//Whether the module is a first module.
		if ( has_term( 'module-1', 'module-template' ) ) {


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


		}


		//Display the module open tag.
		echo '<div class="module-1 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the container open tag.
			echo '<div class="container">';


				//Whether the title is not empty.
				if ( $title ) {


					//Display the title open tag.
					echo '<p class="title h1">';


						//Display the title.
						echo merge_connectives (

							$title

						);


					//Display the title close tag.
					echo '</p>';


				}


				//Whether the subtitle is not empty.
				if ( $subtitle ) {


					//Display the subtitle open tag.
					echo '<p class="subtitle">';


						//Display the subtitle.
						echo merge_connectives (

							$subtitle

						);


					//Display the subtitle close tag.
					echo '</p>';


				}


				//Whether the description is not empty.
				if ( $description ) {


					//Display the description open tag.
					echo '<div class="description content">';


						//Display the description.
						echo merge_connectives (

							$description,

							true

						);


					//Display the description close tag.
					echo '</div>';


				}


			//Display the container close tag.
			echo '</div>';


			//Whether the module is a first module.
			if ( has_term( 'module-1', 'module-template' ) ) {


				//Display the edit module post link.
				the_edit_post_link();


			}


		//Display the module close tag.
		echo '</div>';


	}


?>