<?php
	
		
	
	$pageid 		= 0;
	$currentStep 	= '';
	
	if(isset($_GET['step'])){
		$currentStep = $_GET['step'];
	}
	if(isset($_GET['pid'])){
		$pageid = $_GET['pid'];
	}
	
	$formHeading = "Modify Block Page";
	
?>


<?php echo Message::getResponseMessage('errorMessages');?>


<div class="formWrapper">
	
    <div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="home-page.php?q=show">Manage Block Pages</a> >> <?php echo $formHeading;?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <div class="tabContainer">
    <!-- #tabContainer-->
            
          <div class="tabButtons">
          		<?php echo drawBlockPageTabs( $currentStep , $pageid );?>      
                <div class="clear"></div>    
          </div>
          
          <div class="tabContent" >
          	<?php
            	loadBlockPageForm($currentStep);
			?>
          </div>
                

    </div> <!-- $tabContainer-->   
    
</div> <!-- $formWrapper-->           


<?php
	
	function loadBlockPageForm($step){
		$subformsArray = array('pagetext'=>'block_pagetext_form','pageseo'=>'block_pageseo_form');	
		if(array_key_exists($step,$subformsArray)){
			include($subformsArray[$step].'.php');	
		}
	}
?> 