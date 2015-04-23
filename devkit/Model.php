<?php


	abstract class Model {
		
		public function __construct() {}

		/*
			_this will filter posted data with table columns
		*/
		public function filter( $postedValues , $tablecolumns ){
			
			$filterValues = array();
			foreach($postedValues as $field => $values){
				if(in_array($field, $tablecolumns)){
					$filterValues[$field] = $values;
				}
			}
			return $filterValues;
		}
		
		
		public function save( $table , $valuesArray , $where = '' , $_db = NULL ){
			if($_db != NULL){
				if(!empty($where)){
					$_db->update($table ,$valuesArray , $where );
				}else{
					$_db->insert($table,$valuesArray);
					return $_db->lastInsertId();
				}
			}
			
		}
		
		
		
		public function remove( $table , $where = '' , $_db = NULL){
			if($_db != NULL){
				$_db->delete($table , $where);
			}
		}
		public function isExist($table , $where = '' , $_db = NULL){
			
		}
		public function counts($table , $where = '' , $_db = NULL){
		
		}
		
		
		public function fetchByWhere ($table , $where , $_db = NULL ){
			if($_db != NULL){
				return $_db->select($table ,$where);	
			}
		}
		
		
		
	} // $
