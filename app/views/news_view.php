<?php
	
	$newsObject = new News();	
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Manage News & Events
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    <div class="singleBtnWrapper">
    <a href="manage-news.php?q=add" class="viewButton">Add News</a>
    </div>
    <br />	
    
    <div class="dataGridView">
            <?php echo $newsObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
