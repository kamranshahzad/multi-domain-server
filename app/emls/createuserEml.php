<?php

		
class createuserEml{
	
	private $mailObj;
	private $fromData;
	
	public function __construct( $_formData  ){
		
		$boot = new bootstrap();
		$this->mailObj = new PHPMailer(true);
		$this->fromData = $_formData;
		$this->mailObj->SetFrom('EMAIL_ID', 'TITLE');
		$admininfo = $boot->getAdminInfo();
		$this->mailObj->AddAddress( $admininfo['email'] , $admininfo['firstname'] );
		
		/*
		$setObject = new Settings();
		
		$cccString = $setObject->fetchById('ccc');
		if(!empty($cccString)){
			$cccArray = array();
			$cccArray = explode(',',$cccString);
			if(count($cccArray) > 0){
				foreach($cccArray as $email){
					$this->mailObj->AddCC( $email , '' );	
				}
			}
		}
		
		$bccString = $setObject->fetchById('bcc');
		if(!empty($bccString)){
			$bccArray = array();
			$bccArray = explode(',',$bccString);
			if(count($bccArray) > 0){
				foreach($bccArray as $email){
					$this->mailObj->AddBCC( $email , '' );	
				}
			}
		}
		*/
	}
	
	
	public function send(){
			$this->mailObj->Subject = '[Title] contact us details';
			try {
				$this->mailObj->MsgHTML($this->emailTemplate());
				$this->mailObj->Send();
				//echo 'Mail Send';
			} catch (phpmailerException $e) {
			} catch (Exception $e){}
	}
	
	private function emailTemplate(){
		$htmlString = '';
		
		
		$htmlString = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml">
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>Wsnielsen</title>
						<style type="text/css">
							body { font-size:12px; font-family:Tahoma, Geneva, sans-serif;}
							h2 { font-size:18px; }
						</style>
						</head>
						<body>
						<table cellpadding="3" cellspacing="2" border="0" width="100%">
							<tr>
								<td style="background:#9d0b0e; color:#FFF; padding:40px;" width="100%">
									<h1>Wsnielsen</h1> 
									<span style="font-weight:bold;">(Contact Us Details</span>)
								</td>  
							</tr>
							<tr>
								<td>
								<br />
								<p style="padding:10px;">Here is contact us information.</p>
								<!-- #content -->
								<table width="500" cellpadding="5" cellspacing="2" border="0">
									<tr>
										<td width="100"><strong>Firstname:</strong> &nbsp;</td>
										<td width="400">'.$this->fromData['firstname'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Lastname:</strong> &nbsp;</td>
										<td width="400">'.$this->fromData['lastname'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Email:</strong> &nbsp;</td>
										<td width="400">'.$this->fromData['email'].'</td>
									</tr>
									<tr>
										<td width="100" colspan="2">&nbsp;<strong style="color:#666;">Account Details</strong></td>
									</tr>
									<tr>
										<td width="100"><strong>Username:</strong> &nbsp;</td>
										<td width="400">'.$this->fromData['username'].'</td>
									</tr>
									<tr>
										<td width="100" valign="top"><strong>Password:</strong> &nbsp;</td>
										<td width="400">'.$this->fromData['password'].'</td>
									</tr>
								</table>
								<!-- #content -->
								</td>
							</tr>	
						</table>
						</body>
						</html>';
		return $htmlString;	
	}

	
}//$


?>