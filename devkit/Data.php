<?php

	
	interface IData {
		function _tostring();	
	}
	
	
	abstract class Data {}
	
	
	
	class ArrayList extends Data implements IData{}

	class String extends Data implements IData{}
	
	
	
	class Dom extends Data{
		
	}//$
	
	
	class Xml extends Data{
		
	}//$
	
	
	class Json extends Data{
		
		public function inJson($array=array()){ // return json string
			if(gettype($array) == 'array'){
				if(!empty($array)){
					return json_encode($array);
				}
			}
		}
		
		public function outJson($json=''){  // this will return assco array
			if(!empty($json)){
				return json_decode( $json , TRUE );	
			}
		}
		
		public function saveJson($json='',$valueArray=array()){  // insert , modify
			if(!empty($json)){
				$currentArray = $this->outJson($json);
				if(!empty($valueArray)){
					$outputArray = array_merge($currentArray , $valueArray); 
					return $this->inJson($outputArray);
				}
			}
		}
		
		public function getValue($keyValue='' , $json=''){
			if(!empty($keyValue)){
				$currentArray = $this->outJson($json);
				if(array_key_exists($keyValue, $currentArray)){
					return $currentArray[$keyValue];
				}
			}
		}
		
		public function removeJson($keyValue='',$json=''){
			if(!empty($keyValue)){
				$currentArray = $this->outJson($json);
				if(array_key_exists($keyValue, $currentArray)){
					unset($currentArray[$keyValue]);
				}
				if(count($currentArray) > 0){
					return $this->inJson($currentArray);	
				}
				return '';
			}
		}
		
		public function existJson($keyValue='',$json=''){
			
		}
		
		public function jsonLength(){
				
		}
		
		public function json2Xml(){
			
		}
		
		public function xml2Json(){
			
		}
		
		public function saveYml($filename=''){
			
		}
		
		public function saveJson($filename=''){
			
		}
		
		public function saveXml($filename=''){
			
		}
		
		
	} //$
	
	
	
	
	
	
	
	class DataException extends Exception {
		
	} //$