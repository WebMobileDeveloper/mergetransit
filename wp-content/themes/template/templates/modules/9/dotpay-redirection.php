<?php
	/**
	 * The dotpay redirection template.
	 *
	 * @see https://ssl.dotpay.pl/files/dotpay_instrukcja_techniczna.pdf
	 *
	 * [Last update: 2019-03-07]
	 */

	
	//Whether the payments page is defined.
	if ( defined( 'PAYMENTS_POST_ID' ) ) {


		//Get the connection type.
		$connection_type = get_field (

			'dotpay-connection-type',

			PAYMENTS_POST_ID

		);


		//The connection type conditions.
		switch ( $connection_type ) {


			//The production.
			case 'production' : {


				//Set up the developer mode.
				$url = 'https://ssl.dotpay.pl/t2/';


				break;

			
			//The development.
			} default : {


				//Set up the development mode.
				$url = 'https://ssl.dotpay.pl/test_payment/';


			}


		}


		//Get the api version.
		$api_version = get_field (

			'dotpay-api-version',

			PAYMENTS_POST_ID

		);


		//Set up the api version.
		$url .= '?api_version=' . urlencode( $api_version ) . '&type=3';


		//Get the vendor id.
		$vendor_id = get_field (

			'dotpay-vendor-id',

			PAYMENTS_POST_ID

		);


		//Set up the vendor id.
		$url .= '&id=' . urlencode( $vendor_id );


		//Get the currency.
		$currency = get_field (

			'dotpay-currency',

			PAYMENTS_POST_ID

		);


		//Set up the currency.
		$url .= '&currency=' . urlencode( $currency );


		//Get the description.
		$description = get_field (

			'dotpay-description',

			PAYMENTS_POST_ID

		);


		//Set up the description.
		$url .= '&description=' . urlencode( $description );


		//Get the language.
		$language = get_field (

			'dotpay-language',

			PAYMENTS_POST_ID

		);


		//Set up the language.
		$url .= '&country=' . urlencode( $language );


		//Get the payment method cache.
		$is_payment_cache = get_field (

			'dotpay-ignore-last-selected-payment-type',

			PAYMENTS_POST_ID

		);


		//Get the regulations status.
		$is_auto_regulations = get_field (

			'dotpay-auto-regulations',

			PAYMENTS_POST_ID

		);


		//Set up the regulations status.
		$url .= '&bylaw=' . urlencode( $is_auto_regulations );


		//Get the personal data status.
		$is_auto_personal_data = get_field (

			'dotpay-personal-data',

			PAYMENTS_POST_ID

		);


		//Set up the personal data status.
		$url .= '&personal_data=' . urlencode( $is_auto_personal_data );


		//Get the vendor name.
		$vendor_name = get_field (

			'dotpay-vendor-name',

			PAYMENTS_POST_ID

		);


		//Set up the vendor name.
		$url .= '&p_info=' . urlencode( $vendor_name );


		//Get the vendor email.
		$vendor_email = get_field (

			'dotpay-vendor-email',

			PAYMENTS_POST_ID

		);


		//Set up the vendor email.
		$url .= '&p_email=' . urlencode( $vendor_email );


		//Get the amount.
		$amount = 0;


		//Set up the amount.
		$url .= '&amount=' . urlencode( $amount );


		//Get the back button url.
		$back_button_url = '';


		//Set up the back button url.
		$url .= '&URL=' . urlencode( $back_button_url );


		//Get the back button label.
		$back_button_label = '';


		//Set up the back button label.
		$url .= '&buttontext=' . urlencode( $back_button_label );


		//Get the buyer first name.
		$buyer_first_name = '';


		//Set up the buyer first name.
		$url .= '&firstname=' . urlencode( $buyer_first_name );


		//Get the buyer surname.
		$buyer_surname = '';


		//Set up the buyer surname.
		$url .= '&surname=' . urlencode( $buyer_surname );


		//Get the buyer email.
		$buyer_email = '';


		//Set up the buyer email.
		$url .= '&email=' . urlencode( $buyer_email );


		//Get the control var.
		$control = '';


		//Set up the control var.
		$url .= '&control=' . urlencode( $control );


		//Get the verify url.
		$verify_url = '';


		//Set up the verify url.
		$url .= '&URLC=' . urlencode( $verify_url );


	}


?>