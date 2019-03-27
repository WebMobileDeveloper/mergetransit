<?php
	/**
	 * The content template.
	 *
	 * [Last update: 2018-04-18]
	 */


	//Whether the content is not empty.
	if ( $usermeta[ 'description' ][ 0 ] ) {


		//Display the post content open tag.
		echo '<div class="post-content content">';


			//Display the content.
			echo merge_connectives (
			
				$usermeta[ 'description' ][ 0 ],
				
				true
				
			);


		//Display the post content close tag.
		echo '</div>';
	

	}


?>