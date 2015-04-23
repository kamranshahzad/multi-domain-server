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
	
	$form = new MuxForm("System_GoDomainForm");
	$form->setController('Domains');
	$form->setMethod('post');
	$form->setAction('switchdomain');	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo drawFavicon(); ?>
<title> <?php echo $boot->SITE_NAME; ?> : Administrator Home</title>
<?php echo $boot->drawCss('public/css',array('style')); ?>
<style type="text/css">
	.iconcontainer { width:650px;}
	.switchcontainer { width:250px; background:#c9ecff; border:#a5ddfb dotted 1px; padding:10px; margin-right:10px;}
</style>
</head>

<body>
<!--Container-->
	<div class="overallwrap">
        <div class="mainwraper">
            <!--Header-->
            <div class="headerwrp">
                <div class="logowrap">
                    <div class="logo">
                    <img src="<?php echo $assets;?>logo.jpg" />
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
            
            <?php echo drawLastLogined(); ?>
            
            <div style="padding:20px 10px 100px;">
            <!-- #start-->   
            	
                <div class="iconcontainer left">
                <!-- #icons-->
                
                    <div class="optionBox">
                          <a href="manage-domains.php?q=show">
                          <img src="<?php echo $assets;?>domain_icon.png" width="64" height="64" />
                          </a>
                          <div style="text-align:center;padding:4px;"> <a href="manage-domains.php?q=show">Manage Domain Informations</a></div>
                    </div>
                    <div class="optionBox">
                          <a href="manage-domains.php?q=add">
                          <img src="<?php echo $assets;?>add_domain_icon.png" width="64" height="64" />
                          </a>
                          <div style="text-align:center;padding:4px;"> <a href="manage-domains.php?q=add">Add Domain</a></div>
                    </div>
                    
                    <div class="optionBox">
                          <a href="database-information.php">
                          <img src="<?php echo $assets;?>databases_icon.png" width="64" height="64" />
                          </a>
                          <div style="text-align:center;padding:4px;"> <a href="database-information.php">Database Information</a></div>
                    </div>
                    
                    
                    <?php if(Session::get('USERID') == 1){ ?>
                    <div class="optionBox">
                          <a href="user-accounts.php?q=show">
                          <img src="<?php echo $assets;?>subusers_icon.png" width="64" height="64" />
                          </a>
                          <div style="text-align:center;padding:4px;"> <a href="user-accounts.php?q=show">User Accounts</a></div>
                    </div>
                    <?php } ?>
                    <div class="optionBox">
                          <a href="my-account.php">
                          <img src="<?php echo $assets;?>user_icon.png" width="64" height="64" />
                          </a>
                          <div style="text-align:center;padding:4px;"> <a href="my-account.php">My Account</a></div>
                    </div>
                	<div class="clear"></div>
                </div><!-- $icons-->
                
                
                <?php
                	$dm = new Domains();
					$domainOptions = $dm->fetchDomain();
					if(count($domainOptions) > 0 ){
				?>
                <div class="switchcontainer round10 right">
                	
                    <?php echo $form->init(); ?>
                    <div class="formWrapper">
                    	<h2>Domain Admin Panels</h2>
                        <div class="field">
                            <?php
    
								echo drawDropdownListAs($domainOptions, 'domain_id' , '' , 'Select Domain');
							?>
                    	</div>
                    	<div class="field">
                    		<input type="submit" value="Go >>" class="goButton" />
                    	</div>
                    </div>
                    <?php echo $form->close(); ?>
                    
                </div><!-- $switchcontainer-->
                <?php
					}
				?>
                
                
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
