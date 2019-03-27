<?php
	/**
	 * The module 16 template.
	 *
	 * [Last update: 2019-01-11]
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
	echo '<div class="module-16 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		//Display the container open tag.
		echo '<div class="container flex space middle">';


			//Display the left block open tag.
			echo '<div class="block-left flex middle">';


				//Get the image post id.
				$image_post_id = get_field (
				
					'image-post-id'
					
				);


				//Whether the image post id is not empty.
				if ( $image_post_id ) {


					//Display the image open tag.
					echo '<figure class="thumbnail">';


						//Display the image.
						the_image (

							$image_post_id

						);
					
					
					//Display the image close tag.
					echo '</figure>';


				}


				//Get the copyright.
				$copyright = get_field (
				
					'copyright'
					
				);
				
				
				//Whether the copyright is not empty.
				if ( $copyright ) {


					//Display the copyright open tag.
					echo '<p class="copyright">';
					
					
						//Display the copyright.
						echo merge_connectives (

							$copyright

						);
					
					
					//Display the copyright close tag.
					echo '</p>';
				
				
				}


			//Display the left block close tag.
			echo '</div>';


			//Display the right block open tag.
			echo '<div class="block-right flex middle right">';


				//Get the navigation module post id.
				$navigation_module_post_id = get_field (
				
					'navigation-module-post-id'
					
				);


				//Whether the navigation module post id is not empty.
				if ( $navigation_module_post_id ) {


					//Display the navigation open tag.
					echo '<div class="navigation">';


						//Display the navigation module.
						the_module (

							$navigation_module_post_id

						);


					//Display the navigation close tag.
					echo '</div>';


				}


				//Get the author.
				$author = get_field (
				
					'created-by'
					
				);
				
				
				//Whether the author is not empty.
				if ( $author ) {


					//Display the author open tag.
					echo '<p class="created-by">';
					
					
						//Display the author.
						echo merge_connectives (

							$author

						);
					
					
					//Display the author close tag.
					echo '</p>';
				
				
				}


			//Display the right block close tag.
			echo '</div>';
	
	
		//Display the container close tag.
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();
	
	
	//Display the module close tag.
	echo '</div>';


?>