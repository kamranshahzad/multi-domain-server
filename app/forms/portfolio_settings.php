<?php
						
	$setObject = new Settings();
	$defaultportfolio = $setObject->fetchById('portfolio');
							
	$portfolioForm1 = new MuxForm("PortfolioSettingsForm");
	$portfolioForm1->setController('Settings');
	$portfolioForm1->setMethod('post');
	$portfolioForm1->setAction('portfolio1');
	
	$portfolioForm2 = new MuxForm("PortfolioSettingsForm");
	$portfolioForm2->setController('Settings');
	$portfolioForm2->setMethod('post');
	$portfolioForm2->setAction('portfolio2');
	
	$portfolioForm3 = new MuxForm("PortfolioSettingsForm");
	$portfolioForm3->setController('Settings');
	$portfolioForm3->setMethod('post');
	$portfolioForm3->setAction('portfolio3');

	$data = array();
	$formHeading = "Portfolio Settings";
	
?>


<div class="formWrapper">
<!-- #formWrapper-->
	<div class="breadcrumb">
        <a href="home.php">Home</a> >> <a href="manage-portfolio.php?q=show">Manage Portfolio</a> >> <?=$formHeading;?>
    </div>
	<h1><?php echo $formHeading; ?></h1>
    <?php echo Message::getResponseMessage('errorMessages');?>
    <br />
   <!--#content-->
	<?php echo $portfolioForm1->init(); ?>
    <div class="left" style="width:350px;">
    <div class="fieldheading">Thumbnail Image <?php echo drawStickyNote("portfolioThumbTip");?> </div>
    <div class="field">
        <label>Portfolio Image Width:</label>
        <input type="text" name="portfolioThumbwidth" id="portfolioThumbwidth" style="width:40px;" value="<?php echo $setObject->getByJson('twidth',$defaultportfolio); ?>"  />&nbsp;px
    </div>
    <div class="field">
        <label>Portfolio Image Height:</label>
        <input type="text" name="portfolioThumbheight" id="portfolioThumbheight" style="width:40px;"  value="<?php echo $setObject->getByJson('theight',$defaultportfolio); ?>" />&nbsp;px
    </div>
    <div class="field">
        <input type="submit" value="Apply" class="round"/>
    </div>
    <?php echo $portfolioForm1->close();?>
    
    <?php echo $portfolioForm2->init(); ?>  
    <div class="fieldheading">Large Image <?php echo drawStickyNote("portfolioLargeTip");?> </div>
    <div class="field">
        <label>Portfolio Image Width:</label>
        <input type="text" name="portfolioLargwidth" id="portfolioLargwidth" style="width:40px;" value="<?php echo $setObject->getByJson('lwidth',$defaultportfolio); ?>"  />&nbsp;px
    </div>
    <div class="field">
        <label>Portfolio Image Height:</label>
        <input type="text" name="portfolioLargheight" id="portfolioLargheight" style="width:40px;" value="<?php echo $setObject->getByJson('lheight',$defaultportfolio); ?>"  />&nbsp;px
    </div>
    <div class="field">
        <a id="viewLargePortfolioBtns" href="javascript:void(0)"  title="Preview large image">Click here to preview large image</a>
    </div>
    <div class="field">
        <input type="submit" value="Apply" class="round"/>
    </div>
    <?php echo $portfolioForm2->close();?>
    
    <?php echo $portfolioForm3->init(); ?> 
    <div class="field">
        <label class="underline">Number of portfolio listings to be displayed per page.</label>
        <?php
            $dataOptions = array();
            for($p = 1; $p <= 50; $p++){
                $dataOptions[] = $p;
            }
            echo drawDropdownList($dataOptions , 'nodisplay' , $setObject->getByJson('nodisplay',$defaultportfolio) );
        ?>
        <?php echo drawStickyNote("portfolioNoDisplayTip");?>
    </div>
    <div class="field">
        <label class="underline">When clicked on thumbnail:</label>
        <?php echo drawPortfolioStyle($setObject->getByJson('displaystyle',$defaultportfolio));?>
    </div>
    <div class="field">
        <input type="submit" value="Save Settings" class="round"/>
    </div>
    </div> <!-- $left-->
    <div class="left" style="width:500px;">
        <div id="drawPortfolioArea" style="width:<?=$setObject->getByJson('twidth',$defaultportfolio);?>px; height:<?=$setObject->getByJson('theight',$defaultportfolio);?>px; background:#666; color:#000;text-align:center; font-size:12px;">Current Size of Image</div>
    </div><!-- $right -->
    <div class="clear"></div>
    <?php echo $portfolioForm3->close();?>  
    <!-- $content-->

<!-- #formWrapper-->
</div>