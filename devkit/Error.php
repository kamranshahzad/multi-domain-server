<?php


	class Error{
		
		public $errors = array();
		public $hintTxt;
		public $errType = 'Error Found';
		
		public static function setCssStyle(){
			$css ='';	
			return $css;	
		}
		
		
		
		public function draw( $errTxt = '' , $errType='Error Found' ){
			$this->hintTxt = $errTxt;
			$this->errType = $errType;
			echo self::setCssStyle();
			set_error_handler(array($this, 'handler'));
		}
		
		function handler($errno, $errstr ,$error_file,$error_line) {
			echo '<div class="errorContainer" >';
			echo '<div class="errorTitle">';
			echo $this->errType;
			echo '</div>';
			if($this->hintTxt != ''){
				echo '<div class="errorGuide">';
				echo $this->hintTxt;
				echo '</div>';
			}
			if($errstr != ''){
				echo '<div class="errorDetails">';
				echo '<ul>';
				echo "<li> <b>Line:</b>$error_line , <b>Code File:</b>$error_file <b>Detail:</b>$errstr <br /></li>";	
				echo '</ul>';
				echo '</div>';
			}
			echo '</div>';
			die();
   		} 
		
		
		
		
	}//$

?>