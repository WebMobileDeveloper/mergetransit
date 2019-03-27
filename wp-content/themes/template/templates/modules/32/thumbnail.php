<?php
	/**
	 * The thumbnail template.
	 *
	 * [Last update: 2019-01-11]
	 */


	//Whether the page is a singular page.
	if ( is_singular() ) {


		//Get the thumbnail.
		$thumbnail = get_thumbnail (

			0,

			'1140x443'

		);


	//The page is not a singular page.
	} else {


		//Get the thumbnail.
		$thumbnail = false;


	}


	//Whether the thumbnail is not empty.
	if ( $thumbnail ) {


		//Display the thumbnail open tag.
		echo '<figure class="post-thumbnail">';


			//Display the thumbnail.
			echo $thumbnail;


		//Display the thumbnail close tag.
		echo '</figure>';


	}


?>