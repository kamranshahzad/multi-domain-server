<?php
	
	
	
	class CronEvents {
		
		private $_dbinfo;
		private $_db;
		private $currDate;
		private $currDt;
		private $targetDate;
		private $targetDt;
		private $noOfDays = 20;
		
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
		}
		
		public function currentTime(){
			$this->currDate 	= date("Y:n:j H:i:s");
			$currentDateString 	= strtotime($this->currDate);
			$this->currDt 		= new DateTime($currentDateString);	
		}
		
		public function targetTime($noOfDays='1'){
			$this->targetDate   = date("Y:n:j H:i:s",strtotime("-$noOfDays day"));
			$this->targetDt		= new DateTime($this->targetDate);	
		}
		
		public function run(){
			
			$this->currentTime();
			$this->targetTime( $this->noOfDays);
			
			$this->_db = new Pdodb($this->_dbinfo);
			$dataArray = $this->_db->select('users_log');
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$logDt = new DateTime($array['starttime']);
					if($this->targetDt > $logDt){
						$this->_db->delete('users_log','id="'.$array['id'].'"');
					}
				}
			}	
			$this->_db = null;
		}
		
		public function ping(){
			$begin = new DateTime( '2012-08-01' );
			$end = new DateTime( '2012-09-07' );
			$end = $end->modify( '+1 day' );
			
			$interval = new DateInterval('P3D');
			$daterange = new DatePeriod($begin, $interval ,$end);
			
			foreach($daterange as $date){
				echo $date->format("Y-m-d") . "<br>";
			}
		}
		
		
	} //$