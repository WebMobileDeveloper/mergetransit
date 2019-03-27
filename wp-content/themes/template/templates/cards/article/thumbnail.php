<?php
	/**
	 * The thumbnail template.
	 *
	 * [Last update: 2019-01-11]
	 */


	//Whether the thumbnail size is not defined.
	if ( !isset( $thumbnail_size ) || !$thumbnail_size ) {


		//Set up the default thumbnail size.
		$thumbnail_size = '1140x443';


	}


	//Get the thumbnail.
	$thumbnail = get_thumbnail_link (

		0,

		array (
		
			'size' => $thumbnail_size
		
		)

	);


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