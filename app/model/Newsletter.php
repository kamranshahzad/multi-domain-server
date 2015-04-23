<?php

	class Newsletter extends Model {
		
		const _TABLE = 'ml_newsletter';
		private $_db = NULL;
		public $_DM_ID = 0;	
		
		public function __construct() {
			$this->_DM_ID = Session::get('DOMAIN_ID');
		}		
		
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
			_processing functions
		*/
		public function checkEmail($email){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "email LIKE '%$email%'");
			$this->dispose();
			print_r($dataArray);
			if(count($dataArray) > 0){
				return true;
			}
			return false;
		}
		public function checkIp($ip){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "ip_address = '$ip'");
			$this->dispose();
			if(count($dataArray) > 0){
				return true;
			}
			return false;
		}
		public function fetchByIdentifier($identifier=''){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "identifier='$identifier' AND status='Y'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0]['block_text'];
			}
		}
		
		public function unsubscribe($email){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, " email='$email' ");
			if(count($dataArray) > 0){
				if($dataArray[0]['subscribed'] == 'Yes'){
					// ready to unscribed.
					$data = array('subscribed'=>'No');
					$this->save( self::_TABLE , $data , "email='$email'" , $this->_db);
					return 'done';
				}else{
					return 'aleady';
				}
			}
			$this->dispose();
			return 'not';
		}
		
		
		
		
		/*
			_htmls
		*/
		public function drawGrid($emailtext = ''){
			$htmlString = '';
			$this->initDb();
			$dataArray = array();
			if(!empty($emailtext)){
				$dataArray = $this->_db->select(self::_TABLE , "email LIKE '%$emailtext%' AND domain_id='$this->_DM_ID'");
			}else{
				$dataArray = $this->_db->select(self::_TABLE);
			}

			$this->dispose();
			$pointer = 1;
			
			if(count($dataArray) > 0){
				$htmlString .= '<div class="singleBtnWrapper">
								'.Link::Action2('Newsletter', 'export' , 'Download (.xls)', 'viewButton' ).'
								</div>
								<br />';
				$htmlString .= '<input type="button" value="remove" class="removejunkButton" id="removeLetterButton"/>';
				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['subscribed'] == 'Yes') ? '<span class="enabled">'.Link::Action('Newsletter', 'unsubscribe' , 'Yes' , array('lid'=>$array['letter_id']) , "Are you sure you want to unsubscribed selected user?").'</span>' : '<span class="disenabled">'.Link::Action('Newsletter', 'subscribed' , 'No' , array('lid'=>$array['letter_id']) , "Are you sure you want to subscribed selected user?").'</span>';
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center"><input type="checkbox" name="newsletterids" class="newsletterids" value="'.$array['letter_id'].'" /></td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['name'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['email'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.$array['date_created'].'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$array['ip_address'].'</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No newsletter subscriber found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="800">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="50" align="center"><input type="checkbox" id="checkall" onclick="toggleChecked(this.checked)"/></td>
								<td class="head" width="300">&nbsp;&nbsp;Name</td>
								<td class="head" width="300">&nbsp;&nbsp;Email</td>
								<td class="head" width="100" align="center">Subscribed?</td>
								<td class="head" width="200">Date Created</td>
								<td class="head" width="100" align="center">IP Address</td>
							</tr>';
			return $htmlString;	
		}
		
		
		
		public function drawNewsletterWidget(){
			
			$htmlString = '';
			
			$htmlString .= '<table cellpadding="3" cellspacing="0" border="0" class="tinywidgetTable">';
			$htmlString .= '<tr>';
			$htmlString .= '<td class="head">Subscriber Name</td>';
			$htmlString .= '<td class="head">Subscriber Email</td>';
			$htmlString .= '<td class="head">Created Date</td>';
			$htmlString .= '<td class="head rightrow">IP Address</td>';
			$htmlString .= '</tr>';
			$htmlString .= '<tr>';
			$htmlString .= '<td class="row">test</td>';
			$htmlString .= '<td class="row">test@hotmail.com</td>';
			$htmlString .= '<td class="row">2012/10/17</td>';
			$htmlString .= '<td class="row rightrow">229.332.214.102</td>';
			$htmlString .= '</tr>';
			$htmlString .= '</table>';
			
			return $htmlString;	
		}
		
		
		public function removeSubscribers($ids){
			if(count($ids)> 0){
				
				$this->initDb();
				foreach($ids as $id){
					$this->remove(self::_TABLE , "letter_id='$id'" , $this->_db);
				}
				$this->dispose();
				return true;
			}
			return false;
		}
		
		
		
		
		
		
		

		
	}  // $
