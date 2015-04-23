<?php
	
	$form = new MuxForm("BannerImageForm");
	$form->setController('Banner');
	$form->setMethod('post');
	$form->setAction(Request::qParam());
		
	$portObject = new Banner();
	
	$data = array();
	$formHeading = "Add Banner Image";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify Banner Image";
		$data = $portObject->fetchById($_GET['bid']);
	}
	
	
	$boot = new bootstrap();
	$asset = $boot->basepath.'/media/banner/thumbs/';
	
	/*
	$setObject = new Settings();
	$defaultportfolio 		= $setObject->fetchById('portfolio');
	*/
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-banner.php?q=show">Manage banner</a> >> <?php echo $formHeading; ?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="bid" value="<?php echo ArrayUtil::value('banner_id',$data);?>" />    
    <input type="hidden" name="currentImage" value="<?php echo ArrayUtil::value('banner_image',$data);?>" />
    <div class="field">
    	<label>Short Description:</label>
        <input type="text" name="short_description" id="short_description"  value="<?php echo ArrayUtil::value('description',$data);?>"  style="width:540px;"/>
    	&nbsp;&nbsp;<span id="portfolioShortdesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
        <div  id="portfolioShortDes"></div>
    </div>
    <div class="field"> 
         <label for="name">Banner Image:</label> 
         <input type="file" name="image" id="image" />
         &nbsp;&nbsp;<span id="portfolioImageTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
         <br />  
         <span id="nameInfo" class="fieldDetails">957 width x 300 height</span><br />
         <?php
           if(ArrayUtil::value('banner_image',$data) != ''){
                echo '<img src="'.$asset.ArrayUtil::value('banner_image',$data).'" /> ';	
           }
         ?>
    </div>
    <div class="field">
        <label>Image Alt tag:</label>
        <input type="text" name="alt_tag" id="alt_tag" value="<?php echo ArrayUtil::value('image_alttag',$data);?>" />
        &nbsp;&nbsp;<span id="portfolioAltTagTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
    </div>
    <div class="field">
            <label>Is Published?:</label>
            <?php echo drawStatusRadio(ArrayUtil::value('status',$data));?>
        </div>
    <div class="field">
       <input type="submit" value="Save" class="round"/>
    </div>
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>