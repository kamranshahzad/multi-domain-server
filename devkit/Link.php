<?php

class Link{
	
	public static function Action($controller , $action , $lbl , $urlParams = array() , $confirmMsg = ''){
		$urlString = $confirmMessage = '';
		$controller = Request::encode64($controller);
		$urlParamsString = Request::urlParamsString($urlParams);
		$urlString = "devkit/ControllerLoader.php?view_state_controller=$controller&action=$action";
		if(count($urlParams) > 0){
			$urlString .= "&".$urlParamsString;
		}
		if(!empty($confirmMsg)){
			 $confirmMessage = 'onclick="return confirm(\''.$confirmMsg.'\')"';
		}
		return "<a href='$urlString' $confirmMessage>$lbl</a>";
	}
	
	public static function Action2($controller , $action , $lbl , $cssClass='', $urlParams = array() , $confirmMsg = ''){
		$urlString = $confirmMessage = '';
		$controller = Request::encode64($controller);
		$urlParamsString = Request::urlParamsString($urlParams);
		$urlString = "../devkit/ControllerLoader.php?view_state_controller=$controller&action=$action";
		if(count($urlParams) > 0){
			$urlString .= "&".$urlParamsString;
		}
		if(!empty($confirmMsg)){
			 $confirmMessage = 'onclick="return confirm(\''.$confirmMsg.'\')"';
		}
		return "<a href='$urlString' $confirmMessage class='$cssClass'>$lbl</a>";
	}
	
	public static function SAction($controller , $action , $lbl , $urlParams = array() , $confirmMsg = ''){
		$urlString = $confirmMessage = '';
		$controller = Request::encode64($controller);
		$urlParamsString = Request::urlParamsString($urlParams);
		$urlString = "devkit/ControllerLoader.php?view_state_controller=$controller&action=$action";
		if(count($urlParams) > 0){
			$urlString .= "&".$urlParamsString;
		}
		if(!empty($confirmMsg)){
			 $confirmMessage = 'onclick="return confirm(\''.$confirmMsg.'\')"';
		}
		return "<a href='$urlString' $confirmMessage>$lbl</a>";
	}
	
	
} //$

?>