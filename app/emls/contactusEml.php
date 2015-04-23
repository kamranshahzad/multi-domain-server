<?php


		
class contactusEml{
	
	
	private $_formValues = array();
	
	public function __construct( $formValues = array() , $adminInfoArray= array()){
		
		$this->refineFormValues($formValues);
		
		//startup data
		$paragraphText = "Here is contact us information.";
		
		//create templates
		$tmlObject 	 		= new EmailTemplate();
		$tmlObject->SHOW_IP = true;
		$emailTemplate 		= $tmlObject->setFormValues($this->_formValues)->setCssStyles()->init()->setParagraph($paragraphText, "padding:10px;")->drawDataTable()->close()->build();
		
		//email sender
		$mlObject = new EmailWorker();
		$mlObject->addSubject("Contact Us Form");
		$mlObject->addAddress(array('email'=>$adminInfoArray['email'] , 'name'=>$adminInfoArray['name']));
		$mlObject->setFrom(array('email'=>'EMAIL_ID', 'name'=>'NAME'));	
		$mlObject->setHtml($emailTemplate);
		$mlObject->sendMail();
			
	}
	
	public function refineFormValues($formValues){
		$this->_formValues['<strong>Fullname:</strong>'] 	= $formValues['fullname'];
		$this->_formValues['<strong>Email:</strong>'] 		= $formValues['email']; 
		$this->_formValues['<strong>Phone:</strong>'] 		= $formValues['phone']; 
		$this->_formValues['<strong>Comments:</strong>'] 	= $formValues['comments'];  
	}
	
}//$


?>