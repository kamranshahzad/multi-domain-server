<?php
	
	$form = new MuxForm("MenusForm");
	$form->setController('Menus');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	$menuObject = new Menus();
	
	$data = array();
	$linkType = '';
	$formHeading = "Add Menu Item";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify Menu Item";
		$data = $menuObject->fetchById($_GET['mid']);
		$linkType = ArrayUtil::value('menu_link_type',$data);
	}
	
	$filedStyle = 'style="display:none;"';
	if($linkType == 'OTHER'){
		$filedStyle = 'style="display:block;"';
	}
	
	
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-menus.php?q=show">Manage Navigations</a> >> <?php echo $formHeading;?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="mid" value="<?php echo ArrayUtil::value('menu_id',$data); ?>" />    
    <div>
    	<label>Page Name:</label> <img src="../public/images/hint_icon.png"  />  The page name as it will appear on front-end of you website , exaple : About Us  
        <input type="text" name="menu_label" id="menu_label" value="<?php echo ArrayUtil::value('menu_label',$data);?>"  class="required" />
    </div>
    <div>
    	<label>Select Navigation:</label><img src="../public/images/hint_icon.png"  />  
        <?php echo drawMenuTypeDdl(ArrayUtil::value('menu_type',$data));?>
    </div>
    <div>
    	<label>Select Navigation Type:</label> <img src="../public/images/hint_icon.png"  />
        <?php echo drawMenuLinkTypeDdl($linkType);?>
    </div>
    <div id="externalLinkText" <?php echo $filedStyle; ?>   >
    	<label>Navigation Link:</label> <img src="../public/images/hint_icon.png"  />
        <input type="text" name="menu_url" id="menu_url" value="<?php echo ArrayUtil::value('menu_url',$data);?>"  />
    </div>
    <div>
    	<label>Navigation Details:</label> <img src="../public/images/hint_icon.png"  />
        <textarea name="menu_detail" cols="" rows="" id="menu_detail"><?php echo ArrayUtil::value('menu_detail',$data); ?></textarea> 
    </div>
    <div>
       <input type="submit" value="Save" class="round"/>
    </div>
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>