<?php
	require_once("../../devkit/init.php");
	
	
	$type 		= $_GET['Type'];
	$emailText 	= $_GET['EmlText'];
	$outputHtml = '';
	
	$setObject = new Settings();
	$output = $setObject->remove( $type , $emailText );
	$totalEmail = $setObject->countemails($type);
	if($totalEmail < 3){
		$outputHtml = '<div id="'.$type.'EmailForm">
                <label>Add CC Email:</label>
                <input type="text" name="'.$type.'emlText" id="'.$type.'emlText" value="" style="width:250px;" />
                <input type="button" data-type="'.$type.'" class="emailIncludeButton removejunkButton" value="Add.." id="'.$type.'emlbtn">
            </div>';	
	}
	$outputHtml .= $setObject->drawEmailsList($type);
	
	echo $outputHtml;