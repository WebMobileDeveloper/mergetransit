<html>
 <head>
  <title>OD Home Moving Service PHP</title>
 </head>
 <body>
  <h1>Reconsign</h1>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

	function parseSoapResults($results) {
		
	  if($results->return->success){
		echo '<h3>SUCCESS</h3>';
		echo 'Response:<pre>';
		print_r($results->return);
		echo '</pre>';
	  } else {
		echo '<h3>FAILURE</h3>';
		$errors=$results->return->errorMessages;
		echo 'Errors:<pre>';
		print_r($errors);
		echo '</pre>';
	  }	
	}

  ///require_once "common.inc";

  // Pull in common configuration for the services
  //$ini = parse_ini_file("configuration.ini");

  // Web Service is protected by BASIC authentication - odfl4me username and password are required
	// note code below is sample code only - we recommend you keep your authentication information secure
	// 401 unauthorized error means username or password were invalid
	// 403 forbidden means the odfl4me user does not have access to bol portions of the site
	$optionsArray=array('login'=>'SUDDATHS1','password'=>'iloveod');	//fill in with your credentials
	$soapClient = new SoapClient("https://www.odfl.com/wsODMove_v2/ODMoveService/WEB-INF/wsdl/ODMoveService.wsdl",$optionsArray);

  // Fill in request fields
  
  $params = new stdClass();
  $params->arg0 = new stdClass();

  // Addrees for the reconsignment
  $destinationAddress = new stdClass();
  $destinationAddress->address1 = 'bill to addr1';
  $destinationAddress->address2 = '';
  $destinationAddress->city = 'Beverly Hills';
  $destinationAddress->country = 'USA';
  $destinationAddress->name = 'dest name';
  $destinationAddress->postalCode = '90212';
  $destinationAddress->state = 'CA';
  $params->arg0->address=$destinationAddress;   

  // Pro number returned from booking request
  $params->arg0->proNumber='77741859894';         
                
  $results = $soapClient->reconsign($params);
  var_dump ($results);
  ?>
 </body>
</html>