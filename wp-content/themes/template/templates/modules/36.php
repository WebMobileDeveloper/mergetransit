<?php
	/**
	 * The module 36 template.
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
	echo '<div class="module-36 module"' . $hash . $style . '>';


		//Display the container open tag.
		echo '<div class="container flex middle space nowrap">';


			//Display the left block open tag.
			echo '<div class="block-left flex middle left">';


				//Load the block left templates parts.
				get_template_parts (

					array (

						'templates/modules/36/logotypes'

					)

				);


			//Display the left block close tag.
			echo '</div>';


			//Display the right block open tag.
			echo '<div class="block-right flex middle right">';


				//Load the block left templates parts.
				get_template_parts (

					array (

						'templates/modules/13/navigation',

						'templates/modules/13/contacts',

						'templates/modules/36/buttons',

						'templates/modules/13/socials'

					)

				);


			//Display the right block close tag.
			echo '</div>';
	
	
		//Display the container close tag.
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();
	
	
	//Display the module close tag.
	echo '</div>';


?>