<?php
	/**
	 * The module 25 template.
	 *
	 * [Last update: 2018-12-31]
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


	//Get the backgrounds.
	$backgrounds = get_field (
	
		'backgrounds'
	
	);


	//Whether the backgrounds are not empty.
	if ( $backgrounds ) {


		//Load the backgrounds.
		foreach ( $backgrounds as $background ) {


			//Get the body style attribute.
			$style = get_style (

				array (
				
					'background-image' => $background[ 'image-post-id' ],

					'background-color' => $background[ 'color' ]
				
				)
				
			);


			//Display the module open tag.
			echo '<div class="module-25 module"' . $hash . $style . '></div>';


		}
		
		
	}


?>