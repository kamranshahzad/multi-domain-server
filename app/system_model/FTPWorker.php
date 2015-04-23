<?php

	class FTPWorker extends Model {
		
		private $_ftp = NULL;
		private $_rootDir = array();
		private $IS_MEDIA = FALSE;
		private $_STARTUP = FALSE;
		private $_DM_ID = 0;
		private $_db = NULL;
		private $_bannerAsset = 'temp/banner';
		private $_newsAsset = 'temp/news';
		private $_blockAsset = 'temp/block';
		private $_portfolioAsset = 'temp/portfolio';
		private $mediaDirTree   = array(
								'media/banner/grid','media/banner/large','media/banner/raw','media/banner/thumbs',
								'media/block/large','media/block/thumbs',
								'media/news/large' ,'media/news/thumbs',
								'media/portfolio/grid','media/portfolio/large','media/portfolio/raw','media/portfolio/thumbs'
								);
								
		public static function Ping($ftpArray = array()){
			$ftp = new SFTP( $ftpArray['ftphost'], $ftpArray['ftpusername'] , $ftpArray['ftppassword'] );
			$response = $ftp->connect();
			unset($ftp);
			if($response){
				return TRUE;
			}
			return FALSE;
		}
		
		public function __construct( $ftpArray = array(), $DOMAIN_ID = 0 , $_startupdomain = false ) {
			$this->_STARTUP 		= $_startupdomain;
			$this->_DM_ID			= $DOMAIN_ID;
			$this->_blockAsset 		= 'temp/'.$this->_DM_ID.'/block/';
			$this->_newsAsset 		= 'temp/'.$this->_DM_ID.'/news/';
			$this->_portfolioAsset 	= 'temp/'.$this->_DM_ID.'/portfolio/';
			$this->_bannerAsset 	= 'temp/'.$this->_DM_ID.'/banner/';
			$this->_ftp 			= new SFTP( $ftpArray['ftphost'], $ftpArray['ftpusername'] , $ftpArray['ftppassword']  );
			
			$this->init();
		}	
		
		/*
			_db connection
		*/
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
		
		
		
		//	_startup functions
		
		public function init(){
			if($this->_ftp->connect()) {
				$this->_rootDir = $this->_ftp->ls();
				if(in_array('media',$this->_rootDir)){
					$this->IS_MEDIA	= TRUE;
				}
			}
			if($this->_STARTUP){
				if(!$this->IS_MEDIA){
					$this->createMediaDirectory();
				}
				$this->createParentTmps();
				$this->buildInitQuery();
				$this->copyFreshSitemap();
			}
		}
		
		public function createMediaDirectory(){
			if($this->_ftp->connect()) {
				foreach($this->mediaDirTree as $dirarray){
					$this->_ftp->mkDirRecursive($dirarray);	
				}
			}
		}
		
		public function copyFreshSitemap(){
			
			$sampleSitemap = "../temp/Sitemap-Sample.xml";
			
			$startupLinks = array('index.php','testimonials.php','news.php','contact-us.php','portfolio.php' );
			if(file_exists($sampleSitemap)){
				copy( $sampleSitemap , '../temp/dm_'.$this->_DM_ID.'/sitemap.xml');
			}
			
			$domain = new Domains();
			$domainArray = $domain->fetchById($this->_DM_ID);
			$serverLoc   = 'http://www.'.$domainArray['domain_url'].'/';
			$siteObject = new Sitemap('../temp/dm_'.$this->_DM_ID.'/sitemap.xml');
			$siteObject->load();
			foreach($startupLinks as $url) {
				$array = array('loc'=>$serverLoc.$url,'lastmod'=>date("Y-m-d"));
				$siteObject->addrow($array);
			}
			$siteObject->dom->save('../temp/dm_'.$this->_DM_ID.'/sitemap.xml'); 
			if($this->_ftp->connect()){
				if(file_exists('../temp/dm_'.$this->_DM_ID.'/sitemap.xml')){
					$this->_ftp->put('../temp/dm_'.$this->_DM_ID.'/sitemap.xml', "sitemap.xml");	
				}else{
					throw new Exception("Invalid path for Sitemap-Sample.xml");
				}
			}
			
		}
		
		
		// _update working
		public function updateSitemap(){
			$newsitemap = "../temp/dm_".$this->_DM_ID.'/sitemap.xml';
			if($this->_ftp->connect()) {
				$this->_ftp->delete("sitemap.xml");
				$this->_ftp->put( $newsitemap , "sitemap.xml");			
			}	
		}
		
		// _on parent server
		public function createParentTmps(){
			if (file_exists("../temp")) {
				
				$folderPermCode = 0700;
				
				mkdir( "../temp/dm_".$this->_DM_ID , $folderPermCode );
				mkdir( "../temp/dm_".$this->_DM_ID."/banner" , $folderPermCode );
				mkdir( "../temp/dm_".$this->_DM_ID , $folderPermCode );
				mkdir( "../temp/dm_".$this->_DM_ID."/news" , $folderPermCode );
				mkdir( "../temp/dm_".$this->_DM_ID , $folderPermCode );
				mkdir( "../temp/dm_".$this->_DM_ID."/block" , $folderPermCode);
				mkdir( "../temp/dm_".$this->_DM_ID , $folderPermCode );
				mkdir( "../temp/dm_".$this->_DM_ID."/portfolio" , $folderPermCode );
				
			}else{
				echo "No folder exist with name of temp";	
			}
		}
		
		
		/*
			_portfolio
		*/
		public function insertPortfolioImages($dataArray=array()){
			$response = FALSE;
			
			$this->_ftp->passive = true; 
			
			if($this->_ftp->connect()){
				
				$localPath = '../temp/dm_'.$this->_DM_ID.'/portfolio/';
				$imagename = $dataArray['imagename'];
				$extention = $dataArray['ext'];
				
				$rawImage = $this->_DM_ID.'_raw_'.$imagename.'.'.$extention;
				$gridImage = $this->_DM_ID.'_grid_'.$imagename.'.'.$extention;
				$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
				$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
				$this->_ftp->put( $localPath.$rawImage , "media/portfolio/raw/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$gridImage , "media/portfolio/grid/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$largeImage , "media/portfolio/large/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$thumbImage , "media/portfolio/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);
				
				unlink($localPath.$rawImage);
				unlink($localPath.$gridImage);
				unlink($localPath.$largeImage);
				unlink($localPath.$thumbImage);
				$response = TRUE;
			}
			return $response;
		}
		
		public function modifyPortfolioImages($dataArray=array() , $currentImage=''){
			$response = FALSE;
			if(!empty($dataArray)){
				$this->_ftp->passive = true; 
				if($this->_ftp->connect()){
					$localPath = '../temp/dm_'.$this->_DM_ID.'/portfolio/';
					$imagename = $dataArray['imagename'];
					$extention = $dataArray['ext'];
					
					$rawImage = $this->_DM_ID.'_raw_'.$imagename.'.'.$extention;
					$gridImage = $this->_DM_ID.'_grid_'.$imagename.'.'.$extention;
					$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
					$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
					$this->_ftp->put( $localPath.$rawImage , "media/portfolio/raw/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$gridImage , "media/portfolio/grid/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$largeImage , "media/portfolio/large/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$thumbImage , "media/portfolio/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);
					
					unlink($localPath.$rawImage);
					unlink($localPath.$gridImage);
					unlink($localPath.$largeImage);
					unlink($localPath.$thumbImage);
					
					if(!empty($currentImage)){
						$this->_ftp->delete("media/portfolio/raw/".$currentImage);
						$this->_ftp->delete("media/portfolio/grid/".$currentImage);
						$this->_ftp->delete("media/portfolio/large/".$currentImage);
						$this->_ftp->delete("media/portfolio/thumbs/".$currentImage);
					}
					$response = TRUE;
				}
			}
			return $response;
		}
		
		public function removePortfolioImage($currentImage=''){
			$response = FALSE;
			if(!empty($currentImage)){
				$this->_ftp->delete("media/portfolio/raw/".$currentImage);
				$this->_ftp->delete("media/portfolio/grid/".$currentImage);
				$this->_ftp->delete("media/portfolio/large/".$currentImage);
				$this->_ftp->delete("media/portfolio/thumbs/".$currentImage);
				$response = TRUE;
			}
			return $response;
		}
		
		
		
		/*
			_banner
		*/
		public function insertBannerImages($dataArray=array()){
			$response = FALSE;
			
			$this->_ftp->passive = true; 
			
			if($this->_ftp->connect()){
				
				$localPath = '../temp/dm_'.$this->_DM_ID.'/banner/';
				$imagename = $dataArray['imagename'];
				$extention = $dataArray['ext'];
				
				$rawImage = $this->_DM_ID.'_raw_'.$imagename.'.'.$extention;
				$gridImage = $this->_DM_ID.'_grid_'.$imagename.'.'.$extention;
				$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
				$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
				$this->_ftp->put( $localPath.$rawImage , "media/banner/raw/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$gridImage , "media/banner/grid/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$largeImage , "media/banner/large/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$thumbImage , "media/banner/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);
				
				
				unlink($localPath.$rawImage);
				unlink($localPath.$gridImage);
				unlink($localPath.$largeImage);
				unlink($localPath.$thumbImage);
			
				
				$response = TRUE;
			}
			return $response;
		}
		
		public function modifyBannerImages($dataArray=array() , $currentImage=''){
			$response = FALSE;
			if(!empty($dataArray)){
				$this->_ftp->passive = true; 
				if($this->_ftp->connect()){
					$localPath = '../temp/dm_'.$this->_DM_ID.'/banner/';
					$imagename = $dataArray['imagename'];
					$extention = $dataArray['ext'];
					
					$rawImage = $this->_DM_ID.'_raw_'.$imagename.'.'.$extention;
					$gridImage = $this->_DM_ID.'_grid_'.$imagename.'.'.$extention;
					$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
					$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
					$this->_ftp->put( $localPath.$rawImage , "media/banner/raw/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$gridImage , "media/banner/grid/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$largeImage , "media/banner/large/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$thumbImage , "media/banner/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);
					
					unlink($localPath.$rawImage);
					unlink($localPath.$gridImage);
					unlink($localPath.$largeImage);
					unlink($localPath.$thumbImage);
					
					if(!empty($currentImage)){
						$this->_ftp->delete("media/banner/raw/".$currentImage);
						$this->_ftp->delete("media/banner/grid/".$currentImage);
						$this->_ftp->delete("media/banner/large/".$currentImage);
						$this->_ftp->delete("media/banner/thumbs/".$currentImage);
					}
					$response = TRUE;
				}
			}
			return $response;
		}
		
		public function removeBannerImage($currentImage=''){
			$response = FALSE;
			if(!empty($currentImage)){
				$this->_ftp->delete("media/banner/raw/".$currentImage);
				$this->_ftp->delete("media/banner/grid/".$currentImage);
				$this->_ftp->delete("media/banner/large/".$currentImage);
				$this->_ftp->delete("media/banner/thumbs/".$currentImage);
				$response = TRUE;
			}
			return $response;
		}
		
		
		/*
			_news
		*/
		public function insertNewsImages($dataArray=array()){
			$response = FALSE;
			
			$this->_ftp->passive = true; 
			
			if($this->_ftp->connect()){
				
				$localPath = '../temp/dm_'.$this->_DM_ID.'/news/';
				$imagename = $dataArray['imagename'];
				$extention = $dataArray['ext'];
				
				$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
				$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
				$this->_ftp->put( $localPath.$largeImage , "media/news/large/".$imagename.'.'.$extention ,  FTP_BINARY);
				$this->_ftp->put( $localPath.$thumbImage , "media/news/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);

				unlink($localPath.$largeImage);
				unlink($localPath.$thumbImage);
				
				$response = TRUE;
			}
			return $response;
		}
		
		public function modifyNewsImages($dataArray=array() , $currentImage=''){
			$response = FALSE;
			if(!empty($dataArray)){
				$this->_ftp->passive = true; 
				if($this->_ftp->connect()){
					
					$localPath = '../temp/dm_'.$this->_DM_ID.'/news/';
					$imagename = $dataArray['imagename'];
					$extention = $dataArray['ext'];
					$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
					$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
					
					$this->_ftp->put( $localPath.$largeImage , "media/news/large/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$thumbImage , "media/news/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);
					
					unlink($localPath.$largeImage);
					unlink($localPath.$thumbImage);
					
					if(!empty($currentImage)){
						$this->_ftp->delete("media/news/large/".$currentImage);
						$this->_ftp->delete("media/news/thumbs/".$currentImage);
					}
					$response = TRUE;
				}
			}
			return $response;
		}
		
		public function removeNewsImage($currentImage=''){
			$response = FALSE;
			if(!empty($currentImage)){
				$this->_ftp->delete("media/news/large/".$currentImage);
				$this->_ftp->delete("media/news/thumbs/".$currentImage);
				$response = TRUE;
			}
			return $response;
		}
		
			/*
			_news
		*/
		
		public function modifyBlockImages($dataArray=array() , $currentImage=''){
			$response = FALSE;
			if(!empty($dataArray)){
				$this->_ftp->passive = true; 
				if($this->_ftp->connect()){

					$localPath = '../temp/dm_'.$this->_DM_ID.'/block/';
					$imagename = $dataArray['imagename'];
					$extention = $dataArray['ext'];
					$largeImage = $this->_DM_ID.'_large_'.$imagename.'.'.$extention;
					$thumbImage = $this->_DM_ID.'_thumb_'.$imagename.'.'.$extention;
					
					$this->_ftp->put( $localPath.$largeImage , "media/block/large/".$imagename.'.'.$extention ,  FTP_BINARY);
					$this->_ftp->put( $localPath.$thumbImage , "media/block/thumbs/".$imagename.'.'.$extention ,  FTP_BINARY);
					
					unlink($localPath.$largeImage);
					unlink($localPath.$thumbImage);
					
					$this->_ftp->delete("media/block/large/".$currentImage);
					$this->_ftp->delete("media/block/thumbs/".$currentImage);
						
					$response = TRUE;
				}
			}
			return $response;
		}
		
		
		
		
		
		/*
			Default SQL Queries
		*/
		public function buildInitQuery(){
				// blocks
				$block_Val1 = array( $this->_DM_ID , 'N', 'Social Media Links', 'socialicons', '', '', '', 'N', 'Y');
				$block_Val2 = array( $this->_DM_ID , 'Y', 'block1', 'box1', '', '', 'Image description', 'Y', 'Y');
				$block_Val3 = array( $this->_DM_ID , 'Y', 'block2', 'box2', '', '', '', 'Y', 'Y');
				$block_Val4 = array( $this->_DM_ID , 'Y', 'block3', 'box3', '', '', '', 'Y', 'Y');
				$block_Val5 = array( $this->_DM_ID , 'Y', 'block4', 'box4', '', '', '', 'Y', 'Y');
				$block_SQL = "INSERT INTO ml_blocks ( domain_id , block_type, block_title , identifier, block_text , image, alt_tag, islink, status) VALUES
								( ? , ? , ? , ? , ? , ? , ? , ? , ? )";
				
				// blocks pages
				$blockpages_Val1 = array( $this->_DM_ID , 'block#1', 'block#1', 'block1', '', '', '', '2012-11-15 11:30:09');
				$blockpages_Val2 = array( $this->_DM_ID , 'block#2', 'block#2', 'block2', '', '', '', '2012-11-15 11:31:07');
				$blockpages_Val3 = array( $this->_DM_ID , 'block#3', 'block#3', 'block3', '', '', '', '2012-11-15 11:33:57');
				$blockpages_Val4 = array( $this->_DM_ID , 'block#4', 'block#4', 'block4', '', '', '', '2012-11-15 11:34:27');
				$blockpages_SQL = "INSERT INTO ml_block_pages ( domain_id , page_title , page_text , page_url, head_title , head_keywords , head_description , date_created) VALUES
								( ? , ?, ?, ?, ?, ?, ?, ?)";
				
				// module pages
				$modulepages_Val1 = array($this->_DM_ID  , 'Home Page', '', '', 'simple keywords', 'meta description will come here', '2012-11-22 00:00:00');
				$modulepages_Val2 = array($this->_DM_ID , 'Image Gallery', '', '', '', 'page description', '2012-11-22 17:44:30');
				$modulepages_Val3 = array($this->_DM_ID , 'Testimonials and Reviews', '', 'Testimonials ', '', 'place description here', '2012-11-22 00:00:00');
				$modulepages_Val4 = array($this->_DM_ID , 'News', '', 'News', 'Awards, events', 'page description', '2012-11-22 17:51:34');
				$modulepages_SQL = "INSERT INTO ml_module_pages ( domain_id , page_title , page_text , head_title, head_keywords , head_description , date_modified) VALUES
								( ? , ? , ? , ? , ? , ? , ? )";
				
				$pointer = 0;
				$this->initDb();
				$sqlQuery = "SELECT menu_id FROM ml_menus ORDER BY menu_id DESC LIMIT 1";
				$dataArray = $this->_db->run($sqlQuery);
				$this->dispose();
				if(count($dataArray) > 0){
					$pointer = $dataArray['menu_id'];
				}
				
				// menus
				$menus_Val1 = array( $this->_DM_ID , 'Home', 'index.php', 'left,footer', ($pointer+1) , ($pointer+1) , 'Y');
				$menus_Val2 = array( $this->_DM_ID , 'Testimonials', 'testimonials.php', 'left,footer', ($pointer+2), ($pointer+2), 'Y');
				$menus_Val3 = array( $this->_DM_ID , 'News', 'news.php', 'left,footer', ($pointer+3), ($pointer+3), 'Y');
				$menus_Val4 = array( $this->_DM_ID , 'Contact Us', 'contact-us.php', 'left,footer', ($pointer+4), ($pointer+4), 'Y');
				$menus_Val6 = array( $this->_DM_ID , 'Portfolio', 'portfolio.php', 'left,footer', ($pointer+5), ($pointer+5), 'Y');
				$menus_SQL = "INSERT INTO ml_menus ( domain_id , menu_label , menu_url , menu_types , leftmenu_sort_order , footermenu_sort_order , status ) VALUES
								( ? , ? , ? , ? , ?,  ?, ?)";
				
				// settings
				$sett_Val1 = array($this->_DM_ID , 'dateformat' , 'l, F d, Y');
				$sett_Val2 = array($this->_DM_ID , 'test', 'r,sh');
				$sett_Val3 = array($this->_DM_ID , 'ccc', '');
				$sett_Val4 = array($this->_DM_ID , 'bcc', '');
				$sett_Val5 = array($this->_DM_ID , 'bussinessname', '');
				$sett_Val6 = array($this->_DM_ID , 'jscodes', '');
				$sett_Val7 = array($this->_DM_ID , 'portfolio', '{"twidth":"160","theight":"120","lwidth":"500","lheight":"420","nodisplay":"19","displaystyle":"a"}');
				$sett_Val8 = array($this->_DM_ID , 'googlecodes', 'google code testasdasd');
				$sett_Val9 = array($this->_DM_ID , 'nofollowdays', '10' );
				$sett_Val10 = array($this->_DM_ID , 'leftadcode', 'Left Side Google adSense Code');
				$sett_Val11 = array($this->_DM_ID ,  'rightadcode', 'Right Side Google adSense Code');
				$sett_Val12 = array($this->_DM_ID , 'middleadcode', 'Middle Side Google adSense Code' );
				$settings_SQL = "INSERT INTO ml_settings ( domain_id , variable_key , variable_value ) VALUES 
								( ? , ?, ? )";
				
				
				$this->initDb();
				$this->_db->insertQuery( $block_SQL , $block_Val1);
				$this->_db->insertQuery( $block_SQL , $block_Val2);
				$this->_db->insertQuery( $block_SQL , $block_Val3);
				$this->_db->insertQuery( $block_SQL , $block_Val4);
				$this->_db->insertQuery( $block_SQL , $block_Val5);
				
				$this->_db->insertQuery( $blockpages_SQL , $blockpages_Val1);
				$this->_db->insertQuery( $blockpages_SQL , $blockpages_Val2);
				$this->_db->insertQuery( $blockpages_SQL , $blockpages_Val3);
				$this->_db->insertQuery( $blockpages_SQL , $blockpages_Val4);
				
				$this->_db->insertQuery( $modulepages_SQL , $modulepages_Val1);
				$this->_db->insertQuery( $modulepages_SQL , $modulepages_Val2);
				$this->_db->insertQuery( $modulepages_SQL , $modulepages_Val3);
				$this->_db->insertQuery( $modulepages_SQL , $modulepages_Val4);
				
				$this->_db->insertQuery( $menus_SQL , $menus_Val1);
				$this->_db->insertQuery( $menus_SQL , $menus_Val2);
				$this->_db->insertQuery( $menus_SQL , $menus_Val3);
				$this->_db->insertQuery( $menus_SQL , $menus_Val4);
				$this->_db->insertQuery( $menus_SQL , $menus_Val5);
				$this->_db->insertQuery( $menus_SQL , $menus_Val6);
				
				$this->_db->insertQuery( $settings_SQL , $sett_Val1);
				$this->_db->insertQuery( $settings_SQL , $sett_Val2);
				$this->_db->insertQuery( $settings_SQL , $sett_Val3);
				$this->_db->insertQuery( $settings_SQL , $sett_Val4);
				$this->_db->insertQuery( $settings_SQL , $sett_Val5);
				$this->_db->insertQuery( $settings_SQL , $sett_Val6);
				$this->_db->insertQuery( $settings_SQL , $sett_Val7);
				$this->_db->insertQuery( $settings_SQL , $sett_Val8);
				$this->_db->insertQuery( $settings_SQL , $sett_Val9);
				$this->_db->insertQuery( $settings_SQL , $sett_Val10);
				$this->_db->insertQuery( $settings_SQL , $sett_Val11);
				$this->_db->insertQuery( $settings_SQL , $sett_Val12);
				
				$this->dispose();	
		}
		
			
		
		/*
			_remove domain
		*/
		function removedomain(){
			
			$_rootDir = $this->_ftp->ls();
			
			if($this->_ftp->connect()) {
				if(in_array('sitemap.xml',$_rootDir)){
					$this->_ftp->delete("sitemap.xml");
				}
			}
			
			/*
			if(in_array('media',$_rootDir)){
				$this->_ftp->ftp_rmdirr('media');	
			}
			*/
			
			
			if (file_exists("../temp")) {
				$folder = "../temp/dm_".$this->_DM_ID;
				if(file_exists($folder)){ 
					$this->rmdirr($folder);
				}	
			}
			
			// remove db
			$this->initDb();
			$this->_db->delete("ml_blocks", "domain_id='{$this->_DM_ID}'");
			$this->_db->delete("ml_ml_block_pages", "domain_id='{$this->_DM_ID}'");
			$this->_db->delete("ml_module_pages", "domain_id='{$this->_DM_ID}'");
			$this->_db->delete("ml_menus", "domain_id='{$this->_DM_ID}'");
			$this->_db->delete("ml_settings", "domain_id='{$this->_DM_ID}'");
			$this->dispose();	
				
		}
		
		
		
		
		
		/* 
			_core functions
		*/
			
		public function rmdirr($dirname){
			
			if (!file_exists($dirname)) {
				return false;
			}
			
			if (is_file($dirname)) {
				return unlink($dirname);
			}
			
			$dir = dir($dirname);
			while (false !== $entry = $dir->read()) {
				if ($entry == '.' || $entry == '..') {
					continue;
				}	
				$this->rmdirr("$dirname/$entry");
			}
			
			$dir->close();
			return rmdir($dirname);
		}

		
	}  // $
