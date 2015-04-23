<?php

	require_once('devkit/init.php');
	require_once('blocks.php');
	$boot = new bootstrap();
	$routeOptions = array('view'=>array('show'=>'newsletter_view'));
	$routeParam   =  $_REQUEST['q'];
	
	$assets = $boot->img;
	
	if(!$boot->isAdminLogined()){
		Request::redirect('index.php','');	
	}
	
	$setObject = new Settings();
	$defaultlogo = $setObject->fetchById('logoimage');
	
	$domain = new Domains();
	$rawInfo = $domain->fetchById(Session::get('DOMAIN_ID'));
	$domainUrl =  ArrayUtil::value('domain_url',$rawInfo);	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo drawFavicon(); ?>
<title> <?php echo $boot->SITE_NAME; ?> : Newsletter Emails </title>
<?=$boot->drawCss('public/css',array('style')); ?>
<?php echo $boot->drawVender('venders','jquery');?>
<?=$boot->drawJs('public/js',array('init-js')); ?>
<?php echo $boot->drawVender('venders','editor');?>

</head>

<body>
<!--Container-->
	<div class="overallwrap">
        <div class="mainwraper">
            <!--Header-->
            <div class="headerwrp">
                <div class="logowrap">
                    <div class="logo">
                    <h1>
					<a href="home.php">
					<?php echo $domainUrl; ?>
                    </a>
                    </h1>
                    </div>
                    
                    <div class="dashboardlink right">
                        <a href="dashboard.php">
                            <img src="<?php echo $assets;?>dashboard.jpg" />
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
          	<div class="header"><span>Newsletter Emails</span></div>
            
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