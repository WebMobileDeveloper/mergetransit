<?php
	/**
	 * The module 21 template.
	 *
	 * [Last update: 2018-08-21]
	 */


	//Get the margin.
	$margin = absint (

		get_field (

			'margin'

		)

	);


	//Whether the margin is not empty.
	if ( $margin ) {


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
		echo '<div class="module-21 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the edit module post link.
			the_edit_post_link();


			//Get the module style attribute.
			$style = get_style (

				array (
				
					'height' => $margin . 'px'
				
				)
				
			);


			//Display the margin.
			echo '<hr' . $style . '/>';


		//Display the module close tag.
		echo '</div>';


	}


?>