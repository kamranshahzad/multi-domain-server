<?php

class EmailTemplate{
		
		private $unitType 		= '%'; //px
		private $_layoutWidth   = 100;
		private $assets   		= '';
		private $domain   		= '';
		private $_formValues	= array();
		public $_html			= '';
		public $SHOW_IP    		= FALSE;
		public $DT_HEADING      = FALSE;
		public $DT_ROW_WIDTH1   = '30%';
		public $DT_ROW_WIDTH2   = '70%';
		
		public function __construct($layoutWidth = 100 , $layoutUnit='%'){
			$this->_layoutWidth = $layoutWidth.$layoutUnit;
			
		}
		
		public function init($inlineStyles=''){
			$this->_html .= "<div style='width:{$this->_layoutWidth};$inlineStyles' >";	
			return $this;
		}
		
		public function close(){
			$this->_html .= "</div>";			
			return $this;
		}
		
		public function build(){
			return $this->_html;	
		}
		
		public function debug($render=FALSE){
			if($render){
				header("Content-Type: text/plain");
			}
			echo $this->_html;
		}
		
		/*
			_styles
		*/
		public function setCssStyles($cssStyles=''){
			$this->_html = '<style type="text/css">';
			if(empty($cssStyles)){
				$this->_html .= "body{font-family:Tahoma, Geneva, sans-serif;font-size:12px;}";
				$this->_html .= "table{font-size:12px;}";	
			}else{
				$this->_html .= $cssStyles;	
			}
			$this->_html .= '</style>';
			return $this;	
		}

		/*
			_normal text & html
		*/
		public function setParagraph($paraText= '', $inlinestyle=''){
			$htmlString = '';
			if(!empty($paraText)){
				$_inlinestyle = (!empty($inlinestyle))?"style='$inlinestyle'":'';
				$this->_html .= "<p $_inlinestyle>";
				$this->_html .= $paraText;
				$this->_html .= "</p>";	
			}
			return $this;
		}
		
		public function setHtml($htmlString=''){
			$this->_html .= $htmlString;
			return $this;
		}
		
		public function setSignature($signatureText='', $inlinestyle=''){
			if(!empty($signatureText)){
				$_inlinestyle = (!empty($inlinestyle))?"style='$inlinestyle'":'';
				$this->_html .= "<br/><div $_inlinestyle>";
				$this->_html .= $signatureText;
				$this->_html .= "</div>";		
			}
			return $this;
		}

		
		/*
			_dynamic form values 
		*/
		
		public function setFormValues($formValues=array()){
			if(!empty($formValues)){
				$this->_formValues = $formValues;
			}
			return $this;
		}
		
		public function drawDataTable($inlineStyles='', $tblCellSpacing=2, $tblCellPadding=2 , $row1Width=0, $row2Width=0 ){

			$htmlString = '';
			$_inlinestyle = (!empty($inlinestyle))?"style='$inlinestyle'":'';
			$this->_html .= "<table width='100%' border='0' cellspacing='$tblCellSpacing' cellpadding='$tblCellPadding' $_inlinestyle>";
			if($this->DT_HEADING){   // heading
				$htmlString .= $this->drawDataTableHeading();
			}
			if(count($this->_formValues) > 0){
				foreach($this->_formValues as $fieldLabel=>$fieldValue){
					if(!empty($fieldValue)){
						$this->drawDataTableField($fieldLabel,$fieldValue , $row1Width, $row2Width);
					}
				}
			}
			if($this->SHOW_IP){
				$this->drawIP($row1Width,$row2Width);
			}
			$this->_html .= "</table>";
			return $this;
		}
		
		public function drawIP($row1Width,$row2Width){
			$this->drawDataTableField('IP Address' , $_SERVER['REMOTE_ADDR'] , $row1Width , $row2Width);
			return $this;
		}
		
		
		public function drawDataTableHeading($headingText1='',$headingText2='' ,$inlineStyles='' ){
			$_inlinestyle = (!empty($inlinestyle))?"style='$inlinestyle'":'';
			$this->_html .= "<tr>";
			$this->_html .= "<td width='{$this->DT_ROW_WIDTH1}' $_inlinestyle valign='top'>$headingText1</td>";
			$this->_html .= "<td  width='{$this->DT_ROW_WIDTH2}' $_inlinestyle valign='top'>$headingText2</td>";
			$this->_html .= "</tr>";
			return $this;
		}
		
		public function drawDataTableField($_fieldLabel='',$_fieldValue='',$col1Width=0,$col2Width=0){
			$width1 = ($col1Widt == 0) ? $this->DT_ROW_WIDTH1:$col1Width;
			$width2 = ($col2Widt == 0) ? $this->DT_ROW_WIDTH2:$col2Width; 
			$this->_html .= "<tr>";
			$this->_html .= "<td width='$width1' valign='top'>$_fieldLabel</td>";
			$this->_html .= "<td width='$width2' valign='top'>$_fieldValue</td>";
			$this->_html .= "</tr>";
			return $this;
		}
		
		public function draw1ColumnTableField($textString='',$inlineStyles){
			$_inlinestyle = (!empty($inlinestyle))?"style='$inlinestyle'":'';
			$this->_html .= "<tr>";
			$this->_html .= "<td colspan='2' $_inlinestyle  valign='top'>$textString</td>";
			$this->_html .= "</tr>";
			return $this;
		}
		
		public function initTable($inlineStyles='', $tblCellSpacing=2, $tblCellPadding=2){
			$_inlinestyle = (!empty($inlinestyle))?"style='$inlinestyle'":'';
			$this->_html .= "<table width='100%' border='0' cellspacing='$tblCellSpacing' cellpadding='$tblCellPadding' $_inlinestyle>";
			return $this;
		}
		
		public function closeTable(){
			$this->_html .= "</table>";
			return $this;
		}
		
		
	} //$
	
	
	
		// _usage
		/*
		$formValues = array('Name'=>'Jhon Hax', 'Occupation'=>'Web Developer','Address'=>'Test address , FL , 00154454 ');
		
		$tmlObject 	 = new EmailTemplate();
		$tmlObject->SHOW_IP = true;
		$paragraphText = "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.";
		
		// multi lines 
		$tmlObject->setCssStyles();
		$tmlObject->setFormValues($formValues);
		$tmlObject->init();
		$tmlObject->drawDataTable();
		$tmlObject->close();
		$emlTemplate = $tmlObject->build();
		
		// in one line
		$tmlObject->setFormValues($formValues)->setCssStyles()->init()->setParagraph($paragraphText)->drawDataTable()->close()->build();
		$tmlObject->debug();
		
		*/