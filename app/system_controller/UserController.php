<?php
	

	class UserController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function loginAction(){
			
			$this->_db = new Pdodb($this->_dbinfo);
			
			$username = $this->getValue('username');
			$password = $this->getValue('password');
			
			$obj = new User();
			if($obj->validateUser($username,$password , $this->_db)){
				Request::redirect('dashboard.php');
			}else{
				Message::setResponseMessage("Invalid username/password!",'e');
				Request::redirect('index.php');	
			}
		}
		
		
		private function changeinfoAction(){
			
			$currentUserId = Session::get('USERID');
			
			$this->_db = new Pdodb($this->_dbinfo);
			$userObject = new User();
			$userArray = $userObject->fetchById($currentUserId);
			$dbCurrentPassword = $userArray['password'];
			
			
			$email     		= $this->getValue('email');
			$firstname     	= $this->getValue('firstname');
			$lastname     	= $this->getValue('lastname');
			$phone     		= $this->getValue('phone');
			$cpassword 		= $this->getValue('cpassword');
			$npassword 		= $this->getValue('npassword');

			
			if($dbCurrentPassword == $cpassword){
				
				$dataArray = array();
				$dataArray['email'] 		= $email;
				$dataArray['firstname'] 	= $firstname;
				$dataArray['lastname'] 		= $lastname;
				$dataArray['phone'] 		= $phone;
				if($npassword != ''){
					$dataArray['password'] = $npassword;
				}
				
				$userObject->save(User::_TABLE , $dataArray , "uid='$currentUserId'" , $this->_db);
				Message::setResponseMessage("User settings saved successfully!", 's');
				Request::redirect('my-account.php');
				
			}else{
				Message::setResponseMessage("Invalid current password!",'e');
				Request::redirect('my-account.php');	
			}
		}
		
		
		private function logoutAction(){
			$logObject = new UserLog();
			$logObject->logOut(Session::get('USERID'), Session::get('SESSID'),Session::get('USERNAME'));
			Session::dispose(array('USERID','USERNAME','USEREMAIL','SESSID'));
			Request::redirect('index.php');
		}
		
		
		private function emptyAction(){
			$this->_db 		= new Pdodb($this->_dbinfo);
			$logObject 		= new UserLog();
			$totalRecords	= $logObject->countLogRecords(); 
			$pointer 		= $totalRecords-1;
			$dataArray = $this->_db->select(UserLog::_TABLE , "id IS NOT NULL LIMIT 0,$pointer");
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$this->_db->delete(UserLog::_TABLE, "id='".$array['id']."'");	
				}
			}
			//$this->_db->run("TRUNCATE users_log");
			Message::setResponseMessage("User log history successfully removed.", 's');
			Request::redirect('view-logined-log.php');
		}
		
		
		// sub user actions
		
		private function addAction(){
			$mdlObj = new User();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(User::_TABLE));
			$filteredValues['created_date'] = DateUtil::curDateDb();
			$mdlObj->save( User::_TABLE , $filteredValues , '' ,$this->_db);
			
			/*
				$emlObject = new createuserEml();
			*/
			
			Message::setResponseMessage("New user created successfully.", 's');
			Request::redirect('user-accounts.php?q=show');
		}
		
		private function modifyAction(){
			$uid = $this->getValue('uid');
			$mdlObj = new User();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(User::_TABLE));
			$mdlObj->save( User::_TABLE , $filteredValues , "uid='$uid'" ,$this->_db);
			Message::setResponseMessage("Selected user modify successfully.", 's');
			Request::redirect('user-accounts.php?q=show');
		}
		
		private function removeAction(){
			$uid = $this->getValue('uid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new User();
			$mdlObj->remove(User::_TABLE , "uid='$uid'" , $this->_db);
			
			Message::setResponseMessage("Selected user removed successfully.", 's');
			Request::redirect('user-accounts.php?q=show');
		}
		
		
		private function enableAction(){
			$uid = $this->getValue('uid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new User();
			$dateArray = array('active'=>'Y');
			$mdlObj->save( User::_TABLE , $dateArray ,"uid='$uid'" ,$this->_db);
			Message::setResponseMessage("Selected user activated successfully!", 's');
			Request::redirect('user-accounts.php?q=show');
		}
		
		private function disableAction(){
			$uid = $this->getValue('uid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new User();
			$dateArray = array('active'=>'N');
			$mdlObj->save( User::_TABLE , $dateArray ,"uid='$uid'" ,$this->_db);
			Message::setResponseMessage("Selected user disabled successfully!", 's');
			Request::redirect('user-accounts.php?q=show');
		}
		
		
		private function forgetpasswordAction(){
			if (strlen(session_id()) < 1) {
					session_start();
			}
			
			$valid_time = true;
			if(isset($_SESSION['timestmc'])){
				$mailtime = DateUtil::eventTime($_SESSION['timestmc'], 20);
				if($mailtime == 'false') $valid_time = false;
			}
				
			if($valid_time == true){
			
				$email = $this->getValue('email');
				
				$mdlObject = new User();
				$dataArray = $mdlObject->forgetpasswordFind($email);
				
				if(count($dataArray) > 0){
					
					$data = array('password'=>$dataArray['password'] ,'username'=>$dataArray['username'] );
					$senderEmail = array('email'=>$dataArray['email'],'name'=>$dataArray['firstname'].' '.$dataArray['lastname'] );       
					$emlObject  = new forgetpasswordEml($senderEmail , $data );
					$emlObject->send();
					$_SESSION['timestmc'] = date('H:i:s');
					Message::setResponseMessage("Your password sended successfully on your email");
					
				}else{
					Message::setResponseMessage("No user found with this email address");
					
				}
			}else{
				Message::setResponseMessage("Wrong security code , please try again.");
			}
			Request::redirect('forget-password.php');

		}
		
		
		
		
		
		
	} //$
	
	
	
?>