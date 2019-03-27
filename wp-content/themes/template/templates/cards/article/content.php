<?php
	/**
	 * The content template.
	 *
	 * [Last update: 2017-12-12]
	 */

	
	//Whether the excerpt is defined.
	if ( has_excerpt() ) {


		//Get the excerpt.
		$content = get_the_excerpt();
		
		
	//The excerpt is not defined.
	} else {


		//Get the content.
		$content = get_the_content (
	
			false
		
		);
	
	
	}


	//Whether the content is not empty.
	if ( $content ) {


		//Display the post content open tag.
		echo '<div class="post-content content">';


			//Display the content.
			echo merge_connectives (
			
				$content,
				
				true
				
			);


		//Display the post content close tag.
		echo '</div>';
	

	}


?>