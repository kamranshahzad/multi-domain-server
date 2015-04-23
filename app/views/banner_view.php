<?php
	
	$blockObject = new Banner();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage Banner
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>
	
    <div class="singleBtnWrapper">
    <a href="manage-banner.php?q=add" class="viewButton">Add Banner Image</a>
    </div>
    <br />	
    
    <div class="dataGridView">
            <?php echo $blockObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
