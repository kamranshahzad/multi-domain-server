<?php
	

	class BlockPagesController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function pagetextAction(){
			
			$postedValues 	= $this->getValues();
			$pageid 		= $this->getValue('pageid');
			$DM_ID 			= Session::get('DOMAIN_ID');
			$this->_db 		= new Pdodb($this->_dbinfo);
			$mdlObj 		= new BlockPages();
			
			$data = array('page_title'=>$postedValues['page_title'], 'page_text'=>$postedValues['page_text']);
			$mdlObj->save( 'ml_block_pages' , $data , "page_id='$pageid' AND domain_id='$DM_ID'" , $this->_db );
			
			
			Request::redirect("home-page.php?q=modify&step=pageseo&pid=".$pageid);
				
		}
		
		
		private function pageseoAction(){
			
			
			$postedValues 	= $this->getValues();
			$pageid 		= $this->getValue('pageid');
			$DM_ID 			= Session::get('DOMAIN_ID');
			$this->_db 		= new Pdodb($this->_dbinfo);
			$mdlObj 		= new BlockPages();
			
			
			$dataContent = array('page_url'=>$postedValues['page_url'],'head_title'=>$postedValues['head_title'] ,'head_keywords'=>$postedValues['head_keywords'] ,'head_description'=>$postedValues['head_description']);
			$mdlObj->save( 'ml_block_pages' , $dataContent , "page_id='$pageid' AND domain_id='$DM_ID'" , $this->_db);
			
			Message::setResponseMessage("Selected page modified successfully.", 's');
			Request::redirect("home-page.php?q=show");
				
		}
		
		
		
		
	
			
	} //$
	
	
	
?>