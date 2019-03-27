<?php
	/**
	 * The module 10 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the socials slugs.
	$socials_slugs = get_field (
	
		'socials-order'
		
	);
	
	
	//Whether the socials are not empty.
	if ( $socials_slugs ) {


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
		echo '<div class="module-10 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container flex middle nowrap">';


				//Get the label.
				$label = get_field (

					'label'

				);


				//Whether the label is not empty.
				if ( $label ) {


					//Display the label open tag.
					echo '<p class="socials-label">';


						echo merge_connectives (

							$label

						);


					//Display the label close tag.
					echo '</p>';


				}


				//Display the socials open tag.
				echo '<div class="socials flex middle">';
				
				
					//Load the socials.
					foreach ( $socials_slugs AS $social_slug ) {

					
						//Get the socials.
						$socials = get_field (
						
							$social_slug[ 'name' ]
						
						);

						
						//Whether the socials are not empty.
						if ( $socials ) {


							//Load the socials.
							foreach ( $socials as $social ) {

							
								//Whether the url is not empty.
								if ( $social[ 'url' ] ) {
								
								
									//Whether the label or the icon is not empty.
									if ( $social[ 'label' ] || $social[ 'icon-post-id' ] ) {
								
								
										//Display the social open tag.
										echo '<a href="' . esc_url( $social[ 'url' ] ) . '" class="social flex middle nowrap" target="_blank" rel="nofollow">';
										
										
											//Whether the icon is not empty.
											if ( $social[ 'icon-post-id' ] ) {
											
											
												//Display the icon.
												the_icon (
												
													$social[ 'icon-post-id' ]
													
												);
											
											
											}
										
										
											//Whether the label is not empty.
											if ( $social[ 'label' ] ) {
											
											
												//Display the label open tag.
												echo '<span class="label">';
												
												
													//Display the label.
													echo merge_connectives (
													
														$social[ 'label' ]
													
													);
													
												
												//Display the label close tag.
												echo '</span>';
											
											
											}
										
										
										//Display the social close tag.
										echo '</a>';
										
										
									}
								
								
								}


							}


						}
					
					
					}
				
			
				//Display the socials close tag.
				echo '</div>';
			
			
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';
			
	
	}


?>