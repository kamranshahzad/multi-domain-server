<?php
	
	$setObject = new Settings();
	
	$defaultjsCodes 		= $setObject->fetchById('jscodes');
	$defaultgoogleCodes 	= $setObject->fetchById('googlecodes');
	$defaultnofollowdays 	= $setObject->fetchById('nofollowdays');
	
	$defaultLeftadcode 		= $setObject->fetchById('leftadcode');
	$defaultRightadcode 	= $setObject->fetchById('rightadcode');
	$defaultMiddleadcode 	= $setObject->fetchById('middleadcode');
	
	$jscodeForm = new MuxForm("JSCodeForm");
	$jscodeForm->setController('Settings');
	$jscodeForm->setMethod('post');
	$jscodeForm->setAction('jscodes');
	
	$googleadsForm = new MuxForm("GoogleAdsForm");
	$googleadsForm->setController('Settings');
	$googleadsForm->setMethod('post');
	$googleadsForm->setAction('googleadscode');
	
	
	
	$boot = new bootstrap();
	
	
	$nofollowDays = array();
	for($p = 1; $p < 31; $p++){
		$nofollowDays[$p] = $p;
	}
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage JS Codes
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    
    <div class="dataGridView">
    	
        
        <div class="formWrapper">
        
        <fieldset>   
        <legend>JS Codes</legend>
        	<!--#content-->
            <?php echo $jscodeForm->init(); ?>
            <div class="left">
            	<div class="field left">
                    <label>Google Analytics</label>
                    <textarea name="googlecodes" cols="" rows="" id="googlecodes" style="height:170px;"><?php echo $defaultgoogleCodes;?></textarea>
                	&nbsp;&nbsp;<span id="jsgooglecodeTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                </div>
                <p class="right noteText" style="width:300px; margin-top:20px;">You need to fetch Google analytics code from your account. If you are not already member of Google analytics<strong><a href="http://www.google.com/analytics/" target="new">click here</a></strong> to sign up now.</p>
            	<div class="clear"></div>
                <div class="field">
                    <label>JS Code</label>
                    <textarea name="jscodes" cols="" rows="" id="jscodes" style="height:170px;"><?php echo $defaultjsCodes;?></textarea>
                	&nbsp;&nbsp;<span id="jscodesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                </div>
                <div class="field">
            		<label>How soon you want Google to visit your website for indexing:</label>
                    <?php echo drawDropdownList( $nofollowDays , 'nofollowdays' , $defaultnofollowdays );?> (Days)
            		&nbsp;&nbsp;<span id="nofollowTagTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                </div>
            
            </div>
            <div class="right">
            	<div>
                   <input type="submit" value="Save" class="round"/>
              	</div>
            </div>
            <div class="clear"></div>
            <?php echo $jscodeForm->close();?>  
            <!-- $content-->
        </fieldset>    
        <br />
        <fieldset>   
        <legend>Google AdSense</legend>
        	 <?php echo $googleadsForm->init(); ?>
            <div class="field">
                <label>Leftside Bar</label>
                <textarea name="leftsidecode" cols="" rows="" id="leftsidecode" style="height:110px;"><?php echo $defaultLeftadcode; ?></textarea>
                &nbsp;&nbsp;<span id="jscodesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
            </div>
            <div class="field">
                <label>Rightside Bar</label>
                <textarea name="rightsidecode" cols="" rows="" id="rightsidecode" style="height:110px;"><?php echo $defaultRightadcode; ?></textarea>
                &nbsp;&nbsp;<span id="jscodesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
            </div>
            <div class="field">
                <label>Middle Bar</label>
                <textarea name="middlebar" cols="" rows="" id="middlebar" style="height:110px;"><?php echo $defaultMiddleadcode; ?></textarea>
                &nbsp;&nbsp;<span id="jscodesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
            </div>
            <div class="right">
            	<div>
                   <input type="submit" value="Save" class="round"/>
              	</div>
            </div>
            <div class="clear"></div>
            <?php echo $googleadsForm->close();?>  
        </fieldset> 
        
            
        </div>   
           
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
