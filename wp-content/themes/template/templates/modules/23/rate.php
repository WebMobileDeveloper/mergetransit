<?php
	/**
	 * The rate template.
	 *
	 * [Last update: 2018-10-02]
	 */


	//Whether the WordPress path is defined.
	if ( !defined( 'ABSPATH' ) ) {


		//Load the WordPress.
		require_once (

			'../../../../../../wp-load.php'
			
		);


	}


	//Whether the module post id is defined.
	if ( isset( $_REQUEST[ 'module-post-id' ] ) ) {


		//Whether the module post id is not empty.
		if ( $_REQUEST[ 'module-post-id' ] ) {


			//Whether the post id is defined.
			if ( isset( $_REQUEST[ 'post-id' ] ) ) {


				//Whether the post id is not empty.
				if ( $_REQUEST[ 'post-id' ] ) {


					//Whether the rate value is defined.
					if ( isset( $_REQUEST[ 'rate-value' ] ) ) {


						//Set up the rate value.
						$rate_value = intval (

							$_REQUEST[ 'rate-value' ]

						);


						//Whether the rate value is not empty.
						if ( $rate_value ) {


							//Get the rate max value.
							$rate_max_value = intval (

								get_field (

									'rate-max-value',

									$_REQUEST[ 'module-post-id' ]

								)

							);


							//Whether the rate max value is greater then one.
							if ( $rate_max_value > 1 ) {


								//Whether the rate value is not greater then rate max value.
								if ( $rate_value <= $rate_max_value ) {


									//Get the post rate value.
									$post_rate_value = intval (

										get_field (

											'post-rate-value',

											$_REQUEST[ 'post-id' ]

										)

									);


									//Get the post rate count.
									$post_rate_count = intval (

										get_field (

											'post-rate-count',

											$_REQUEST[ 'post-id' ]

										)

									);


									//Add the post rate value.
									$post_rate_value += $rate_value;


									//Add the post rate count.
									$post_rate_count++;


									//Set up the post rate name.
									$post_rate_name = 'post-rate-' . $_REQUEST[ 'post-id' ];


									//Whether the post rate session is defined.
									if ( isset( $_SESSION[ $post_rate_name ] ) ) {


										//Subtract the post rate value.
										$post_rate_value -= $_SESSION[ $post_rate_name ];


										//Subtract the post rate count.
										$post_rate_count--;


									//Whether the post rate cookie is defined.
									} else if ( isset( $_COOKIE[ $post_rate_name ] ) ) {


										//Subtract the post rate value.
										$post_rate_value -= $_COOKIE[ $post_rate_name ];


										//Subtract the post rate count.
										$post_rate_count--;


									}


									//Update the post rate value.
									update_field (

										'post-rate-value',

										$post_rate_value,

										$_REQUEST[ 'post-id' ]

									);


									//Update the post rate count.
									update_field (

										'post-rate-count',

										$post_rate_count,

										$_REQUEST[ 'post-id' ]

									);


									//Set up the post rate session.
									$_SESSION[ $post_rate_name ] = $rate_value;


									//Set up the post rate cookie.
									setcookie (
									
										$post_rate_name,
									
										$rate_value,
										
										0,
										
										'/'
										
									);


									//Initiate the output buffering.
									buffering_initiate();


									//Set up the module post as a global post data.
									setup_postdata (

										$GLOBALS[ 'post' ] =& $_REQUEST[ 'module-post-id' ]
										
									);


									//Load the rating template.
									get_template_part (

										'templates/modules/23/rating'

									);


									//Release the output buffering.
									$template = buffering_release();


									//Get the success notifications.
									$notifications = get_field (

										'notification-success',

										$_REQUEST[ 'module-post-id' ]

									);


									//Whether the success notifications are not empty.
									if ( $notifications ) {


										//Load the success notifications.
										foreach ( $notifications as $notification ) {


											//Display the notification.
											echo success (

												$notification[ 'title' ],

												$notification[ 'content' ],

												array (

													'target' => '.module-23 > .container',

													'template' => $template

												)

											);


											exit();


										}


									//The success notifications are empty.
									} else {


										//Display the default notification.
										success (

											'',

											'',

											array (

												'target' => '.module-23 > .container',

												'template' => $template

											)

										);


										exit();


									}


								}



							}


						}



					}



				}


			}


			//Get the error notifications.
			$notifications = get_field (

				'notification-error',

				$_REQUEST[ 'module-post-id' ]

			);


			//Whether the error notifications are not empty.
			if ( $notifications ) {


				//Load the success notifications.
				foreach ( $notifications as $notification ) {


					//Display the notification.
					echo error (

						$notification[ 'title' ],

						$notification[ 'content' ]

					);


					exit();


				}


			}



		}


	}


?>