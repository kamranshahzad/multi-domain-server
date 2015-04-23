<?php 

	
	$form = new MuxForm('ForgetPasswordForm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('forgetpassword');
	

?>


<div class="formWrapper">
<!-- #formWrapper-->

	<h1>Retrieve your Password</h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <div class="field">
    	<label>Your Email:</label>
        <input type="text" name="email" id="email" class="required email"/>
        <span id="wp-email"></span>
    </div>
    <div class="field">
    	<label>Security Image:</label>
        <?php echo Captcha::show('public/images/captcha/','','forget');?>
    </div>
    <div class="field">
    	<label>Type the code shown on the image above:</label>
        <input type="text" name="forgetscode" id="forgetscode" class="textfieldCls" style="width:50px;" />
         <span id="wp-scode"></span>
    </div>
    <div class="field">
    	<input type="button" class="round" value="Send" id="forgetpassButtton"/>
    </div>
    <?php echo $form->close();?>
    
    
<!-- #formWrapper-->
</div>

<?php
	
	//Helpers
	
	function generate_password($length = 20){
	  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
				'0123456789-=~!@#$%^&*()_+/<>?[]{}\|';
	
	  $str = '';
	  $max = strlen($chars) - 1;
	
	  for ($i=0; $i < $length; $i++)
		$str .= $chars[rand(0, $max)];
	
	  return $str;
	}


?>
