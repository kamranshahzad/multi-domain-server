<?php

	class Testimonial extends Model {
		
		const _TABLE = 'ml_testimonials';
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
		
		
		public function drawGrid(){
			$htmlString = '';
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE ," tid IS NOT NULL ORDER BY sort_order");
			$this->dispose();
			$totalRecords = count($dataArray);
			
			/*
			$form = new MuxForm('SortTestimonials'.$MENU_TYPE);
			$form->setController('Testimonial');
			$form->setMethod('post');
			$form->setAction('sort');
			*/
			
			if($totalRecords > 0){
				/*$htmlString .= $form->init();
				$htmlString .= '<div class="singleBtnWrapper">
								<input type="submit" value="Save Sort Order" class="viewinnerButton" style="float:left;"/> 
								<div class="clear"></div>
								</div>';*/
				$htmlString .= $this->gridHeader();
				$pointer = 1;
				
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					$statusLink = ($array['status'] == 'Y') ? '<span class="enabled">'.Link::Action('Testimonial', 'disable' , 'Yes' , array('tid'=>$array['tid']) , "Are you sure you want to disable selected testimonial?").'</span>' : '<span class="disenabled">'.Link::Action('Testimonial', 'enable' , 'No' , array('tid'=>$array['tid']) , "Are you sure you want to active selected testimonial?").'</span>';
					
					$sortWrapper = '';
					if($pointer == 1){
						$sortWrapper = '<img src="public/images/down-arrow.png" class="SortButton" id="down-'.$pointer.'" data-row="'.$array['tid'].'-'.$array['sort_order'].'-testimonials"   >';	
					}
					if($totalRecords == $pointer){
						$sortWrapper = '<img src="public/images/up-arrow.png" class="SortButton" id="up-'.$pointer.'" data-row="'.$array['tid'].'-'.$array['sort_order'].'-testimonials"  >';
					}
					if($pointer > 1 && $pointer < $totalRecords){
						$sortWrapper = '<img src="public/images/down-arrow.png" class="SortButton" id="down-'.$pointer.'" data-row="'.$array['tid'].'-'.$array['sort_order'].'-testimonials" >
										<img src="public/images/up-arrow.png" class="SortButton"  id="up-'.$pointer.'"  data-row="'.$array['tid'].'-'.$array['sort_order'].'-testimonials"  >';
					}
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['title'],45).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top">'.StringUtil::short($array['data_text'],75).'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$sortWrapper.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'" valign="top" align="center">
									<span>
									<a href="manage-testimonials.php?q=modify&tid='.$array['tid'].'">modify</a>
									</span>
									&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('Testimonial', 'remove' , 'remove' , array('tid'=>$array['tid']), "Are you sure you want to remove selected company?").'
									</a>
									</td>';
					$htmlString .= '</tr>';
					$pointer++;					
				}
				$htmlString .= '</table>';
				//$htmlString .= $form->close();
			}else{
				$htmlString .= '<div class="totalGridRecords round">No testimonials found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="300">&nbsp;&nbsp;Title</td>
								<td class="head" width="450">&nbsp;&nbsp;Description</td>
								<td class="head" width="80" align="center">Ordering</td>
								<td class="head" width="50" align="center">Status</td>
								<td class="head" width="130" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "tid='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		
		
		public function drawRandomTestimonials(){
			$htmlString = '';
			$setObject  = new Settings();
			$testString = $setObject->fetchById('test');
			$testArray  = explode(',',$testString);
			$displayOrder = $testArray[0];
			$effect       = $testArray[1];
			$OrderText = '';
			switch($displayOrder){
				case 'd':
					$OrderText = 'ORDER BY sort_order DESC';
					break;
				case 'a':
					$OrderText = 'ORDER sort_order tid';
					break;
				case 'r':
					$OrderText = 'ORDER BY RAND()';
					break;	
			}
			
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "status='Y' $OrderText");
			$this->dispose();
			 
			$textArray = array();
			if(count($dataArray) > 0){
				$htmlString .= '<ul id="testimonialsList" >';
				foreach($dataArray as $array){
					if(!empty($array['data_text'])){
						$htmlString .= '<li><p>'.StringUtil::shortGoto($array['data_text'],160,'<span class="readmore"><a href="testimonials.php">...read more</a></span>').'</p></li>';
					}
				}
				$htmlString	.= '</ul>';
				$htmlString .= '<script type="application/javascript">';
				if($effect == 'sl'){
					$htmlString .= '$("#testimonialsList").slideTestimonials({\'delay\':5000, \'fadeSpeed\': 1000});';
				}else{
					$htmlString .= '$("#testimonialsList").rotateTestimonials({\'delay\':5000, \'fadeSpeed\': 1000});';	
				}
				$htmlString .= '</script>';
			}
			return $htmlString;
		}
		
		
		public function drawAllTestimonials(){
			$htmlString = '';
			$setObject  = new Settings();
			$testString = $setObject->fetchById('test');
			$testArray  = explode(',',$testString);
			$displayOrder = $testArray[0];
			$OrderText = '';
			switch($displayOrder){
				case 'd':
					$OrderText = 'ORDER BY sort_order DESC';
					break;
				case 'a':
					$OrderText = 'ORDER sort_order tid';
					break;
				case 'r':
					$OrderText = 'ORDER BY RAND()';
					break;	
			}
			
			
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "status='Y' $OrderText");
			$this->dispose();
			if(count($dataArray) > 0){
				foreach($dataArray as $array){
					$htmlString .= $array['data_text'];
				}
			}
			return $htmlString;
		}
		
		public function setSortOrder($tid,$sortOrder,$targetTid,$targetSortOrder){
			$this->initDb();
			$data1 = array('sort_order'=>$targetSortOrder);
			parent::save( self::_TABLE , $data1 , "tid='$tid'" ,$this->_db);
			$data2 = array('sort_order'=>$sortOrder);
			parent::save( self::_TABLE , $data2 , "tid='$targetTid'" ,$this->_db);
			$this->dispose();	
		}
		
		
		
		
	}  // $
