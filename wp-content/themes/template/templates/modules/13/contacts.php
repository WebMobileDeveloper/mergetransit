<?php
	/**
	 * The contacts template.
	 *
	 * [Last update: 2018-08-21]
	 */


	//Get the contacts.
	$contacts = get_field (
	
		'contacts'
		
	);


	//Whether the contacts are not empty.
	if ( $contacts ) {


		//Display the contacts open tag.
		echo '<div class="contacts flex middle">';


			//Load the contacts.
			foreach ( $contacts as $contact ) {


				//Whether the text is not empty.
				if ( $contact[ 'text' ] ) {


					//Display the contact open tag.
					echo '<div class="contact flex middle nowrap">';


						//Display the left icon.
						the_icon (

							$contact[ 'left-icon-post-id' ]

						);


						//Display the text open tag.
						echo '<div class="text">';


							//Display the text.
							echo merge_connectives (

								$contact[ 'text' ],

								true

							);


						//Display the text close tag.
						echo '</div>';


						//Display the right icon.
						the_icon (

							$contact[ 'right-icon-post-id' ]

						);


					//Display the contact close tag.
					echo '</div>';


				}


			}


		//Display the contacts close tag.
		echo '</div>';


	}


?>