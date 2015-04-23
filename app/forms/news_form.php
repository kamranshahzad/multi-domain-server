<?php
	
	$form = new MuxForm("NewsForm");
	$form->setController('News');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	$newsObject = new News();	
	
	$data = array();
	$formHeading = "Add News";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify News";
		$data = $newsObject->fetchById($_GET['nid']);
	}
	
	$boot = new bootstrap();
	$asset = $newsObject->_DM_Media.'news/thumbs/';
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-news.php?q=show">Manage News</a> >> <?php echo $formHeading; ?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="nid" value="<?php echo ArrayUtil::value('news_id',$data);?>" />    
    <input type="hidden" name="currentImage" value="<?php echo ArrayUtil::value('news_img',$data);?>" />
    
    <div style="width:595px; float:left;">
    	<!-- #left-->
        <div class="field">
            <label>News Heading:</label>
            <input type="text" name="news_title" id="news_title" value="<?php echo ArrayUtil::value('news_title',$data);?>"  class="required"/>
        </div>
        <div class="field">
            <label style="display:inline;">News Date:</label> &nbsp;&nbsp;&nbsp;<span style="font-style:italic; color:#F60; font-size:11px;">*(News/Event Date)</span>
            <br />
            <input type="text" name="news_date" id="news_date" style="width:100px;" value="<?php echo ArrayUtil::value('news_date',$data);?>"  />
        </div>
        <script>
            $(function(){ $( "#news_date" ).datepicker(); });
        </script>
        <div class="field">
            <label style="display:inline;">News Short Description:</label> &nbsp;&nbsp;&nbsp;<span style="font-style:italic; color:#F60;font-size:11px;">*(This short text will display on Home page)</span>
            <textarea name="news_short_text" cols="" rows="" id="news_short_text" class="required"><?php echo ArrayUtil::value('news_short_text',$data);?></textarea> 
        </div>
        <!-- $left-->
    </div>
    <div style="width:330px; float:right;">
    	<!-- #right-->
        <div class="field"> 
                 <label for="name">News Image:</label> &nbsp;&nbsp;&nbsp;<span style="font-style:italic; color:#F60;font-size:11px;">*(This image will display on Home page)</span>
                 <input type="file" name="iconfile" id="iconfile" />
                 <br />  
                 <span id="nameInfo" class="fieldDetails">122 width x 122 height</span><br />
                 <?php
                   if(ArrayUtil::value('news_img',$data) != ''){
                        echo '<img src="'.$asset.ArrayUtil::value('news_img',$data).'" width="122" height="122" /> ';	
                    }
                 ?>
        </div>
        <div class="field">
            <label>Alt tag:</label>
            <input type="text" name="alt_tag" id="alt_tag" value="<?php echo ArrayUtil::value('alt_tag',$data);?>" />
        </div>
        <div class="field">
            <label>Publish News?:</label>
            <?php echo drawStatusRadio(ArrayUtil::value('status',$data));?>
        </div>
        <!-- $right-->
    </div>
    <div class="clear"></div>
    <div class="field">
    	<label>News Details:</label>
        <textarea name="news_detail_text" cols="" rows="" id="news_detail_text"><?php echo ArrayUtil::value('news_detail_text',$data);?></textarea> 
        <script type="text/javascript">                                    
			var editor = CKEDITOR.replace( 'news_detail_text',{enterMode : CKEDITOR.ENTER_BR});
			CKFinder.setupCKEditor(  editor, { basePath : '../venders/editor/ckfinder/', rememberLastFolder : true } );
		</script>
    </div>
    <div class="field">
       <input type="submit" value="Save" class="round" />
    </div>
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>