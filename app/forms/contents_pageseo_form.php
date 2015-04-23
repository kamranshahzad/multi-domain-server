<?php
	
	$data = array();
	$contentObject = new Contents();
	
	$cid = $menuid = 0;
	if(isset($_GET['cid'])){
		$cid    = $_GET['cid'];
		$data = $contentObject->loadContentsById($cid);
		$menuid = ArrayUtil::value('menu_id',$data);
	}
	
	$form = new MuxForm("PageTextSeoForm");
	$form->setController('Contents');
	$form->setMethod('post');
	$form->setAction('pageseo');
	
	
	echo $form->init();		
	
?>
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" /> 
<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $menuid;?>" />
<p class="fieldDetails">
	Your page is uploaded with content and will work fine even if you ignore this SEO part. However if you want Search Engine Optimized website, you must follow the steps below for better ranking.
</p>
<div class="field">
    <label>Page Url:</label>
    <input type="text" name="menu_url" id="menu_url" value="<?php echo ArrayUtil::value('menu_url',$data);?>"  style="width:340px;"/> &nbsp;&nbsp;<span id="pageurlTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
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
       <input type="button" value="Save" class="round" id="pageseoButton"/>
</div>
<?php echo $form->close();?> 