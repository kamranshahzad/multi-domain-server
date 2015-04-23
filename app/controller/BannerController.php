<?php
	

	class BannerController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		// define actions
		
		private function addAction(){
			
			$mdlObj = new Banner();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			
			$DM_ID 			= Session::get('DOMAIN_ID');
			$folderPath 		= '../temp/dm_'.$DM_ID.'/banner/';	
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			
			$boot 				= new bootstrap();
			$uploadtype  		= '';
			$allowImageTypes 	= $boot->getMedia('allowimgtypes');
			$gridimageArray    	= $boot->getMedia('gridimage');
			$gridimageSize      = $gridimageArray['size'];
			
			$gridWidth			= $gridimageSize['width'];
			$gridHeight			= $gridimageSize['height'];
			
			$largeimageArray    = $boot->getMedia('banner');
			$largeimageSize      = $largeimageArray['size'];
			
			$thumbWidth			= 200;
			$thumbHeight		= 180;
			$largeWidth			= $largeimageSize['width'];
			$largeHeight		= $largeimageSize['height'];
			
			$filename = '';
			$cropObj = new ThumbnCrop();
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				
				$ftpImagesArray = array();
				if(!empty($_FILES['image']['name'])){
					
					$uploadtype = $_FILES['image']['type'];
					
					if(in_array( $uploadtype , $allowImageTypes)){
						
						$upObj = new EasyUploads($folderPath);     // save raw images
						$result = $upObj->upload($_FILES['image'] , $DM_ID.'_raw_');
						$filename = $upObj->rawFilename.'.'.$upObj->rawFileextention;
						
						$imageLocation = $folderPath.$DM_ID.'_raw_'.$filename;
						
						$IMG_META 	= getimagesize($imageLocation);
						$IMG_WIDTH 	= $IMG_META[0];
						$IMG_HEIGHT = $IMG_META[1];

						$cropObj->openImage($imageLocation);
						
						if($IMG_WIDTH == $largeWidth && $IMG_HEIGHT == $largeHeight){
							copy( $imageLocation , $folderPath.$DM_ID.'_large_'.$upObj->rawFilename.'.'.$upObj->rawFileextention  );
						}else{
							$cropObj->createThumbRatio( $largeWidth );
							$cropObj->setThumbAsOriginal();
							$cropObj->saveThumb($folderPath.$DM_ID.'_large_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save large croped images
						}
					
						$cropObj->createThumbRatio( $thumbWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);   // save thumb images
						
						$cropObj->createThumbRatio( $gridWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_grid_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save grid croped images
	
						$cropObj->closeImg();
						
						$ftpImagesArray = array('imagename'=>$upObj->rawFilename , 'ext'=>$upObj->rawFileextention);
						
					}else{
						Message::setResponseMessage("Only these types of images allowed to upload : png, jpg, gif ", 'e');
						header("Location: ".$_SERVER['HTTP_REFERER']);
						exit();
					}
				}
				
								// _ftp 
				$response = $ftp->insertBannerImages($ftpImagesArray);
				
				if($response){
					
					$data['domain_id'] 		= $DM_ID;
					$data['banner_image'] 	= $filename;
					$data['image_alttag'] 	= $postedValues['alt_tag'];
					$data['description'] 	= $postedValues['short_description'];
					$data['status'] 		= $postedValues['status'];
					$data['date_created'] 	= DateUtil::curDateDb();
		
					$mdlObj->save( Banner::_TABLE , $data , '' , $this->_db );
					
					Message::setResponseMessage("New banner image added successfully!", 's');
					Request::redirect("manage-banner.php?q=show");
				}else{
					Message::setResponseMessage("Issue found in FTP , please try again", 'e');
					Request::redirect("manage-portfolio.php?q=show");	
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-banner.php?q=show");	
			}
		}
		
		
		private function modifyAction(){
			
			$mdlObj 	= new Banner();
			$boot 		= new bootstrap();
			$this->_db 	= new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();
			$bid    	= $postedValues['bid'];
			$data 		= array();
			
			$DM_ID 			= Session::get('DOMAIN_ID');
			$folderPath 		= '../temp/dm_'.$DM_ID.'/banner/';	
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			$uploadtype  		= '';
			$allowImageTypes 	= $boot->getMedia('allowimgtypes');
			$gridimageArray    	= $boot->getMedia('gridimage');
			$gridimageSize      = $gridimageArray['size'];
			
			$gridWidth			= $gridimageSize['width'];
			$gridHeight			= $gridimageSize['height'];
			
			$largeimageArray    = $boot->getMedia('banner');
			$largeimageSize      = $largeimageArray['size'];
			
			$thumbWidth			= 200;
			$thumbHeight		= 180;
			$largeWidth			= $largeimageSize['width'];
			$largeHeight		= $largeimageSize['height'];
			
			$filename = '';
			$cropObj = new ThumbnCrop();
			
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$ftpImagesArray = array();
				$filename = '';
				if(!empty($_FILES['image']['name'])){
					
					$uploadtype = $_FILES['image']['type'];
					if(in_array( $uploadtype , $allowImageTypes)){
						
						$upObj = new EasyUploads($folderPath);
						$result = $upObj->upload($_FILES['image'] , $DM_ID.'_raw_');
						
						$filename = $upObj->rawFilename.'.'.$upObj->rawFileextention;
						$imageLocation = $folderPath.$DM_ID.'_raw_'.$filename;
						
						$IMG_META 	= getimagesize($imageLocation);
						$IMG_WIDTH 	= $IMG_META[0];
						$IMG_HEIGHT = $IMG_META[1];
						
						$cropObj->openImage($imageLocation);
						
						if($IMG_WIDTH == $largeWidth && $IMG_HEIGHT == $largeHeight){
							copy( $imageLocation , $folderPath.$DM_ID.'_large_'.$upObj->rawFilename.'.'.$upObj->rawFileextention  );
						}else{
							$cropObj->createThumbRatio( $largeWidth );
							$cropObj->setThumbAsOriginal();
							$cropObj->saveThumb($folderPath.$DM_ID.'_large_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save large croped images
						}
						
						$cropObj->createThumbRatio( $thumbWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);   // save thumb images
						
						$cropObj->createThumbRatio( $gridWidth );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_grid_'.$upObj->rawFilename.'.'.$upObj->rawFileextention);  // save grid croped images
	
						$cropObj->closeImg();
						
						$ftpImagesArray = array('imagename'=>$upObj->rawFilename , 'ext'=>$upObj->rawFileextention);
						
						$data['banner_image'] 	= $filename;				
					}
				}
				
				$data['image_alttag'] 	= $postedValues['alt_tag'];
				$data['description'] 	= $postedValues['short_description'];
				$data['status'] 		= $postedValues['status'];
					
					
				if(empty($filename)){
					$mdlObj->save( Banner::_TABLE , $data , "banner_id='$bid'" , $this->_db );
					Message::setResponseMessage("New banner image modified successfully!", 's');
					Request::redirect("manage-banner.php?q=show");
				}else{
					$currentImage = $postedValues['currentImage'];
					$response = $ftp->modifyBannerImages($ftpImagesArray , $currentImage);
					if($response){
						$mdlObj->save( Banner::_TABLE , $data , "banner_id='$bid'" , $this->_db );
						Message::setResponseMessage("New banner image modified successfully!", 's');
						Request::redirect("manage-banner.php?q=show");
					}else{
						Message::setResponseMessage("Issue found in FTP , please try again", 'e');
						Request::redirect("manage-portfolio.php?q=show");	
					}
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-banner.php?q=show");	
			}	
		}
		
		private function enableAction(){
			$bid = $this->getValue('bid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Banner();
			$dateArray = array('status'=>'Y');
			$mdlObj->save( Banner::_TABLE , $dateArray ,"banner_id='$bid'" ,$this->_db);
			Message::setResponseMessage("Selected banner enabled successfully!", 's');
			Request::redirect('manage-banner.php?q=show');
		}
		
		private function disableAction(){
			$bid = $this->getValue('bid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Banner();
			$dateArray = array('status'=>'N');
			$mdlObj->save( Banner::_TABLE , $dateArray ,"banner_id='$bid'" ,$this->_db);
			Message::setResponseMessage("Selected banner image disabled successfully!", 's');
			Request::redirect('manage-banner.php?q=show');
		}
		
		
		private function removeAction(){
			
			$bid = $this->getValue('bid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Banner();
			
			$DM_ID 		= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$currentData 	= $mdlObj->fetchById($bid);
				$currentImage	= $currentData['banner_image'];
				$response = $ftp->removeBannerImage($currentImage);
				
				if($response){
					$mdlObj->remove( Banner::_TABLE , "banner_id='$bid'" , $this->_db);
					
					Message::setResponseMessage("Selected banner image removed successfully.", 's');
					Request::redirect('manage-banner.php?q=show');
				}else{
					Message::setResponseMessage("Issue found in FTP , please try again", 'e');
					Request::redirect("manage-banner.php?q=show");
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-banner.php?q=show");	
			}
		}
		
		
		/*
			_helper functions
		*/
		
		private function createWindow(){
			
		}
		
		private function createAndResizeImage($assetname=''){
			if(!empty($assetname)){
				if(file_exists($assetname)){
										
				}
			}
		}
		
	
			
	} //$
	
	
	
?>