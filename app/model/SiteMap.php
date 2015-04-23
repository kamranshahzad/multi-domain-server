<?php


	class SiteMap{
		
		
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
		
		
		// workers
		
		
		
		
		
		
	} //@