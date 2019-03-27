<?php
	/**
	 * The module 7 template.
	 *
	 * [Last update: 2019-01-28]
	 */


	//Get the navigation main menu items.
	$main_items = get_field (

		'items-main'

	);


	//Get the navigation mobile menu items.
	$mobile_items = get_field (

		'items-mobile'

	);


	//Whether the navigation menu items are not empty.
	if ( $main_items || $mobile_items ) {


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
		echo '<div class="module-7 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Whether the navigation main menu items are not empty.
				if ( $main_items ) {


					//Display the navigation main menu open tag.
					echo '<nav class="navigation-main-menu">';


						//Display the navigation main menu.
						the_navigation_menu(

							'items-main',

							get_the_id()

						);


					//Display the navigation main menu close tag.
					echo '</nav>';


				}


				//Whether the navigation mobile menu items are not empty.
				if ( $mobile_items ) {


					//Display the navigation mobile menu open tag.
					echo '<nav class="navigation-mobile-menu flex middle nowrap">';


						//Display the navigation mobile menu wrap open tag.
						echo '<div class="menu-wrap">';


							//Get the icon post id.
							$icon_post_id = get_field (
							
								'mobile-close-icon-post-id'
								
							);
							
							
							//Whether the icon post id is not empty.
							if ( $icon_post_id ) {


								echo '<p' .

									' class="hide-menu js-removeClass"' .

									' data-removeObject=".module-7 .navigation-mobile-menu, body"' .

									' data-removeClass="is-mobile-navigation"' .

								'>';


									//Display the icon.
									the_icon (
									
										$icon_post_id
										
									);


								echo '</p>';
							
							
							}


							//Display the navigation mobile menu.
							the_navigation_menu(

								'items-mobile',

								get_the_id()

							);


						//Display the navigation mobile menu wrap open tag.
						echo '</div>';


						//Get the label.
						$label = get_field (

							'mobile-show-label'

						);


						//Whether the label is not empty.
						if ( $label ) {


							//Display the label open tag.
							echo '<p' .

								' class="label js-addClass"' .

								' data-addObject="parent, body"' .

								' data-addClass="is-mobile-navigation"' .

							'>';


								//Display the label.
								echo merge_connectives (

									$label

								);


							//Display the label close tag.
							echo '</p>';


						}


						//Get the icon post id.
						$icon_post_id = get_field (
						
							'mobile-show-icon-post-id'
							
						);
						
						
						//Whether the icon post id is not empty.
						if ( $icon_post_id ) {


							echo '<span' .

								' class="show-menu js-addClass"' .

								' data-addObject="parent, body"' .

								' data-addClass="is-mobile-navigation"' .

							'>';


								//Display the icon.
								the_icon (
								
									$icon_post_id
									
								);
							
							
							echo '</span>';
						
						
						}


						//Display the menu overlay.
						echo '<div' .

							' class="menu-overlay js-removeClass"' .

							' data-removeObject="parent, body"' .

							' data-removeClass="is-mobile-navigation"' .

						'></div>';


					//Display the navigation mobile menu close tag.
					echo '</nav>';


				}
		
			
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';


	}


?>