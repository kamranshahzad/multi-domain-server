<?php
	

	class HtmlController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		
		private function modifyAction(){
			$bid = $this->getValue('bid');
			$mdlObj = new Html();
			$this->_db = new Pdodb($this->_dbinfo);
			$postedValues = $this->getValues();	
			$html = $postedValues['block_text'];
			if(!empty($html)){
				$postedValues['block_text'] = stripslashes($html);	
			}
			$filteredValues = $mdlObj->filter($postedValues,$this->_db->columns(Html::_TABLE));
			
			
			$DM_ID 			= Session::get('DOMAIN_ID');
			$folderPath 	= '../temp/dm_'.$DM_ID.'/block/';
			$domain = new Domains();
			$ftp    = new FTPWorker( $domain->fetchDomainFTP($DM_ID) , $DM_ID );
			
			
			$boot 			= new bootstrap();
			$uploadtype  	= '';
			$allowImageTypes = $boot->getMedia('allowimgtypes');
			
			$filename		= '';
			if( FTPWorker::Ping( $domain->fetchDomainFTP($DM_ID))){
				$ftpImagesArray = array();
				$cropObj = new ThumbnCrop();
				if(!empty($_FILES['image']['name'])){
					
					$uploadtype = $_FILES['image']['type'];
					
					if(in_array( $uploadtype , $allowImageTypes)){
							$upObj = new EasyUploads($folderPath);
							$result = $upObj->upload($_FILES['image']  , $DM_ID.'_large_' );
							
							$cropObj->openImage($upObj->getFileLocation());
							
							$cropObj->createThumbRatio( 200 );
							$cropObj->setThumbAsOriginal();
							$cropObj->saveThumb($folderPath.$DM_ID.'_thumb_'.$upObj->rawFilename.'.'.$upObj->rawFileextention); 
							
							$filename = $upObj->rawFilename.'.'.$upObj->rawFileextention;
						  	
							$ftpImagesArray = array('imagename'=>$upObj->rawFilename , 'ext'=>$upObj->rawFileextention);
							$cropObj->closeImg();
							
							$filteredValues['image'] = $filename;
							
					}else{
						Message::setResponseMessage("Only these types of images allowed to upload : png, jpg, gif ", 'e');
						Request::redirect('manage-blocks.php?q=modify&bid='.$bid);
						exit();
					}
				}
				
				if(!array_key_exists('islink',$filteredValues)){
					$filteredValues['islink'] = 'N';
				}
				
				if(empty($filename)){
					$mdlObj->save( Html::_TABLE , $filteredValues , "block_id='$bid'" ,$this->_db);
					Message::setResponseMessage("Selected block modify successfully.", 's');
					Request::redirect('manage-blocks.php?q=show');
				}else{
					$response = $ftp->modifyBlockImages($ftpImagesArray , $postedValues['currentImage']);
					
					if($response){
						$mdlObj->save( Html::_TABLE , $filteredValues , "block_id='$bid'" ,$this->_db);
						Message::setResponseMessage("Selected block modify successfully.", 's');
						Request::redirect('manage-blocks.php?q=show');
					}else{
						Message::setResponseMessage("Issue found in FTP , please try again", 'e');
						Request::redirect("manage-blocks.php?q=show");	
					}
					
				}	
			}else{
				Message::setResponseMessage("FTP not connecting , please check FTP information!", 'e');
				Request::redirect("manage-blocks.php?q=show");	
			}	
		}
		
		
		private function enableAction(){
			$bid = $this->getValue('bid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Html();
			$dateArray = array('status'=>'Y');
			$mdlObj->save( Html::_TABLE , $dateArray ,"block_id='$bid'" ,$this->_db);
			Message::setResponseMessage("Selected block active successfully!", 's');
			Request::redirect('manage-blocks.php?q=show');
		}
		
		private function disableAction(){
			$bid = $this->getValue('bid');
			$this->_db = new Pdodb($this->_dbinfo);
			$mdlObj = new Html();
			$dateArray = array('status'=>'N');
			$mdlObj->save( Html::_TABLE , $dateArray ,"block_id='$bid'" ,$this->_db);
			Message::setResponseMessage("Selected block disabled successfully!", 's');
			Request::redirect('manage-blocks.php?q=show');
		}
		
		
		
		
	} //$
	
	
	
?>