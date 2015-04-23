<?php
	

	class PortfolioController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function addAction(){
			
			$mdlObj = new Portfolio();
			$this->_db = new Pdodb($this->_dbinfo);
			$DM_ID 			= Session::get('DOMAIN_ID');
			$postedValues = $this->getValues();	
			$data = array();
			
			$folderPath 		= '../temp/dm_'.$DM_ID.'/portfolio/';
			
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			$boot 				= new bootstrap();
			$uploadtype  		= '';
			$allowImageTypes 	= $boot->getMedia('allowimgtypes');
			$gridimageArray    	= $boot->getMedia('gridimage');
			$gridimageSize      = $gridimageArray['size'];
			
			$setObject 			= new Settings();
			$defaultportfolio 	= $setObject->fetchById('portfolio');
			$thumbWidth 		= $setObject->getByJson('twidth',$defaultportfolio);
			$thumbHeight		= $setObject->getByJson('theight',$defaultportfolio);
			$largeWidth			= $setObject->getByJson('lwidth',$defaultportfolio);
			$largeHeight		= $setObject->getByJson('lheight',$defaultportfolio);
			$gridWidth			= $gridimageSize['width'];
			$gridHeight			= $gridimageSize['height'];
			
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$filename = '';
				$ftpImagesArray = array();
				$cropObj = new ThumbnCrop();
				if(!empty($_FILES['image']['name'])){
					
					$uploadtype = $_FILES['image']['type'];
					
					if(in_array( $uploadtype , $allowImageTypes)){
						
						$upObj = new EasyUploads($folderPath);     // save raw images
						$result = $upObj->upload($_FILES['image'], $DM_ID.'_raw_');
						$cropObj->openImage($upObj->getFileLocation());
						
						
						$cropObj->createThumbRatio( $thumbWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);   // save thumb images
						
						$cropObj->createThumbRatio( $largeWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_large_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save large croped images
						
						$cropObj->createThumbRatio( $gridWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_grid_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save grid croped images
						
						//$filename = $upObj->getFileName();
						$filename = $upObj->rawFilename.'.'.$upObj->rawFileextention;
						
						$ftpImagesArray = array('imagename'=>$upObj->rawFilename , 'ext'=>$upObj->rawFileextention);
						
						$cropObj->closeImg();
					}else{
						Message::setResponseMessage("Only these types of images allowed to upload : png, jpg, gif ", 'e');
						header("Location: ".$_SERVER['HTTP_REFERER']);
						exit();
					}
				}
				
				// _ftp 
				$response = $ftp->insertPortfolioImages($ftpImagesArray);
				
				if($response){
					
					$data['image'] 				= $filename;
					$data['domain_id']			= $DM_ID;
					$data['alt_tag'] 			= $postedValues['alt_tag'];
					$data['short_description'] 	= $postedValues['short_description'];
					$data['full_description'] 	= $postedValues['full_description'];
					$data['date_created'] 		= DateUtil::curDateDb();
		
					$mdlObj->save( Portfolio::_TABLE , $data , '' , $this->_db );
					
					Message::setResponseMessage("New portfolio item added successfully!", 's');
					Request::redirect("manage-portfolio.php?q=show");
				}else{
					Message::setResponseMessage("Issue found in FTP , please try again", 'e');
					Request::redirect("manage-portfolio.php?q=show");	
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-portfolio.php?q=show");	
			}
			
		}
		
		
		private function modifyAction(){
			
			$mdlObj 	= new Portfolio();
			$this->_db 	= new Pdodb($this->_dbinfo);
			$DM_ID 		= Session::get('DOMAIN_ID');
			$postedValues = $this->getValues();
			$pid    	= $postedValues['pid'];
			$data 		= array();
			
			$folderPath 		= '../temp/dm_'.$DM_ID.'/portfolio/';
			
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			
			$boot 				= new bootstrap();
			$uploadtype  		= '';
			$allowImageTypes 	= $boot->getMedia('allowimgtypes');
			$gridimageArray    	= $boot->getMedia('gridimage');
			$gridimageSize      = $gridimageArray['size'];
			
			$setObject 			= new Settings();
			$defaultportfolio 	= $setObject->fetchById('portfolio');
			$thumbWidth 		= $setObject->getByJson('twidth',$defaultportfolio);
			$thumbHeight		= $setObject->getByJson('theight',$defaultportfolio);
			$largeWidth			= $setObject->getByJson('lwidth',$defaultportfolio);
			$largeHeight		= $setObject->getByJson('lheight',$defaultportfolio);
			$gridWidth			= $gridimageSize['width'];
			$gridHeight			= $gridimageSize['height'];
			
			
			$cropObj = new ThumbnCrop();
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$currentImage = $filename = '';
				$ftpImagesArray = array();
				
				if(!empty($_FILES['image']['name'])){
					$uploadtype = $_FILES['image']['type'];
					if(in_array( $uploadtype , $allowImageTypes)){
						
							$upObj = new EasyUploads($folderPath);     // save raw images
							$result = $upObj->upload($_FILES['image'], $DM_ID.'_raw_');
							$cropObj->openImage($upObj->getFileLocation());
							
							$cropObj->createThumbRatio( $thumbWidth );
							$cropObj->setThumbAsOriginal();
							$cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);   // save thumb images
							
							$cropObj->createThumbRatio( $largeWidth );
							$cropObj->setThumbAsOriginal();
							$cropObj->saveThumb($folderPath.$DM_ID.'_large_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save large croped images
							
							$cropObj->createThumbRatio( $gridWidth );
							$cropObj->setThumbAsOriginal();
							$cropObj->saveThumb($folderPath.$DM_ID.'_grid_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save grid croped images
							
							$cropObj->closeImg();
							
							$filename = $upObj->rawFilename.'.'.$upObj->rawFileextention;
							$data['image'] = $filename;
							
							$ftpImagesArray = array('imagename'=>$upObj->rawFilename , 'ext'=>$upObj->rawFileextention);
							
							$currentImage = $postedValues['currentImage'];
							
						
					}else{
						Message::setResponseMessage("Only these types of images allowed to upload : png, jpg, gif ", 'e');
						header("Location: ".$_SERVER['HTTP_REFERER']);
						exit();
					}
				}
				
				
				$data['alt_tag'] 			= $postedValues['alt_tag'];
				$data['short_description'] 	= $postedValues['short_description'];
				$data['full_description'] 	= $postedValues['full_description'];
					
				if(empty($filename)){
					$mdlObj->save(Portfolio::_TABLE , $data , "pid='$pid' AND domain_id='$DM_ID'" , $this->_db);
					Message::setResponseMessage("New portfolio item modified successfully!", 's');
					Request::redirect("manage-portfolio.php?q=show");
				}else{
					$response = $ftp->modifyPortfolioImages($ftpImagesArray , $currentImage);
				
					if($response){		
	
						$mdlObj->save(Portfolio::_TABLE , $data , "pid='$pid' AND domain_id='$DM_ID'" , $this->_db);
						Message::setResponseMessage("New portfolio item modified successfully!", 's');
						Request::redirect("manage-portfolio.php?q=show");
					}else{
						Message::setResponseMessage("Issue found in FTP , please try again", 'e');
						Request::redirect("manage-portfolio.php?q=show");
					}
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-portfolio.php?q=show");	
			}
			
		}
		
		
		private function enableAction(){
			$pid = $this->getValue('pid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Portfolio();
			$dateArray = array('status'=>'Y');
			$mdlObj->save( Portfolio::_TABLE , $dateArray ,"pid='$pid'" ,$this->_db);
			Message::setResponseMessage("Selected portfolio item enabled successfully!", 's');
			Request::redirect('manage-portfolio.php?q=show');
		}
		
		private function disableAction(){
			$pid = $this->getValue('pid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Portfolio();
			$dateArray = array('status'=>'N');
			$mdlObj->save( Portfolio::_TABLE , $dateArray ,"pid='$pid'" ,$this->_db);
			Message::setResponseMessage("Selected portfolio item disabled successfully!", 's');
			Request::redirect('manage-portfolio.php?q=show');
		}
		
		
		private function removeAction(){
			
			$pid = $this->getValue('pid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Portfolio();
			
			$DM_ID 		= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				
				
				$currentData 	= $mdlObj->fetchById($pid);
				$currentImage	= $currentData['image'];
				
				$response = $ftp->removePortfolioImage($currentImage);
				if($response){
					
					$mdlObj->remove(Portfolio::_TABLE , "pid='$pid'" , $this->_db);
					Message::setResponseMessage("Selected portfolio item removed successfully.", 's');
					Request::redirect('manage-portfolio.php?q=show');
				}else{
					Message::setResponseMessage("Issue found in FTP , please try again", 'e');
					Request::redirect("manage-portfolio.php?q=show");
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-portfolio.php?q=show");	
			}
		}
		
		
		
	} //$
	
	
	
?>