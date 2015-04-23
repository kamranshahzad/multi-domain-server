<?php


class MuxForm{
	
	
	private $controller  	= '';
	private $formMethod  	= '';
	private $formAction 	= '';
	private $formname       = '';
	
	function __construct($formname = '') {
		$this->formname = $formname;
	}
	
	public function setController($controller){
		 $this->controller = $controller;
	}
	
	public function setMethod($method){
        $this->formMethod = $method;
    }
	
	public function setAction($action){
		$this->formAction = '<input type="hidden" name="action" value="'.$action.'" />';
	}
	
	public function init($whereCall='',$attrubutes=''){
		$htmlTag = '';
		$htmlTag .= '<form action="devkit/ControllerLoader.php" method="Post"  enctype="multipart/form-data" name="'.$this->formname.'" id="'.$this->formname.'" '.$attrubutes.'   >';
		
		/*if($whereCall == 'admin'){
			$htmlTag .= '<form action="../devkit/ControllerLoader.php" method="Post"  enctype="multipart/form-data" name="'.$this->formname.'" id="'.$this->formname.'">';
		}else{
			$htmlTag .= '<form action="devkit/ControllerLoader.php" method="Post"  enctype="multipart/form-data" name="'.$this->formname.'" id="'.$this->formname.'" '.$attrubutes.'   >';
		}*/
		
		$htmlTag .= '<input type="hidden" name="view_state_controller" value="'.Request::encode64($this->controller).'" >';
		$htmlTag .= $this->formAction;
		return $htmlTag;
    }
	
	public function close(){
		return '</form>';	
	}
	
	
	public function createElement($type , $name , $value ){
		return "<input type='hidden' name='$name' value='$value' > \n";	
	}
	
	
	public function selectAs($fieldname ,$data = array() , $filtervalue='' , $caption='',$class='' , $style=''   ){
		$htmlString = '';
		$htmlString .= "<select name='$fieldname' id='$fieldname'";
		if(!empty($class)){
			$htmlString .= " class='$class'";	
		}
		if(!empty($style)){
			$htmlString .= " style='$style'";	
		}
		$htmlString .= '>';
		if(!empty($caption)){
			$htmlString .= "<option value=''>$caption</option>";
		}
		foreach($data as $key=>$val){
			if($filtervalue == $key){
				$htmlString .= "<option value='$key' selected='selected'>$val</option>";
			}else{
				$htmlString .= "<option value='$key'>$val</option>";
			}
		}
		$htmlString .= '</select>';
		
		return $htmlString;
	}
	
	
	public function select($fieldname ,$data = array()  , $caption='',$class='' , $style=''   ){
		$htmlString = '';
		$htmlString .= "<select name='$fieldname' id='$fieldname'";
		if(!empty($class)){
			$htmlString .= " class='$class'";	
		}
		if(!empty($style)){
			$htmlString .= " style='$style'";	
		}
		$htmlString .= '>';
		if(!empty($caption)){
			$htmlString .= "<option value=''>$caption</option>";
		}
		foreach($data as $key=>$val){
				$htmlString .= "<option value='$key'>$val</option>";
		}
		$htmlString .= '</select>';
		
		return $htmlString;
	}
	
	
}//$


?>