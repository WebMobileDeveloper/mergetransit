<?php
	/**
	 * The module 4 template.
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
	echo '<div class="module-4 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		//Display the header.
		get_template_part (

			'templates/modules/1'

		);


		//Display the container open tag.
		echo '<div class="container">';


			//Get the global posts.
			global $posts;


			//Whether the global posts are not empty.
			if ( $posts ) {
	
	
				//Display the cards open tag.
				echo '<div class="' . esc_attr( get_post_type( $posts[ 0 ] ) . '-cards ' ) . 'cards flex stretch center">';


					//Load the global posts.
					foreach ( $posts as $global_post ) {


						//Display the post card.
						the_post_card (

							$global_post->ID

						);


					}


				//Display the cards close tag.
				echo '</div>';

				
				//Display the pagination.
				the_pagination();


			//The the posts are empty.
			} else {


				//Get the archive no posts text.
				$no_posts_text = get_archive_no_posts_text();


				//Whether the no posts text is not empty.
				if ( $no_posts_text ) {


					//Display the no posts open tag.
					echo '<div class="no-posts content">';
					
					
						//Display the no posts text.
						echo merge_connectives (
						
							$no_posts_text,
							
							true
							
						);
					
					
					//Display the no posts close tag.
					echo '</div>';
					
					
				}


			}


		//Display the container cloase tag.
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();


	//Display the module cloase tag.
	echo '</div>';


?>