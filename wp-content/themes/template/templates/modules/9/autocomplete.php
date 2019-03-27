<?php
	/**
	 * The autocomplete template.
	 *
	 * [Last update: 2018-03-09]
	 */


	//Load the WordPress.
	require_once (

		'../../../../../../wp-load.php'
		
	);


	//Whether the search value is defined.
	if ( isset( $_POST[ 'search' ] ) ) {


		//The posts arguments
		$args = array (
		
			's' => $_POST[ 'search' ]
			
		);
		
		
		//Whether the post quantity is defined.
		if ( isset( $_POST[ 'numberposts' ] ) ) {
			
			
			//Parse the posts quantity.
			$_POST[ 'numberposts' ] = abs (
			
				intval (
				
					$_POST[ 'numberposts' ]
					
				)
				
			);
			
			
			//Whether the posts quantity is not empty.
			if ( $numberposts > 0 ) {


				$args[ 'numberposts' ] = $_POST[ 'numberposts' ];


			}


		}


		//Get the autocomplete posts.
		$autocomplete_posts = get_posts (
		
			$args
			
		);


		//Whether the posts are not empty.
		if ( $autocomplete_posts ) {


			//Display the posts open tag.
			echo '<ul class="posts">';
				
				
				//Load the posts.
				foreach ( $autocomplete_posts AS $post ) {
			
			
					//Set up the post as a new global post data.
					setup_postdata (
					
						$GLOBALS[ 'post' ] =& $post
						
					);
				
				
					//Get the title.
					$title = get_the_title();
					
					
					//Whether the title is not empty.
					if ( $title ) {
					
					
						//Display the post open tag.
						echo '<li class="post">';
						
						
							//Display the post link open tag.
							echo '<a href="' . esc_url( get_permalink() ) . '">';
						
						
								//Display the title.
								echo merge_connectives (
								
									$title
									
								);
								
							
							//Display the post link close tag.
							echo '</a>';
						
						
						//Display the post close tag.
						echo '</li>';
						
						
					}
					
					
				}
				
			
			//Display the posts close tag.
			echo '</ul>';
			
			
		}
		
		
	}
	
	
?>