<?php
	
	
	$form = new MuxForm("System_DbForm");
	$form->setController('SystemVars');
	$form->setMethod('post');
	$form->setAction('db');
	
		
	$dmObject = new Domains();	
	
	
	
	$varObject 		= new SystemVars();
	$defaultDbInfo 	= $varObject->fetchById('db_info');
	
	
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="dashboard.php">Home</a> >> Database Information
    </div>
	<h1>Database Information</h1>
    
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>

    <fieldset>
        <legend>Database</legend>
        <div class="field">
            <label>Hostname:</label>
            <input type="text" name="host" id="host" value="<?php echo $varObject->getByJson('host',$defaultDbInfo); ?>"  class="required" />
        </div>
        <div class="field">
            <label>Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $varObject->getByJson('username',$defaultDbInfo); ?>"  class="required" />
        </div>
        <div class="field">
            <label>Password:</label>
            <input type="text" name="password" id="password" value="<?php echo $varObject->getByJson('password',$defaultDbInfo); ?>"  class="required"  />
        </div>
    </fieldset>
    
    <br />
    <div class="field">
       <input type="submit" value="Save" class="round"/>
    </div>
    
    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>