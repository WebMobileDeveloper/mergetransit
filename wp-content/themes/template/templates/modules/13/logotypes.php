<?php
	/**
	 * The logotypes template.
	 *
	 * [Last update: 2019-01-11]
	 */


	//Get the logotypes.
	$logotypes = get_field (
		
		'logotypes'
		
	);


	//Whether the logotypes are not empty.
	if ( $logotypes ) {


		//Load the logotypes
		foreach ( $logotypes as $logotype ) {


			//Whether the image post id is not empty.
			if ( $logotype[ 'image-post-id' ] ) {


				//Load the logotype image.
				$logotype_image = new Image (
				
					$logotype[ 'image-post-id' ]
					
				);


				//Get the logotype image HTML structure.
				$logotype_image = $logotype_image->get_image();


				//Whether the logotype image is not empty.
				if ( $logotype_image ) {


					//Display the logotype open tag.
					echo '<figure class="logotype">';


						//Whether the logotype link is not empty.
						if ( $logotype[ 'post-id' ] ) {


							//Load the link.
							$a = new A (

								$logotype_image,

								get_permalink (

									$logotype[ 'post-id' ]

								)

							);


							//Display the link.
							$a->display();


						//The logotype link is empty.
						} else {


							//Display the logotype.
							echo $logotype_image;


						}


					//Display the logotype close tag.
					echo '</figure>';
				
				
				}


			}


		}
		
		
	}


?>