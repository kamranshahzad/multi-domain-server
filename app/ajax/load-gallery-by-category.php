<?php
	require_once("../../devkit/init.php");
	
	$CATEGORY_ID = $_GET['CATEGORY_ID'];
	
	$galleryObject = new Gallery();
	
	echo $galleryObject->drawGrid($CATEGORY_ID);
?>
