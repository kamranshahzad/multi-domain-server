<?php
	require_once("../../devkit/init.php");
	
	$pagename 	= $_POST['PageName'];
	$cid 		= $_POST['CID'];
	$menuid 	= $_POST['MenuID'];
	$outputArray = array();
	$contentObject = new Contents();
	
	
	
	if(checkPhisicalPage($pagename)){
		$outputArray['response'] = 'p';	
	}else{
		if(!$contentObject->isExistsInMenuItem($pagename , $menuid)){
			$outputArray['response'] = 'o';	
		}else{
			$outputArray['response'] = 'a';	
		}		
	}
	
	echo json_encode($outputArray);
	
	
	
	
	
	
	function checkPhisicalPage($pagename){
		$config = new config();
		$array = $config->restrictedPages;
		foreach($array as $page){
			if(strcasecmp($pagename, $page) == 0) {return true;}
		}
		return false;
	}