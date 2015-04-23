<?php
	
	require("PHPMailer.php");
	
	class EmailWorker {
		
		private $emlObject = NULL;
		private $_html     = '';
		private $_subject  = '';
		private $addressArray = array();
		private $ccAddressArray = array();
		private $bccAddressArray = array();
		private $fromAddressArray = array();
		public $IS_SMTP			= TRUE;
		public $SMTP_INFO			= array();
		public $CURRENT_STATUS    = 'INIT';
		
		
		public function __construct(){
			try{
				$this->emlObject = NULL;
				$this->emlObject = new PHPMailer(true);
			}catch(Exception $e){
				$this->CURRENT_STATUS = "Can't access PHPMailer class";
				echo $e->getMessage();
			}
		}
		
		public function sendMail(){
			
			if(count($this->addressArray) > 0){  // add address
				foreach($this->addressArray  as $array){
					$this->emlObject->AddAddress( $array['email'] , $array['name'] );
				}
			}
			if(count($this->ccAddressArray) > 0){ // ccc address
				foreach($this->ccAddressArray  as $array){
					$this->emlObject->AddCC( $array['email'] , $array['name'] );
				}
			}
			if(count($this->bccAddressArray) > 0){ // bcc address
				foreach($this->bccAddressArray  as $array){
					$this->emlObject->AddBCC( $array['email'] , $array['name'] );
				}
			}
			
			$fromAddressArray = $this->getFrom();
			if(count($fromAddressArray)>0){ 
				$this->mailObj->SetFrom( $fromAddressArray['email'], $fromAddressArray['name'] );
			}
			
			$this->emlObject->Subject = $this->getSubject();
			
			try {
				$this->emlObject->MsgHTML($this->getHtml());
				$this->emlObject->Send();
				$this->CURRENT_STATUS = 'DONE';
			} catch (phpmailerException $e) {
				$this->CURRENT_STATUS = $e->getMessage();
			} catch (Exception $e){
				$this->CURRENT_STATUS = $e->getMessage();
			}
			
		}
		
		public function setFrom($fromaddressArray= array()){
			if(!empty($fromaddressArray)){
				$this->fromAddressArray = $fromaddressArray;	
			}
		}
		
		public function getFrom(){
			return $this->fromAddressArray;	
		}
		
		public function addSubject($subjecttext = ''){
			if(!empty($subjecttext)){
				$this->_subject = $subjecttext;	
			}
		}
		
		public function getSubject(){
			return $this->_subject;
		}
		
		public function addAddress($addressArray = array()){
			if(!empty($addressArray)){
				if(is_array($addressArray)){
					if(array_key_exists('email',$addressArray) && array_key_exists('name',$addressArray)){
						if($this->isValid($addressArray['email'])){
							$this->addressArray['email'] = $addressArray['email'];
							$this->addressArray['name'] = $addressArray['name'];
						}
					}else{
						foreach($addressArray as $array){
							if($this->isValid($array['email'])){
								$this->addressArray[] = array('email'=>$array['email'], 'name'=>$array['name']);
							}
						}
					}
				}
			}
		}
		
		public function addCCCAddress($addressArray = array()){
			if(!empty($addressArray)){
				if(is_array($addressArray)){
					foreach($addressArray as $name=>$emailids ){
						if($this->isValid($emailids)){
							$array = array('name'=>$name , 'email'=>$emailids);
							$this->ccAddressArray[] = $array;	
						}
					}
				}
			}
		}
		
		public function addBCCAddress($addressArray = array()){
			if(!empty($addressArray)){
				if(is_array($addressArray)){
					foreach($addressArray as $name=>$emailids ){
						if($this->isValid($emailids)){
							$array = array('name'=>$name , 'email'=>$emailids);
							$this->bccAddressArray[] = $array;	
						}
					}
				}
			}
		}
		
		public function setHtml($bodyHtml=''){
			if(!empty($bodyHtml)){
				$this->_html = $bodyHtml;	
			}
		}
		
		public function getHtml(){
			return $this->_html;
		}

		private function isValid($emailtext=''){
			if(!empty($emailtext)){
				if(filter_var($emailtext , FILTER_VALIDATE_EMAIL)) {
					return true;
				}
				return false;
			}
			throw new Exception("Invalid email address found.".$emailtext);
		}
		
		public function debug(){
			echo '<pre>';
			print_r($this->addressArray);
			echo '<pre>';	
		}
		
		
	} //$
	
	
	
	//Usage
	
	
	$multiUsers = array(
						array('name'=>'Kamran','email'=>'kamran@medialinkers.com'),
						array('name'=>'Jawad','email'=>'jawad@medialinkers.com')
					);
	$singleUser = array('email'=>'info@wsnielsen.com' , 'name'=>'wsnielsen system');
	
	$mlObject = new EmailWorker();
	$mlObject->addSubject("Contact Us Form");
	$mlObject->addAddress($multiUsers);
	
	$mlObject->debug();
	//$mlObject->sendMail();
	
	
	
	
	
	

		
		
		
	