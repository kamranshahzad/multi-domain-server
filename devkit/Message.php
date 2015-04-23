<?php

	class Message{
		
		public static function setResponseMessage($messageText='', $messageType='e'){
			if(!empty($messageText)){
				$_SESSION['RESPONSE_TEXT_MESSAGE'] = $messageText;
				$_SESSION['RESPONSE_TEXT_TYPE']   = $messageType;	
			}
		}
		
		public static function setResponseJsMessage($messageText=''){
			if(!empty($messageText)){
				$_SESSION['JS_TEXT_MESSAGE'] = $messageText;	
			}
		}
		
		public static function getResponseMessage($cssStyle='errorMessages'){
			$style = '';
			if(isset($_SESSION['RESPONSE_TEXT_MESSAGE'])){
				if($_SESSION['RESPONSE_TEXT_TYPE'] == 'e'){
					$style = 'errorMessages';
				}else{
					$style = 'successMessages';
				}
				$responseMessage = "<div class='$style'>".$_SESSION['RESPONSE_TEXT_MESSAGE']."</div>";
				unset($_SESSION['RESPONSE_TEXT_MESSAGE']);
				unset($_SESSION['RESPONSE_TEXT_TYPE']);
				return $responseMessage;	
			}
		}
		
		public static function getResponseJsMessage(){
			if(isset($_SESSION['JS_TEXT_MESSAGE'])){
				$htmlString .= '<script>';
				$htmlString .= 'alert("'.$_SESSION['JS_TEXT_MESSAGE'].'");';
				$htmlString .= '</script>';
				unset($_SESSION['JS_TEXT_MESSAGE']);
				return $htmlString;	
			}
		}
		
		public static function getSiteResponseMessage($cssStyle='errorMessages'){
			if(isset($_SESSION['RESPONSE_TEXT_MESSAGE'])){
				$responseMessage = "<p style='padding:5px; color:#F00; text-align:center;'>".$_SESSION['RESPONSE_TEXT_MESSAGE']."</p>";
				unset($_SESSION['RESPONSE_TEXT_MESSAGE']);
				return $responseMessage;	
			}
		}
		
		public static function setFlashMessage($messageText = '' , $messageType = 's' ){
			$_SESSION['FLASH_MESSAGE_TEXT'] = $messageText;
			$_SESSION['FLASH_MESSAGE_TYPE'] = $messageType;
		}
		
		
		public static function getFlashMessage(){
			$tempArr = array();
			if(isset($_SESSION['FLASH_MESSAGE_TEXT']) && isset($_SESSION['FLASH_MESSAGE_TYPE']) ){
				$tempArr['msg'] = $_SESSION['FLASH_MESSAGE_TEXT'];
				$tempArr['type'] = $_SESSION['FLASH_MESSAGE_TYPE'];
				unset($_SESSION['FLASH_MESSAGE_TEXT']);
				unset($_SESSION['FLASH_MESSAGE_TYPE']);
			}
			return $tempArr; 
		}
		
		
		
		
		
	} //$
	
	
	/*
		Message::setFlashMessage("Test",'e');
	*/
?>