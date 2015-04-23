<?php
	
	//$boot = new bootstrap();
	
	function drawWelcome(){
		$htmlString = '';
		$htmlString .= 'Welcome '.Session::get('USERNAME').Link::SAction('User', 'logout' , 'logout');
		return $htmlString;	
	}
	
	function drawTabsNavigation(){
		$htmlString = '';
		$htmlString .= '<div class="tabMenus">
						 <a href="home.php"> Home </a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="home-page.php?q=show"> Home Page Blocks </a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="manage-contents.php?q=show"> Manage Pages </a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="manage-blocks.php?q=show"> Manage Blocks </a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="positioning-navigations.php?q=show"> Navigation Positioning </a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="manage-testimonials.php?q=show"> Manage Testimonials </a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="manage-news.php?q=show"> Manage News </a> ';
		$htmlString .= '<br/>';
		$htmlString .= '<a href="manage-portfolio.php?q=show"> Manage Portfolio </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="newsletter-emails.php?q=show"> Newsletter Emails </a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="js-codes.php"> Manage JS Codes</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="site-settings.php?q=show"> Site Settings </a> 
					  </div>';
		return $htmlString;			  
	}
	
	function drawLastLogined(){
		$htmlString = ''; 
		$logObject = new UserLog();
		$dataArray = $logObject->getUserLog();
		
		$userObject = new User();
		$userInfo	= $userObject->fetchUserInfo($dataArray['uid']);
		
		if(Session::get('USERID') == 1){
			if($logObject->countLogRecords() > 1){
				$htmlString .= '<div class="newsletterContainer round center" ><h3>Last logined</h3>';
				$htmlString .= '
								<div style="width:700px;" class="left">
									<p> 
									<strong>Login Date/Time:</strong> '.$dataArray['starttime'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<strong>IP Address:</strong> '.$dataArray['ip_address'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<strong>Which Username?:</strong> '.$userInfo['username'].'
									</p>
								</div>
								<div style="width:150px;text-align:right;" class="right">
									<p>
										<a href="view-logined-log.php" class="viewButton">View previous log</a>
									</p>
								</div>
								<div class="clear"></div>
					';
				$htmlString .= ' </div>';
			}
		}
		return $htmlString;
	}
	
	function drawFooter(){
		
		$blockHtml   = drawCopyrights();
		$htmlString = '';
		$htmlString .= '<div class="footerinn">'.$blockHtml.'
							<span>Powered by: <a href="SITE_URL" target="_blank">SITE NAME</a></span>
					</div>';
		return $htmlString;	
	}
	
	
	function drawGroupRadioButtons($options = array(), $id ,  $selectedKey='' , $seperator=''){
		$htmlString = '';
		foreach($options as $keyValue => $labelValue){
			if($selectedKey == $keyValue){
				$htmlString .= '<input type="radio" name="'.$id.'" value="'.$keyValue.'" checked="checked" /> '.$labelValue.' &nbsp;&nbsp;&nbsp;'.$seperator;
			}else{
				$htmlString .= '<input type="radio" name="'.$id.'" value="'.$keyValue.'" /> '.$labelValue.' &nbsp;&nbsp;&nbsp;'.$seperator;
			}
		}
		return $htmlString;
	}
	
	
	function drawPortfolioStyle(  $selectedKey = '' ){
		$htmlString = $aSelected = $bSelected ='';
		if(!empty($selectedKey)){
			if($selectedKey == 'a'){
				$aSelected = 'checked="checked"';
			}else{
				$bSelected = 'checked="checked"';
			}
		}
		
		$portfolioDisStyle = array('a'=>'Show image in Light Box pop up.  Click on right side image for sample.',
							   'b'=>'Show image on a new page (Without animation)'
		);
		
		$htmlString .= '<table cellpadding="2" cellspacing="0" border="0">';
		$htmlString .= '<tr>
							<td valign="top"><input type="radio" name="displaystyle" value="a" '.$aSelected.'/></td>
							<td valign="top">'.$portfolioDisStyle['a'].'</td>
							<td><img src="public/images/popup-img.jpg" class="viewPortfolioStyleBtn pointer imagestyle round"></td>
						</tr>';
		$htmlString .= '<tr>
							<td><input type="radio" name="displaystyle" value="b" '.$bSelected.'/></td>
							<td colspan="2">'.$portfolioDisStyle['b'].'</td>
						</tr>';				
		$htmlString .= '</table>';
		
		return $htmlString;
	}
	
	
	function drawStatusRadio($key , $label='status'){
		$htmlString = '';
		if(!empty($key)){
			if($key== 'Y'){
				$htmlString = '<input type="radio" name="'.$label.'" value="Y" checked="checked" /> Yes &nbsp;&nbsp;&nbsp;
        					   <input type="radio" name="'.$label.'" value="N" /> No';	
			}else{
				$htmlString = '<input type="radio" name="'.$label.'" value="Y"  /> Yes &nbsp;&nbsp;&nbsp;
        					   <input type="radio" name="'.$label.'" value="N" checked="checked" /> No';
			}
		}else{
			$htmlString = '<input type="radio" name="'.$label.'" value="Y" checked="checked" /> Yes &nbsp;&nbsp;&nbsp;
        				   <input type="radio" name="'.$label.'" value="N" /> No';
		}
		return $htmlString;
	}
	
	function drawStatusCheckbox($filterKey=''){
		$htmlString = '';
		if($filterKey == 'Y'){
			$htmlString = '<input type="checkbox" name="islink"  id="islink" value="Y" checked="checked" />';	
		}else{
			$htmlString = '<input type="checkbox" name="islink"  id="islink" value="Y" />';	
		}
		return $htmlString;
	}
	
	
	function drawDropdownList( $data = array(), $label , $filterKey='' ){
		$htmlString = '';
		$htmlString .= "<select name='$label' id='$label'>";
		foreach($data as $val){
			if($filterKey == $val ){
				$htmlString .= '<option value="'.$val.'" selected="selected">'.$val.'</option>';	
			}else{
				$htmlString .= '<option value="'.$val.'">'.$val.'</option>';
			}
		}
		$htmlString .= '</select>';	
		return $htmlString;
	}
	
	function drawDropdownListAs( $data = array(), $label , $filterKey='' , $startupText=''){
		$htmlString = '';
		$htmlString .= "<select name='$label' id='$label'>";
		if(!empty($startupText)){
			$htmlString .= '<option value="">'.$startupText.'</option>';	
		}
		foreach($data as $key=>$val){
			if($filterKey == $key ){
				$htmlString .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';	
			}else{
				$htmlString .= '<option value="'.$key.'">'.$val.'</option>';
			}
		}
		$htmlString .= '</select>';	
		return $htmlString;
	}
	
	
	function drawMenuTypeDdl($input=''){
		$options = array('LEFT'=>'Left Menu','BOTTOM'=>'Bottom Menus');
		$htmlString = '';
		$htmlString .= '<select name="menu_type" id="menu_type"><option value="">Select Menu</option>';
		foreach($options as $key=>$val){
			if($input == $key ){
				$htmlString .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';	
			}else{
				$htmlString .= '<option value="'.$key.'">'.$val.'</option>';
			}
		}
		$htmlString .= '</select>';	
		return $htmlString;
	}
	
	function drawMenuLinkTypeDdl($input=''){
		$options = array('CONTENT'=>'Content Page','OTHER'=>'Link To Page');
		$htmlString = '';
		$htmlString .= '<select name="menu_link_type" id="menu_link_type"><option value="">Select Menu Type</option>';
		foreach($options as $key=>$val){
			if($input == $key ){
				$htmlString .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';	
			}else{
				$htmlString .= '<option value="'.$key.'">'.$val.'</option>';
			}
		}
		$htmlString .= '</select>';	
		return $htmlString;
	}
	
	function drawCheckbox($checkboxId = 'checkboxid' , $filterKey=''){
		$htmlString = '';
		if($filterKey == 'Y'){
			$htmlString = '<input type="checkbox" name="'.$checkboxId.'"  id="'.$checkboxId.'" value="Y" checked="checked" />';	
		}else{
			$htmlString = '<input type="checkbox" name="'.$checkboxId.'"  id="'.$checkboxId.'" value="Y" />';	
		}
		return $htmlString;
	}
	
	
	
	function drawTabs( $currentStep = 'placement' , $page_id =0 ){
		
		$tabOptions = array('placement'=>'Page Placement','pagetext'=>'Page Text', 'pageseo'=>'Page SEO');
		$htmlString = '';
		
		foreach($tabOptions as $key=>$labels){
			$argValue = $key.'-'.$page_id;
			if($page_id == 0){
				if($currentStep == $key){
					$htmlString .= "<div class='buttonItem activeTab topround' >$labels</div>";	
				}else{
					$htmlString .= "<div class='buttonItem unactiveTab topround' onClick='DefaultTabs();'  >$labels</div>";
				}
			}else{
				if($currentStep == $key){
					$htmlString .= "<div class='buttonItem activeTab topround' >$labels</div>";	
				}else{
					$htmlString .= "<div class='buttonItem unactiveTab topround' onClick='".$key."Tab(\"$argValue\");'  >$labels</div>";
				}
			}
		}
		
		return $htmlString;
	}
	
	
	function drawBlockPageTabs( $currentStep = 'pagetext' , $page_id =0 ){
		
		$tabOptions = array('pagetext'=>'Page Text', 'pageseo'=>'Page SEO');
		$htmlString = '';
		
		foreach($tabOptions as $key=>$labels){
			$argValue = $key.'-'.$page_id;
			if($currentStep == $key){
				$htmlString .= "<div class='buttonItem activeTab topround' >$labels</div>";	
			}else{
				$htmlString .= "<div class='buttonItem unactiveTab topround' onClick='".$key."BlockPageTab(\"$argValue\");'  >$labels</div>";
			}
		}
		
		return $htmlString;
	}
	
	function drawModulePageTabs( $currentStep = 'pagetext' , $page_id =0 ){
		
		$tabOptions = array('pagetext'=>'Page Text', 'pageseo'=>'Page SEO');
		$htmlString = '';
		
		foreach($tabOptions as $key=>$labels){
			$argValue = $key.'-'.$page_id;
			if($currentStep == $key){
				$htmlString .= "<div class='buttonItem activeTab topround' >$labels</div>";	
			}else{
				$htmlString .= "<div class='buttonItem unactiveTab topround' onClick='".$key."ModulePageTab(\"$argValue\");'  >$labels</div>";
			}
		}
		
		return $htmlString;
	}
	
	function commonDateFormatDdl($input=''){
		$options = array('Y-m-d'=>'Y-m-d','F j, Y'=>'F j, Y','d/m/Y'=>'d/m/Y','l, F d, Y'=>'l, F d, Y','Y/m/d'=>'Y/m/d');
		$htmlString = '';
		$htmlString .= '<select name="dateformat" id="dateformat" style="width:261px;">';
		foreach($options as $key=>$val){
			if($input == $key ){
				$htmlString .= '<option value="'.$key.'" selected="selected">'.date($val).'</option>';	
			}else{
				$htmlString .= '<option value="'.$key.'">'.date($val).'</option>';
			}
		}
		$htmlString .= '</select>';	
		return $htmlString;
	}
	
	
	function drawFavicon($domainID = '', $imagePath = ''){
		$htmlString = '';
		if(!empty($domainID)){
			
		}else{
			$path = (!empty($imagePath)) ? $imagePath : 'public/images/';
			$htmlString = '<link rel="icon" type="image/png" href="'.$path.'favicon.ico">';
		}
		return $htmlString;
	}
	
	function drawCopyrights(){
		$htmlString = '';
		$setObject = new Settings();
		$bussniessnameTxt = $setObject->fetchById('bussinessname');	
		$htmlString .= 'Copyright ';
		$htmlString .= date('Y');
		$htmlString .= ' '.$bussniessnameTxt;
		return $htmlString;
	}
	
	function drawStickyNote($stickyid=''){
		$htmlString = '';
		$htmlString .= '&nbsp;&nbsp;<span id="'.$stickyid.'" style="cursor:pointer;"><img src="public/images/hint_icon.png"  /></span>';
		return $htmlString;	
	}
	
		