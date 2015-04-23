<?php


	class Captcha {
		
		
		
		public static function show($path='', $attrib = '' , $prefix =''){
			
			$attributes = '';
			$asset = 'public/siteimages/captcha/';
			if(!empty($path)){
				$asset = $path;
			}
			if(!empty($attrib)){
				$attributes = $attrib;
			}
			
			if (strlen(session_id()) < 1) {
				session_start();
			}
			$htmlString = '';
			
			$metaArray = array('G7K5'=>"_Ml__c--ImG__e_.jpg", 
								'T9L4'=>"_Ml__c--ImG__g_.jpg",
								'X3FD'=> '_Ml__c--ImG__i_.jpg',
								'N2W5'=>'_Ml__c--ImG__k_.jpg',
								'B4W8'=>'_Ml__c--ImG__m_.jpg');
			
			$key = array_rand($metaArray);
			$selectedImage = $metaArray[$key];
			$_SESSION['img_cpt_url___w'] = $key;
			$htmlString = '<img src="'.$asset.$selectedImage.'" '.$attributes.' /><input type="hidden" name="'.$prefix.'allowcode" id="'.$prefix.'allowcode" value="'.$key.'" />';
			
			return $htmlString;	
		}
		
	}