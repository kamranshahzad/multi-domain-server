<?php
	
	$form = new MuxForm("PortfolioForm");
	$form->setController('Portfolio');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	$portObject = new Portfolio();
	
	$data = array();
	$formHeading = "Add Portfolio Item";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify Portfolio Item";
		$data = $portObject->fetchById($_GET['pid']);
	}
	
	
	$boot = new bootstrap();
	$asset = $portObject->_DM_Media.'portfolio/thumbs/';
	
	$setObject = new Settings();
	$defaultportfolio 		= $setObject->fetchById('portfolio');
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-portfolio.php?q=show">Manage portfolio</a> >> <?php echo $formHeading; ?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="pid" value="<?php echo ArrayUtil::value('pid',$data);?>" />    
    <input type="hidden" name="currentImage" value="<?php echo ArrayUtil::value('image',$data);?>" />
    <div class="field">
    	<label>Short Description:</label>
        <input type="text" name="short_description" id="short_description"  value="<?php echo ArrayUtil::value('short_description',$data);?>"  style="width:540px;"/>
    	&nbsp;&nbsp;<span id="portfolioShortdesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
        <div  id="portfolioShortDes"></div>
    </div>
    <div class="field">
    	<label>Full Description:</label>
        <textarea name="full_description" cols="" rows="" id="full_description" style="height:150px;" ><?php echo ArrayUtil::value('full_description',$data);?></textarea> 
    	&nbsp;&nbsp;<span id="portfolioFulldesTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
    </div>
    <div class="field"> 
         <label for="name">Portfolio Image:</label> 
         <input type="file" name="image" id="image" />
         &nbsp;&nbsp;<span id="portfolioImageTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
         <br />  
         <span id="nameInfo" class="fieldDetails"><?php echo $setObject->getByJson('twidth',$defaultportfolio); ?> width x <?php echo $setObject->getByJson('theight',$defaultportfolio); ?> height</span><br />
         <?php
           if(ArrayUtil::value('image',$data) != ''){
                echo '<img src="'.$asset.ArrayUtil::value('image',$data).'" /> ';	
           }
         ?>
    </div>
    <div class="field">
        <label>Image Alt tag:</label>
        <input type="text" name="alt_tag" id="alt_tag" value="<?php echo ArrayUtil::value('alt_tag',$data);?>" />
        &nbsp;&nbsp;<span id="portfolioAltTagTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
    </div>
    <div class="field">
       <input type="submit" value="Save" class="round"/>
    </div>
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>