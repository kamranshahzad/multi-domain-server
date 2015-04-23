<?php

	class DateUtil{
		
		public static function curDateDb(){
			return date("y:m:d , h:i:s");	
		}
		
		public static function format($dateString , $format = 'Y-m-d'){
			if(!empty($dateString)){
				return date($format ,strtotime($dateString));
			}
		}
		
		public static function eventTime($etime, $elength){
				$curr_time= date('H:i:s');
				$event_time = $etime;
				$event_length = $elength;
				$timestamp = strtotime("$event_time");
				$etime = strtotime("+$event_length minutes", $timestamp);
				$next_time = date('H:i:s', $etime);
				$next_time;
				if($curr_time > $next_time)
					return "true";
				else
					return "false";	
		}
		
		public static function setDateformat($dateString){
			if($dateString != ''){
				if($dateString != "0000-00-00"){
					return date('y:m:d', strtotime($dateString));
				}else{
					return "0000-00-00";
				}
			}else{
				return "0000-00-00";
			}
		}
	
		public static function nicetime($timestamp, $detailLevel = 1) {

				$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
				$lengths = array("60", "60", "24", "7", "4.35", "12", "10");
			
				$now = time();
			
				// check validity of date
				if(empty($timestamp)) {
					return "Unknown time";
				}
			
				// is it future date or past date
				if($now > $timestamp) {
					$difference = $now - $timestamp;
					$tense = "ago";
			
				} else {
					$difference = $timestamp - $now;
					$tense = "from now";
				}
			
				if ($difference == 0) {
					return "1 second ago";
				}
			
				$remainders = array();
			
				for($j = 0; $j < count($lengths); $j++) {
					$remainders[$j] = floor(fmod($difference, $lengths[$j]));
					$difference = floor($difference / $lengths[$j]);
				}
			
				$difference = round($difference);
			
				$remainders[] = $difference;
			
				$string = "";
			
				for ($i = count($remainders) - 1; $i >= 0; $i--) {
					if ($remainders[$i]) {
						$string .= $remainders[$i] . " " . $periods[$i];
			
						if($remainders[$i] != 1) {
							$string .= "s";
						}
			
						$string .= " ";
			
						$detailLevel--;
			
						if ($detailLevel <= 0) {
							break;
						}
					}
				}
			
				return $string . $tense;
			
			}
		
		
	}  //@ class