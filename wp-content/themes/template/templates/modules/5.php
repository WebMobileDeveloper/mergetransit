<?php
	/**
	 * The module 5 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the page term.
	$term = get_queried_object();

	
	//Whether the term taxonomy is defined.
	if ( isset( $term->taxonomy ) ) {


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
		echo '<div class="module-5 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Display the terms open tag.
				echo '<div class="terms flex middle">';
				
				
					//Load the label template.
					get_template_part (
					
						'templates/modules/5/label'
						
					);
				
				
					//Get the term ancestors.
					$ancestors = get_ancestors (
					
						$term->term_id,
						
						$term->taxonomy
					
					);


					//The terms arguments.
					$args = array (

						'taxonomy' => $term->taxonomy,
						
						'hide_empty' => false
						
					);

					
					//Whether the term ancestors are not empty.
					if ( $ancestors ) {
						
						
						//Set up the top ancestor.
						$term = get_term (
						
							$ancestors[ 0 ]
							
						);
					
					
						//Load the term template.
						include (
						
							locate_template (
						
								'templates/modules/5/term.php'
								
							)
							
						);


						//Set up the ancestor.
						$args[ 'child_of' ] = $term->term_id;
					
					
					//The term ancestors are empty.
					} else {
					

						//Get the term children.
						$terms = get_terms (
						
							array (

								'parent' => $term->term_id,
						
								'taxonomy' => $term->taxonomy

							)

						);


						//Whether the terms are not empty.
						if ( $terms ) {


							//Load the term template.
							include (
							
								locate_template (
							
									'templates/modules/5/term.php'
									
								)
								
							);


							//Set up the ancestor.
							$args[ 'parent' ] = $term->term_id;


						}


					}
					
					

					//Get the terms list.
					$terms = get_terms (
					
						$args
						
					);
				
				
					//Whether the terms are not empty.
					if ( $terms ) {
						
						
						//Load the terms.
						foreach ( $terms AS $term ) {
					
					
							//Load the term template.
							include (
							
								locate_template (
							
									'templates/modules/5/term.php'
									
								)
								
							);
							
							
						}
						
						
					}
				
				
				//Display the terms close tag.
				echo '</div>';
			
			
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';
		
		
	}
	
	
?>