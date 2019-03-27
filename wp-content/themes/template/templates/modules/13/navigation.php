<?php
	/**
	 * The navigation template.
	 *
	 * [Last update: 2018-08-21]
	 */


	//Get the navigation module post id.
	$navigation_module_post_id = get_field (
	
		'navigation-module-post-id'
		
	);


	//Whether the navigation module post id is not empty.
	if ( $navigation_module_post_id ) {


		//Display the navigation module.
		the_module (

			$navigation_module_post_id

		);


	}


?>