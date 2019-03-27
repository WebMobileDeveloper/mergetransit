<?php
	/**
	 * The module 20 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Set up the module post id.
	$module_post_id = get_the_id();


	//Restore the global posts query.
	wp_reset_query();


	//Get the breadcrumbs.
	$breadcrumbs = get_breadcrumbs (

		$module_post_id

	);


	//Set up the module post as a global post data.
	setup_postdata (

		$GLOBALS[ 'post' ] =& $module_post_id
		
	);


	//Whether the breadcrumbs are not empty.
	if ( $breadcrumbs ) {


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
		echo '<div class="module-20 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Display the breadcrumbs.
				echo $breadcrumbs;
		
		
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';


	}


?>