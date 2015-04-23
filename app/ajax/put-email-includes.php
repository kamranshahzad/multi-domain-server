<?php
	require_once("../../devkit/init.php");
	
	
	$type 		= $_POST['Type'];
	$emailText 	= $_POST['EmlText'];
	
	$response  = array();
	$setObject = new Settings();
	$setObject->put( $type , $emailText );
	
	$response['response'] = TRUE;
	$response['whomuch'] = $setObject->countemails($type);
	echo json_encode($response);
	
	//echo $setObject->drawEmailsList($type);