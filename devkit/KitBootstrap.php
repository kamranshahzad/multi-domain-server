<?php


abstract class KitBootstrap{
	
	
	public static function drawCss( $location = '' ,$cssArray = array() ){
		$output = '';
		if(count($cssArray) > 0){
			foreach($cssArray as $cssfile){
					$output .= '<link rel="stylesheet" type="text/css" href="'.$location.'/'.$cssfile.'.css"/>'."\n";
			}
		}
		return $output;
	}

	public static function drawJs( $location = '' ,$jsArray = array() ){
		$output = '';
		if(count($jsArray) > 0){
			foreach($jsArray as $jsfile){
				$output .= '<script type="text/javascript" src="'.$location.'/'.$jsfile.'.js"></script>'."\n";
			}
		}
		return $output;
	}
	
	
	public function fetchVenders( $path = 'vender' , $venderinfo ){
		$output = '';
		if(array_key_exists('css',$venderinfo)){
			$cssFiles = $venderinfo['css'];
			$output  .= self::drawCss($path , $cssFiles);
		}
		if(array_key_exists('js',$venderinfo)){
			$jsFiles = $venderinfo['js'];
			$output  .= self::drawJs($path , $jsFiles);
		}
		return $output;
	}
	
	
	public function routeUrls($routUrlsArr , $opt){
		require_once($routUrlsArr['route'][$opt].'.php');	
	}
	
	public function setDefaultRoute( $view = 'form' , $routeOption ){
		switch($view){
			case 'form':
				require_once(dirname(dirname(__FILE__)).'/app/forms/'.$routeOption.'.php');
				break;
			case 'view':
				require_once(dirname(dirname(__FILE__)).'/app/views/'.$routeOption.'.php');
				break;
		}
	}
	
	public function setRoute($routeOption = '' , $parameters = array()){
	   
	   // set q parameter here  , remove this one $routeOption
	   if($routeOption != ''){
		   if(!empty($parameters)){
			   if(array_key_exists('view',$parameters)){
				   if(array_key_exists($routeOption ,$parameters['view'])){
					  require_once(dirname(dirname(__FILE__)).'/app/views/'.$parameters['view'][$routeOption].'.php');  
				   }
			   }
			   if(array_key_exists('form',$parameters)){
				   if(array_key_exists($routeOption ,$parameters['form'])){
					  require_once(dirname(dirname(__FILE__)).'/app/forms/'.$parameters['form'][$routeOption].'.php');  
				   }
			   }
		   }   
	   }else{
		  // $this->errObj->draw("Please pass route argument.");
		  // trigger_error('', E_USER_ERROR); 
	   }
    }
	
	
	public function setRoute2($filename=''){
	   
	   // set q parameter here  , remove this one $routeOption
	   if($filename != ''){
		   require_once(dirname(dirname(__FILE__)).'/app/views/'.$filename.'.php');  
	   }else{
		  // $this->errObj->draw("Please pass route argument.");
		  // trigger_error('', E_USER_ERROR); 
	   }
    }
	
	
	
	//@ venders
	public function initVenders( $venderDetails , $whereCall){
		
	}
	
	//@ get assets
	protected function getAssets( $asset , $whereCall ){
		switch($whereCall){
			case 'site':
				return 'public/'.$asset.'/';
				break;
			case 'admin':
				return '../public/'.$asset.'/';
				break;
		}
	}
	
	
}//$