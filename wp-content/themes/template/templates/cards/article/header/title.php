<?php
	/**
	 * The title template.
	 *
	 * [Last update: 2018-06-25]
	 */


	//Get the title.
	$title = get_the_title();


	//Whether the title is not empty.
	if ( $title ) {


		//Display the title open tag.
		echo '<p class="post-title">';
		
		
			//Display the title link open tag.
			echo '<a href="' . esc_url( get_permalink() ) . '">';


				//Display the title.
				echo merge_connectives (
				
					$title
					
				);
				
			
			//Display the title link close tag.
			echo '</a>';


		//Display the title close tag.
		echo '</p>';


	}


?>