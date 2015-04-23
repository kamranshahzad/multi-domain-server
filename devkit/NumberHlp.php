<?php

	class NumberHlp{
		
		public static function random($length = 20){
		  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
					'0123456789!#$%+?|';
		  $str = '';
		  $max = strlen($chars) - 1;
		
		  for ($i=0; $i < $length; $i++)
			$str .= $chars[rand(0, $max)];
		
		  return $str;
		}
		
		
	} //$