<?php

	$data = array();
	$mpageObject = new ModulePages();
	
	$pageid = 0;
	if(isset($_GET['pid'])){
		$pageid    = $_GET['pid'];
		$data = $mpageObject->fetchModulePagesById($pageid);
	}
	
	$form = new MuxForm("ModulePageTextForm");
	$form->setController('ModulePages');
	$form->setMethod('post');
	$form->setAction('pagetext');
	
	
	echo $form->init();		
?>
<input type="hidden" name="pageid" id="pageid" value="<?php echo $pageid;?>" /> 
<div class="field">
    <label>Page Heading:</label>
    <input type="text" name="page_title" id="page_title" value="<?php echo ArrayUtil::value('page_title',$data);?>" class="required"  /> &nbsp;&nbsp;<span id="pageheadingTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span> <span id="wp-page_title"></span>
</div>
<div class="field">
    <label>Page Text:</label>
    <textarea name="page_text" cols="" rows="" id="page_text"><?php echo ArrayUtil::value('page_text',$data);?></textarea> 
    <script type="text/javascript">                                    
        var editor = CKEDITOR.replace( 'page_text',{enterMode : CKEDITOR.ENTER_BR});
        CKFinder.setupCKEditor(  editor, { basePath : '../venders/editor/ckfinder/', rememberLastFolder : true , clientHeight : 1000} );
    </script>
</div>
<div class="field">
    <input type="button" value="Save & Continue >" class="round" id="saveandcontinuemoduleBtn"/> &nbsp;
</div>
<?php echo $form->close();?> 