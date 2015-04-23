<?php

	class BlockPages extends Model {
		
		const _TABLE = 'ml_block_pages';
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
				return $this;
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		
		
		/*
			workers
		*/
		public function loadBlockPagesById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "page_id ='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		public function fetchByBlockIdentifier($blockidentifier='box1'){
			
			$this->initDb();
			$pageid = 0;
			switch($blockidentifier){
				case 'box1':
					$pageid = 1;
					break;
				case 'box2':
					$pageid = 2;
					break;
				case 'box3':
					$pageid = 3;
					break;
				case 'box4':
					$pageid = 4;
					break;			
			}
			
			$dataArray = $this->_db->select(self::_TABLE, "page_id='$pageid'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0]['page_url'];
			}
		}
		
		
		/*
			_draw block pages
		*/
		public function drawBlockPagesGrid(){
			$htmlString = '';
			$this->initDb();
			
			$dataArray = $this->_db->select(self::_TABLE , "domain_id='{$this->_DM_ID}'");
			
			$this->dispose();
			$pointer = 1;
			
			if(count($dataArray) > 0){
				$htmlString .= $this->gridBlockPageHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}

					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['page_title'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['page_url'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" >'.date("Y-m-d",strtotime($array['date_created'])) .'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="home-page.php?q=modify&step=pagetext&pid='.$array['page_id'].'">modify</a>
									</span>';					
					$htmlString .= '</td>';
					$htmlString .= '</tr>';
					$pointer++;	
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No block page found.</div>';	
			}
			
			return $htmlString;	
		}
		
		public function gridBlockPageHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="250">Page name</td>
								<td class="head" width="210">Page Url</td>
								<td class="head" width="70">Date Created</td>
								<td class="head" width="120" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		/*
			_helper functions
		*/
		public function isExistsInMenuItem( $pagename ){
			$this->initDb();
			$dataArray = $this->_db->select( self::_TABLE , "page_title='$pagename'");
			$this->dispose();
			if(count($dataArray) > 0){
				return true;
			}
			return false;
		}
		
		public function isExistsInMenuUrl($pageurl  ){
			$this->initDb();
			$dataArray = $this->_db->select( self::_TABLE , "page_url = '$pageurl' ");
			$this->dispose();
			if(count($dataArray) > 0){
				return true;
			}
			return false;
		}
		
		
		// step2 if page is block
		public function BlockisExistsInMenuUrl($pageurl , $pageid = 0 ){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE , "page_url = '$pageurl' ");
			$this->dispose();
			if(count($dataArray) > 0){
				if($dataArray[0]['page_id'] == 	$pageid ){
					return false;
				}
				return true;
			}
			return false;
		}
		
		
		
		
	}  // $
