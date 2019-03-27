<?php
	/**
	 * The buttons template.
	 *
	 * [Last update: 2018-08-21]
	 */


	//Get the buttons.
	$buttons = get_field (
	
		'buttons'
		
	);


	//Whether the buttons are not empty.
	if ( $buttons ) {


		//Display the buttons open tag.
		echo '<div class="buttons flex middle">';


			//Load the buttons.
			foreach ( $buttons as $key => $button ) {


				//Whether the label is not empty.
				if ( $button[ 'label' ] ) {


					//The button url.
					$button_url = '';


					//Whether the button link type is not empty.
					if ( $button[ 'link-type' ] ) {


						//Whether the button link is url.
						if ( $button[ 'link-type' ] == 'url' ) {


							//Set up the button url.
							$button_url = $button[ 'link-url' ];


						}


						//Whether the button link is post.
						if ( $button[ 'link-type' ] == 'post' ) {


							//Whether the button post id is not empty.
							if ( $button[ 'link-post-id' ] ) {


								//Set up the button url.
								$button_url = get_permalink (

									$button[ 'link-post-id' ]

								);


							}


						}


						//Whether the button link is term.
						if ( $button[ 'link-type' ] == 'term' ) {


							//Whether the button term id is not empty.
							if ( $button[ 'link-term-id' ] ) {


								//Set up the button url.
								$button_url = get_term_link (

									get_term (

										$button[ 'link-term-id' ]

									)

								);


							}


						}


					}


					//Whether the button url is not empty.
					if ( $button_url ) {


						//Display the button open tag.
						echo '<a href="' . esc_url( $button_url ) . '" class="button btn medium ' . ( $key%2 ? 'blue' : 'blue stroke' ) .'">';


							//Display the left icon.
							the_icon (

								$button[ 'left-icon-post-id' ]

							);


							//Display the label open tag.
							echo '<span class="label">';


								//Display the label.
								echo merge_connectives (

									$button[ 'label' ]

								);


							//Display the label close tag.
							echo '</span>';


							//Display the right icon.
							the_icon (

								$button[ 'right-icon-post-id' ]

							);


						//Display the button close tag.
						echo '</a>';


					}


				}


			}


		//Display the contacts close tag.
		echo '</div>';


	}