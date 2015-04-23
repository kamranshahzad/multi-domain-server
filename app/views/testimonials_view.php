<?php
	
	$testObject = new Testimonial();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage Testimonials
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    <div class="singleBtnWrapper">
    <a href="manage-testimonials.php?q=add" class="viewButton">Add Testimonial</a>
    </div>
    <br />	
    
    <div class="dataGridView" id="sortableGridView">
            <?php echo $testObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
