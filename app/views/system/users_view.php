<?php
	$userObject = new User();	
?>

<div class="breadcrumb">
    <a href="dashboard.php">Home</a> >> Manage User Accounts
</div>


<div class="viewContainer">
<!-- #singleBtnWrapper-->
	
    <?php echo Message::getResponseMessage('errorMessages');?>

    <div class="singleBtnWrapper">
    <a href="user-accounts.php?q=add" class="viewButton">Create User</a>
    </div>
    <br />	
    
    <div class="dataGridView">
            <?php echo $userObject->drawGrid(); ?>
    </div>
    <br />
    
<!-- $singleBtnWrapper-->
</div>
