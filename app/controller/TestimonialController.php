<?php
	

	class TestimonialController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		private function addAction(){
			$mdlObj = new Testimonial();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();
			$DM_ID 	= Session::get('DOMAIN_ID');
			$html = $postedValues['data_text'];
			if(!empty($html)){
				$postedValues['data_text'] = stripslashes($html);	
			}		
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(Testimonial::_TABLE));
			$filteredValues['domain_id'] = $DM_ID;
			$filteredValues['date_created'] = DateUtil::curDateDb();
			$last_id = $mdlObj->save( Testimonial::_TABLE , $filteredValues , '' ,$this->_db);
			$dataArray = array('sort_order'=>$last_id);
			$mdlObj->save( Testimonial::_TABLE , $dataArray , "tid='$last_id'" ,$this->_db);
			Message::setResponseMessage("New testimonial added successfully.", 's');
			Request::redirect('manage-testimonials.php?q=show');
		}
		
		private function modifyAction(){
			$tid = $this->getValue('tid');
			$mdlObj = new Testimonial();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$html = $postedValues['data_text'];
			$DM_ID 	= Session::get('DOMAIN_ID');
			if(!empty($html)){
				$postedValues['data_text'] = stripslashes($html);	
			}
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(Testimonial::_TABLE));
			$mdlObj->save( Testimonial::_TABLE , $filteredValues , "tid='$tid' AND domain_id='$DM_ID'" ,$this->_db);
			Message::setResponseMessage("Selected testimonial modify successfully.", 's');
			Request::redirect('manage-testimonials.php?q=show');
		}
		
		private function removeAction(){
			$tid = $this->getValue('tid');
			$DM_ID 	= Session::get('DOMAIN_ID');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Testimonial();
			$mdlObj->remove(Testimonial::_TABLE , "tid='$tid' AND domain_id='$DM_ID'" , $this->_db);
			
			Message::setResponseMessage("Selected testimonial removed successfully.", 's');
			Request::redirect('manage-testimonials.php?q=show');
		}
		
		private function enableAction(){
			$tid = $this->getValue('tid');
			$DM_ID 	= Session::get('DOMAIN_ID');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Testimonial();
			$dateArray = array('status'=>'Y');
			$mdlObj->save( Testimonial::_TABLE , $dateArray ,"tid='$tid' AND domain_id='$DM_ID'" ,$this->_db);
			Message::setResponseMessage("Selected testimonial active successfully!", 's');
			Request::redirect('manage-testimonials.php?q=show');
		}
		
		private function disableAction(){
			$tid = $this->getValue('tid');
			$DM_ID 	= Session::get('DOMAIN_ID');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Testimonial();
			$dateArray = array('status'=>'N');
			$mdlObj->save( Testimonial::_TABLE , $dateArray ,"tid='$tid' AND domain_id='$DM_ID'" ,$this->_db);
			Message::setResponseMessage("Selected testimonial disabled successfully!", 's');
			Request::redirect('manage-testimonials.php?q=show');
		}
		
		private function sortAction(){
			$postedValues = $this->getValues(); 
			
			unset($postedValues['view_state_controller']);
			unset($postedValues['action']);
			
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Testimonial();
			
			foreach($postedValues as $input=>$values){
				$dataarray = explode('_',$input);
				$tid = $dataarray[1];
				$data = array('sort_order'=>$values);
				$mdlObj->save( Testimonial::_TABLE , $data ,"tid='$tid'" ,$this->_db);		
			}
			
			Message::setResponseMessage("Testimonials sort order saved successfully.", 's');
			header("Location: {$_SERVER['HTTP_REFERER']}");	
		}
		
		
		
	} //$
	
	
	
?>