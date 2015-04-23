<?php


			
class forgetpasswordEml{
	
	private $mailObj;
	private $fromData;
	
	public function __construct( $sendToEmails , $form  ){
		
		$this->mailObj 			= new PHPMailer(true);
		$this->fromData = $form;
		$this->mailObj->SetFrom('EMAIL_ID', 'NAME');
		$this->mailObj->AddAddress( $sendToEmails['email'] , $sendToEmails['name'] );
		
	}

	public function send(){
			$this->mailObj->Subject = 'Your accounts password.';
			try {
				$this->mailObj->MsgHTML($this->emailTemplate());
				$this->mailObj->Send();
				echo 'Mail Send';
			} catch (phpmailerException $e) {
			} catch (Exception $e){}
	}
	
	private function emailTemplate(){
		
		$boot = new bootstrap();
		
		$htmlString = '';
		
		$htmlString .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml">
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>Wsnielsen</title>
						<style type="text/css">
							body { font-size:12px; font-family:Tahoma, Geneva, sans-serif;}
							h3 { font-size:18px; }
						</style>
						</head>
						<body>
						<table cellpadding="3" cellspacing="2" border="0" width="100%">
							<tr>
								<td style="background:#9d0b0e; color:#FFF; padding:25px;" width="100%">
									<h3>Wsnielsen</h3> 
									<span style="font-weight:bold;">(Retrieve your Password)</span>
								</td>  
							</tr>
							<tr>
								<td>
								<br />
								<p style="padding:10px;">Here is you account information.</p>
								<!-- #content -->
								<table width="500" cellpadding="5" cellspacing="2" border="0">
									<tr>
											<td style="font-weight:bold; width:130px; " align="right">Your Username: &nbsp;</td>
											<td style="">'.$this->fromData['username'].'</td>
									</tr>
									<tr>
											<td style="font-weight:bold; width:130px; " align="right">Your Password: &nbsp;</td>
											<td style="">'.$this->fromData['password'].'</td>
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