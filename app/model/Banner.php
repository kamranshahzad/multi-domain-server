<?php

	class Banner extends Model {
		
		const _TABLE = 'ml_banners';
		private $_db = NULL;
		public $_DM_ID = 0;
		public $_DM_URL = '';
		public $_DM_Media = '';	
		
		public function __construct() {
			$this->_DM_ID = Session::get('DOMAIN_ID');
			$domain = new Domains();
			$domainArray = $domain->fetchById($this->_DM_ID);
			$this->_DM_Media = 'http://www.'.$domainArray['domain_url'].'/media/';
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
			helper functions
		*/
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "banner_id='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		public function fetchByAllEnabled(){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE , "status='Y'");
			$this->dispose();
			if(count($dataArray) > 0){
				$tmpArray = array();
				foreach($dataArray as $array){
					if(!empty($array['banner_image'])){
						$tmpArray[$array['banner_image']] = $array['image_alttag'];
					}
				}
				return $tmpArray;
			}
		}
		
		
		/*
			workers (_html)
		*/
		
		public function drawGrid(){
			$htmlString = '';
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE);
			$this->dispose();
			$pointer = 1;
			$boot = new bootstrap();
			
			if(count($dataArray) > 0){
				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('Banner', 'disable' , 'Yes' , array('bid'=>$array['banner_id']) , "Are you sure you want to un-publish selected image?").'</span>' : '<span class="disenabled">'.Link::Action('Banner', 'enable' , 'No' , array('bid'=>$array['banner_id']) , "Are you sure you want to publish selected image?").'</span>';
					$imageSrc = $this->_DM_Media.'banner/grid/'.$array['banner_image'];
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center"><strong>'.$pointer.')</strong></td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center"><img src="'.$imageSrc.'" class="gridimg round" /></td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['description'],150).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.date("Y-m-d",strtotime($array['date_created'])) .'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="manage-banner.php?q=modify&bid='.$array['banner_id'].'">modify</a>
									</span>
									&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('Banner', 'remove' , 'remove' , array('bid'=>$array['banner_id']), "Are you sure you want to remove selected image?").'
									</a>
									</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No banner image found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="200" align="center">Banner Image</td>
								<td class="head" width="400">&nbsp;&nbsp;Short Description</td>
								<td class="head" width="100" align="center">Published?</td>
								<td class="head" width="80" align="center">Created Date</td>
								<td class="head" width="130" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
	}  // $
