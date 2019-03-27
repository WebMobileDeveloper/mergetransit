<?php
	/**
	 * The header template.
	 *
	 * [Last update: 2017-11-23]
	 */


	//Display the header open tag.
	echo '<div class="post-header">';


		//Load the terms template.
		include (
		
			locate_template (

				'templates/cards/article/header/terms.php'

			)

		);


		//Load the title template.
		include (
		
			locate_template (

				'templates/cards/article/header/title.php'

			)

		);

	
	//Display the header close tag.
	echo '</div>';


?>