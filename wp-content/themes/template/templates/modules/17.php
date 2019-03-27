<?php
	/**
	 * The module 17 template.
	 *
	 * [Last update: 2018-09-14]
	 */


	//Get the disqus url.
	$disqus_url = get_field (

		'disqus-url'

	);


	//Whether the disqus url is not empty.
	if ( $disqus_url ) {


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
		echo '<div class="module-17 module' . ( $style ? ' has-background' : '' ) . '"' . $hash . $style . '>';


			//Display the header.
			get_template_part (

				'templates/modules/1'

			);


			//Display the container open tag.
			echo '<div class="container">';


				//Display the disqus thread.
				echo '<div id="disqus_thread"></div>';


				?><script>
					(function() {
						var d = document, s = d.createElement('script');
						s.src = '<?= esc_url( $disqus_url ) ?>';
						s.setAttribute('data-timestamp', +new Date());
						(d.head || d.body).appendChild(s);
					})();
				</script><?php
		
		
			//Display the container close tag.
			echo '</div>';


			//Display the edit module post link.
			the_edit_post_link();
		
		
		//Display the module close tag.
		echo '</div>';


	}


?>