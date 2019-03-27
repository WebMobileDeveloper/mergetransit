<?php
	/**
	 * The module 24 template.
	 *
	 * [Last update: 2018-12-13]
	 */


	//Whether the cookies session is not defined.
	if ( !isset( $_SESSION[ 'cookies'] ) ) {


		//Whether the cookies cookie is not defined.
		if ( !isset( $_COOKIE[ 'cookies'] ) ) {


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
			echo '<div class="module-24 module"' . $style . '>';


				//Display the text open tag.
				echo '<div class="text">';


					//Get the title.
					$title = get_field (
					
						'title'
						
					);
					
					
					//Whether the title is not empty.
					if ( $title ) {
						
						
						//Display the title open tag.
						echo '<p class="title">';
						
						
							//Display the title.
							echo merge_connectives (
							
								$title
								
							);
							
						
						//Display the title close tag.
						echo '</p>';
						
						
					}


					//Get the description.
					$description = get_field (
					
						'description'
						
					);
					
					
					//Whether the description is not empty.
					if ( $description ) {


						//Display the description open tag.
						echo '<div class="description content">';


							//Display the content.
							echo merge_connectives (
							
								$description,
								
								true
								
							);
					
					
						//Display the description close tag.
						echo '</div>';
						
						
					}
					
				
				//Display the text close tag.
				echo '</div>';


				//Get the close icon post id.
				$close_icon_post_id = get_field (
				
					'close-icon-post-id'
					
				);


				//Whether the close icon post id is not empty.
				if ( $close_icon_post_id ) {
				
				
					//Display the close icon open tag.
					echo '<span class="close-icon js-slide js-load" data-slideDuration="500" data-slide=".module-24" data-load="' . esc_url( get_template_directory_uri() .'/templates/modules/24/set.php' ) . '" data-target=".module-24" data-relation="after">';
				
				
						//Display the close icon.
						the_icon (
						
							$close_icon_post_id
							
						);
						
						
					//Display the close icon close tag.
					echo '</span>';
					
					
				}


				//Get the close text.
				$text = get_field (
				
					'close-text'
					
				);
				
				
				//Whether the close text is not empty.
				if ( $text ) {
					
					
					//Display the close text open tag.
					echo '<div class="close-text js-slide js-load" data-slideDuration="500" data-slide=".module-24" data-load="' . esc_url( get_template_directory_uri() .'/templates/modules/24/set.php' ) . '" data-target=".module-24" data-relation="after">';
					
					
						//Display the close text.
						echo merge_connectives (
						
							$text,
							
							true
							
						);
						
					
					//Display the close text close tag.
					echo '</div>';
					
					
				}


				//Get the background icon post id.
				$background_icon_post_id = get_field (
				
					'background-icon-post-id'
					
				);


				//Whether the background icon post id is not empty.
				if ( $background_icon_post_id ) {
				
				
					//Display the background icon open tag.
					echo '<span class="background-icon">';
				
				
						//Display the background icon.
						the_icon (
						
							$background_icon_post_id
							
						);
						
						
					//Display the background icon close tag.
					echo '</span>';
					
					
				}


				//Display the edit module post link.
				the_edit_post_link();


			//Display the module close tag.
			echo '</div>';


		}


	}


?>