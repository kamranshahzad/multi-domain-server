<?php

	require_once('devkit/init.php');
	require_once('blocks.php');
	
	
	$boot = new bootstrap();
	$setObject = new Settings();
	
	$assets = $boot->img;
	
	if($boot->isAdminLogined()){
		Request::redirect('home.php','');	
	}
	
	$defaultlogo = $setObject->fetchById('logoimage');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo drawFavicon(); ?>
<title> .:<?php echo $boot->SITE_NAME; ?> : Administrator Login:. </title>
<?=$boot->drawCss('public/css',array('style')); ?>
</head>

<body>



<div class="logincontainer center round10">
	<div class="companylogo">
    	<img src="<?php echo $assets;?>logo.jpg" />
    </div>
    <div class="loginwrapper">
    	<!-- #loginwrapper -->
        <div class="loginheading">
        	<img src="<?php echo $assets;?>logicon.png" class="left" />
            <h2>Please Login!</h2>
        </div>
        <div class="errorrow">
        <?=Message::getResponseMessage();?>
        </div>
        <div class="formfields">
        	<?php $boot->setDefaultRoute('form','system/login_form');?>
            <br />
        </div>
        <!-- $loginwrapper -->
    </div>
</div><!-- $logincontainer-->


</body>
</html>


