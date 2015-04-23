<?php

	class User extends Model {
		
		const _TABLE = 'system_users';
		
			
		public function __construct() {}	
		
		
		private $_db = NULL;
		
		
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
			_Helper functions
		*/
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "uid='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		public function fetchUserInfo($uid){
			$dataArr = array();
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE,"uid='$uid'");
			
			$dataArr = array('username'=>$dataArray[0]['username']
								,'firstname'=>$dataArray[0]['firstname']
								,'lastname'=>$dataArray[0]['lastname']
								,'password'=>$dataArray[0]['password']
								,'email'=>$dataArray[0]['email']
								);
			$this->dispose();	
			return $dataArr;	
		}
		
		public function validateUser( $username, $password , $_db ){
			
			$response = FALSE;
			
			$sqlGet = "SELECT * FROM ".self::_TABLE." WHERE username=:username AND password=:userpassword AND active ='Y'";
			$stmt = $_db->prepare($sqlGet);
			$stmt->bindParam(':username', $username , PDO::PARAM_STR);
			$stmt->bindParam(':userpassword', $password , PDO::PARAM_STR);
			$stmt->execute();
			$userArray = $stmt->fetch();
		
			
			if($stmt->rowCount() > 0){
				$sessId = session_id();
				$this->storeUserInSession($userArray['uid'] , $userArray['username'] , $userArray['email'] , $sessId );
				$userLog = new UserLog();
				$userLog->signln($userArray['uid'], $sessId , $userArray['username'] );
				$response = TRUE;	
			}
			return $response;
		}
		
		
		public function storeUserInSession( $uid , $username , $email , $sessid ){
			$_SESSION ["USERID"] 		= $uid;
			$_SESSION ["USERNAME"]  	= $username;
			$_SESSION ["USEREMAIL"]  	= $email;
			$_SESSION ["SESSID"]  		= $sessid;	
		}
		
		public function forgetpasswordFind($useroremail){
			$this->initDb();
			//$dataArray = $this->_db->select(self::_TABLE, "username='$useroremail' OR email ='$useroremail'");
			$dataArray = $this->_db->select(self::_TABLE, "email ='$useroremail'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		/*
			_htmls
		*/
		public function drawGrid(){
			$htmlString = '';
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE , "uid <> '1'");
			$this->dispose();
			$pointer = 1;
			
			if(count($dataArray) > 0){
				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					
					$statusLink = ($array['active'] == 'Y') ? '<span class="enabled">'.Link::Action('User', 'disable' , 'Yes' , array('uid'=>$array['uid']) , "Are you sure you want to disable selected user?").'</span>' : '<span class="disenabled">'.Link::Action('User', 'enable' , 'No' , array('uid'=>$array['uid']) , "Are you sure you want to active selected user?").'</span>';
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'"  align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" >'.$array['firstname'].'</td>';
					$htmlString .= '<td class="'.$class.'" >'.$array['lastname'].'</td>';
					$htmlString .= '<td class="'.$class.'"  >'.$array['username'].'</td>';
					$htmlString .= '<td class="'.$class.'"  >'.$array['email'].'</td>';
					$htmlString .= '<td class="'.$class.'"  align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'"  >'.DateUtil::format($array['created_date']) .'</td>';
					$htmlString .= '<td class="'.$class.'"  align="center">
									<span>
									<a href="user-accounts.php?q=modify&uid='.$array['uid'].'">modify</a>
									</span>
									&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('User', 'remove' , 'remove' , array('uid'=>$array['uid']), "Are you sure you want to remove selected user?").'
									</a>
									</td>';
					$htmlString .= '</tr>';
					$pointer++;	
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No sub user found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="200">&nbsp;&nbsp;Firstname</td>
								<td class="head" width="200">&nbsp;&nbsp;Lastname </td>
								<td class="head" width="160">&nbsp;&nbsp;Username</td>
								<td class="head" width="150">&nbsp;&nbsp;Email</td>
								<td class="head" width="100" align="center">Is Active?</td>
								<td class="head" width="100">&nbsp;&nbsp;Date Created</td>
								<td class="head" width="150" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		
				
		
	}  // $
