<?php


class StringUtil{

	public static function className($class){
		$pos = strpos($class, 'Mapper');
		return substr($class,0,$pos);
	}
	
	public static function get_page_title($url){
		if( !($data = file_get_contents($url)) ) return false;
		if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
			return trim($t[1]);
		} else {
			return false;
		}
	}
	
	
	public static function stripTags($txt){
		if(!empty($txt)){
			return stripslashes($txt);	
		}
		return $txt;
	}
	
	public static function currentFile( $urlString = ''){
		$currPage = array();
		if($urlString != ''){
			$currPage = explode('.', $urlString );
		}else{
			$currPage = explode('.', basename($_SERVER['PHP_SELF']));
		}
		return $currPage[0];	
	}
	
	
	public static function short($orgString , $cutterNo){
		$inputString = '';
		if(!empty($orgString)) { 
			$inputString = strip_tags($orgString);	
		}
		if(strlen($inputString) > $cutterNo){
			return substr($inputString, 0, $cutterNo);	
		}
		return $inputString;
	}
	
	public static function shortGoto($orgString , $cutterNo , $linkText = ''){
		$inputString = '';
		if(!empty($orgString)) { 
			$inputString = strip_tags($orgString);	
		}
		if(strlen($inputString) > $cutterNo){
			return substr($inputString, 0, $cutterNo).'&nbsp;'.$linkText;	
		}
		return $inputString;
	}
	
	
	public static function toAscii($str , $replace=array(),$delimiter='-'){
		setlocale(LC_ALL, 'en_US.UTF8');
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
	
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;	
	}
	
	
	
	

} //$


?>