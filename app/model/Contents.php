<?php

	class Contents extends Model {
		
		const _TABLE = 'ml_contents';
		private $_db = NULL;
		public $_DM_ID = 0;
		public $_DM_URL = '';
		
		public function __construct() {
			$this->_DM_ID = Session::get('DOMAIN_ID');
			$domain = new Domains();
			$domainArray = $domain->fetchById($this->_DM_ID);
			$this->_DM_URL = 'http://www.'.$domainArray['domain_url'].'/';
		}	
		
		
		public function initDb(){
			$configObj 	= new config();
			$_dbinfo 	= $configObj->getDbConfig();
			try {
				$this->_db = new Pdodb($_dbinfo);
				return $this;
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		/*
			_helper functions
		*/
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "content_id='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		public function getMenuText($option=''){
			$textString = '';
			if(!empty($option)){
				$menuObject = new Menus();
				$data = $menuObject->fetchById($option);
				$textString = $data['menu_label'];
			}else{
				$textString = '---';
			}
			return $textString;
		}
		
		
		
		public function findContentByUrl($contentUrl){
			
			$notfound = false;
			$dataArray = array();
			$this->initDb();
			
			
			$sqlQuery = "SELECT * FROM menus INNER JOIN contents ON menus.menu_id = contents.menu_id WHERE menus.menu_url='$contentUrl' GROUP BY menus.menu_id";
			$resultArray1 = $this->_db->run($sqlQuery);
			if(count($resultArray1) > 0){
				$dataArray = $resultArray1[0];
				$dataArray['pagetype'] = 'page';
				$notfound  = true;
			}
			
			if(!$notfound){
				$resultArray2 = $this->_db->select("ml_block_pages" , " page_url = '$contentUrl' ");
				if(count($resultArray2) > 0){
					$dataArray = $resultArray2[0];
					$dataArray['pagetype'] = 'block';
				}	
			}
			$this->dispose();			
			
			return $dataArray;
		}
		
		
		
		public function findContentUrl( $menu_id , $menu_type){
			$this->initDb();
			$condition = '';
			if($menu_type == 'LEFT'){
				$condition = "left_menu_id = '$menu_id'";
			}else{
				$condition = "bottom_menu_id ='$menu_id'";
			}
			$dataArray = $this->_db->select(self::_TABLE, $condition );
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0]['content_url'];
			}
		}
		
		
		
		
		/*
			_admin html grid
		*/
		public $totalpages 		= 0;
		public $gridpointer		= 1;
		
		public function drawGrid(){
			
			$htmlString = '';
			$this->initDb();
			$regularPagesHtml = $this->drawRegularPages();
			$dataArray = $this->_db->select("ml_module_pages" , "domain_id='$this->_DM_ID'");
			$this->totalpages = count($dataArray);
			
			
			
			
			if($this->totalpages > 0){
				$htmlString .= $this->startGrid();
				$htmlString .= $regularPagesHtml;
			
				if(count($dataArray) > 0){
					foreach($dataArray as $array){
						$htmlString .= $this->drawModulePages($array['page_id']);	
					}
				}
				$htmlString .= $this->endGrid();
			}else{
				$htmlString .= '<div class="totalGridRecords round">No page found.</div>';	
			}
			$this->dispose();
			return $htmlString;
					
		}
		
		public function startGrid(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="200">Page Name</td>
								<td class="head" width="200">Page Type</td>
								<td class="head" width="150">Page Url</td>
								<td class="head" width="70" align="center">Status</td>
								<td class="head" width="90">Date Created</td>
								<td class="head" width="130" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		public function endGrid(){
			$htmlString = '';
			$htmlString .= '</table>';
			//$htmlString .= '<div class="totalGridRecords round">No contents found.</div>';
			return $htmlString;
		}
		
		public function drawRegularPages(){
			$htmlString = '';
			
			$sqlQuery = "SELECT * FROM ml_menus INNER JOIN ml_contents ON ml_menus.menu_id = ml_contents.menu_id WHERE ml_menus.domain_id='{$this->_DM_ID}' GROUP BY ml_menus.menu_id";
			$dataArray = $this->_db->run($sqlQuery);
			$this->totalpages = count($dataArray);
			
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$class = 'even';
					if($this->gridpointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('Contents', 'disable' , 'Yes' , array('mid'=>$array['menu_id']) , "Are you sure you want to disable selected page?").'</span>' : '<span class="disenabled">'.Link::Action('Contents', 'enable' , 'No' , array('mid'=>$array['menu_id']) , "Are you sure you want to active selected page?").'</span>';
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$this->gridpointer.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['menu_label'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">Regular Page</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['menu_url'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" >'.date("Y-m-d",strtotime($array['date_created'])) .'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="manage-contents.php?q=modify&step=placement&cid='.$array['content_id'].'">modify</a>
									</span>';
					$htmlString .= '&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('Contents', 'remove' , 'remove' , array('cid'=>$array['content_id'], 'mid'=>$array['menu_id'] ), "Are you sure you want to remove selected page?").'
									</a>
									</span>';					
					$htmlString .= '</td>';
					$htmlString .= '</tr>';
					$this->gridpointer++;	
				}
			}
			return $htmlString;
		}
		
		public function drawModulePages($pageid=1){
			$htmlString = '';
			
			$sqlQuery = "SELECT * FROM ml_module_pages WHERE page_id='$pageid'";
			$dataArray = $this->_db->run($sqlQuery);
			foreach($dataArray as $array){
				$class = 'even';
				if($this->gridpointer%2 == 0){
					$class = 'odd';	
				}
				
				$htmlString .= '<tr>';
				$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$this->gridpointer.'</td>';
				$htmlString .= '<td class="'.$class.'" valign="top">'.$array['page_title'].'</td>';
				$htmlString .= '<td class="'.$class.'" valign="top">Module Page</td>';
				$htmlString .= '<td class="'.$class.'" valign="top" align="center"></td>';
				$htmlString .= '<td class="'.$class.'" valign="top" align="center"></td>';
				$htmlString .= '<td class="'.$class.'" valign="top" >'.date("Y-m-d",strtotime($array['date_modified'])) .'</td>';
				$htmlString .= '<td class="'.$class.'" valign="top" align="center">';
				$htmlString .= '<span>
								<a href="manage-contents.php?q=pages&step=pagetext&pid='.$array['page_id'].'">modify</a>
								</span>';					
				$htmlString .= '</td>';
				$htmlString .= '</tr>';
				$this->gridpointer++;
				$this->totalpages++;	
			}
			return $htmlString;
		}
		
		
		
		
		public function gridHeader(){
			
		}
		
		
		
		
		
		public function getPlacement($inputString=''){
			$placement = '';
			$options = array('left'=>'Left Menu','footer'=>'Footer Menu');
			if(!empty($inputString)){
				$menuArray = explode(',',$inputString);
				$pointer = 1;
				$tmpArray = array();
				foreach($menuArray as $item){
					if(array_key_exists($item,$options)){
						$tmpArray[] = $options[$item];
					}
				}
				$placement = join(', ',$tmpArray);
			}
			return $placement;
		}
		
		
		
	
		
		
		public function drawLeftMenus($input = 999){
			$htmlString = '<span style="color:#ff740e;font-size:11px;">No Menu Item</span>';
			$this->initDb();
			$dataArray = $this->_db->select(Menus::_TABLE , 'menu_type="LEFT" AND status = "Y"');
			//print_r($dataArray);
			$this->dispose();
			if(count($dataArray) > 0){
				$htmlString = '<select name="left_menu_id" id="left_menu_id"><option value="">Select Menu Item</option>';
				foreach($dataArray as $array){
					if($input == $array['menu_id'] ){
						$htmlString .= '<option value="'.$array['menu_id'].'" selected="selected">'.$array['menu_label'].'</option>';	
					}else{
						$htmlString .= '<option value="'.$array['menu_id'].'">'.$array['menu_label'].'</option>';
					}
				}
				$htmlString .= '</select>';	
			}
			return $htmlString;
		}
		
		
		public function drawFooterMenus($input = 999){
			$htmlString = '<span style="color:#ff740e;font-size:11px;">No Menu Item</span>';
			$this->initDb();
			$dataArray = $this->_db->select(Menus::_TABLE , 'menu_type="BOTTOM" AND status = "Y"');
			$this->dispose();
			if(count($dataArray) > 0){
				$htmlString = '<select name="bottom_menu_id" id="bottom_menu_id"><option value="">Select Menu Item</option>';
				foreach($dataArray as $array){
					if($input == $array['menu_id'] ){
						$htmlString .= '<option value="'.$array['menu_id'].'" selected="selected">'.$array['menu_label'].'</option>';	
					}else{
						$htmlString .= '<option value="'.$array['menu_id'].'">'.$array['menu_label'].'</option>';
					}
				}
				$htmlString .= '</select>';	
			}
			return $htmlString;
		}
		
		
		
		/* content svn */
		
		
		/*
		public function isExistInMenu( $pagename , $cid = 0){
			$this->initDb();
			if($cid != 0){
				$sqlQuery = 'SELECT menu_label FROM menus INNER JOIN contents ON menus.menu_id = contents.menu_id WHERE contents.content_id <> '.$cid.'GROUP BY menu_id';
				$dataArray = $this->_db->run($sqlQuery);
			}else{
				$dataArray = $this->_db->select("menus" , "menu_label='$pagename'");
			}
			$this->dispose();
			if(count($dataArray) > 0){
				return array('content_id'=>$dataArray[0]['content_id'],'content_type'=>$dataArray[0]['content_type'],'title'=>$dataArray[0]['page_title'] , 'content-text'=>$dataArray[0]['page_text']);
			}
		}
		*/
		
		public function isExistsInMenuItem($pagename , $menuid = 0 ){
			$this->initDb();
			$dataArray = $this->_db->select("ml_menus" , "menu_label='$pagename' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			if(count($dataArray) > 0){
				if($dataArray[0]['menu_id'] == 	$menuid ){
					return false;
				}
				return true;
			}
			return false;
		}
		
		public function isExistsInMenuUrl($pageurl , $menuid = 0 ){
			$this->initDb();
			$dataArray = $this->_db->select("ml_menus" , "menu_url = '$pageurl' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			if(count($dataArray) > 0){
				if($dataArray[0]['menu_id'] == 	$menuid ){
					return false;
				}
				return true;
			}
			return false;
		}
		
		// fort step 2 for block pages
		public function BlockisExistsInMenuUrl($pageurl ){
			$this->initDb();
			$dataArray = $this->_db->select("ml_menus" , "menu_url = '$pageurl' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			if(count($dataArray) > 0){
				return true;
			}
			return false;
		}
		
		
		
		public function loadContentsById($contentid){
			$this->initDb();
			$sqlQuery = "SELECT * FROM ml_menus INNER JOIN ml_contents ON ml_menus.menu_id=ml_contents.menu_id WHERE ml_contents.content_id='$contentid' GROUP BY ml_menus.menu_id";
			$dataArray = $this->_db->run($sqlQuery);
			$this->dispose();
			return $dataArray[0];
		}
		
		
		
		public function SaveContentPlacement($pagename, $menuarray , $menuid = 0 , $cid = 0){
			$menuString = '';
			$this->initDb();
			if(count($menuarray) > 0){
				$menuString = implode(',',$menuarray);	
			}
			
			if($cid == 0){
				$data = array('menu_label'=>$pagename , 'menu_types'=>$menuString);
				$menuid = $this->save( 'menus' , $data , '' , $this->_db);
				$data2 = array('menu_id'=>$menuid,'date_created' => DateUtil::curDateDb());
				$contentid = $this->save( 'contents' , $data2 , '' , $this->_db );
			}else{
				$data = array('menu_label'=>$pagename , 'menu_types'=>$menuString);
				$menuid = $this->save( 'menus' , $data , "menu_id='$menuid'" , $this->_db);	
			}
			
			$this->dispose();	
		}
		
		
		public function SaveContentText($pageheading, $pagetext , $menuid = 0 , $cid = 0){
			$this->initDb();
			
			$data = array('page_title'=>$pageheading,'page_text ' => $pagetext);
			$this->save( 'ml_contents' , $data , "content_id='$cid'" , $this->_db );
			
			$this->dispose();	
		}
		
		
		/*
			_sitemap
		*/
		public function scanUrls(){
			
			$blockUrls = $contentUrls = array();
			$this->initDb();
			$blockPages = $this->_db->select("ml_block_pages","domain_id='{$this->_DM_ID}'");
			if(count($blockPages) > 0){
				foreach($blockPages as $array){
					$blockUrls[] = $array['page_url'];
				}
			}
			
			$conetntPages = $this->_db->select("ml_menus" , "domain_id='{$this->_DM_ID}'");
			if(count($conetntPages) > 0){
				foreach($conetntPages as $array){
					$contentUrls[] = $array['menu_url'];
				}
			}
			
			$outputUrls = array_merge($contentUrls,$blockUrls);
			return $outputUrls;
		}
		
		
		public function scanAndBuildSitemap(){
			
			$boot = new bootstrap();
			$pagesArray = $this->scanUrls();
			$urlArray  	= array();
			$urlArray[] =  $boot->basepath;
			foreach($pagesArray as $array){
				$urlArray[] = $this->_DM_URL.$array;	
			}
			
			if(count($urlArray) > 0){
				
				$whichSitemap = '../temp/dm_'.$this->_DM_ID.'/sitemap.xml';
				if(file_exists($whichSitemap)){
					$mapObject = new Sitemap($whichSitemap);
					$mapObject->load();
					foreach($urlArray as $url) {
						$array = array('loc'=>$url,'lastmod'=>date("Y-m-d") );
						$mapObject->addrow($array);
					}
					$mapObject->dom->save($whichSitemap);
				}else{
					throw new Exception("sitemap.xml not found on local system");	
				}
			}
		}
		
		
		
		
	}  // $
