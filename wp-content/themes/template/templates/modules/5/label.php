<?php
	/**
	 * The label template.
	 *
	 * [Last update: 2018-06-25]
	 */


	//Get the label.
	$label = get_field (

		'label'

	);


	//Whether the label is not empty.
	if ( $label ) {


		//Display the label open tag.
		echo '<p class="label term h6">';


			//Display the label.
			echo merge_connectives (
			
				$label
				
			);
			
		
		//Display the label close tag.
		echo '</p>';


	}


?>