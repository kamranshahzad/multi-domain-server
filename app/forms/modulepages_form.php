<?php
	
		
	
	$contentid 		= 0;
	$currentStep 	= '';
	
	if(isset($_GET['step'])){
		$currentStep = $_GET['step'];
	}
	if(isset($_GET['pid'])){
		$pageid = $_GET['pid'];
	}
	
	$formHeading = "Modify Page";
	
?>


<?php echo Message::getResponseMessage('errorMessages');?>


<div class="formWrapper">
	
    <div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-contents.php?q=show">Manage Pages</a> >> <?php echo $formHeading;?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <div class="tabContainer">
    <!-- #tabContainer-->
            
          <div class="tabButtons">
          		<?php echo drawModulePageTabs( $currentStep , $pageid );?>           
                <div class="clear"></div>    
          </div>
          
          <div class="tabContent" >
          	<?php
            	loadContentForm($currentStep);
			?>
          </div>
                

    </div> <!-- $tabContainer-->   
    
</div> <!-- $formWrapper-->           


<?php
	
	function loadContentForm($step){
		$subformsArray = array('pagetext'=>'module_pagetext_form','pageseo'=>'module_pageseo_form');	
		if(array_key_exists($step,$subformsArray)){
			include($subformsArray[$step].'.php');	
		}
	}
?> 