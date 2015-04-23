<?php
	
	$form = new MuxForm("BlockForm");
	$form->setController('Html');
	$form->setMethod('post');
	$form->setAction('modify');	
	$blockObject = new Html();
	
	$data = array();
	$formHeading = "Modify Portfolio Page";
	//$data = $blockObject->fetchById($_GET['bid']);
	
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-portfolio.php?q=show">Manage Portfolio</a> >> <?php echo $formHeading; ?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="bid" value="<?php echo ArrayUtil::value('block_id',$data);?>" />    
    <div class="field">
    	<label>Page Heading:</label>
        <input type="text" name="block_title" id="block_title" value="<?php echo ArrayUtil::value('block_title',$data);?>"  class="required"/>
    </div>
    <div class="field">
    	<label>Intro Text:</label>
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