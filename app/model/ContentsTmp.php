<?php

	class ContentsTmp extends Model {
		
		const _TABLE = 'ml_contents_tmp';
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
			_Helper functions
		*/
		public function fetchTmpContentText(){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "tmp_id='1'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		public function setTmpContentText( $pageTitle='', $contentText='' ){
			$data = array(
				'tmp_id'=>1,
				'page_title'=>$pageTitle,
				'page_text'=>$contentText
			);
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE);
			if(count($dataArray) > 0){
				$this->save( self::_TABLE , $data , "tmp_id='1'" , $this->_db);
			}else{
				$this->save( self::_TABLE , $data , '' , $this->_db);
			}
			$this->dispose();
		}
		
		
		
		
		
		
	}  // $
