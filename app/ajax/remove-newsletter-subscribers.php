<?php
	
	require_once("../../devkit/init.php");
	
	$ids = $_GET['subscriberids'];
	
	$letterObject = new Newsletter();
	$letterObject->removeSubscribers($ids);
	
	$outputHtml = '';
	$outputHtml = $letterObject->drawGrid();
	echo $outputHtml;