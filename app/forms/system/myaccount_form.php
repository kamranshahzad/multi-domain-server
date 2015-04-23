<?php 
	$userObject = new User();
	
	$dataArray = array();
	
	$form = new MuxForm('MyAccountForm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('changeinfo');
	
	$dataArray = $userObject->fetchById(Session::get('USERID'));
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<h1>Modify You Account</h1>
    <div class="breadcrumb">
        <a href="dashboard.php">Home</a> >> My Account
    </div>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="uid" id="uid" value="<?php echo ArrayUtil::value('uid',$data);?>" />
    
    <fieldset>
        <legend>Account Information</legend>
    <div class="field">
    	<label>User Email:</label>
        <input type="text" name="email" id="email" value="<?php echo $dataArray['email']; ?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Firstname:</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo $dataArray['firstname']; ?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Lastname:</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $dataArray['lastname']; ?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Phone#:</label>
        <input type="text" name="phone" id="phone" value="<?php echo $dataArray['phone']; ?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    </fieldset>
    
    <fieldset>
        <legend>Change Password</legend>
    <div class="field">
    	<label>Current Password:</label>
        <input type="password" name="cpassword" id="cpassword" class="required"/>
    </div>
    <div class="field">
    	<label>New Password:</label>
        <input type="password" name="npassword" id="npassword" />
    </div>
    <div class="field">
    	<label>Confirm New Password:</label>
        <input type="password" name="rpassword" id="rpassword"  />
    </div>
    <div class="field">
       <input type="submit" value="Save" class="round" />
    </div>
    </fieldset>
    <?php echo $form->close();?>
    
    
<!-- #formWrapper-->
</div>