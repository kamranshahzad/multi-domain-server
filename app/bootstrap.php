<?php
//error_reporting(E_ALL ^ E_STRICT);
ini_set('max_execution_time', 300);

class bootstrap extends KitBootstrap{
   
   private $configObj 	= array();
   private $debugEmail  = array();
   public $siteimg    	= '';
   public $img			= '';
   public $basepath     = '';
   public $media		= '';
   public $SITE_NAME    = ''; 
   
   function __construct() {   
   	   date_default_timezone_set("America/Los_Angeles");
	   
	   $this->configObj 	= new config();
	   $this->siteimg 		= $this->configObj->getBasePath().$this->configObj->getSiteImages();
	   $this->img			= $this->configObj->getBasePath().$this->configObj->getImages();
	   $this->basepath  	= $this->configObj->getBasePath();
	   $this->media			= $this->configObj->getBasePath().'/media';
	   $this->debugEmail 	= $this->configObj->debugConfig['debugemails'];
	   $this->SITE_NAME    = config::SITE_NAME;
   }
   
   public function isAdminLogined(){
	   if(isset($_SESSION['USERID']) && isset($_SESSION['USERNAME']) && isset($_SESSION['USEREMAIL'])){
			return true;	
		}
		return false;
   }
   
   public function isUserLogined(){
	   if(isset($_SESSION['COMPANYID']) && isset($_SESSION['COMPANY_USERNAME']) && isset($_SESSION['COMPANY_USEREMAIL'])){
			return true;	
		}
		return false;
   }
   
   public function getAdminInfo(){
	   $mdlObject = new User();
	   return $mdlObject->fetchUserInfo(1);   
   }
   
   public function getDebugEmails(){
	   return $this->debugEmail;
   }
   
   public function drawVender($location , $vender){
		return $this->fetchVenders($location , $this->configObj->getVender($vender));   
   }
   
   
   public function getMedia($medianame=''){
	    $mediaarray = $this->configObj->mediaConfig;
		if(array_key_exists($medianame,$mediaarray)){
			return $mediaarray[$medianame];	
		}
   }
   

    
}//$
