<?php
	
	$form = new MuxForm("TestimonialForm");
	$form->setController('Testimonial');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	$testObject = new Testimonial();	
	
	$data = array();
	$formHeading = "Add Testimonials";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify Testimonials";
		$data = $testObject->fetchById($_GET['tid']);
	}
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-testimonials.php?q=show">Manage testimonials</a> >> <?php echo $formHeading;?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="tid" value="<?php echo ArrayUtil::value('tid',$data);?>" />    
    <div class="field">
    	<label>Testimonial Title:</label>
        <input type="text" name="title" id="title" value="<?php echo ArrayUtil::value('title',$data);?>"  class="required" />
    </div>
    <div class="field">
    	<label>Visiblity Status:</label>
		<?php echo drawStatusRadio(ArrayUtil::value('status',$data));?>
    </div>
    <div class="field">
    	<label>Testimonials Details:</label>
        <textarea name="data_text" cols="" rows="" id="data_text"><?php echo ArrayUtil::value('data_text',$data);?></textarea> 
    </div>
    <script type="text/javascript">                                    
			var editor = CKEDITOR.replace( 'data_text',{enterMode : CKEDITOR.ENTER_BR});
			CKFinder.setupCKEditor(  editor, { basePath : '../venders/editor/ckfinder/', rememberLastFolder : true } );
	</script>
    <div class="field">
       <input type="submit" value="Save" class="round"/>
    </div>
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>