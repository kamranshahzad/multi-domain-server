<?php

	class UserLog extends Model {
		
		const _TABLE = 'system_users_log';
		private $_db = NULL;
			
		public function __construct() {}	
		
		public function initDb(){
			$configObj 	= new config();
			$_dbinfo 	= $configObj->getDbConfig();
			try {
				$this->_db = new Pdodb($_dbinfo);
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		
		/*
			helper functions
		*/
		public function signln($uid , $sessionid , $username ){
			$currTime = DateUtil::curDateDb();
			$data = array('uid'=>$uid,
					   'sessid' => $sessionid.'_'.md5($username),
					   'starttime'=>$currTime,
					   'ip_address'=> Request::ip()
					  );
			$this->initDb();		  	
			parent::save( self::_TABLE , $data , '' , $this->_db );
			$this->dispose();
		}	
		public function logOut($uid , $sessid , $username){
			$currTime = DateUtil::curDateDb();
			$data = array('endtime'=>$currTime);
			$sessionid = $sessid.'_'.md5($username);
			$this->initDb();	
			parent::save( self::_TABLE , $data , "uid='$uid' AND sessid='$sessionid'" , $this->_db );
			$this->dispose();	
		}
		public function getUserLog(){
			$this->initDb();
			$totalRecords = count($this->_db->select(self::_TABLE));
			$pointer = $totalRecords - 2; 
			$dataArray = $this->_db->select(self::_TABLE, "id IS NOT NULL LIMIT $pointer ,1");
			$this->dispose();
			if(count($dataArray)> 0){
				return $dataArray[0];	
			}
		}
		public function countLogRecords(){
			$this->initDb();
			$totalRecords = count($this->_db->select(self::_TABLE));
			$this->dispose();
			return $totalRecords; 	
		}
		
		
		/*
			_htmls
		*/
		
		
		
		public function drawGrid(){
			$htmlString = '';
			$totalRecords = $this->countLogRecords();
			$this->initDb();
			$pointer = $totalRecords - 1; 
			$dataArray = $this->_db->select(self::_TABLE , "id IS NOT NULL ORDER BY id DESC LIMIT 1 , $pointer ");
			$this->dispose();
			$pointer = 1;
			
			$userObject = new User();
			
			if(count($dataArray) > 0){
				$htmlString .= '<div class="singleBtnWrapper">
						'.Link::Action2('User', 'empty' , 'Empty Log', 'viewButton' , array() , 'Are you sure to empty logined history?' ).'
						</div>
						<br />';
				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$userInfo	= $userObject->fetchUserInfo($array['uid']);
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$userInfo['username'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$array['starttime'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$array['endtime'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$this->calculateInterval($array['starttime'],$array['endtime']).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$array['ip_address'].'</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No user logined history.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="110" align="center">Username</td>
								<td class="head" width="110" align="center">Start Dtae/Time</td>
								<td class="head" width="110" align="center">End Date/Time</td>
								<td class="head" width="110" align="center">Duration</td>
								<td class="head" width="100" align="center">IP Address</td>
							</tr>';
			return $htmlString;	
		}
		
		
		private function calculateInterval($startdate = '0000-00-00 00:00:00' , $enddate= '0000-00-00 00:00:00'){
			
			$htmlString = '';
			if($enddate != '0000-00-00 00:00:00'){
				$datetime1 = date_create($startdate);
				$datetime2 = date_create($enddate);
				$interval = date_diff($datetime1, $datetime2);
				$htmlString =  $interval->format('%i <span class="intervaltime">Minutes</span> %s <span class="intervaltime">Seconds</sapn>');	
			}else{
				$htmlString = '----';	
			}
			return $htmlString;
		}
		
		

		
	}  // $
