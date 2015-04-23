<?php

	class ModulePagesController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		// actions
		private function pagetextAction(){
			
			$postedValues 	= $this->getValues();
			$pageid 		= $this->getValue('pageid');
			$DM_ID			= Session::get('DOMAIN_ID');
			$this->_db 		= new Pdodb($this->_dbinfo);
			$mdlObj 		= new ModulePages();
			
			$data = array('page_title'=>$postedValues['page_title'], 'page_text'=>$postedValues['page_text']);
			$mdlObj->save( ModulePages::_TABLE , $data , "page_id='$pageid' AND domain_id='$DM_ID'" , $this->_db );
			
			Request::redirect("manage-contents.php?q=pages&step=pageseo&pid=".$pageid);
				
		}
		
		
		private function pageseoAction(){
			
			
			$postedValues 	= $this->getValues();
			$pageid 		= $this->getValue('pageid');
			$DM_ID			= Session::get('DOMAIN_ID');
			$this->_db 		= new Pdodb($this->_dbinfo);
			$mdlObj 		= new ModulePages();
			
			
			$dataContent = array('head_title'=>$postedValues['head_title'] ,'head_keywords'=>$postedValues['head_keywords'] ,'head_description'=>$postedValues['head_description']);
			$mdlObj->save( ModulePages::_TABLE , $dataContent , "page_id='$pageid' AND domain_id='$DM_ID'" , $this->_db);
			
			Message::setResponseMessage("Selected page modified successfully.", 's');
			Request::redirect("manage-contents.php?q=show");
				
		}
		
		
		
	}