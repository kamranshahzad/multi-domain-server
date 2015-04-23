<?php
	
	$menuObject = new Menus();
	
	
		
?>


<div class="breadcrumb">
    <a href="home.php">Home</a> >> Navigation Positioning
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

   
    <br />	
    
    <div class="dataGridView" id="sortableGridView">
    	
        <div style="padding:20px; border:#eae7e7 dotted 1px; margin-bottom:10px;">
        	<h4 style="padding:0px; margin:0px; font-size:14px; color:#666666;">Left Menus</h4>
            <?php echo $menuObject->drawGrid('left'); ?>
        </div>
        
        <div style="padding:20px; border:#eae7e7 dotted 1px;">
        	<h4 style="padding:0px; margin:0px; font-size:14px; color:#666666;">Footer Menus</h4>
            <?php echo $menuObject->drawGrid('footer'); ?>
        </div>
        
        
            
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
