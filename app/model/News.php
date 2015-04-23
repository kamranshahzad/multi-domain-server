<?php

	class News extends Model {
		
		const _TABLE = 'ml_news';
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
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('News', 'disable' , 'Yes' , array('nid'=>$array['news_id']) , "Are you sure you want to un-publish selected news?").'</span>' : '<span class="disenabled">'.Link::Action('News', 'enable' , 'No' , array('nid'=>$array['news_id']) , "Are you sure you want to publish selected news?").'</span>';
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['news_title'],35).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['news_short_text'],100).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.date("Y-m-d",strtotime($array['date_created'])) .'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="manage-news.php?q=modify&nid='.$array['news_id'].'">modify</a>
									</span>
									&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('News', 'remove' , 'remove' , array('nid'=>$array['news_id']), "Are you sure you want to remove selected news?").'
									</a>
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
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="300">&nbsp;&nbsp;Title</td>
								<td class="head" width="400">&nbsp;&nbsp;Short Description</td>
								<td class="head" width="100" align="center">Published?</td>
								<td class="head" width="80" align="center">Created Date</td>
								<td class="head" width="130" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "news_id='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		public function drawLatestNews($showNoNews = 3){
			
			$htmlString = '';
			$boot = new bootstrap();
			$media = $boot->media.'/news/thumbs/';
			
			$setObject = new Settings();
			$defaultDate = $setObject->fetchById('main');
			
			
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "status='Y' ORDER BY news_id DESC LIMIT 0 , $showNoNews");
			$this->dispose();
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					if(!empty($array['news_img'])){
						$htmlString .= '<div class="newsItem">
								<img src="'.$media.$array['news_img'].'" width="122" height="122" class="left" />
								<div class="details right">
									<div class="newsdate">'.DateUtil::format($array['news_date'] ,$defaultDate ).'</div>
									<h2>'.$array['news_title'].'</h2>
									<p>'.$array['news_short_text'].'</p>
									<a href="news-awards.php">more..</a>
								</div>
								<div class="clear"></div>	
							</div>';
					}else{
						$htmlString .= '<div class="newsItem">
										<div class="fulldetails">
											<div class="newsdate">'.DateUtil::format($array['news_date'] , $defaultDate).'</div>
											<h2>'.$array['news_title'].'</h2>
											<p>'.$array['news_short_text'].'</p>
											<a href="news-awards.php">more..</a>
										</div>
									</div>';	
					}
				}
			}
			return $htmlString;
		}
		
		
		public function drawAllNews(){
			
			$htmlString = '';
			
			$setObject = new Settings();
			$defaultDate = $setObject->fetchById('main');
			
			
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "status='Y' ORDER BY news_id DESC");
			$this->dispose();
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$htmlString .= '<div class="newsWrapper">
									<!-- #newsWrapper-->
										<h2>'.$array['news_title'].'</h2>
										<div class="eventdate">'.DateUtil::format($array['news_date'] , $defaultDate ).'</div>
										<p>'.$array['news_detail_text'].'</p>
									<!-- $newsWrapper-->
									</div>';	
				}
			}
			
			return $htmlString;
		}
		
		
	}  // $
