<?php
	/**
	 * The thumbnail template.
	 *
	 * [Last update: 2019-01-11]
	 */


	//Whether the thumbnail size is not defined.
	if ( !isset( $thumbnail_size ) || !$thumbnail_size ) {


		//Set up the default thumbnail size.
		$thumbnail_size = 'post-thumbnail';


	}


	//Get the thumbnail post id.
	$thumbnail_post_id = get_field (

		'thumbnail-image-id',
		
		$term->taxonomy . '_' . $term->term_id

	);


	//Whether the thumbnail id is not empty.
	if ( $thumbnail_post_id ) {


		//Display the thumbnail open tag.
		echo '<figure class="post-thumbnail">';
		
		
			//Display the thumbnail link open tag.
			echo '<a href="' . esc_url( get_term_link( $term ) ) . '">';


				//Display the thumbnail.
				the_image (
				
					$thumbnail_id,
					
					$thumbnail_size

				);
				
			
			//Display the thumbnail link close tag.
			echo '</a>';


		//Display the thumbnail close tag.
		echo '</figure>';


	}


?>