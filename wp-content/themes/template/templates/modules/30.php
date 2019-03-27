<?php
	/**
	 * The module 30 template.
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
	echo '<div class="module-30 module-8 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


		//Display the container open tag.
		echo '<div class="container">';


			//Display the article open tag.
			echo '<article class="post-article">';


				//Set up the module post id.
				$module_post_id = get_the_id();


				//Restore the global posts query.
				wp_reset_query();
				
				
				//Load the article parts templates.
				get_template_parts (
				
					array (
					
						'templates/modules/8/header',
						
						'templates/modules/8/thumbnail',
						
						'templates/modules/8/content'
						
					)
					
				);


				//Set up the module post as a global post data.
				setup_postdata (

					$GLOBALS[ 'post' ] =& $module_post_id
					
				);

			
			//Display the article close tag.
			echo '</article>';
	
	
		//Display the container close tag.
		echo '</div>';


		//Display the edit module post link.
		the_edit_post_link();
	
	
	//Display the module close tag.
	echo '</div>';


?>