<?php 
	
	session_start();
	
	define("BASE_PATH", dirname(dirname(__FILE__)));
	
	set_include_path(
		realpath(BASE_PATH.'/devkit/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/config/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/model/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/system_model/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/controller/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/system_controller/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/emls/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/cron/') . PATH_SEPARATOR .
		get_include_path()
	);
	
	function __autoload($className) {
		require "$className.php";
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*define("BASE_PATH", dirname(dirname(__FILE__)));
	$developmentKit = array('Util.php','Core.php','Helper.php');
	
	foreach($developmentKit as $files){
		require_once(BASE_PATH.'/devkit/'.$files);
	}*/
	