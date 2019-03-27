<?php
	/**
	 * The article template.
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


		//Load the thumbnail template.
		include (
		
			locate_template (

				'templates/cards/article/thumbnail.php'

			)
		
		);


		echo '<div class="wrap">';


			//Load the header template.
			include (
			
				locate_template (

					'templates/cards/article/header.php'

				)

			);


			//Load the content template.
			include (
			
				locate_template (
					
					'templates/cards/article/content.php'

				)

			);


		echo '</div>';


	//Display the card close tag.
	echo '</div>';


?>