<?php


	class ModulePages extends Model {
		
		const _TABLE = 'ml_module_pages';
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
				return $this;
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		// core functions
		public function fetchModulePagesById($id){
			$this->initDb();
			$dataArray = $this->_db->select( self::_TABLE , "page_id='$id' AND domain_id='{$this->_DM_ID}'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		public function drawModulePages($pagesarray=array()){
			if(!empty($pagesarray)){
				$sqlQuery = '';
				if(count($pagesarray) > 0){
						
				}
			}
		}
		
		public function drawInClause($array=array()){
			$string = '';
			$string = 'IN ('.join(',',$array).')';
			return $string;
		}
		
		

	} //$