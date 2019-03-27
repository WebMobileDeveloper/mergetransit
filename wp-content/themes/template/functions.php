<?php
	/**
	 * Script Name: Settings
	 * Script URI: none
	 * Description: A template functions & properties.
	 * Project: Empty Template
	 * Author: Daniel Pietrasik
	 * Author URI: http://danielpietrasik.pl
	 * License: Commercial
	 * License URI: http://danielpietrasik.pl/commercial-license/
	 *
	 * [Last update: 2019-01-23]
	 */


	//Loat the template framework.
	get_template_part (
	
		'lib/framework'
		
	);


	//Load the configuration.
	$configuration = new Configuration();
	
	
	//Initialize the configuration.
	$configuration->init();


	//Remove the async & defer attributes from scripts.
	remove_filter( 'script_loader_tag', array( $configuration, 'set_async_and_defer_attributes_for_scripts' ) );


	/**
	 * Get the formatted date.
	 *
	 * @date (string) - the date.
	 * @format (string) - the format.
	 *
	 * @return (string) - the formatted date.
	 */
	function get_formatted_date( $date, $format = 'd.m.Y' ) {


		//Whether the date is not a timestamp.
		if ( !is_numeric( $date ) ) {


			//Get the date timestamp.
			$date = strtotime (

				$date

			);


		}


		//Return the formatted date.
		return date_i18n (

			$format,

			$date

		);


	}


	/**
	 * Get the formatted hour from date.
	 *
	 * @date (string) - the date.
	 * @format (string) - the format.
	 *
	 * @return (string) - the formatted date.
	 */
	function get_formatted_hour( $date, $format = 'H:i:s' ) {


		//Return the formatted date.
		return get_formatted_date (

			$date,

			$format

		);


	}


	/**
	 * Get the formatted price.
	 *
	 * @number (float) - the unformatted price.
	 * @is_currency_label (boolean) - whether to display a currency label.
	 *
	 * @return (string) - the formatted price.
	 */
	function get_formatted_price( $price ) {


		return number_format (

			round (

				$price,

				2

			),

			2,

			',',

			'&nbsp;'

		) . '&nbsp;PLN';


	}




	/**
	 * Set up the Quality Pixels helpdesk meta box.
	 *
	 * @text (string) [required] - the meta box content.
	 *
	 * @return (string) - the meta box content.
	 */
	function get_meta_box_helpdesk_for_qualitypixels( $text ) {


		//Set up the meta box open tag.
		$return = '<div style="display: flex; flex-wrap: nowrap; align-items: center;">';


			//Whether the image class is defined.
			if ( class_exists( 'Image' ) ) {


				//Load the image.
				$image = new Image (
				
					0
					
				);


				//Add the image url.
				$image->set_url (
				
					IMG_DIR . 'helpdesk.png'
					
				);


				//Set up the image.
				$return .= $image->get_image();
				
				
			}


			//Set up the contact data;
			$return .= '<p style="margin-left: 20px;"><strong style="font-size: 18px;">Quality Pixels</strong><br/>biuro@qualitypixels.pl<br/>42 686 20 10</p>';


		//Set up the meta box close tag.
		$return .= '</div>';


		//Return the meta box content.
		return $return;


	}
	add_filter( 'get_the_meta_box_helpdesk', 'get_meta_box_helpdesk_for_qualitypixels' );



	function get_the_body_class_for_home( $classes ) {
		if ( is_front_page() ) {
			$classes[] = 'home';
		}
		return $classes;
	}
	add_filter( 'get_the_body_class', 'get_the_body_class_for_home' );


