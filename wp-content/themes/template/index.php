<?php
	/**
	 * The default template.
	 *
	 * [Last update: 2019-02-28]
	 */


	//Display the html open tag.
	echo '<!DOCTYPE html><html lang="' . esc_attr( substr( get_bloginfo( 'language' ), 0, 2 ) ) . '">';


		//Display the head open tag.
		echo '<head>';


			//Fire the head action.
			wp_head();
			
			
		//Display the head close tag.
		echo '</head>';
		
		
		//Display the body open tag.
		echo '<body' . get_the_body_class_attribute() . ' data-scrollOffset=".module-13, .module-36">';


			//The header hooks.
			do_action (

				'get_header'

			);


			//Whether the OnePage is enabled.
			if ( is_onepage() ) {


				//Display the OnePage structure.
				the_onepage_structure();


			//The OnePage is disabled.
			} else {


				//Display the structure.
				the_structure();


			}

	 
			//The footer hooks.
			wp_footer();
				
				
		//Display the body close tag.
		echo '</body>';


	//Display the html close tag.
	echo '</html>';

	
	//Load the theme configuration object.
	global $configuration;


	//Display the HTML compressed files.
	$configuration->compression->display();


?>