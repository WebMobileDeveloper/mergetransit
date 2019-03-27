<?php
	/**
	 * The socials template.
	 *
	 * [Last update: 2018-08-21]
	 */


	//Get the socials module post id.
	$socials_module_post_id = get_field (
	
		'socials-module-post-id'
		
	);


	//Whether the socials module post id is not empty.
	if ( $socials_module_post_id ) {


		//Display the socials module.
		the_module (

			$socials_module_post_id

		);


	}


?>