<?php
	/**
	 * The rating template.
	 *
	 * [Last update: 2018-09-07]
	 */


	//Get the marked icon post id.
	$marked_icon_post_id = get_field (

		'marked-icon-post-id'

	);


	//Whether the marked icon post id is not empty.
	if ( $marked_icon_post_id ) {


		//Get the unmarked icon post id.
		$unmarked_icon_post_id = get_field (

			'unmarked-icon-post-id'

		);


		//Whether the unmarked icon post id is not empty.
		if ( $unmarked_icon_post_id ) {


			//Get the rate max value.
			$rate_max_value = intval (

				get_field (

					'rate-max-value'

				)

			);


			//Whether the rate max value is greater then one.
			if ( $rate_max_value > 1 ) {


				$global_post_id = 0;


				if ( isset( $_REQUEST[ 'post-id' ] ) ) {


					$global_post_id = $_REQUEST[ 'post-id' ];


				} else {


					//Get the global posts.
					global $posts;


					//Whether the global posts are not empty.
					if ( $posts ) {


						//Load the global posts.
						foreach ( $posts as $global_post ) {


							$global_post_id = $global_post->ID;


							break;


						}


					}


				}


				if ( $global_post_id ) {


					$label = get_field (

						'engine-label'

					);


					if ( $label ) {


						echo '<p class="engine-label label">';


							echo merge_connectives (

								$label

							);


						echo '</p>';


					}


					echo '<div class="engine flex middle nowrap">';


						for ( $i = 1; $i <= $rate_max_value; $i++ ) {


							$is_selected = false;


							//Set up the post rate name.
							$post_rate_name = 'post-rate-' . $global_post_id;


							//Whether the rate value is defined.
							if ( isset( $_REQUEST[ 'rate-value' ] ) ) {


								if ( $i <= $_REQUEST[ 'rate-value' ] ) {


									$is_selected = true;


								}


							//Whether the post rate session is defined.
							} else if ( isset( $_SESSION[ $post_rate_name ] ) ) {


								if ( $i <= $_SESSION[ $post_rate_name ] ) {


									$is_selected = true;


								}


							//Whether the post rate cookie is defined.
							} else if ( isset( $_COOKIE[ $post_rate_name ] ) ) {


								if ( $i <= $_COOKIE[ $post_rate_name ] ) {


									$is_selected = true;


								}


							}


							echo '<form class="mark' . ( $is_selected ? ' selected' : '' ) . '" data-action="' . esc_url( get_template_directory_uri() . '/templates/modules/23/rate.php' ) . '" data-notification>';


								echo '<input name="rate-value" type="hidden" value="' . esc_attr( $i ) . '" />';


								echo '<input name="module-post-id" type="hidden" value="' . esc_attr( get_the_id() ) . '" />';


								echo '<input name="post-id" type="hidden" value="' . esc_attr( $global_post_id ) . '" />';


								echo '<button class="unmarked">';


									the_icon (

										$unmarked_icon_post_id

									);


								echo '</button>';


								echo '<button class="marked">';


									the_icon (

										$marked_icon_post_id

									);


								echo '</button>';


							echo '</form>';


						}


					echo '</div>';


					$post_rate_value = intval (

						get_field (

							'post-rate-value',

							$global_post_id

						)

					);


					$post_rate_count = intval (

						get_field (

							'post-rate-count',

							$global_post_id

						)

					);


					$post_rate = 0;


					if ( $post_rate_count ) {


						$post_rate = $post_rate_value / $post_rate_count;


					}


					$label = get_field (

						'rate-label'

					);


					if ( $label ) {


						echo '<p class="rate-label label">';


							echo merge_connectives (

								$label

							);


						echo '</p>';


						echo '<p class="rate-value value">';


							echo number_format (

								round (

									$post_rate,

									2

								),

								2,

								',',

								' '

							);


							$rate_separator = get_field (

								'rate-separator'

							);


							if ( $rate_separator ) {


								echo merge_connectives (

									$rate_separator . $rate_max_value

								);


							}


						echo '</p>';


					}


					$label = get_field (

						'count-label'

					);


					if ( $label ) {


						echo '<p class="count-label label">';


							echo merge_connectives (

								$label

							);


						echo '</p>';


						echo '<p class="count-value value">';


							echo $post_rate_count;


						echo '</p>';


					}


				}


			}


		}


	}


?>