<?php
	
	$data = array();
	$contentObject = new Contents();
	
	
	$form = new MuxForm("PlacementContentForm");
	$form->setController('Contents');
	$form->setMethod('post');
	$form->setAction('placement');
	
	$cid = $menuid = 0;
	$leftmenu = $footermenu = '';
	if(isset($_GET['q'])){
		if($_GET['q'] == 'modify'){
			$cid    = $_GET['cid'];
			$data = $contentObject->loadContentsById($cid);
			$menuid = ArrayUtil::value('menu_id',$data);
			$menustring = ArrayUtil::value('menu_types',$data);
			if(!empty($menustring)){
				$menusArray = explode(',',ArrayUtil::value('menu_types',$data));
				foreach($menusArray as $item){
					if($item == 'left'){
						$leftmenu = 'Y';
					}
					if($item == 'footer'){
						$footermenu = 'Y';
					}
				}
			}
		}
	}
	
	
	echo $form->init();
?>
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" /> 
<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $menuid;?>" /> 
<div class="field">
    <label>Page Name: </label>
    <input type="text" name="page_name" id="page_name" value="<?php echo ArrayUtil::value('menu_label',$data);?>" class="required"  /> &nbsp;&nbsp;<span id="pagenameTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span>
</div>
<div class="field">
    <label>Placement:</label> 
    <div style="line-height:25px;"><?php echo drawCheckbox('leftmenu', $leftmenu);?> Left Menu  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="placementTip" style="cursor:pointer;"><img src="../public/images/hint_icon.png"  /></span></div>
    <div style="line-height:25px;"><?php echo drawCheckbox('footermenu', $footermenu );?> Footer Menu  </div>
</div>
<div class="field">
    <input type="button" value="Save & Continue >" class="round" id="placementBtn"/>
</div>
<?php echo $form->close();?>     