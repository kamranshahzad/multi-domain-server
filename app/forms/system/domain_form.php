<?php
	
	
	$form = new MuxForm("System_DoaminForm");
	$form->setController('Domains');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	$dmObject = new Domains();	
	
	$data = array();
	$formHeading = "Add Domain";
	if(Request::qParam() == 'modify'){
		$formHeading = "Modify Domain";
		$data = $dmObject->fetchById($_GET['did']);
	}
	
	
	
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="dashboard.php">Home</a> >> <a href="manage-domains.php?q=show">Manage Domain Informations </a> >> <?php echo $formHeading;?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    
	<?php echo $form->init();?>
    <input type="hidden" name="did" value="<?php echo ArrayUtil::value('domain_id',$data);?>" />    
    
    
    <fieldset>
        <legend>General Information</legend>
        <div class="field">
            <label>Domain Url:</label>
            <input type="text" name="domain_url" id="domain_url" value="<?php echo ArrayUtil::value('domain_url',$data);?>"  class="required" />
        </div>
        <div class="field">
            <label>Security Key:</label>
            <input type="text" name="security_key" id="security_key" value="<?php echo ArrayUtil::value('security_key',$data);?>"  class="required" readonly="readonly" style="background:#e8e8e8;" />
        	<span class="fieldDetails">Ready only text field.</span>
        </div>
        <div class="field">
            <label>Access Enabled?</label>
            <?php echo drawStatusRadio(ArrayUtil::value('access_enable',$data));?>
        </div>
    </fieldset>
    

    <fieldset>
        <legend>CPanel Information</legend>
        <div class="field">
            <label>Username:</label>
            <input type="text" name="cp_username" id="cp_username" value="<?php echo ArrayUtil::value('cp_username',$data);?>"  class="required" />
        </div>
        <div class="field">
            <label>Password:</label>
            <input type="text" name="cp_password" id="cp_password" value="<?php echo ArrayUtil::value('cp_password',$data);?>"  class="required" />
        </div>
     </fieldset>   
        
    
    <fieldset>
        <legend>FTP Information</legend>
    	 <div class="field">
            <label>Username:</label>
            <input type="text" name="ftp_username" id="ftp_username" value="<?php echo ArrayUtil::value('ftp_username',$data);?>"  class="required" />
        </div>
		<div class="field">
            <label>Password:</label>
            <input type="text" name="ftp_password" id="ftp_password" value="<?php echo ArrayUtil::value('ftp_password',$data);?>"  class="required" />
        </div>
        
    </fieldset>
    <br />
    <div class="field">
       <input type="submit" value="Save" class="round"/>
    </div>
    

    <?php echo $form->close();?>   
    
    
<!-- #formWrapper-->
</div>