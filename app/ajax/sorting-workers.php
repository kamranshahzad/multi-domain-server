<?php
	
	require_once("../../devkit/init.php");
	
	$id 				= $_GET['Id'];
	$sortOrder 			= $_GET['SortValue'];
	$targetId 			= $_GET['TargetId'];
	$targetSortOrder 	= $_GET['TargetSortValue'];
	$where  			= $_GET['Where'];
	$extraValues  		= $_GET['ExtraData'];
	
	$outputHtml 		= '';
	
	switch($where){
		case 'testimonials':
			$testObject = new Testimonial();
			$testObject->setSortOrder($id,$sortOrder,$targetId,$targetSortOrder);
			$outputHtml = $testObject->drawGrid();
			break;
		case 'menus':
			$menuObject = new Menus();
			$menuObject->setSortOrder( $id , $sortOrder , $targetId , $targetSortOrder , $extraValues );
			$outputHtml .= '<div style="padding:20px; border:#eae7e7 dotted 1px; margin-bottom:10px;">
        					<h4 style="padding:0px; margin:0px; font-size:14px; color:#666666;">Left Menus</h4>';
			$outputHtml .= $menuObject->drawGrid('left');
			$outputHtml .= '</div>';
			
			$outputHtml .= '<div style="padding:20px; border:#eae7e7 dotted 1px;">
        					<h4 style="padding:0px; margin:0px; font-size:14px; color:#666666;">Footer Menus</h4>';
			$outputHtml .= $menuObject->drawGrid('footer');
			$outputHtml .= '</div>';
			break;	
		default:
			$outputHtml = 'Loading Error....';
	}
	
	
	echo $outputHtml;