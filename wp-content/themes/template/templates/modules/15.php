<?php
	/**
	 * The module 15 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the video post.
	$video_post = get_field (

		'video-post'

	);


	//Whether the video post is empty.
	if ( !$video_post ) {


		//Get the video url.
		$video_url = get_field (

			'video-url'

		);



	}


	//Get the poster image post id.
	$poster_image_post_id = get_field (

		'poster-image-post-id'

	);


	//Whether the video post or video url or poster are not empty.
	if ( $video_post || $video_url || $poster_image_post_id ) {


		//Get the full size status.
		$is_full_size = get_field (

			'is-full'

		);


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
		echo '<div class="module-15 module' . ( $style ? ' has-background' : '' ) . ( $is_full_size ? ' is-full' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Whether the video post or video url are not empty.
				if ( $video_post || $video_url ) {


					//Set up the video shortcode tag.
					$shortcode_tag = '[video';


					//Whether the poster image post id is not empty.
					if ( $poster_image_post_id ) {


						//Get the poster image url.
						$poster_image_url = get_image_url (

							$poster_image_post_id

						);


						//Whether the poster image url is not empty.
						if ( $poster_image_url ) {


							//Set up the poster attribute.
							$shortcode_tag .= ' poster="' . esc_url( $poster_image_url ) . '"';


						}


					}


					//Whether the video post is not empty.
					if ( $video_post ) {


						//Set up the src attribute.
						$shortcode_tag .= ' src="' . esc_url( $video_post[ 'url' ] ) . '"';


						//Set up the width attribute.
						$shortcode_tag .= ' width="' . esc_attr( $video_post[ 'width' ] ) . '"';


						//Set up the height attribute.
						$shortcode_tag .= ' height="' . esc_attr( $video_post[ 'height' ] ) . '"';


					//The video post is empty.
					} else {


						//Set up the src attribute.
						$shortcode_tag .= ' src="' . esc_url( $video_url ) . '"';


					}


					//Get the autoplay status.
					$is_autoplay = get_field (

						'is-autoplay'

					);


					//Whether the autoplay is not empty.
					if ( $is_autoplay ) {


						//Set up the autoplay attribute.
						$shortcode_tag .= ' autoplay="true"';


					//The autoplay is empty.
					} else {


						//Set up the autoplay attribute.
						$shortcode_tag .= ' autoplay="false"';


					}


					//Get the loop status.
					$is_loop = get_field (

						'is-loop'

					);


					//Whether the loop is not empty.
					if ( $is_loop ) {


						//Set up the loop attribute.
						$shortcode_tag .= ' loop="true"';


					//The loop is empty.
					} else {


						//Set up the loop attribute.
						$shortcode_tag .= ' loop="false"';


					}


					//Get the muted status.
					$is_muted = get_field (

						'is-muted'

					);


					//Whether the muted is not empty.
					if ( $is_muted ) {


						//Set up the muted attribute.
						$shortcode_tag .= ' muted="true"';


					//The muted is empty.
					} else {


						//Set up the muted attribute.
						$shortcode_tag .= ' muted="false"';


					}


					//Set up the controls attribute.
					$shortcode_tag .= ' controls="false"';


					//Set up the video shortcode tag close bracket.
					$shortcode_tag .= ']';


					//Display the video.
					echo do_shortcode (

						$shortcode_tag

					);


				//The video post and video url are empty.
				} else {


					//Whether the full size is not empty.
					if ( $is_full_size ) {


						//Get the poster styles.
						$styles = get_style (

							array (

								'background-image' => get_image_url (

									$poster_image_post_id

								)

							)


						);


						//Display the poster open tag.
						echo '<div class="poster"' . $styles . '>';


						//Display the poster close tag.
						echo '</div>';


					//The full size is empty.
					} else {


						//Display the poster image.
						the_image (

							$poster_image_post_id

						);


					}


				}


				echo '<div class="texts">';


					echo '<div class="container">';


						$text1 = get_field (

							'text-1'

						);


						if ( $text1 ) {


							echo '<p class="text-1 h1">';


								echo merge_connectives (

									$text1

								);


							echo '</p>';


						}


						$text2 = get_field (

							'text-2'

						);


						if ( $text2 ) {


							echo '<p class="text-2">';


								echo merge_connectives (

									$text2

								);


							echo '</p>';


						}



						$buttons = get_field (

							'buttons'

						);


						if ( $buttons ) {


							echo '<div class="buttons flex middle nowrap">';


								foreach ( $buttons as $button ) {


									$image = get_image (

										$button[ 'image-post-id' ]

									);


									$a = new A (

										$image,

										$button[ 'url' ]

									);


									$a->set_target( 

										'_blank'

									);


									echo $a->display();


								}


							echo '</div>';


						}


						$scrolls = get_field (

							'scrolls'

						);


						if ( $scrolls ) {


							echo '<div class="scrolls">';


								foreach ( $scrolls as $scroll ) {


									echo '<a href="' . esc_url( $scroll[ 'url' ] ) . '" class="scroll">';


										the_icon (

											$scroll[ 'icon-post-id' ]

										);


									echo '</a>';


								}


							echo '</div>';


						}


					echo '</div>';


				echo '</div>';
			
			
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';


	}


?>