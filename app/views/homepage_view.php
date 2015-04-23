<?php
	
	$bpageObject = new BlockPages();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage Block Pages
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    
    <br />	
    
    <div class="dataGridView">
    	
            <?php echo $bpageObject->drawBlockPagesGrid(); ?>
  
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
