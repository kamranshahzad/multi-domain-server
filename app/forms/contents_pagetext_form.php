<?php

	$data = array();
	$contentObject = new Contents();
	
	$cid = $menuid = 0;
	if(isset($_GET['cid'])){
		$cid    = $_GET['cid'];
		$data = $contentObject->loadContentsById($cid);
		$menuid = ArrayUtil::value('menu_id',$data);
	}
	
	$form = new MuxForm("PageTextContentForm");
	$form->setController('Contents');
	$form->setMethod('post');
	$form->setAction('pagetext');
	
	
	echo $form->init();		
?>
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" /> 
<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $menuid;?>" />
<input type="hidden" name="wherefield" id="wherefield" />
<div class="field">
    <label>Content Heading:</label>
    <input type="text" name="page_title" id="page_title" value="<?php echo ArrayUtil::value('page_title',$data);?>" class="required"  /> &nbsp;&nbsp;<span id="pageheadingTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span> <span id="wp-page_title"></span>
</div>
<div class="field">
    <label>Content Details:</label>
    <textarea name="page_text" cols="" rows="" id="page_text"><?php echo ArrayUtil::value('page_text',$data);?></textarea> 
    <script type="text/javascript">                                    
        var editor = CKEDITOR.replace( 'page_text',{enterMode : CKEDITOR.ENTER_BR});
        CKFinder.setupCKEditor(  editor, { basePath : '../venders/editor/ckfinder/', rememberLastFolder : true , clientHeight : 1000} );
    </script>
</div>
<div class="field">
    <input type="button" value="Save & Continue >" class="round" id="saveandcontinueBtn"/> &nbsp;
</div>
<?php echo $form->close();?> 