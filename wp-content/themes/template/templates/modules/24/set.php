<?php
	/**
	 * Set up the cookies.
	 *
	 * [Last update: 2018-09-13]
	 */


	//Set up the cookies session.
	$_SESSION[ 'cookies' ] = true;


	//Set up the cookies cookie.
	setcookie (
	
		'cookies',
	
		'true',
		
		0,
		
		'/'
		
	);


?>