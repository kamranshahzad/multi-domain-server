<?php
	
	$form = new MuxForm("BlockForm");
	$form->setController('Html');
	$form->setMethod('post');
	$form->setAction('modify');	
	$blockObject = new Html();
	
	$data = array();
	$formHeading = "Add Content";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify Block";
		$data = $blockObject->fetchById($_GET['bid']);
	}
	
	
	$boot = new bootstrap();
	$asset = $blockObject->_DM_Media.'block/thumbs/';

?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-blocks.php?q=show">Manage Blocks</a> >> <?php echo $formHeading; ?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="bid" value="<?php echo ArrayUtil::value('block_id',$data);?>" />    
    <input type="hidden" name="identifier" id="identifier" value="<?php echo ArrayUtil::value('identifier',$data);?>" />
    <input type="hidden" name="currentImage" value="<?php echo ArrayUtil::value('image',$data);?>" />
    <div class="field">
    	<label>Content Heading:</label>
        <input type="text" name="block_title" id="block_title" value="<?php echo ArrayUtil::value('block_title',$data);?>"  class="required"/>
    </div>
    <?php if(ArrayUtil::value('block_type',$data) == 'Y'){ ?>
    <div class="field"> 
         <label for="name">Block Image:</label> 
         <input type="file" name="image" id="image" />
         &nbsp;&nbsp;&nbsp;<span style="font-style:italic; color:#F60;font-size:11px;">*(This image will display on Home page)</span>
         <br />  
         <span id="nameInfo" class="fieldDetails">200 width x 164 height</span><br />
         <?php
           if(ArrayUtil::value('image',$data) != ''){
                echo '<img src="'.$asset.ArrayUtil::value('image',$data).'" width="200" height="164" /> ';	
            }
         ?>
    </div>
    <div class="field">
        <label>Block Image Alt tag:</label>
        <input type="text" name="alt_tag" id="alt_tag" value="<?php echo ArrayUtil::value('alt_tag',$data);?>" />
    </div>
    <div class="field">
        <label>Have Detailed Page?:</label>
        <?php echo drawStatusCheckbox(ArrayUtil::value('islink',$data));?>
    </div>
    <?php }  ?>
    <div class="field">
    	<label>Content Details:</label>
        <textarea name="block_text" cols="" rows="" id="block_text"><?php echo ArrayUtil::value('block_text',$data);?></textarea> 
        <script type="text/javascript">                                    
			var editor = CKEDITOR.replace( 'block_text',{enterMode : CKEDITOR.ENTER_BR});
			CKFinder.setupCKEditor(  editor, { basePath : '../venders/editor/ckfinder/', rememberLastFolder : true } );
		</script>
    </div>
    <div class="field">
       <input type="submit" value="Save" class="round"/>
    </div>
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>