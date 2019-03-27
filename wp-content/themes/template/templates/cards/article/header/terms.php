<?php
	/**
	 * The terms template.
	 *
	 * [Last update: 2018-06-25]
	 */


	//Set up the taxonomy name.
	$taxonomy = 'article-tag';


	//Whether the taxonomy is defined.
	if ( taxonomy_exists( $taxonomy ) ) {


		//Get the terms.
		$terms = get_the_terms (

			get_the_id(),
			
			$taxonomy
			
		);


		//Whether the terms are not empty.
		if ( $terms ) {


			//Display the terms open tag.
			echo '<ul class="post-terms flex middle">';


				//Load the terms.
				foreach( $terms AS $term ) {


					//Display the term open tag.
					echo '<li class="term">';


						//Display the term link open tag.
						echo '<a href="' . esc_url( get_term_link( $term ) ) . '" title="' . esc_attr( $term->name ) . '" class="h6">';


							//Display the term name.
							echo merge_connectives (
							
								$term->name
								
							);


						//Display the term link close tag.
						echo '</a>';


					//Display the term close tag.
					echo '</li>';


				}


			//Display the terms close tag.
			echo '</ul>';


		}


	}


?>