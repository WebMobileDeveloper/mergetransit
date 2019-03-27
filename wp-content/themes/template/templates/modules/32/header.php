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

				'templates/modules/32/header/title',

				'templates/cards/article/header/terms'
				
			)

		);

		
	//Display the header close tag.
	echo '</header>';


?>