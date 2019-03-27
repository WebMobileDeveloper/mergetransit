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
	echo '<div class="module-35 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		//Display the header.
		get_template_part (

			'templates/modules/1'

		);


		//Display the container open tag.
		echo '<div class="container">';


			$blocks = get_field (

				'blocks'

			);


			if ( $blocks ) {


				echo '<div class="blocks flex stretch space nowrap">';


					foreach ( $blocks as $block ) {


						echo '<div class="block">';


							if ( $block[ 'text-1' ] ) {


								echo '<p class="text-1 h1">';
								

									echo merge_connectives (

										$block[ 'text-1' ]

									);


								echo '</p>';


							}


							if ( $block[ 'text-2' ] ) {


								echo '<p class="text-2 h1">';
								

									echo merge_connectives (

										$block[ 'text-2' ]

									);


								echo '</p>';


							}


							if ( $block[ 'text-3' ] ) {


								echo '<hr />';


								echo '<div class="text-3 content">';
								

									echo merge_connectives (

										$block[ 'text-3' ],

										true

									);


								echo '</div>';


							}


						echo '</div>';


					}


				echo '</div>';


			}
	
	
		//Display the container close tag.
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();
	
	
	//Display the module close tag.
	echo '</div>';


?>