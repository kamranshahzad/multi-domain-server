
<div class="breadcrumb">
    <a href="home.php">Home</a> >> User logined history
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    
    <div class="dataGridView">
	   <?php
        	$logObject = new UserLog();
			echo $logObject->drawGrid();
       ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
