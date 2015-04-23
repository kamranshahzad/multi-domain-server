<?php
	
	$portObject = new Portfolio();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage Portfolio Items
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

	
    
    <div class="singleBtnWrapper right">
    <a href="manage-portfolio.php?q=add" class="viewButton">Add Portfolio Item</a>
    </div>
    &nbsp;&nbsp;&nbsp;
    <div class="singleBtnWrapper right" style="margin-right:5px;">
    <a href="manage-portfolio.php?q=settings" class="viewButton">Portfolio Settings</a>
    </div>
    <div class="clear"></div>
    <br />	
    
    <div class="dataGridView" id="sortableGridView">
            <?php echo $portObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
