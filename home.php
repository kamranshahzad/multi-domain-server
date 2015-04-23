<?php

	require_once('devkit/init.php');
	require_once('blocks.php');
	$boot = new bootstrap();
	$setObject = new Settings();
	
	$assets = $boot->img;

	if(!$boot->isAdminLogined()){
		Request::redirect('index.php','');	
	}
	
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
<title> <?php echo $boot->SITE_NAME; ?> : Administrator Home</title>
<?php echo $boot->drawCss('public/css',array('style')); ?>
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
                </div>
            </div>
            <!--/Header-->  
          <!--Content-->
          <div class="contentwrp">
          	<div class="header"><span>Dashboard</span></div>
            
            
            <div style="padding:20px 10px 100px;">
            <!-- #start-->   
            	
<div class="optionBox">
                      <a href="home-page.php?q=show">
                      <img src="<?php echo $assets;?>home-page.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="home-page.php?q=show">Home Page Blocks</a></div>
                </div>    
                <div class="optionBox">
                      <a href="manage-contents.php?q=show">
                      <img src="<?php echo $assets;?>content_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="manage-contents.php?q=show">Manage Pages</a></div>
                </div>
            	<div class="optionBox">
                      <a href="manage-blocks.php?q=show">
                      <img src="<?php echo $assets;?>blocks_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="manage-blocks.php?q=show">Manage Blocks</a></div>
                </div>
            	<div class="optionBox">
                      <a href="positioning-navigations.php?q=show">
                      <img src="<?php echo $assets;?>menu_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="positioning-navigations.php?q=show">Navigation Positioning</a></div>
                </div>
            	<div class="optionBox">
                      <a href="manage-testimonials.php?q=show">
                      <img src="<?php echo $assets;?>testimonial_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="manage-testimonials.php?q=show">Manage Testimonial</a></div>
                </div> 
                <div class="optionBox">
                      <a href="manage-portfolio.php?q=show">
                      <img src="<?php echo $assets;?>portfolio_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="manage-portfolio.php?q=show">Manage Portfolio</a></div>
                </div>
                
                <div class="optionBox">
                      <a href="manage-banner.php?q=show">
                      <img src="<?php echo $assets;?>banner_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="manage-banner.php?q=show">Manage Banner</a></div>
                </div>
                 
                <div class="optionBox">
                      <a href="manage-news.php?q=show">
                      <img src="<?php echo $assets;?>news_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="manage-news.php?q=show">Manage News</a></div>
                </div>
                <div class="optionBox">
                      <a href="newsletter-emails.php?q=show">
                      <img src="<?php echo $assets;?>newsletter_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="newsletter-emails.php?q=show">Newsletter Emails</a></div>
                </div>
                <div class="optionBox">
                      <a href="js-codes.php">
                      <img src="<?php echo $assets;?>js-code_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="js-codes.php">Manage JS Codes</a></div>
                </div>
                <div class="optionBox">
                      <a href="site-settings.php?q=show">
                      <img src="<?php echo $assets;?>settings_icon.png" width="64" height="64" />
                      </a>
                      <div style="text-align:center;padding:4px;"> <a href="site-settings.php?q=show">Settings</a></div>
                </div>
                
            <div class="clear"></div>
            </div><!-- $end-->
            
            
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
