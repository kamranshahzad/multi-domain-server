<?php
	

	class DomainsController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		private function addAction(){
			
			$_callback 	= 'e';
			$_errorMsg  = '';
			
			$mdlObj = new Domains();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();
			
			$ftpHostname = $postedValues['domain_url'];
			$ftpUsername = $postedValues['ftp_username'];
			$ftpPassword = $postedValues['ftp_password'];
			
			$ftp_detail = array('ftphost'=>$ftpHostname,'ftpusername'=>$ftpUsername,'ftppassword'=>$ftpPassword);
			

			
			$filteredValues = $mdlObj->filter( $postedValues , $this->_db->columns(Domains::_TABLE) );
			$filteredValues['date_modified'] 	= DateUtil::curDateDb();
			$filteredValues['date_created'] 	= DateUtil::curDateDb();
			
			$last_id = $mdlObj->save( Domains::_TABLE , $filteredValues , '' ,$this->_db);
			
			$keyArray = array('security_key'=>$last_id.'@'.NumberHlp::random(16));
			$mdlObj->save( Domains::_TABLE , $keyArray , "domain_id='$last_id'" ,$this->_db);
			
			
			if(FTPWorker::Ping($ftp_detail)){
				$ftp = new FTPWorker($ftp_detail,$last_id, TRUE);
				
				$_errorMsg = "New domain added successfully.";
				$_callback = 's';	
			}else{
				
				$_errorMsg = "FTP information is incorrect, please enter correct informtion.";
			}
			
			
			Message::setResponseMessage($_errorMsg , $_callback );
			if($_callback == 's'){
				Request::redirect('manage-domains.php?q=show');
			}else{
				Request::redirect("manage-domains.php?q=modify&did=$last_id");
			}
			
		}
		
		private function modifyAction(){
			
			$mid = $this->getValue('mid');
			$mdlObj = new Media();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$html = $postedValues['description'];
			if(!empty($html)){
				$postedValues['description'] = stripslashes($html);	
			}
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(Media::_TABLE));
			$mdlObj->save( Media::_TABLE , $filteredValues , "media_id='$mid'" ,$this->_db);
			Message::setResponseMessage("Selected media modify successfully.", 's');
			Request::redirect('manage-media.php?q=show');
		}
		
		private function removeAction(){
			$did = $this->getValue('did');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Domains();
			
			$ftp = new FTPWorker($mdlObj->fetchDomainFTP($did) ,$did );
			$ftp->removedomain();
			
			$mdlObj->remove(Domains::_TABLE , "domain_id='$did'" , $this->_db);
			
			Message::setResponseMessage("Selected domain removed successfully.", 's');
			Request::redirect('manage-domains.php?q=show');
			
		}
		
		private function enableAction(){
			$id = $this->getValue('did');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Domains();
			$dateArray = array('access_enable'=>'Y');
			$mdlObj->save( Domains::_TABLE , $dateArray ,"domain_id='$id'" ,$this->_db);
			Message::setResponseMessage("Selected domain enable successfully!", 's');
			Request::redirect('manage-domains.php?q=show');
		}
		
		private function disableAction(){
			$id = $this->getValue('did');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Domains();
			$dateArray = array('access_enable'=>'N');
			$mdlObj->save( Domains::_TABLE , $dateArray ,"domain_id='$id'" ,$this->_db);
			Message::setResponseMessage("Selected domain disabled access successfully!", 's');
			Request::redirect('manage-domains.php?q=show');
		}
		
		
		private function switchdomainAction(){
	
			$DOMAIN_ID = $this->getValue('domain_id');
			
			if(empty($DOMAIN_ID)){
				Request::redirect('dashboard.php');
				exit();
			}
			if(Session::isExist('DOMAIN_ID')){
				Session::dispose(array('DOMAIN_ID'));	
			}
			Session::put(array('DOMAIN_ID'=>$DOMAIN_ID));
			Request::redirect('home.php');
		}
		
		
	} //$
	
	
	
?>