<?php
	/**
	 * The content template.
	 *
	 * [Last update: 2017-12-18]
	 */


	//Whether the page is a singular page.
	if ( is_singular() ) {


		//Get the content.
		$content = get_the_content (

			false
		
		);


	//The page is not a singular page.
	} else {


		//Whether the page is an author page.
		if ( is_author() ) {


			//Get the author description.
			$content = get_the_author_meta (
			
				'description'
				
			);
			
			
		//The page is not an author page.
		} else {


			//Get the term description.
			$content = term_description();
			
			
		}


	}


	//Whether the content is not empty.
	if ( $content ) {


		//Display the content open tag.
		echo '<div class="post-content content">';


			//Display the content.
			echo merge_connectives (
			
				$content,
				
				true
				
			);


		//Display the content close tag.
		echo '</div>';
		
		
	}
	
	
?>