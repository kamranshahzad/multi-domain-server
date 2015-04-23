<?php
	$testOptions = array('r'=>'Random','d'=>'Decending', 'a'=>'Accesnding');
	$testEffectOptions = array('sh'=>'Show/Hide','sl'=>'Slide Show');

	
	
	$setObject = new Settings();
	
	$defaultDate 			= $setObject->fetchById('dateformat');
	$bussniessnameTxt 		= $setObject->fetchById('bussinessname');
	$defaultTestimonials 	= $setObject->fetchById('test');
	$defaultnofollowdays 	= $setObject->fetchById('nofollowdays');
	
	
	$testArray = array();
	if(!empty($defaultTestimonials)){
		$testArray = explode(',',$defaultTestimonials);
	}
	
	
	$dateForm = new MuxForm("DefaultDate");
	$dateForm->setController('Settings');
	$dateForm->setMethod('post');
	$dateForm->setAction('general');
	
	$testForm = new MuxForm("DefaultTest");
	$testForm->setController('Settings');
	$testForm->setMethod('post');
	$testForm->setAction('test');
	
	
	$boot = new bootstrap();
	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Site Settings
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    
    <div class="dataGridView">
    	
        
        <div class="formWrapper">
        <fieldset>
        <legend>General Settings</legend>
        <!-- #content-->
        	<?php echo $dateForm->init(); ?>
            <input type="hidden" name="currentlogo" value="<?php echo $defaultlogo;?>" />
            <div class="left">
            	<div class="field">
                    <label>Bussiness Name:</label>
                    <input type="text" name="bussinessnameTxt" id="bussinessnameTxt" value="<?php echo $bussniessnameTxt; ?>" style="width:250px;" />
                    &nbsp;&nbsp;<span id="bussinessnameTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                </div>
                <div class="field">
                    <label>Date/Time Format:</label>
                    <?php echo commonDateFormatDdl($defaultDate); ?>
                    &nbsp;&nbsp;<span id="defaultdateformatTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                </div>
            </div>
            <div class="right">
                <div>
                   <input type="submit" value="Save" class="round"/>
              	</div>
            </div>
            <div class="clear"></div>
            <?php echo $dateForm->close();?>  
        <!-- $content-->
        </fieldset>
        <br />
        <fieldset>
        <legend>Testimonials</legend>
        	<!--#content-->
            <?php echo $testForm->init(); ?>
            <div class="left">
            	<div class="field">
                    <label>Display Order</label>
                    <?php echo drawGroupRadioButtons($testOptions , 'test' , ArrayUtil::value(0,$testArray));?>
                </div>
                <div class="field">
                    <label>Display Effects</label>
                    <?php echo drawGroupRadioButtons($testEffectOptions , 'testeffects',ArrayUtil::value(1,$testArray));?>
                </div>
            </div>
            <div class="right">
            	<div>
                   <input type="submit" value="Save" class="round"/>
              	</div>
            </div>
            <div class="clear"></div>
            <?php echo $testForm->close();?>  
            <!-- $content-->
        </fieldset>    
        <br />
        
        <fieldset>
        <legend>Emails</legend>
        
        <div class="left" style="width:400px;">
            <div class="emailGrid" id="cccEmlGrid">
            	<?php if($setObject->countemails('ccc') < 3){?>
                <div id="cccEmailForm">
                    <label>Add CC Email:</label>
                    <input type="text" name="cccemlText" id="cccemlText" value="" style="width:250px;" />
                    &nbsp;&nbsp;<span id="cccemailTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                    <div style="padding-top:8px;">
                    <input type="button" data-type="ccc" class="emailIncludeButton removejunkButton" value="Add.." id="cccemlbtn">
                    </div>
                </div>
                <?php } ?>
                <br />
            	<?php echo $setObject->drawEmailsList('ccc');?>
            </div>
        </div>
        <div class="right" style="width:400px;">
            <div class="emailGrid" id="bccEmlGrid">
                <?php if($setObject->countemails('bcc') < 3){?>
                <div id="bccEmailForm">
                    <label>Add BCC Email:</label>
                    <input type="text" name="bccemlText" id="bccemlText" value=""  style="width:250px;"/>
                    &nbsp;&nbsp;<span id="bccemailTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
                    <div style="padding-top:8px;">
                    <input type="button" data-type="bcc" class="emailIncludeButton removejunkButton" value="Add.." id="bccemlbtn">
                    </div>
                </div>
                <?php } ?>
                <br />
            	<?php echo $setObject->drawEmailsList('bcc');?>
            </div>
        </div>
        <div class="clear"></div>
        </fieldset> 
        
       
        
            
        </div>   
           
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
