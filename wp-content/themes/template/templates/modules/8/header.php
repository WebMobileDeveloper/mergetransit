<?php
	/**
	 * The header template.
	 *
	 * [Last update: 2018-06-13]
	 */


	//Display the header open tag.
	echo '<header class="post-header">';


		//Load the header parts templates.
		get_template_parts (
		
			array (

				'templates/modules/8/header/title'
				
			)

		);

		
	//Display the header close tag.
	echo '</header>';


?>