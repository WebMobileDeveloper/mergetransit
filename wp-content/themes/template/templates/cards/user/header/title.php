<?php
	/**
	 * The title template.
	 *
	 * [Last update: 2018-06-25]
	 */


	//Whether the title is not empty.
	if ( $user->data->display_name ) {


		//Display the title open tag.
		echo '<p class="post-title">';
		
		
			//Display the title link open tag.
			echo '<a href="' . esc_url( get_author_posts_url( $user->ID ) ) . '">';


				//Display the title.
				echo merge_connectives (
				
					$user->data->display_name
					
				);
				
				
			//Display the title link close tag.
			echo '</a>';


		//Display the title close tag.
		echo '</p>';


	}


?>