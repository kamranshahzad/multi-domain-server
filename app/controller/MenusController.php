<?php
	

	class MenusController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		private function addAction(){
			$mdlObj = new Menus();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(Menus::_TABLE));
			$filteredValues['date_created'] = DateUtil::curDateDb();
			$mdlObj->save( Menus::_TABLE , $filteredValues , '' ,$this->_db);
			Message::setResponseMessage("New menu added successfully.", 's');
			Request::redirect('manage-menus.php?q=show');
		}
		
		private function modifyAction(){
			$mid = $this->getValue('mid');
			$mdlObj = new Menus();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(Menus::_TABLE));
			$mdlObj->save( Menus::_TABLE , $filteredValues , "menu_id='$mid'" ,$this->_db);
			Message::setResponseMessage("Selected menu item modify successfully.", 's');
			Request::redirect('manage-menus.php?q=show');
		}
		
		private function removeAction(){
			$mid = $this->getValue('mid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Menus();
			$mdlObj->remove(Menus::_TABLE , "menu_id='$mid'" , $this->_db);
			
			Message::setResponseMessage("Selected menu item removed successfully.", 's');
			Request::redirect('manage-menus.php?q=show');
		}
		
		
		private function enableAction(){
			$mid = $this->getValue('mid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Menus();
			$dateArray = array('status'=>'Y');
			$mdlObj->save( Menus::_TABLE , $dateArray ,"menu_id='$mid'" ,$this->_db);
			Message::setResponseMessage("Selected menu item active successfully!", 's');
			Request::redirect('manage-menus.php?q=show');
		}
		
		private function disableAction(){
			$mid = $this->getValue('mid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Menus();
			$dateArray = array('status'=>'N');
			$mdlObj->save( Menus::_TABLE , $dateArray ,"menu_id='$mid'" ,$this->_db);
			Message::setResponseMessage("Selected menu item disabled successfully!", 's');
			Request::redirect('manage-menus.php?q=show');
		}
		
		
		private function sortAction(){
			$postedValues = $this->getValues(); 
			
			unset($postedValues['view_state_controller']);
			unset($postedValues['action']);
			
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Menus();
			
			foreach($postedValues as $input=>$values){
				$dataarray = explode('_',$input);
				$mid = $dataarray[1];
				$data = array('sort_order'=>$values);
				$mdlObj->save( Menus::_TABLE , $data ,"menu_id='$mid'" ,$this->_db);		
			}
			
			Message::setResponseMessage("Menus sort order saved successfully.", 's');
			header("Location: {$_SERVER['HTTP_REFERER']}");	
		}
	
			
	} //$
	
	
	
?>