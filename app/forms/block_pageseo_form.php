<?php
	
	$data = array();
	$bpageObject = new BlockPages();
	
	$pageid = 0;
	if(isset($_GET['pid'])){
		$pageid    = $_GET['pid'];
		$data = $bpageObject->loadBlockPagesById($pageid);
	}
	
	$form = new MuxForm("BlockPageTextSeoForm");
	$form->setController('BlockPages');
	$form->setMethod('post');
	$form->setAction('pageseo');
	
	
	echo $form->init();		
	
?>
<input type="hidden" name="pageid" id="pageid" value="<?php echo $pageid;?>" /> 
<p class="fieldDetails">
	Your page is uploaded with content and will work fine even if you ignore this SEO part. However if you want Search Engine Optimized website, you must follow the steps below for better ranking.
</p>
<div class="field">
    <label>Page Url:</label>
    <input type="text" name="page_url" id="page_url" value="<?php echo ArrayUtil::value('page_url',$data);?>"  style="width:340px;"/> &nbsp;&nbsp;<span id="pageurlTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
</div>
<div class="field">
    <label>Page Title:</label>
    <input type="text" name="head_title" id="head_title" value="<?php echo ArrayUtil::value('head_title',$data);?>"  style="width:340px;"  /> &nbsp;&nbsp;<span id="pagetitleTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span> 
    <span id="wp-head_title"></span>
    <div  id="counter1"></div>
</div>
<div class="field">
    <label>Meta Keywords:</label>
    <textarea name="head_keywords" cols="" rows="" id="head_keywords" style="height:40px;"><?php echo ArrayUtil::value('head_keywords',$data);?></textarea>  &nbsp;&nbsp;<span id="pagemetaTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
	<span id="wp-head_keywords"></span>
    <div  id="counter2"></div>
</div>
<div class="field">
    <label>Meta Description:</label>
    <textarea name="head_description" cols="" rows="" id="head_description" style="height:40px;"><?php echo ArrayUtil::value('head_description',$data);?></textarea> &nbsp;&nbsp;<span id="pagedescriptionTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
	<span id="wp-head_description"></span>
    <div  id="counter3"></div>
</div>
<div class="field">
       <input type="button" value="Save" class="round" id="pageseoblockButton"/>
</div>
<?php echo $form->close();?> 