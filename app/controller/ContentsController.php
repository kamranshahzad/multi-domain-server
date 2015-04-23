<?php
	

	class ContentsController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		private function addAction(){
			$contentObj = new Contents();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();
			$leftMenuId 	= $postedValues['left_menu_id'];
			$bottomMenuId 	= $postedValues['bottom_menu_id'];
			$html = $postedValues['page_text'];
			
			
			if(!empty($html)){
				$postedValues['page_text'] = stripslashes($html);	
			}
			$filteredValues = $contentObj->filter($postedValues,$this->_db->columns(Contents::_TABLE));
			//$filteredValues['content_url'] = StringUtil::toAscii($postedValues['page_title']);
			$filteredValues['date_created'] = DateUtil::curDateDb();
			$filteredValues['content_type'] = 'C';
			
			
			$DM_ID 	= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
			
				$contentObj->save( Contents::_TABLE , $filteredValues , '' ,$this->_db);
				$contentObj->scanAndBuildSitemap();
				$ftp->updateSitemap();
				
				exit();
				Message::setResponseMessage("New content added successfully.", 's');
				Request::redirect('manage-contents.php?q=show');
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-contents.php?q=show");	
			}
		}
		
		private function modifyAction(){
			$contentObj = new Contents();
			$cid = $this->getValue('cid');
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();
			$leftMenuId 	= $postedValues['left_menu_id'];
			$bottomMenuId 	= $postedValues['bottom_menu_id'];
			$html = $postedValues['page_text'];
			if(!empty($html)){
				$postedValues['page_text'] = stripslashes($html);	
			}
			$filteredValues = $contentObj->filter($postedValues,$this->_db->columns(Contents::_TABLE));
			//$filteredValues['content_url'] = StringUtil::toAscii($postedValues['page_title']);
			$contentObj->save( Contents::_TABLE , $filteredValues , "content_id='$cid'" ,$this->_db);
			
			//$contentObj->buildSiteMap();
			
			Message::setResponseMessage("Selected content modified successfully.", 's');
			Request::redirect('manage-contents.php?q=show');
		}
		
		private function removeAction(){
			
			$cid = $this->getValue('cid');
			$mid = $this->getValue('mid');
			$DM_ID 		= Session::get('DOMAIN_ID');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Contents();

			
			$DM_ID 	= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				
				$mdlObj->remove(Contents::_TABLE , "content_id='$cid'" , $this->_db);
				$mdlObj->remove(Menus::_TABLE , "menu_id='$mid' AND domain_id='$DM_ID'" , $this->_db);
				
				$mdlObj->scanAndBuildSitemap();
				$ftp->updateSitemap();
				
				Message::setResponseMessage("Selected content removed successfully.", 's');
				Request::redirect("manage-contents.php?q=show");
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-contents.php?q=show");	
			}
			
		}
		
		private function placementAction(){
			
			$cid 		= $this->getValue('cid');
			$menuid 	= $this->getValue('menu_id');
			$pagename	= $this->getValue('page_name');
			$DM_ID 		= Session::get('DOMAIN_ID');
			$postedValues = $this->getValues();
			
			$menusoptions = array();
			$menustring   = '';
			if(array_key_exists('leftmenu',$postedValues)){
				$menusoptions[] = 'left';
			}
			if(array_key_exists('footermenu',$postedValues)){
				$menusoptions[] = 'footer';
			}
			if(count($menusoptions) > 0){
				$menustring = join(',',$menusoptions);	
			}
			
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Contents();
			
			$DM_ID 	= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );

			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				if($cid == 0){
					$data = array('menu_label'=>$pagename , 'domain_id' => $DM_ID , 'menu_url'=>StringUtil::toAscii($pagename) ,'menu_types'=>$menustring ,'leftmenu_sort_order'=>999,'footermenu_sort_order'=>999);
					$menu_id = $mdlObj->save( 'ml_menus' , $data  , '' , $this->_db);
					$data2 = array('menu_id'=>$menu_id , 'date_created' => DateUtil::curDateDb());
					$cid = $mdlObj->save( 'ml_contents' , $data2 , '' , $this->_db );
					
					// update placement sort
					$sortvalues = array('leftmenu_sort_order'=>$menu_id , 'footermenu_sort_order'=>$menu_id );
					$mdlObj->save( 'ml_menus' , $sortvalues  , "menu_id='$menu_id'" , $this->_db);
				}else{
					$data = array('menu_label'=>$pagename , 'menu_types' => $menustring);
					$menuid = $mdlObj->save( 'ml_menus' , $data , "menu_id='$menuid'" , $this->_db);	
				}
				$mdlObj->scanAndBuildSitemap();
				$ftp->updateSitemap();
				Request::redirect("manage-contents.php?q=modify&step=pagetext&cid=".$cid);
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-contents.php?q=show");	
			}
			
		}
		
		
		private function pagetextAction(){
			
			$postedValues = $this->getValues();
			$cid = $this->getValue('cid');
			$DM_ID 	= Session::get('DOMAIN_ID');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Contents();
			
			$data = array('page_title'=>$postedValues['page_title'], 'page_text'=>$postedValues['page_text']);
			$mdlObj->save( 'ml_contents' , $data , "content_id='$cid'" , $this->_db );
			
			
			Request::redirect("manage-contents.php?q=modify&step=pageseo&cid=".$cid);
					
		}
		
		
		private function pageseoAction(){
			
			$postedValues 	= $this->getValues();
			$cid 			= $this->getValue('cid');
			$menuid 		= $this->getValue('menu_id');
			$this->_db 		= new Pdodb($this->_dbinfo);
			
			$mdlObj 		= new Contents();
			
			$DM_ID 	= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
			
				$dataMenus 	 = array('menu_url'=>$postedValues['menu_url']);
				$mdlObj->save( 'ml_menus' , $dataMenus , "menu_id='$menuid'" , $this->_db);
				
				$dataContent = array('head_title'=>$postedValues['head_title'] ,'head_keywords'=>$postedValues['head_keywords'] ,'head_description'=>$postedValues['head_description']);
				$mdlObj->save( 'ml_contents' , $dataContent , "content_id='$cid'" , $this->_db);
				
				$mdlObj->scanAndBuildSitemap();
				$ftp->updateSitemap();
				
				Message::setResponseMessage("Selected page modified successfully.", 's');
				Request::redirect("manage-contents.php?q=show");
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-contents.php?q=show");	
			}
				
		}
		
		
		
		
	
			
	} //$
	
	
	
?>