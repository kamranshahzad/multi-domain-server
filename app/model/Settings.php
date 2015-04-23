<?php

	class Settings extends Model {
		
		const _TABLE = 'ml_settings';
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
			helper functions
		*/
		
		public function fetchById($settingid){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "variable_key='$settingid' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0]['variable_value'];
			}
		}
		
		function objectToArray($d) {
			if (is_object($d)) {
				// Gets the properties of the given object
				// with get_object_vars function
				$d = get_object_vars($d);
			}
	 
			if (is_array($d)) {
				/*
				* Return array converted to object
				* Using __FUNCTION__ (Magic constant)
				* for recursive call
				*/
				return array_map(__FUNCTION__, $d);
			}
			else {
				// Return array
				return $d;
			}
		}
	
		public function getByJson($fieldname='', $jsonString = ''){
			if(!empty($fieldname)){
				$data = json_decode($jsonString , true);
				if(array_key_exists($fieldname, $data)){
					return $data[$fieldname];
				}
			}
		}
		
		
		
		public function countemails($type){
			$current = $this->get($type);
			$emailArray = array();
			if(!empty($current)){
				$emailArray = explode(',',$current);	
			}
			return count($emailArray);
		}
		
		public function get($type){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "variable_key='$type' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			return $dataArray[0]['variable_value'];	
		}
		
		public function put( $type , $emailText ){
			$current = $this->get($type);
			$this->initDb();
			if(!empty($current)){
				$newvalue = $current .','.$emailText;	
			}else{
				$newvalue = $emailText;		
			}
			$this->_db->update(self::_TABLE, array('variable_value'=>$newvalue) , "variable_key='$type' AND domain_id='{$this->_DM_ID}'" );
			$this->dispose();
		}
		
		public function remove($type, $emailtext ){
			$current = $this->get($type);
			
			
			$emailArray = array();
			if(!empty($current)){
				$emailArray = explode(',',$current);	
			}
			if(($key = array_search($emailtext, $emailArray)) !== false) {
				unset($emailArray[$key]);
			}
			$this->initDb();
			if(count($emailArray) > 0){
				$afterRemoved =  join(',',$emailArray);		
				$this->_db->update(self::_TABLE, array('variable_value'=>$afterRemoved) , "variable_key='$type' AND domain_id='{$this->_DM_ID}'" );	
			}else{
				$this->_db->update(self::_TABLE, array('variable_value'=>'') , "variable_key='$type' AND domain_id='{$this->_DM_ID}'" );
			}
			$this->dispose();
		}
		
	
		
		/*
			_htmls
		*/
		
		public function drawEmailsList($type='ccc'){
			$htmlString = '';
			$this->initDb();
			$current = $this->get($type);
			$emailArray = array();
			if(!empty($current)){
				$emailArray = explode(',',$current);	
			}
			foreach($emailArray as $email){
				$htmlString .= '<span class="emailItem round" >'.$email.'<img src="public/images/close_icon.png" data-email="'.$email.'" title="'.$type.'"/></span><br />';
			}
			return $htmlString;
		}
		
		
		
		
		

		
	}  // $
