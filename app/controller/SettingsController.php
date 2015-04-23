<?php

	
class SettingsController extends Controller {
	
	private $_dbinfo;
	private $_db;
		
	function __construct() {
		$configObj 	= new config();
		$this->_dbinfo 	= $configObj->getDbConfig();
		parent::__construct();			
		call_user_func(array($this, $this->getAction()));
	}
	
	private function generalAction(){
		$this->_db  = new Pdodb($this->_dbinfo);	
		$DM_ID 		= Session::get('DOMAIN_ID');
		$setObject = new Settings();
		
		$dateformat = $this->getValue('dateformat');
		$setObject->save( Settings::_TABLE , array('variable_value'=>$dateformat) , "variable_key='dateformat' AND domain_id='$DM_ID'" ,$this->_db);
		
		$bussniessname = $this->getValue('bussinessnameTxt');
		$setObject->save( Settings::_TABLE , array('variable_value'=>$bussniessname) , "variable_key='bussinessname' AND domain_id='$DM_ID'" ,$this->_db);
		
		
		Message::setResponseMessage("General settings saved successfully.", 's');
		Request::redirect('site-settings.php?q=show');
	}
	
	private function testAction(){
		$this->_db  = new Pdodb($this->_dbinfo);
		$DM_ID 		= Session::get('DOMAIN_ID');	
		$setObject = new Settings();
		
		
		$val1 = $this->getValue('test');
		$val2 = $this->getValue('testeffects');
		$value = $val1 .','.$val2; 
		$setObject->save( Settings::_TABLE , array('variable_value'=>$value) , "variable_key='test' AND domain_id='$DM_ID'" ,$this->_db);
		
		Message::setResponseMessage(" Testimonials settings saved successfully.", 's');
		Request::redirect('site-settings.php?q=show');	 
	}
	
	
	private function gsettingsAction(){
		$this->_db  = new Pdodb($this->_dbinfo);
		$DM_ID 		= Session::get('DOMAIN_ID');	
		$setObject = new Settings();
		
		Message::setResponseMessage(" Testimonials settings saved successfully.", 's');
		Request::redirect('site-settings.php?q=show');	
	}
	
	private function seosettingsAction(){
		$this->_db  = new Pdodb($this->_dbinfo);
		$DM_ID 		= Session::get('DOMAIN_ID');	
		$setObject = new Settings();
		
		Message::setResponseMessage(" Testimonials settings saved successfully.", 's');
		Request::redirect('site-settings.php?q=show');	
	}
	
	
	
	private function jscodesAction(){
		
		$this->_db 	= new Pdodb($this->_dbinfo);	
		$DM_ID 		= Session::get('DOMAIN_ID');
		
		$setObject = new Settings();
		
		$jscodes 	  = $this->getValue('jscodes');
		$googlecodes  = $this->getValue('googlecodes');
		$noFollowdays = $this->getValue('nofollowdays');
		
		$setObject->save( Settings::_TABLE , array('variable_value'=>$jscodes) , "variable_key='jscodes' AND domain_id='$DM_ID'" ,$this->_db);
		$setObject->save( Settings::_TABLE , array('variable_value'=>$googlecodes) , "variable_key='googlecodes' AND domain_id='$DM_ID'" ,$this->_db);
		$setObject->save( Settings::_TABLE , array('variable_value'=>$noFollowdays) , "variable_key='nofollowdays' AND domain_id='$DM_ID'" ,$this->_db);
		
		
		Message::setResponseMessage("Settings saved successfully.", 's');
		Request::redirect('js-codes.php');
			
	}
	
	private function googleadscodeAction(){
		
		$this->_db  = new Pdodb($this->_dbinfo);
		$DM_ID 		= Session::get('DOMAIN_ID');	
		$setObject = new Settings();

		$leftadcode 	= $this->getValue('leftsidecode');
		$rightadcode  	= $this->getValue('rightsidecode');
		$middleadcode 	= $this->getValue('middlebar');
		
		$setObject->save( Settings::_TABLE , array('variable_value'=>$leftadcode) , "variable_key='leftadcode' AND domain_id='$DM_ID'" ,$this->_db);
		$setObject->save( Settings::_TABLE , array('variable_value'=>$rightadcode) , "variable_key='rightadcode' AND domain_id='$DM_ID'" ,$this->_db);
		$setObject->save( Settings::_TABLE , array('variable_value'=>$middleadcode) , "variable_key='middleadcode' AND domain_id='$DM_ID'" ,$this->_db);
		
		
		Message::setResponseMessage("Settings saved successfully.", 's');
		Request::redirect('js-codes.php');

	}
	
	
	private function portfolio1Action(){
		
		$this->_db 	= new Pdodb($this->_dbinfo);	
		$setObject 	= new Settings();
		$DM_ID 		= Session::get('DOMAIN_ID');
		$postedValues = $this->getValues();
		
		$thumbWidth 	= $postedValues['portfolioThumbwidth'];
		$thumbHeight	= $postedValues['portfolioThumbheight'];
		
		// check by already given size
		$defaultportfolio = $setObject->fetchById('portfolio');
		$currThumbWidth   = $setObject->getByJson('twidth',$defaultportfolio);
		$currThumbHeight  = $setObject->getByJson('theight',$defaultportfolio);
		
		$thumbUpload 		= '../media/portfolio/thumbs/';
		$rawUpload 			= '../media/portfolio/raw/';
		
		
		if($thumbWidth != $currThumbWidth || $thumbHeight != $currThumbHeight ){
			$dataArray = $this->_db->select(Portfolio::_TABLE);
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$cropObj = new ThumbnCrop();
					$cropObj->openImage($rawUpload.$array['image']);
					$newHeight = $cropObj->getRightHeight($thumbWidth);
					$cropObj->createThumb( $thumbWidth , $thumbHeight);
					$cropObj->setThumbAsOriginal();
					$cropObj->saveThumb($thumbUpload.$array['image']); 
					$cropObj->closeImg();	
				}
			}
		}
		
		$otherData = json_decode($defaultportfolio , true );
		$data = array('twidth'=>$thumbWidth, 'theight'=>$thumbHeight);
		$outputArray = array_merge( $otherData , $data );
		$jsonData = json_encode($outputArray);
		
		$setObject->save( Settings::_TABLE , array('variable_value'=>$jsonData) , "variable_key='portfolio' AND domain_id='$DM_ID'" ,$this->_db);
		
		Message::setResponseMessage("Portfolio settings saved successfully.", 's');
		Request::redirect('manage-portfolio.php?q=settings');
	}
	
	private function portfolio2Action(){
		
		$this->_db 	= new Pdodb($this->_dbinfo);	
		$DM_ID 		= Session::get('DOMAIN_ID');
		$setObject = new Settings();
		$postedValues = $this->getValues();
		
		$largeWidth     = $postedValues['portfolioLargwidth'];
		$largeHeight	= $postedValues['portfolioLargheight'];
		
		$largeUpload 	= '../media/portfolio/large/';
		$rawUpload 		= '../media/portfolio/raw/';
		
		$defaultportfolio = $setObject->fetchById('portfolio');
		$currLargeWidth	  = $setObject->getByJson('lwidth',$defaultportfolio);
		$currLargeHeight  = $setObject->getByJson('lheight',$defaultportfolio);
		
		// recrop all large images
		if($largeWidth != $currLargeWidth || $largeHeight != $currLargeHeight ){
			$dataArray = $this->_db->select(Portfolio::_TABLE);
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$cropObj = new ThumbnCrop();
					$cropObj->openImage($rawUpload.$array['image']);
					$newHeight = $cropObj->getRightHeight($largeWidth);
					$cropObj->createThumb( $largeWidth , $largeHeight);
					$cropObj->setThumbAsOriginal();
					$cropObj->saveThumb($largeUpload.$array['image']); 
					$cropObj->closeImg();	
				}
			}	
		}
		
		$otherData = json_decode($defaultportfolio , true );
		$data = array('lwidth'=>$largeWidth, 'lheight'=>$largeHeight);
		$outputArray = array_merge( $otherData , $data );
		$jsonData = json_encode($outputArray);
		
		$setObject->save( Settings::_TABLE , array('variable_value'=>$jsonData) , "variable_key='portfolio' AND domain_id='$DM_ID'" ,$this->_db);
		
		Message::setResponseMessage("Portfolio settings saved successfully.", 's');
		Request::redirect('manage-portfolio.php?q=settings');
		
		
	}
	
	
	
	
	
	private function portfolio3Action(){
		
		$this->_db 	= new Pdodb($this->_dbinfo);
		$DM_ID 		= Session::get('DOMAIN_ID');	
		$setObject = new Settings();
		$postedValues = $this->getValues();
		
		
		$defaultportfolio = $setObject->fetchById('portfolio');
		
		$numberOfdisplay= $postedValues['nodisplay'];
		$displayStyle	= 'a';
		if(array_key_exists('displaystyle',$postedValues)){
			$displayStyle = $postedValues['displaystyle'];
		}
		
		$otherData = json_decode($defaultportfolio , true );
		$data = array( 'nodisplay'=>$numberOfdisplay , 'displaystyle'=>$displayStyle);
		$outputArray = array_merge( $otherData , $data );
		$jsonData = json_encode($outputArray);
		
		$setObject->save( Settings::_TABLE , array('variable_value'=>$jsonData) , "variable_key='portfolio' AND domain_id='$DM_ID'" ,$this->_db);
		
		Message::setResponseMessage("Portfolio settings saved successfully.", 's');
		Request::redirect('manage-portfolio.php?q=settings');
		
	}
	
	
} //$