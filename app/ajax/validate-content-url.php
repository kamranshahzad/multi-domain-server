<?php
	require_once("../../devkit/init.php");
	
	$outputArray = array();
	$contentObject = new Contents();
	$bpageObject   = new BlockPages();
	
					
	$PageType 	= $_POST['PageType'];
	$pageURL 	= $_POST['PageURL'];
	
	
	if($PageType == 'page'){
		
			$cid 		= $_POST['CID'];
			$menuid 	= $_POST['MenuID'];

			if(checkPhisicalPage($pageURL)){
				$outputArray['response'] = 'p';	
			}else{
				if(!$contentObject->isExistsInMenuUrl($pageURL , $menuid )){
					if (preg_match('/^[a-z0-9-]+$/', $pageURL)){
						if(!$bpageObject->isExistsInMenuUrl($pageURL  )){
							$outputArray['response'] = 'o';
						}else{
							$outputArray['response'] = 'a';
						}
					}else{
						$outputArray['response'] = 'i';	
					}	
				}else{
					$outputArray['response'] = 'a';	
				}		
			}
	}else{
		$pageid		= $_POST['PageID'];
		
		if(checkPhisicalPage($pageURL)){
			$outputArray['response'] = 'p';	
		}else{
				if(!$bpageObject->BlockisExistsInMenuUrl($pageURL , $pageid )){
					if (preg_match('/^[a-z0-9-]+$/', $pageURL)){
						if(!$contentObject->BlockisExistsInMenuUrl($pageURL)){
							$outputArray['response'] = 'o';
						}else{
							$outputArray['response'] = 'a';
						}
					}else{
						$outputArray['response'] = 'i';	
					}	
				}else{
					$outputArray['response'] = 'a';	
				}		
		}
	}
	
	
	
	
	

	
	echo json_encode($outputArray);
	
	/*	*/
	
	
	
	
	function checkPhisicalPage($pagename){
		$config = new config();
		$array = $config->restrictedPages;
		foreach($array as $page){
			if(strcasecmp($pagename, $page) == 0) {return true;}
		}
		return false;
	}
	
	
	
	/*
	
	
	$urltext = $_POST['URLText'];
	
	$outputArray = array();
	$outputArray['response'] = false;
	$outputArray['input']	 = $urltext;
	
	if (!preg_match('/[\'^!£$%&*()}{@#~?><>,|=_+¬-]/', $urltext)){
		$array['response'] = true;
	}
	
	
	echo json_encode($outputArray);
	*/