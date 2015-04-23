<?php

	class Portfolio extends Model {
		
		const _TABLE = 'ml_portfolio';
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
		
		
		/*
			helper functions
		*/
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "pid='$id' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		/*
			_htmls
		*/
		
		public function drawGrid(){
			$htmlString = '';
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE ," pid IS NOT NULL ORDER BY pid DESC");
			$this->dispose();
			$totalRecords = count($dataArray);
			$boot = new bootstrap();
			
			if($totalRecords > 0){
				$htmlString .= $this->gridHeader();
				$pointer = 1;
				
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('Portfolio', 'disable' , 'Yes' , array('pid'=>$array['pid']) , "Are you sure you want to disable selected portfolio item?").'</span>' : '<span class="disenabled">'.Link::Action('Portfolio', 'enable' , 'No' , array('pid'=>$array['pid']) , "Are you sure you want to active selected portfolio item?").'</span>';
					$imageSrc = $this->_DM_Media.'portfolio/grid/'.$array['image'];
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center"><strong>'.$pointer.'</strong>)</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center"><img src="'.$imageSrc.'" class="gridimg round" /></td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['short_description'],150).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.date("Y-m-d",strtotime($array['date_created'])) .'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="manage-portfolio.php?q=modify&pid='.$array['pid'].'">modify</a>
									</span>
									&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('Portfolio', 'remove' , 'remove' , array('pid'=>$array['pid']), "Are you sure you want to remove selected portfolio item?").'
									</a>
									</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No portfolio item found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="110" align="center">Image</td>
								<td class="head" width="370">&nbsp;&nbsp;Short Description</td>
								<td class="head" width="50" align="center">Published?</td>
								<td class="head" width="50" align="center">Created Date</td>
								<td class="head" width="140" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		
		
		/*
			front-end grid
		*/		
		public function drawPortfolio(){
			
			$htmlString = '';
			
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE ,"status = 'Y'");
			$totalRecords = count($dataArray);
			$boot = new bootstrap();
			$setObject = new Settings();  // get custom setting values
			$defaultportfolio = $setObject->fetchById('portfolio');
			$thumbWidth  = $setObject->getByJson('twidth',$defaultportfolio);
			$thumbHeight = $setObject->getByJson('theight',$defaultportfolio);
			$noItemsScroll      =  $setObject->getByJson('nodisplay',$defaultportfolio);
			$containerWidth 	= $thumbWidth + 10;
			$containerHeight	= $thumbHeight + 55 + 18; 
			$displaystyle 		=  $setObject->getByJson('displaystyle',$defaultportfolio);
			
			
			if($totalRecords > 0){
				$record_per_page	= $noItemsScroll;
				$scroll				= 5 ;
				$page 				= new Pagger();
				$page->set_page_data( $totalRecords,$record_per_page,$scroll,true,true,true);
				$sqlQuery = $page->get_limit_query("SELECT * FROM ".self::_TABLE." WHERE status = 'Y' ORDER BY pid DESC");				
				$resultArray = $this->_db->run($sqlQuery);
				foreach($resultArray as $array){
					
					$imageSrc = $boot->media.'/portfolio/thumbs/'.$array['image'];
					//$imgTag   = ($displaystyle == 'a') ? '<a class="ajax" href="portfolio-popup-details.php?pid='.$array['pid'].'"><img src="'.$imageSrc.'" style="border:none;" /></a>' : '<a href="portfolio-details.php?pid='.$array['pid'].'"><img src="'.$imageSrc.'" style="border:none;" /></a>';
					$imgTag   = ($displaystyle == 'a') ? '<img class="portfolioImageItem" data-portfolioid="'.$array['pid'].'" src="'.$imageSrc.'" style="border:none;" /></a>' : '<a href="portfolio-details.php?pid='.$array['pid'].'"><img src="'.$imageSrc.'" style="border:none;" /></a>';
					
					$htmlString .= '<div class="portfolioItem round left" style="width:'.$containerWidth.'px;height:'.$containerHeight.'px;overflow:hidden;">
										'.$imgTag.'
										<p>
										'.StringUtil::short($array['short_description'], 100 ).'
										</p>
									</div>';	
				}
				$htmlString .= '<div class="paggingWrapper">'.$page->get_page_nav("", true).'</div>';
			}else{
				$htmlString .= '';	
			}
			$this->dispose();
			return $htmlString;	
		}
		
		

		
	}  // $
