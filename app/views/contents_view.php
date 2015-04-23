<?php
	$contentObject = new Contents();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage Pages
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    <div class="singleBtnWrapper">
    	<a href="manage-contents.php?q=add&step=placement" class="viewButton">Add Page</a>
    </div>
    <br />	
    
    <div class="dataGridView">
    	
            <?php echo $contentObject->drawGrid(); ?>
  
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
