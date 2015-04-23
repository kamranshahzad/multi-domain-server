<?php

	
class NewsletterController extends Controller {
	
	private $_dbinfo;
	private $_db;
		
	function __construct() {
		$configObj 	= new config();
		$this->_dbinfo 	= $configObj->getDbConfig();
		parent::__construct();			
		call_user_func(array($this, $this->getAction()));
	}
	
	private function findAction(){
		
		$email = $this->getValue('emailtext');
		Request::redirect("newsletter-emails.php?q=show&email=".$email);	
	}
	
	
	private function subscribeAction(){
		
		if (strlen(session_id()) < 1) {
				session_start();
		}
		
		$this->_db = new Pdodb($this->_dbinfo);
		
		$name = $this->getValue('nameField');
		$email = $this->getValue('emailField');
		$IP_ADDRESS = Request::ip();
				
		$letterObject = new Newsletter();
		
		$valid_time = true;
		if(isset($_SESSION['timestmc'])){
			$mailtime = DateUtil::eventTime($_SESSION['timestmc'], 20);
			if($mailtime == 'false') $valid_time = false;
		}
		
		
		if($valid_time == true){
			if($letterObject->checkEmail($email)){
				// Already exist in our db
				Message::setResponseJsMessage("You have already subscribed.",'s');
				header("Location: {$_SERVER['HTTP_REFERER']}");
			}else{
				$data = array('name'=>$name,'email'=>$email,'ip_address'=>$IP_ADDRESS,'date_created'=>DateUtil::curDateDb());	
				$letterObject->save( Newsletter::_TABLE , $data , '' ,$this->_db);
				$_SESSION['timestmc'] = date('H:i:s');
				Message::setResponseJsMessage("Thank you for subscribing our newsletter.",'s');
				header("Location: {$_SERVER['HTTP_REFERER']}");
			}
		}else{
			Message::setResponseJsMessage("You have already subscribed.",'s');
			header("Location: {$_SERVER['HTTP_REFERER']}");	
		}
	}
	
	
	private function exportAction(){
		//$sql = "SELECT * FROM  listing";
		
		$this->_db = new Pdodb($this->_dbinfo);
		$sqlDataArray = $this->_db->select(Newsletter::_TABLE , "letter_id IS NOT NULL ORDER by letter_id DESC");
		$dataArr = array();
		$pointer = 1;
		foreach($sqlDataArray as $array){
			$arr = array('#'=>$pointer, 
						'Name'=>$array['name'],
						'Email'=>$array['email'],
						'IP Address'=> $array['ip_address'],
						'Created Date'=>$array['date_created'],
						);
			$dataArr[] = $arr;	
		}

		
		$contents = $this->getExcelData($dataArr);
		$filename = "Newletter Subscribers(".date("F-j-Y").").xls";
		header ("Content-type: application/octet-stream");
		header ("Content-Disposition: attachment; filename=".$filename);
		$expiredate = time() + 30;
		$expireheader = "Expires: ".gmdate("D, d M Y G:i:s",$expiredate)." GMT";
		header ($expireheader);
		echo $contents;
		exit;	
	}
	
	
	private function subscribedAction(){
			$lid = $this->getValue('lid');
			
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Newsletter();
			$dateArray = array('subscribed'=>'Yes');
			$mdlObj->save( Newsletter::_TABLE , $dateArray , "letter_id='$lid'" ,$this->_db);
			Message::setResponseMessage("Selected user subscribed successfully!", 's');
			Request::redirect('newsletter-emails.php?q=show');
	}
		
	private function unsubscribeAction(){
			$lid = $this->getValue('lid');
			$this->_db = new Pdodb($this->_dbinfo);

			$mdlObj = new Newsletter();
			$dateArray = array('subscribed'=>'No');
			$mdlObj->save( Newsletter::_TABLE , $dateArray ,"letter_id='$lid'" ,$this->_db);
			Message::setResponseMessage("Selected user unsubscribed successfully!", 's');
			Request::redirect('newsletter-emails.php?q=show');
	}
	
	public function getExcelData($data){
		$retval = "";
		if (is_array($data)  && !empty($data))
		{
		 $row = 0;
		 foreach(array_values($data) as $_data){
		  if (is_array($_data) && !empty($_data))
		  {
			  if ($row == 0)
			  {
				  $retval = implode("\t",array_keys($_data));
				  $retval .= "\n";
			  }
				  $retval .= implode("\t",array_values($_data));
				  $retval .= "\n";
				  $row++;
		   }
		 }
		}
	  return $retval;
	 }
	
	
	
	
} //$