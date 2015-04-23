<?php

	class Html extends Model {
		
		const _TABLE = 'ml_blocks';
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
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "block_id='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		public function fetchByIdentifier($identifier=''){
			$htmlString = '';
			$boot = new bootstrap();
			$media = $boot->media.'/block/thumbs/';
			$bpageObject = new BlockPages();
			
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "identifier='$identifier' AND status='Y'");
			
			$this->dispose();
			if(count($dataArray) > 0){
				
				$array = $dataArray[0];
				$urlString = '';
				
				if($array['block_type'] == 'Y'){
					$urlString = $boot->basepath.'/'.$bpageObject->fetchByBlockIdentifier($array['identifier']);
				}

				if(!empty($array['image'])){
					if($array['islink'] == 'Y'){		
						$htmlString .= '<a href="'.$urlString.'"><img src="'.$media.$array['image'].'" style="border="0" alt="'.$media.$array['alt_tag'].'" /></a>';
					}else{
						$htmlString .= '<img src="'.$media.$array['image'].'" style="border="0" alt="'.$media.$array['alt_tag'].'" />';
					}
				}
				$htmlString .= $array['block_text'];
				if($array['islink'] == 'Y'){
					$htmlString .= '<span class="blockreadmore"><a href="'.$urlString.'" >read more...</a></span>';
				}
			}
			
			return $htmlString;
		}
		
		
		
		
		public function drawGrid(){
			$htmlString = '';
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE);
			$this->dispose();
			$pointer = 1;
			
			if(count($dataArray) > 0){
				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('Html', 'disable' , 'Yes' , array('bid'=>$array['block_id']) , "Are you sure you want to disable selected block?").'</span>' : '<span class="disenabled">'.Link::Action('Html', 'enable' , 'No' , array('bid'=>$array['block_id']) , "Are you sure you want to active selected block?").'</span>';
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['block_title'],100).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="manage-blocks.php?q=modify&bid='.$array['block_id'].'">modify</a>
									</span>
									</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No news & event found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="500">
							<tr>
								<td class="head" width="300">&nbsp;&nbsp;Title</td>
								<td class="head" width="100" align="center">Is Visible?</td>
								<td class="head" width="150" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		

		
	}  // $
