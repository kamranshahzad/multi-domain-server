<?php
	

	class SystemVarsController extends Controller{
		
		private $_dbinfo;
		private $_db;
				
		function __construct() {
			$configObj 	= new config();
			$this->_dbinfo 	= $configObj->getDbConfig();
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function dbAction(){
			
			$this->_db = new Pdodb($this->_dbinfo);	
			$varObject = new SystemVars();
			$postedValues = $this->getValues();
			
			$data = array('host'=>$postedValues['host'],
						'username'=>$postedValues['username'],
						'password'=>$postedValues['password']
						);
									
			$jsonData = json_encode($data);
			
			$varObject->save( SystemVar::_TABLE , array('variable_value'=>$jsonData) , "variable_key='db_info'" ,$this->_db);
	
			Message::setResponseMessage("Database information updated successfully.", 's');
			Request::redirect('database-information.php');
			
		}
		
	
		
		
	} //$
	
	
	
?>