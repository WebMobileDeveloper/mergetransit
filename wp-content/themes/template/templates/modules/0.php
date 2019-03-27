<?php
	/**
	 * The module 0 template.
	 *
	 * [Last update: 2019-01-28]
	 */


	//Get the modules.
	$modules = get_field (

		'modules'

	);


	//Whether the modules are not empty.
	if ( $modules ) {


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
		echo '<div class="module-0 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Load the modules.
			foreach ( $modules as $module ) {


				//Display the module.
				the_module (

					$module[ 'post-id' ]

				);


			}


			//Display the edit module post link.
			the_edit_post_link();


		//Display the module close tag.
		echo '</div>';


	}


?>