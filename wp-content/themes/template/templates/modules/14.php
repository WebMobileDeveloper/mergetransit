<?php
	/**
	 * The module 14 template.
	 *
	 * [Last update: 2019-01-11]
	 */


	//Get the blocks.
	$blocks = get_field (

		'blocks'

	);


	//Whether the blocks are not empty.
	if ( $blocks ) {


		//Set up the module default hash attribute.
		$hash = '';


		//Get the module identifier.
		$identifier = get_field (

			'module-identifier'

		);


		//Whether the module identifier is not empty.
		if ( $identifier ) {


			//Set up the module hash attribute.
			$hash = ' data-hash="' . esc_attr( $identifier ) . '"';


		}


		//Get the module style attribute.
		$style = get_style (

			array (
			
				'background-image' => get_field (

					'background-image-url'

				),
			
				'background-color' => get_field (

					'background-color'

				)
			
			)
			
		);


		//Display the module open tag.
		echo '<div class="module-14 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Display the blocks open tag.
				echo '<div class="blocks flex stretch center">';


					//Load the blocks.
					foreach ( $blocks as $block ) {


						//Display the block open tag.
						echo '<div class="block flex column stretch space">';


							//The block url.
							$block_url = '';


							//Whether the button link type is not empty.
							if ( $block[ 'link-type' ] ) {


								//Whether the button link is url.
								if ( $block[ 'link-type' ] == 'url' ) {


									//Set up the button url.
									$block_url = $block[ 'link-url' ];


								}


								//Whether the button link is post.
								if ( $block[ 'link-type' ] == 'post' ) {


									//Whether the button post id is not empty.
									if ( $block[ 'link-post-id' ] ) {


										//Set up the button url.
										$block_url = get_permalink (

											$block[ 'link-post-id' ]

										);


									}


								}


								//Whether the button link is term.
								if ( $block[ 'link-type' ] == 'term' ) {


									//Whether the button term id is not empty.
									if ( $block[ 'link-term-id' ] ) {


										//Set up the button url.
										$block_url = get_term_link (

											get_term (

												$block[ 'link-term-id' ]

											)

										);


									}


								}


							}


							//Whether the image is not empty.
							if ( $block[ 'image' ][ 'id' ] ) {


								//Get the image html structure.
								$image = get_image (

									$block[ 'image' ][ 'id' ]

								);


								//Whether the image html structure is not empty.
								if ( $image ) {


									//Whether the block url is not empty.
									if ( $block_url ) {


										$image = '<a href="' . esc_url( $block_url ) . '">' . $image . '</a>';


									}


									//Display the thumbnail open tag.
									echo '<figure class="thumbnail">';


										//Display the thumbnail.
										echo $image;


									//Display the thumbnail close tag.
									echo '</figure>';


								}


							}


							//Whether the texts are not empty.
							if ( $block[ 'title' ] || $block[ 'description' ] ) {


								//Display the texts open tag.
								echo '<div class="texts flex column space">';


									//Whether the title is not empty.
									if ( $block[ 'title' ] ) {


										//Whether the block url is not empty.
										if ( $block_url ) {


											//Set up the title with a link.
											$block[ 'title' ] = '<a href="' . esc_url( $block_url ) . '">' . $block[ 'title' ] . '</a>';


										}


										//Display the title open tag.
										echo '<p class="title">';


											//Display the title.
											echo merge_connectives (

												$block[ 'title' ]

											);


										//Display the title close tag.
										echo '</p>';


									}


									//Whether the description is not empty.
									if ( $block[ 'description' ] ) {


										//Display the description open tag.
										echo '<div class="description content">';


											//Display the description.
											echo merge_connectives (

												$block[ 'description' ],

												true

											);


										//Display the description close tag.
										echo '</div>';


									}


									//Whether the block url is not empty.
									if ( $block_url ) {


										//Whether the button is not empty.
										if ( $block[ 'button' ] ) {


											//The button label.
											$button_label = '';


											//Whether the button left icon post id is not empty.
											if ( $block[ 'button' ][ 0 ][ 'left-icon-post-id' ] ) {


												//Set up the button icon.
												$button_label .= get_icon (

													$block[ 'button' ][ 0 ][ 'left-icon-post-id' ]

												);


											}


											//Whether the button label is not empty.
											if ( $block[ 'button' ][ 0 ][ 'label' ] ) {


												//Set up the button label.
												$button_label .= '<span>' . $block[ 'button' ][ 0 ][ 'label' ] . '</span>';


											}


											//Whether the button right icon post id is not empty.
											if ( $block[ 'button' ][ 0 ][ 'right-icon-post-id' ] ) {


												//Set up the button icon.
												$button_label .= get_icon (

													$block[ 'button' ][ 0 ][ 'right-icon-post-id' ]

												);


											}


											//Whether the button label is not empty.
											if ( $button_label ) {


												//Display the button open tag.
												echo '<div class="button">';


													//Display the link open tag.
													echo '<a href="' . esc_url( $block_url ) . '" class="btn black small">';


														//Display the button label.
														echo merge_connectives (

															$button_label

														);


													//Display the link close tag.
													echo '</a>';


												//Display the button close tag.
												echo '</div>';


											}


										}


									}


								//Display the texts open tag.
								echo '</div>';


							}


						//Display the block open tag.
						echo '</div>';


					}


				//Display the blocks open tag.
				echo '</div>';
		
		
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';


	}


?>