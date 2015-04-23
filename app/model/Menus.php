<?php

	class Menus extends Model {
		
		const _TABLE = 'ml_menus';
		private $_db = NULL;
		public $_DM_ID = 0;	
		
		public function __construct() {
			$this->_DM_ID = Session::get('DOMAIN_ID');
		}		
		
		public function initDb(){
			$configObj 	= new config();
			$_dbinfo 	= $configObj->getDbConfig();
			try {
				$this->_db = new Pdodb($_dbinfo);
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		
		public function drawGrid($MENU_TYPE='left'){
			$htmlString = '';
			$this->initDb();
			$filterKey = $MENU_TYPE.'menu_sort_order';
			$dataArray = $this->_db->select(self::_TABLE , "menu_types LIKE '%$MENU_TYPE%' AND domain_id='{$this->_DM_ID}' ORDER by $filterKey");
			$this->dispose();
			$pointer = 1;
			$totalRecords = count($dataArray);
			
			//echo $filterKey;
			
			if($totalRecords > 0){

				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('Menus', 'disable' , 'Enabled' , array('mid'=>$array['menu_id']) , "Are you sure you want to disable selected menu item?").'</span>' : '<span class="disenabled">'.Link::Action('Menus', 'enable' , 'Disabled' , array('mid'=>$array['menu_id']) , "Are you sure you want to active selected menu item?").'</span>';
					
					$sortWrapper = '';
					if($pointer == 1){
						$sortWrapper = '<img src="public/images/down-arrow.png" class="SortButton" id="down-'.$pointer.'-'.$MENU_TYPE.'" data-row="'.$array['menu_id'].'-'.$array[$filterKey].'-menus-'.$MENU_TYPE.'"   >';	
					}
					if($totalRecords == $pointer){
						$sortWrapper = '<img src="public/images/up-arrow.png" class="SortButton" id="up-'.$pointer.'-'.$MENU_TYPE.'" data-row="'.$array['menu_id'].'-'.$array[$filterKey].'-menus-'.$MENU_TYPE.'"  >';
					}
					if($pointer > 1 && $pointer < $totalRecords){
						$sortWrapper = '<img src="public/images/down-arrow.png" class="SortButton" id="down-'.$pointer.'-'.$MENU_TYPE.'" data-row="'.$array['menu_id'].'-'.$array[$filterKey].'-menus-'.$MENU_TYPE.'" >
										<img src="public/images/up-arrow.png" class="SortButton"  id="up-'.$pointer.'-'.$MENU_TYPE.'"  data-row="'.$array['menu_id'].'-'.$array[$filterKey].'-menus-'.$MENU_TYPE.'"  >';
					}
					
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['menu_label'],35).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['menu_url'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$sortWrapper.'</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
				//$htmlString .= $form->close();
			}else{
				$htmlString .= '<div class="totalGridRecords round">No menu item found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="220">&nbsp;&nbsp;Menu Text</td>
								<td class="head" width="300">&nbsp;&nbsp;Menu Url</td>
								<td class="head" width="100" align="center">Ordering</td>
							</tr>';
			return $htmlString;	
		}
		
		
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "menu_id='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		public function drawMenus($menu_type='left'){
			$htmlString = '';
			$boot = new bootstrap();
			$baseURL = $boot->basepath;
			$this->initDb();
			$filterKey = $menu_type.'menu_sort_order';
			$sqlQuery = "SELECT * FROM menus LEFT JOIN contents ON menus.menu_id=contents.menu_id WHERE menus.menu_types LIKE '%$menu_type%' AND menus.status='Y' GROUP BY menus.menu_id ORDER BY menus.$filterKey";
			$dataArray = $this->_db->run($sqlQuery);
			
			$this->dispose();
			if(count($dataArray) > 0){
				$htmlString .= '<ul>';
				foreach($dataArray as $array){
					$htmlString .= $this->drawExternalLinks($array['menu_label']  , $array['menu_url'] , $baseURL);	
				}
				$htmlString .= '</ul>';
			}
			return $htmlString;	
		}
		
		private function drawExternalLinks( $menu_label ,$menu_url  , $baseURL){
			$htmlString  = '';
			$htmlString .= '<li><a href="'.$baseURL.'/'.$menu_url.'">'.$menu_label.'</a></li>';	
			return $htmlString;	
		}
		
		
		public function setSortOrder($id , $sortOrder, $targetId , $targetSortOrder , $menutype ){
			$this->initDb();
			$filterKey = $menutype.'menu_sort_order';
			$data1 = array($filterKey=>$targetSortOrder);
			parent::save( self::_TABLE , $data1 , "menu_id='$id'" ,$this->_db);
			$data2 = array($filterKey=>$sortOrder);
			parent::save( self::_TABLE , $data2 , "menu_id='$targetId'" ,$this->_db);
			$this->dispose();	
		}
		
		
		public function triggerLeftMenusAutoSort(){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "menu_id IS NOT NULL ORDER BY leftmenu_sort_order");
			if(count($dataArray) > 0){
				$pointer = 1;
				foreach($dataArray as $array){
					$data = array('leftmenu_sort_order'=>$pointer );
					$menuid = $array['menu_id'];
					parent::save( self::_TABLE , $data , "menu_id='$menuid'" ,$this->_db);
					$pointer++;
				}
			}
			$this->dispose();
		}
		
		public function triggerFooterMenusAutoSort(){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "menu_id IS NOT NULL ORDER BY footermenu_sort_order");
			if(count($dataArray) > 0){
				$pointer = 1;
				foreach($dataArray as $array){
					$data = array('footermenu_sort_order'=>$pointer );
					$menuid = $array['menu_id'];
					parent::save( self::_TABLE , $data , "menu_id='$menuid'" ,$this->_db);
					$pointer++;
				}
			}
			$this->dispose();
		}
		
		
	}  // $
