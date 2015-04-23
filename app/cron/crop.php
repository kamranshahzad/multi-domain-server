<?php

	require_once("../../devkit/init.php");	
	
	$gridUpload 	= '../../media/gallery/grid/';
	$thumbUpload 	= '../../media/gallery/thumbs/';
	
	
	$obj = new Gallery();
	$obj->initDb();
	$dataArray = $obj->_db->select(Gallery::_TABLE);
	
	
	foreach($dataArray as $array){
		$cropObj = new ThumbnCrop();
		
		$cropObj->openImage($thumbUpload.$array['image_name']);
		$newHeight = $cropObj->getRightHeight(90);
		$cropObj->createThumb( 90 , 90);
		$cropObj->setThumbAsOriginal();
		$cropObj->saveThumb($gridUpload.$array['image_name']);
		
		$cropObj->closeImg();
	}
	
	echo 'Action done.';
	
	/*
	echo "<pre>";
	print_r($dataArray);
	echo "</pre>";
	*/
	