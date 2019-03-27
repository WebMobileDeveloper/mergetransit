<?php
	/**
	 * The category template.
	 *
	 * [Last update: 2018-06-25]
	 */	


	//Whether the card custom class is not defined.
	if ( !isset( $class ) ) {


		//The default card custom classes.
		$class = '';


	}


	//Display the card open tag.
	echo '<div class="article-card' . esc_attr( $class ) . '">';


		//Load the header template.
		include (

			locate_template (

				'templates/cards/category/header.php'

			)

		);


		//Load the thumbnail template.
		include (
		
			locate_template (

				'templates/cards/category/thumbnail.php'

			)
		
		);


		//Load the content template.
		include (
		
			locate_template (

				'templates/cards/category/content.php'

			)
		
		);


	//Display the card close tag.
	echo '</div>';


?>