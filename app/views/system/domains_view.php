<?php
	
	$domainObject = new Domains();	
?>


<div class="breadcrumb">
    <a href="dashboard.php">Home</a> >> Manage Domain Informations
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    <div class="singleBtnWrapper">
    <a href="manage-domains.php?q=add" class="viewButton">Add Domain</a>
    </div>
    <br />	
    
    <div class="dataGridView">
            <?php echo $domainObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
