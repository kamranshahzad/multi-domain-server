<?php 
	$userObject = new User();
	$data = array('password'=>generate_password(8));

	
	$form = new MuxForm('UserAccountForm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction(Request::qParam());
	
	$formheading = 'Create User';
	if(Request::qParam() == 'modify'){
		$formheading = "Modify User";
		$data = $userObject->fetchById($_GET['uid']);
	}
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="user-accounts.php?q=show">User Accounts</a> >> <?php echo $formheading;?>
    </div>
	<h1><?php echo $formheading;?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="uid" id="uid" value="<?php echo ArrayUtil::value('uid',$data);?>" />
    <div class="field">
    	<label>Username:</label>
        <input type="text" name="username" id="username" value="<?php echo ArrayUtil::value('username',$data);?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Password:</label>
        <input type="text" name="password" id="password" value="<?php echo ArrayUtil::value('password',$data);?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Firstname:</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo ArrayUtil::value('firstname',$data);?>" class="required"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Lastname:</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo ArrayUtil::value('lastname',$data);?>"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
    	<label>Email:</label>
        <input type="text" name="email" id="email" value="<?php echo ArrayUtil::value('email',$data);?>" class="required email"/>
        <span class="fieldDetails"></span>
    </div>
    <div class="field">
        <label>Is Active?:</label>
        <?php echo drawStatusRadio(ArrayUtil::value('active',$data));?>
    </div>
    <div class="field">
       <input type="submit" value="Save" class="round" />
    </div>
    <?php echo $form->close();?>
    
    
<!-- #formWrapper-->
</div>

<?php
	
	//Helpers
	
	function generate_password($length = 20){
	  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
				'0123456789-=~!@#$%^&*()_+/<>?[]{}\|';
	
	  $str = '';
	  $max = strlen($chars) - 1;
	
	  for ($i=0; $i < $length; $i++)
		$str .= $chars[rand(0, $max)];
	
	  return $str;
	}


?>
