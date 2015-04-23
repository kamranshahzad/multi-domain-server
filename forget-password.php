<?php

	require_once('devkit/init.php');
	require_once('blocks.php');
	$boot = new bootstrap();
	$setObject = new Settings();
	$routeOptions = array('form'=>array('forgetpassword'=>'system/forgetpassword_form'));
	$routeParam   =  'forgetpassword';
	
	$assets = $boot->img;
	

	
	$defaultlogo = $setObject->fetchById('logoimage');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo drawFavicon(); ?>
<title> <?php echo $boot->SITE_NAME; ?> : Forget Password  </title>
<?=$boot->drawCss('public/css',array('style')); ?>
<?php echo $boot->drawVender('venders','jquery');?>
<?=$boot->drawJs('public/js',array('init-js')); ?>
</head>
<body>
<!--Container-->
	<div class="overallwrap">
        <div class="mainwraper">
            <!--Header-->
            <div class="headerwrp">
                <div class="logowrap">
                    <div class="logo">
                    	<a href="index.php">
                    	<img src="<?php echo $assets;?>logo.jpg" border="0"/>
                        </a>
                     </div>
                    <div class="clear"></div>
                </div>
                
                
                <div class="headnav">
               	&nbsp;
                <div class="clear"></div>
                </div>
                      
            </div>
            <!--/Header-->  
           
            
          <!--Content-->
          
          <div class="contentwrp">
          	<div class="header"><span>ForgetPassword</span></div>
            
            
            <!-- #playarea-->
            <?php
        		$boot->setRoute( $routeParam , $routeOptions );
			?>
            <!-- #playarea-->
            
            
          </div>
          <!--/Content-->
                        
	</div>

<!--Footer Start-->
<div class="footerwrap">
    <?php echo drawFooter(); ?>
</div>
<!--Footer End-->


</div>
<!--/Container-->
</body>
</html>