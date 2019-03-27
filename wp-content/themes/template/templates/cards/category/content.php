<?php
	/**
	 * The content template.
	 *
	 * [Last update: 2018-04-18]
	 */


	//Get the term content.
	$content = term_description (
	
		$term->term_id,
		
		$term->taxonomy
	
	);


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