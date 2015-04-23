<?php
	

	class NewsController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		private function addAction(){
			$mdlObj = new News();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$html = $postedValues['news_detail_text'];
			if(!empty($html)){
				$postedValues['news_detail_text'] = stripslashes($html);	
			}
			
			$filteredValues = $mdlObj->filter($postedValues , $this->_db->columns(News::_TABLE));
			
			$DM_ID 			= Session::get('DOMAIN_ID');
			$folderPath 		= '../temp/dm_'.$DM_ID.'/news/';
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			
			$boot 				= new bootstrap();
			$uploadtype  		= '';
			$allowImageTypes 	= $boot->getMedia('allowimgtypes');
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				  $filename = '';
				  $cropObj = new ThumbnCrop();
				  $ftpImagesArray = array();
				  if(!empty($_FILES['iconfile']['name'])){
					  
					  $uploadtype = $_FILES['iconfile']['type'];
					  
					  if(in_array( $uploadtype , $allowImageTypes)){
						  
						  $upObj = new EasyUploads($folderPath);
						  $result = $upObj->upload( $_FILES['iconfile'] , $DM_ID.'_large_');
						  
						  $cropObj->openImage($upObj->getFileLocation());
						  
						  $cropObj->createThumbRatio( 122 );
						  $cropObj->setThumbAsOriginal();
						  $cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention); 
						  
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
				$response = $ftp->insertNewsImages($ftpImagesArray);
				
				if($response){
					  $filteredValues['domain_id']	= $DM_ID;
					  $filteredValues['news_img'] 	= $filename;
					  $filteredValues['news_date'] 	= DateUtil::setDateformat($postedValues['news_date']);
					  $filteredValues['date_created'] = DateUtil::curDateDb();
					  
					  $mdlObj->save(News::_TABLE , $filteredValues , '' , $this->_db);
					  
					  Message::setResponseMessage("New news created successfully!", 's');
					  Request::redirect("manage-news.php?q=show");
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
			$mdlObj = new News();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();
			$nid    = $postedValues['nid'];
			$html = $postedValues['news_detail_text'];
			if(!empty($html)){
				$postedValues['news_detail_text'] = stripslashes($html);	
			}	
			$filteredValues = $mdlObj->filter($postedValues , $this->_db->columns(News::_TABLE));
			
			$DM_ID 			= Session::get('DOMAIN_ID');
			$folderPath 		= '../temp/dm_'.$DM_ID.'/news/';
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			$boot 				= new bootstrap();
			$uploadtype  		= '';
			$allowImageTypes 	= $boot->getMedia('allowimgtypes');
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$ftpImagesArray = array();
				$filename = '';
				$cropObj = new ThumbnCrop();
				if(!empty($_FILES['iconfile']['name'])){
					
					$filename = '';
					$uploadtype = $_FILES['iconfile']['type'];
					
					if(in_array( $uploadtype , $allowImageTypes)){
						
						$upObj = new EasyUploads($folderPath);
						$result = $upObj->upload( $_FILES['iconfile'] , $DM_ID.'_large_');
						
						$cropObj->openImage($upObj->getFileLocation());
						
						$cropObj->createThumbRatio( 122 );
						$cropObj->setThumbAsOriginal();
						$cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention); 
						
						$filename = $upObj->rawFilename.'.'.$upObj->rawFileextention;
						$ftpImagesArray = array('imagename'=>$upObj->rawFilename , 'ext'=>$upObj->rawFileextention);
					  
						$cropObj->closeImg();
						
						$filteredValues['news_img'] = $filename;
						
					}else{
						Message::setResponseMessage("Only these types of images allowed to upload : png, jpg, gif ", 'e');
						header("Location: ".$_SERVER['HTTP_REFERER']);
						exit();
					}
					
				}
				
				$filteredValues['news_date'] = DateUtil::setDateformat($postedValues['news_date']);
				
				if(empty($filename)){
						$mdlObj->save(News::_TABLE , $filteredValues , "news_id='$nid'" , $this->_db);
						Message::setResponseMessage("Selected news modified successfully!" , 's');
						Request::redirect("manage-news.php?q=show");
					}else{
						$currentImage = $postedValues['currentImage'];
						$response = $ftp->modifyNewsImages($ftpImagesArray , $currentImage);
						if($response){
							$mdlObj->save(News::_TABLE , $filteredValues , "news_id='$nid'" , $this->_db);
							Message::setResponseMessage("Selected news modified successfully!" , 's');
							Request::redirect("manage-news.php?q=show");
						}else{
							Message::setResponseMessage("Issue found in FTP , please try again", 'e');
							Request::redirect("manage-news.php?q=show");	
						}
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-news.php?q=show");	
			}	
		}
		
		private function removeAction(){
			$nid = $this->getValue('nid');
			$this->_db = new Pdodb($this->_dbinfo);
			
			$DM_ID 	= Session::get('DOMAIN_ID');
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$mdlObj = new News();
				$dataArray = $mdlObj->fetchById($nid);
				$currentImage	= $dataArray['news_img'];
				
				$response = $ftp->removeNewsImage($currentImage);
				if($response){
					$mdlObj->remove(News::_TABLE , "news_id='$nid'" , $this->_db);
					
					Message::setResponseMessage("Selected news removed successfully.", 's');
					Request::redirect("manage-news.php?q=show");
				}else{
					Message::setResponseMessage("Issue found in FTP , please try again", 'e');
					Request::redirect("manage-portfolio.php?q=show");
				}
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-portfolio.php?q=show");	
			}
		}
		
		private function enableAction(){
			$nid = $this->getValue('nid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new News();
			$dateArray = array('status'=>'Y');
			$mdlObj->save( Testimonial::_TABLE , $dateArray ,"news_id='$nid'" ,$this->_db);
			Message::setResponseMessage("Selected news published successfully!", 's');
			Request::redirect("manage-news.php?q=show");
		}
		
		private function disableAction(){
			$nid = $this->getValue('nid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new News();
			$dateArray = array('status'=>'N');
			$mdlObj->save( News::_TABLE , $dateArray ,"news_id='$nid'" ,$this->_db);
			Message::setResponseMessage("Selected news un-publish successfully!", 's');
			Request::redirect("manage-news.php?q=show");			
		}
		
		
		
		
	} //$
	
	
	
?>