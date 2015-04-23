<?php
	
	$letterObject = new Newsletter();
	$boot = new bootstrap();
	
	
	$form = new MuxForm("NewsletterEmailFind");
	$form->setController('Newsletter');
	$form->setMethod('post');
	$form->setAction('find');
	
	
	$emailText = '';
	if(isset($_GET['email'])){
		$emailText = $_GET['email'];
	}
	
	$doaminLocation = $boot->basepath.'/unsubscribed.php?email=';
	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Newsletter Emails List
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>
	<br />
    
    <?php echo $form->init();?>
    <div class="searchContainer round7" >
    	<label>Email Address</label>
        <input type="text" name="emailtext" id="emailtext" value="<?php echo $emailText; ?>" />
        <input type="submit" value="Search" class="round" />
    </div>
    <?php echo $form->close();?>   
    
    
    <p class="noteText">In order to ask someone to unsubscribed give them link, <span><?php echo $doaminLocation;?>email@domain.com</span>. Replace email@domain.com with actual email of that subscriber. In other words putting into your browser <span><?php echo $doaminLocation;?><span style=" text-decoration:underline;">john@medialinkers.com</span></span> will unsubscribe John from this newsletter.</p>
    
    <div class="dataGridView" id="newslettercheckboxs">
            <?php echo $letterObject->drawGrid($emailText); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
