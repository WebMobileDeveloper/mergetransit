<?php
	/**
	 * The user template.
	 *
	 * [Last update: 2018-06-25]
	 */	


	//Whether the card custom class is not defined.
	if ( !isset( $class ) ) {


		//The default card custom classes.
		$class = '';


	}


	//Display the card open tag.
	echo '<div class="user-card' . esc_attr( $class ) . '">';


		//Load the header.
		include (

			locate_template (

				'templates/cards/user/header.php'

			)

		);


		//Load the content.
		include (

			locate_template (

				'templates/cards/user/content.php'

			)

		);


	//Display the card close tag.
	echo '</div>';


?>