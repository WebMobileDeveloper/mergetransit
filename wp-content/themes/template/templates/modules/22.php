<?php
	/**
	 * The module 22 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the place identifier.
	$place_id = get_field (

		'place-id'

	);


	//Whether the place identifier is not empty.
	if ( $place_id ) {


		//Get the Google reviews number.
		$number = get_field (

			'number'

		);


		//Get the Google reviews language.
		$language = get_field (

			'language'

		);


		//Get the Google reviews language.
		$api_key = get_field (

			'api-key'

		);


		//Geth the Google reviews.
		$reviews = get_google_reviews (

			$place_id,

			0,

			$language,

			$api_key

		);


		//Whether the Google reviews are not empty.
		if ( $reviews ) {


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
			echo '<div class="module-22 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


				//Display the header.
				get_template_part (

					'templates/modules/1'

				);


				//Display the container open tag.
				echo '<div class="container">';


					//Display the reviews open tag.
					echo '<div class="reviews flex top center">';


						//Get the minimal rate value to display.
						$min_rate = intval (

							get_field (

								'min-rate'

							)

						);


						//The review default index.
						$review_index = 1;


						//Load the Google reviews.
						foreach ( $reviews as $review ) {


							//Whether the Google reviews rate is not less then the minimal value to display.
							if ( $review->rating >= $min_rate ) {


								//Display the review open tag.
								echo '<div class="review">';


									//Whether the review avatar is not empty.
									if ( $review->profile_photo_url ) {


										//Display the avatar open tag.
										echo '<div class="avatar">';


											//Load the image.
											$image = new Image();


											//Set up the image url.
											$image->set_url (

												$review->profile_photo_url

											);


											//Whether the review author url is not empty.
											if ( $review->author_url ) {


												//Load the link.
												$a = new A (

													$image->get_image(),

													$review->author_url

												);


												//Set up the link rel.
												$a->set_rel (

													'nofollow'

												);


												//Set up the link target.
												$a->set_target (

													'_blank'

												);


												//Display the link.
												$a->display();


											//The review author url is empty.
											} else {


												//Display the image.
												$image->display();


											}


										//Display the avatar close tag.
										echo '</div>';


									}


									//Whether the review author name is not empty.
									if ( $review->author_name ) {


										//Whether the review author url is not empty.
										if ( $review->author_url ) {


											//Load the link.
											$a = new A (

												$review->author_name,

												$review->author_url

											);


											//Set up the link class.
											$a->set_class (

												'author'

											);


											//Set up the link rel.
											$a->set_rel (

												'nofollow'

											);


											//Set up the link target.
											$a->set_target (

												'_blank'

											);


											//Display the link.
											$a->display();


										//The review author url is empty.
										} else {


											//Display the author open tag.
											echo '<p class="author">';


												//Display the author name.
												echo merge_connectives (

													$review->author_name

												);


											//Display the author close tag.
											echo '</p>';


										}


									}


									//Whether the review rating is not empty.
									if ( $review->rating ) {


										$marked_icon_post_id = get_field (

											'marked-icon-post-id'

										);


										if ( $marked_icon_post_id ) {


											$unmarked_icon_post_id = get_field (

												'unmarked-icon-post-id'

											);


											if ( $unmarked_icon_post_id ) {


												//Display the rating open tag.
												echo '<p class="rating flex middle center nowrap">';


													//Load the rates.
													for ( $i = 1; $i < 6; $i++ ) {


														//Whether the rate is marked.
														if ( $i <= $review->rating ) {


															//Display the marked open tag.
															echo '<span class="marked">';


																//Display the marked icon.
																the_icon (

																	$marked_icon_post_id

																);


															//Display the marked close tag.
															echo '</span>';


														//The rate is not marked.
														} else {


															//Display the unmarked open tag.
															echo '<span class="unmarked">';


																//Display the unmarked icon.
																the_icon (

																	$unmarked_icon_post_id

																);


															//Display the unmarked close tag.
															echo '</span>';


														}


													}


												//Display the rating close tag.
												echo '</p>';


											}


										}


									}


									//Whether the review comment is not empty.
									if ( $review->text ) {


										//Display the comment open tag.
										echo '<div class="comment content">';


											//Display the comment.
											echo merge_connectives (

												$review->text,

												true

											);


										//Display the comment close tag.
										echo '</div>';


									}


								//Display the review close tag.
								echo '</div>';


								//Whether the review index is not less then the reviews number.
								if ( $review_index >= $number ) {


									break;


								//The review index is not greater then the reviews number.
								} else {


									//Increment the review index.
									$review_index++;


								}


							}


						}


					//Display the reviews close tag.
					echo '</div>';
			
			
				//Display the container close tag.
				echo '</div>';


				//Display the edit module post link.
				the_edit_post_link();
			
			
			//Display the module close tag.
			echo '</div>';


		}


	}


?>