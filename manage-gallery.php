<?php

	require_once('devkit/init.php');
	require_once('blocks.php');
	$boot = new bootstrap();
	$setObject = new Settings();
	$routeOptions = array('view'=>array('show'=>'gallery_view'),'form'=>array('page'=>'portfolio_page_form','settings'=>'portfolio_settings','add'=>'gallery_form','modify'=>'gallery_form'));
	$routeParam   =  $_REQUEST['q'];
	
	$assets = $boot->img;
	
	if(!$boot->isAdminLogined()){
		Request::redirect('index.php','');	
	}
	
	$defaultlogo = $setObject->fetchById('logoimage');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" type="image/png" href="public/siteimages/favicon.ico">
<title> <?php echo $boot->SITE_NAME; ?> : Manage Image Gallery </title>
<?=$boot->drawCss('public/css',array('style')); ?>
<?php echo $boot->drawVender('venders','jquery');?>
<?=$boot->drawJs('venders/jquery',array('jquery.limit')); ?>
<?=$boot->drawJs('venders/jquery',array('jquery.validate')); ?>
<?php echo $boot->drawVender('venders','tooltip');?>
<?=$boot->drawJs('public/js',array('init-js')); ?>
<?php echo $boot->drawVender('venders','editor');?>
</head>
<body>

<div id="mask"></div>

<!--Container-->
	<div class="overallwrap">
        <div class="mainwraper">
            <!--Header-->
            <div class="headerwrp">
                <div class="logowrap">
                    <div class="logo">
                    	<a href="home.php">
                    	<img src="<?php echo $boot->media.'/'.$defaultlogo;?>"  border="0" />
                        </a>
                     </div>
                    <div class="clear"></div>
                </div>
                
                
                <div class="headnav">
               	<?php echo drawWelcome(); ?>
                <div class="clear"></div>
                </div>
                      
            </div>
            <!--/Header-->  
           
           <?php echo drawTabsNavigation(); ?>

            
          <!--Content-->
          <div class="contentwrp">
          	<div class="header"><span>Manage Image Gallery</span></div>
            
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
