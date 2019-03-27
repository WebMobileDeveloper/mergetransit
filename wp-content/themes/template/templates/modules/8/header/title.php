<?php
	/**
	 * The title template.
	 *
	 * [Last update: 2018-03-15]
	 */


	//Whether the page is a singular page.
	if ( is_singular() ) {


		//Get the title.
		$title = get_the_title();


		//Get the edit post link.
		$edit = get_the_edit_post_link();


	//The page is not a singular page.
	} else {


		//Get the title.
		$title = get_archive_title();


		//Get the edit term link.
		$edit = get_the_edit_term_link();


	}


	//Whether the title is not empty.
	if ( $title ) {


		//Display the title open tag.
		echo '<h1 class="post-title h1">';


			//Display the title.
			echo merge_connectives (
			
				$title
				
			);


			//Display the object edit link.
			echo $edit;


		//Display the title close tag.
		echo '</h1>';


	}


?>