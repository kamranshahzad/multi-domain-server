<?php
	
	$form = new MuxForm();
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('login');	
	
	
?>


<?=$form->init();?>
<div class="fieldrow">
<label>Username:</label>
<input type="text" name="username" id="username" class="textfield round"/>
</div>
<div class="fieldrow">
<label>Password:</label>
<input type="password" name="password" id="password" class="textfield round"/>
</div>
<div class="fieldrow">
    <input type="submit" value="Login" class="button" /> 
</div>
<div class="fieldrow" style="padding-top:10px;">
    <a href="forget-password.php">Forgot Password or username</a>
</div>

<?=$form->close();?>
