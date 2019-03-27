<?php
	/**
	 * The header template.
	 *
	 * [Last update: 2018-06-25]
	 */


	//Display the header open tag.
	echo '<div class="post-header">';


		//Load the title template.
		include (
		
			locate_template (

				'templates/cards/user/header/title.php'

			)

		);

	
	//Display the header close tag.
	echo '</div>';


?>