<?php

	
class ContactusController extends Controller {
	
	function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
	}
	
	
	private function sendAction(){
		
		if (strlen(session_id()) < 1) {
				session_start();
		}
		
		$valid_time = true;
		if(isset($_SESSION['timestmc'])){
			$mailtime = DateUtil::eventTime($_SESSION['timestmc'], 20);
			if($mailtime == 'false') $valid_time = false;
		}
			
		if($valid_time == true){
					
			$postedValues = $this->getValues();
			$postedValues['ip'] = Request::ip();
			
			$boot = new bootstrap();
			$admininfo = $boot->getAdminInfo();
			
			$emlObject = new contactusEml( $postedValues , $admininfo );
			
			/*
			$emlObject = new contactusEml( $postedValues );
			$emlObject->send();
			*/
			
			//$_SESSION['timestmc'] = date('H:i:s');
			
			Message::setResponseMessage("Thank you for providing us required information.",'s');
			
		}else{
			Message::setResponseMessage("Wrong security code , please try again.",'e');
		}
		
		Request::redirect('contact-us.php','site');	
				
	}
	
	
} //$