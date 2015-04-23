<?php
	
	$blockObject = new Html();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage Blocks
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>


    
    <div class="dataGridView">
            <?php echo $blockObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
