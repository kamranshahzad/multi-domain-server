// JavaScript Document


var SBHMessages = {	
				pagenameTip:'The page name as it will appear on front-end of you website , example : About Us',
			  	placementTip: 'This determines the display of this link on your website.' ,
				pageheadingTip : 'The heading of this page. Will display on top of page before content.',
				pageurlTip  : 'Even though you can use any page name however we strongly recommend you to give a page name that includes your Google keywords search. Like a dentist may use page name as "Dental-clinic-atlanta".',
				pagetitleTip: 'The Title of page displays in your browser tab. Putting Title that contains your desired Google search keyword is very helpful in Search Engine Optimization. As an example if a dentist has specific page for kids, he will use page title as "Happy Teeth Dental Clinic |  Kids dentist in Atlanta',
				pagemetaTip : 'Set the comma seperated keywords for your page. Like for example a dentist may put "Dental implant, teeth whitening, dentist in Atlanta etc"  ',
				pagedescriptionTip : 'Set the description for your website in statement like for example a dentist may use "Happy Teeth has professional and qualified dentists for any kind of comsmetic and surgeries etc...',
				bussinessnameTip : 'Enter your website name.',
				defaultdateformatTip : 'Select date format. This will apply on front-end of website.',
				cccemailTip : 'The email you put here will receive contact form etc.',
				bccemailTip : 'The email you put here will receive a hidden copy of contact form etc.',
				jscodesTip: 'You can add any js code here.',
				jsgooglecodeTip : 'Insert you Google analytics code here',
				defaultogoimgTip : 'This will change logo of admin panel.',
				portfolioThumbTip : 'Specify the size of thumbnail image here. The grey box on right side shows your specified size in run times',
				portfolioLargeTip : 'Specify the size of image to be displayed on the page when it is actually clicked on its own page. The Preview is available when you click on it below.',
				portfolioNoDisplayTip : 'How many images you would like to display per page?',
				portfolioShortdesTip : 'Enter 250 character long short description.',
				portfolioFulldesTip : 'Enter full detail of your portfolio',
				portfolioImageTip : 'Select image for portfolio',
				portfolioAltTagTip : 'Enter image related text description',
				nofollowTagTip : 'Whatever number of days you put here, make sure you change your website content on same frequency. For example if you have selected 10 days here, you must have modified content by 9th day. Google does not like static websites with same kind of content every time it visits.'
			  };

var allMessages = {
					message02: '',
					message03: '',
					message04: '',
					message05: '',
					message06: '',
					content01: 'This page physically exists. You may have separate facility in this control panel to edit/modify such page.',
					content02: 'You can only proceed to next step once you have decided Page Name and Placement in the first step of "Page Placement"',
					content03: 'This page already exist.',
					content04: 'Invalid Url string , Use "-" instead of spaces.'
				};



var tipconfig = {'where':"right", 'maxwidth':"400px" , 'edgeOffset':8 ,'delay':500 }			  


$(document).ready(function(){	
	observerEvents();
	initEvents();
	initTooltips();
});



window.validArray = (function () {
		  var obj = {};
		  return {
			getObj: function () {return obj;} 
			, add: function (key, data) {
				obj[key] = data;
				return this; // enabling chaining
			  }
		  }
		})();


function initTooltips(){
	$.each(SBHMessages , function(key, value) { 
	  var fieldId = "#"+key;
	  if($(fieldId).length != 0) {
			$(fieldId).tipTip({content :"<span color='red'>"+value+"</span>",defaultPosition :tipconfig['where'],maxWidth : tipconfig['maxwidth'],edgeOffset : tipconfig['edgeOffset'],delay : tipconfig['delay']});
	  }
	});
}

function observerEvents(){
	$("#menu_link_type").change(function(){
		var currentVal = $(this).val();
		if(currentVal == 'OTHER'){
			$('#externalLinkText').css('display','block');
		}else{
			$('#externalLinkText').css('display','none');
			$('#menu_url').val('');
		}
	}); 
	
	
	$('.emailItem img').live( 'click', function(){
	 
	 	var response = confirm("Are you sure to remove selected email?");
	 	if(response){
			var selectedemail = $(this).data("email");
			var type = $(this).attr("title");
			removeEmailIncluds( type , selectedemail);
		}
	});
	
	if ($(".emailIncludeButton").length != 0) {
		$('.emailIncludeButton').live( 'click', function(){
			var type = $(this).data('type');
			insertEmailIncluds(type);
		});
	}
	$('#cccemlText').blur(function() {
		checkEmail($(this).val() , 'ccc');
	});
	$('#bccemlText').blur(function() {
		checkEmail($(this).val() , 'bcc');
	});
	
}


function initEvents(){
	
	
	// portfolio admin popup
	$("#viewLargePortfolioBtns").click(function(event) {
		drawPortfolioImage();
		event.preventDefault();
	});
	$('.close-popup-form').live("click",function(){
		$('#mask').hide();
		$('.popup-link-container').remove();
	});
	if ($(".viewPortfolioStyleBtn").length != 0) {
		$(".viewPortfolioStyleBtn").click(function() {
			drawPortfolioDisplayStyle();
		});	
	}
	
	
	// Gallery image dropdown
	if ($("#gridGalleryCategory").length != 0) {
		$("#gridGalleryCategory").change(function() {
			loadGalleryByCategory($(this).val());
		});
	}
	
	if ($("#game_player1").length != 0) {
		$("#game_player1").limit({limit: 8, id_result: "counter1", alertClass: "alert"});
		$("#game_player2").limit({limit: 8, id_result: "counter2", alertClass: "alert"});
		$("#game_location").limit({limit: 30, id_result: "counter3", alertClass: "alert"});		
	}
	
	
	// content page (seo step)
	
	if ($("#head_title").length != 0) {
		$("#head_title").limit({limit: 60, id_result: "counter1", alertClass: "alert"});
		$("#head_keywords").limit({limit: 256, id_result: "counter2", alertClass: "alert"});
		$("#head_description").limit({limit: 150, id_result: "counter3", alertClass: "alert"});		
	}
	
	if ($("#portfolioShortDes").length != 0) {
		$("#short_description").limit({limit: 250, id_result: "portfolioShortDes", alertClass: "alert"});
	}
	
	
	// content page actions
	if ($("#placementBtn").length != 0) {
		$('#placementBtn').click(function(){
			if(validatePlacementContent()){
				$('#PlacementContentForm').submit();
			}
		});
	}
		
	if ($("#saveandcontinueBtn").length != 0) {
		$('#saveandcontinueBtn').click(function(){
			if(validatePageTextContent()){
				$('#PageTextContentForm').submit();
			}
		});
	}
	
	if ($("#savemobiletextBtn").length != 0) {
		$('#savemobiletextBtn').click(function(){
				$('#MobilePageTextForm').submit();
		});
	}
	
	if ($("#pageseoButton").length != 0) {
		$('#pageseoButton').click(function(){
			if(validatePageSeoContent('page')){
				$('#PageTextSeoForm').submit();
			}
		});
	}
	
	$('#PreviewContentBtn').click(function(){
		var loc 	  = $(this).data('loc');
		var contenttext = CKEDITOR.instances['page_text'].getData();
		priviewContentPage(contenttext , loc);
	});
	
	$('#page_name').blur(function() {
			 checkPagename( false );
	});
	$('#menu_url').blur(function() {
			 checkPageUrl( false , 'page' );
	});
	$('#page_url').blur(function() {
			 checkPageUrl( false , 'block' );
	});
	$('#head_title').blur(function() {
			 checkBlack( 'head_title' ,'' ,false );
	});
	$('#head_keywords').blur(function() {
			 checkBlack( 'head_keywords' ,'' ,false );
	});
	$('#head_description').blur(function() {
			 checkBlack( 'head_description' ,'' ,false );
	});
	
	
	
	// block pages forms
	if ($("#saveandcontinueblockBtn").length != 0) {
		$('#saveandcontinueblockBtn').click(function(){
			if(validatePageTextContent()){
				$('#BlockPageTextForm').submit();
			}
		});
	}
	
	if ($("#pageseoblockButton").length != 0) {
		$('#pageseoblockButton').click(function(){
			if(validatePageSeoContent('block')){
				$('#BlockPageTextSeoForm').submit();
			}
		});
	}
	
	// module pages forms
	if ($("#saveandcontinuemoduleBtn").length != 0) {
		$('#saveandcontinuemoduleBtn').click(function(){
			if(validatePageTextContent()){
				$('#ModulePageTextForm').submit();
			}
		});
	}
	if ($("#pageseomoduleButton").length != 0) {
		$('#pageseomoduleButton').click(function(){
			if(validatePageSeoContent('module')){
				$('#ModulePageTextSeoForm').submit();
			}
		});
	}
	
	
	// for portfolio settings
	if ($("#portfolioThumbwidth").length != 0) {
		$("#portfolioThumbwidth").keyup(function(event) {
			var val = $(this).val();
			if( parseInt(val) != 'NaN' ){
				$("#drawPortfolioArea").css( {'width':val+'px'}); 
			}
		});
		$("#portfolioThumbheight").keyup(function(event) {
			var val = $(this).val();
			if( parseInt(val) != 'NaN'){
				$("#drawPortfolioArea").css( {'height':val+'px'});
			}
		});
	}
	
	
	// fields
	if ($("#ForgetPasswordForm").length != 0) {
		$('#forgetpassButtton').click(function () {
			if(validateForgetForm()){
				$("#ForgetPasswordForm").submit();
			}
		});
		$('#email').blur(function() {
			 checkEmailField('email', false );
		});
		$('#scode').blur(function() {
			 checkBlack('forgetscode','',false );
		});	
	}
	
	if ($("#PortfolioForm").length != 0) {
		$("#PortfolioForm").validate({
		  rules: {
			short_description : "required",
			full_description : "required",
			alt_tag : "required",
			image: {
			  required:true,
			  accept: "jpg|png|gif"
			}
		  }
		});
	}
	
	// validate game board
		
	if ($("#BlockForm").length != 0) {
		$("#BlockForm").validate();
	}
	
	if ($("#MenusForm").length != 0) {
		$("#MenusForm").validate();
	}
	
	if ($("#TestimonialForm").length != 0) {
		$("#TestimonialForm").validate();
	}
	
	if ($("#NewsForm").length != 0) {
		$("#NewsForm").validate();
	}
	
	if ($("#MyAccountForm").length != 0) {
		$("#MyAccountForm").validate();
	}
	
	//user form
	if ($("#UserAccountForm").length != 0) {
		 $("#UserAccountForm").validate({
		 	rules: {
				username: "required"
			}
		});
	}
	
	if ($("#BannerImageForm").length != 0) {
		 $("#BannerImageForm").validate({
		 	rules: {
				short_description: "required",
				alt_tag: "required"
			}
		});
	}
	

	if ($("#removeLetterButton").length != 0) {
		$('#removeLetterButton').live("click", function (){
			removeNewsLetter();
		});
	}
	
	
	
	if ($(".SortButton").length != 0) {
		$('.SortButton').live("click", function (){
			var htmlId    = $(this), output = htmlId.attr('id');
			var idArr = output.split("-");
			
			//data
			var input 	  = $(this).data('row');
			var inputArr  = input.split("-");
			var id   	  = inputArr[0];
			var sortValue = inputArr[1];
			var where     = inputArr[2];
			
			var action    = idArr[0];
			var pointer   = idArr[1];
			
			var extraValues , multiID = '';
			if(inputArr[3] != undefined){
				extraValues = inputArr[3];
			}
			if(idArr[2] != undefined){
				multiID = idArr[2];
			}
			
			if(action == 'up'){
				
				var targetPointer = Math.abs(pointer - 1);
				var selector = '';
				targetPointer = (multiID != '')? targetPointer+"-"+multiID : targetPointer;
				
				if ($("#up-"+targetPointer).length != 0) {
					selector = "#up-"+targetPointer;
				}else{
					selector = "#down-"+targetPointer;
				}
				
				var targetInput 	  = $(selector).data('row');
				var targetInputArr    = targetInput.split("-");
				var targetId   	  = targetInputArr[0];
				var targetSortValue   = targetInputArr[1];
				
				SortItNow(id , sortValue , targetId , targetSortValue , where , extraValues);
				
			}else if(action == 'down'){
				
				var targetPointer = Math.abs(pointer*1 + 1);
				var selector = '';
				
				targetPointer = (multiID != '')? targetPointer+"-"+multiID : targetPointer;
				
				if ($("#down-"+targetPointer).length != 0) {
					selector = "#down-"+targetPointer;
				}else{
					selector = "#up-"+targetPointer;
				}
				
				var targetInput 	  = $(selector).data('row');
				var targetInputArr    = targetInput.split("-");
				var targetId   	  = targetInputArr[0];
				var targetSortValue   = targetInputArr[1];
				
				SortItNow(id ,sortValue , targetId , targetSortValue  , where , extraValues);
			}
		});
	}
	
	
}


function SortItNow( id ,sortValue , targetId , targetSortValue , where , extraValues){

	var loader = '<div class="loader center"><img src="public/images/loader.gif" width="50" height="50" alt="loading" /><br/>Loading...</div>';
	$("#sortableGridView").html(loader);
	
	$.get(
		  "app/ajax/sorting-workers.php",
		  { Id: id , SortValue:sortValue , TargetId: targetId , TargetSortValue: targetSortValue , Where : where , ExtraData: extraValues},
		  function(data) { 
			 $("#sortableGridView").html(data).slideDown("slow");
		  },
		  "html"
	);
	  
}


/*
	_helper functions
*/
function toggleChecked(status) {
	$("#newslettercheckboxs input").each( function() {
		$(this).attr("checked",status);
	});
}
	
function removeNewsLetter(){
	var allVals = [];
	
	$('input[name="newsletterids"]:checked').each(function() { 
       allVals.push($(this).val());
    });
	
	if(allVals.length > 0){
		var areYouSure = confirm("Are you sure to remove selected newsletter subscribers");
		if(areYouSure){
			$.get("app/ajax/remove-newsletter-subscribers.php",
				{ subscriberids: allVals },
				function(data) { 
					$('#newslettercheckboxs').html(data);
				},
				"html"
			);
		}
	}else{
		alert("Select newsletter subscribers for remove.");	
	}
}

function setContentUrl(self){
  	var pagename = self.val();
   	$.ajax({
		  type : 'POST',
		  url : 'app/ajax/validate-pagename.php',
		  data: {
			  Pagename : pagename
		  },
		  success : function(data){
			  var obj = JSON.parse(data);
			  $('#content_url').val(obj.urltext);
		  },
		  error : function(XMLHttpRequest, textStatus, errorThrown) {
			 alert(errorThrown);
			  return false;
		  }
	 });	
}


function checkIsValidContentUrl(urltext){
   	var iChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
   	for (var i = 0; i < urltext.length; i++) {
			  if (iChars.indexOf(urltext.charAt(i)) != -1) {
			  return false;
	  }
   	}
	return true;			
}


function insertEmailIncluds(type){
	
	var target = type+'emlText';
	var emailtext = $('#'+target).val();

	var response = checkEmail( emailtext , type);
	if(response){
		$.ajax({
			  type : 'POST',
			  url : 'app/ajax/put-email-includes.php',
			  data: {
				  Type: type , EmlText: emailtext 
			  },
			  success : function(data){
				  var obj = JSON.parse(data);
				  if(obj.response){
					  var htmlString = '<span class="emailItem round" >'+emailtext+'<img src="public/images/close_icon.png" data-email="'+emailtext+'" title="'+type+'"/></span><br />';
					  $("#"+type+ "EmlGrid").append(htmlString);
					  $('#'+target).val('');
					  var count = obj.whomuch;
					  if(count > 2){
						$("#"+type+ "EmailForm").remove();	  
					  }
				  }
			  },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
				 alert(errorThrown);
				  return false;
			  }
		 });	
	}
}
function checkEmail(emailtext , type){
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var response = reg.test(emailtext);
		var errorLbl = type+"Error";
		
		if ($("#"+errorLbl).length != 0) {
			$("#"+errorLbl).remove();		
		}	
		if(emailtext == ''){
			$('<label id="'+errorLbl+'" class="error" style="padding:3px;">This field required.</label>').insertAfter('#'+type+'emlbtn');	
			return false;
		}else{
			if(response){
				return true;
			}else{
				$('<label id="'+errorLbl+'" class="error" style="padding:3px;">Invalid email address.</label>').insertAfter('#'+type+'emlbtn');	
				return false;	
			}
		}
}



function removeEmailIncluds( type , emailtext ){
	$.get(
		  "app/ajax/remove-email-includes.php",
		  { Type: type , EmlText: emailtext  },
		  function(data) { 
			 $("#"+type+ "EmlGrid").html(data).slideDown("slow");
		  },
		  "html"
	);
}

function priviewContentPage( pagetext , loc ){
	
	var pagetitle = $('#page_title').val();
	var isPreview = true;
	
	if(pagetitle == ""){
		alert("Please enter page title.");
		isPreview = false;
		return false;
	}
	if(pagetext == ""){
		alert("Please enter page text.");
		isPreview = false;
		return false;
	}

	if(isPreview){
		$.ajax({
			  type : 'POST',
			  url : 'app/ajax/priview-content-page.php',
			  data: {
				  ContentText: pagetext , PageTitle :  pagetitle
			  },
			  success : function(data){
				  var obj = JSON.parse(data);
				  if(obj.response){
					var url = "page-preview.php";
					window.open(url,'_blank')
				  }
			  },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
				 alert(errorThrown);
				  return false;
			  }
		 });	
	}	
}



function validatePageTextContent(){
	
	checkBlack('page_title','',true);
	
	var result = true;
	var obj = validArray.getObj();
	for (var prop in obj) {
		if(obj[prop] == false){
			result = false;
		}
	}
	return result;	
}


function validatePlacementContent(){
	
	checkPagename(true);
	
	var result = true;
	var obj = validArray.getObj();
	for (var prop in obj) {
		if(obj[prop] == false){
			result = false;
		}
	}
	return result;				
}
function checkPagename( bool ){
		   
	var response  	= false;
	var pagename  	= $('#page_name').val();
	var contentid 	= $('#cid').val();
	var menuid 		= $('#menu_id').val();
	var errorLbl 	= 'errLblPagename';
	 
	if(pagename != ""){ 
		 $.ajax({
			  type : 'POST',
			  url : 'app/ajax/validate-pagename.php',
			  data: {
				  PageName: pagename , CID :  contentid, MenuID:menuid
			  },
			  success : function(data){
				  if ($('#'+errorLbl).length != 0) {$('#'+errorLbl).remove();}
				  var obj = JSON.parse(data);
				  var res = obj.response;
				  var isok = false;
				  switch(res){
					 case 'p':
					 	 $('<label id="'+errorLbl+'" class="inlineerror">'+allMessages.content01+'</label>').insertAfter($('#pagenameTip'));
						 break;
					 case 'a':
					 	 $('<label id="'+errorLbl+'" class="inlineerror">'+allMessages.content03+'</label>').insertAfter($('#pagenameTip'));
						 break;
					 case 'o':
					 	 isok = true;
						 break;	 	 
				  }
				  validArray.add('page_name' , isok ); 
				  var styles = (isok) ? {'background':'#ffffff','border-color':'#D7D7D7'} : {'background':'#feefef','border-color':'#fcb3b3'};
				  $('#page_name').css(styles);
			  },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
				 //alert(errorThrown);
				  return false;
			  }
		 });		 
	}else{
		if ($('#'+errorLbl).length != 0) {$('#'+errorLbl).remove();}		
		if ($('#'+errorLbl).length == 0) {
			$('<label id="'+errorLbl+'" class="inlineerror">Please enter page name.</label>').insertAfter($('#pagenameTip'));	
		}
		validArray.add('page_name', response );
		var styles = (response) ? {'background':'#ffffff','border-color':'#D7D7D7'} : {'background':'#feefef','border-color':'#fcb3b3'};
		$('#page_name').css(styles);
	}
	if(bool){
			return response;
	}
}



function validatePageSeoContent(pagetype){
	
	if( pagetype != "module" ){
		checkPageUrl(true , pagetype);
	}
	checkBlack('head_title','',true);
	checkBlack('head_keywords','',true);
	checkBlack('head_description','',true);
	
	
	var result = true;
	var obj = validArray.getObj();
	for (var prop in obj) {
		if(obj[prop] == false){
			result = false;
		}
	}
	return result;		
}

function checkPageUrl( bool , pagetype ){
	
	var response  	= false;
	var pageurl		= '';
	var packValues  = {};
	var fieldid     = '';
	
	if(pagetype == 'block'){
		fieldid 		= 'page_url';
		pageurl  		= $('#page_url').val();
		var pageid 		= $('#pageid').val();
		packValues		= { PageURL: pageurl , PageID :  pageid , PageType: pagetype };
	}else{
		fieldid 		= 'menu_url';
		pageurl  		= $('#menu_url').val();
		var contentid 	= $('#cid').val();
		var menuid 		= $('#menu_id').val();
		packValues		= {PageURL: pageurl , CID :  contentid , MenuID : menuid , PageType: pagetype };
	}
	
	var errorLbl 	= 'errLblMenuUrl';
	
	if(pageurl != ""){ 
		 $.ajax({
			  type : 'POST',
			  url : 'app/ajax/validate-content-url.php',
			  data: packValues ,
			  success : function(data){
				  if ($('#'+errorLbl).length != 0) {$('#'+errorLbl).remove();}
				  var obj = JSON.parse(data);
				  var res = obj.response;
				  var isok = false;
				  switch(res){
					 case 'p':
					 	 $('<label id="'+errorLbl+'" class="inlineerror">'+allMessages.content01+'</label>').insertAfter($('#pageurlTip'));
						 break;
					 case 'a':
					 	 $('<label id="'+errorLbl+'" class="inlineerror">'+allMessages.content03+'</label>').insertAfter($('#pageurlTip'));
						 break;
					case 'i':
					 	 $('<label id="'+errorLbl+'" class="inlineerror">'+allMessages.content04+'</label>').insertAfter($('#pageurlTip'));
						 break;	 
					 case 'o':
					 	 isok = true;
						 break;	 	 
				  }
				  validArray.add(fieldid , isok );
				  var styles = (isok) ? {'background':'#ffffff','border-color':'#D7D7D7'} : {'background':'#feefef','border-color':'#fcb3b3'};
					$('#'+fieldid).css(styles); 
			  },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
				 //alert(errorThrown);
				  return false;
			  }
		 });		 
	}else{
		if ($('#'+errorLbl).length != 0) {$('#'+errorLbl).remove();}		
		if ($('#'+errorLbl).length == 0) {
			$('<label id="'+errorLbl+'" class="inlineerror">Please enter page url.</label>').insertAfter($('#pageurlTip'));	
		}
		validArray.add(fieldid , response );
		var styles = (response) ? {'background':'#ffffff','border-color':'#D7D7D7'} : {'background':'#feefef','border-color':'#fcb3b3'};
		$('#'+fieldid).css(styles);
	}

	if(bool){
			return response;
	}
}





// tabs

function DefaultTabs(){
	alert(allMessages.content02);	
}

function placementTab(arg){
	var argArr = arg.split("-");
	window.location = 'manage-contents.php?q=modify&step='+argArr[0]+'&cid='+argArr[1];	
}

function pagetextTab(arg){
	var argArr = arg.split("-");
	window.location = 'manage-contents.php?q=modify&step='+argArr[0]+'&cid='+argArr[1];
}
function mobiletextTab(arg){
	var argArr = arg.split("-");
	window.location = 'manage-contents.php?q=modify&step='+argArr[0]+'&cid='+argArr[1];
}

function pageseoTab(arg){
	var argArr = arg.split("-");
	window.location = 'manage-contents.php?q=modify&step='+argArr[0]+'&cid='+argArr[1];
}


// Block pages tabs 

function pagetextBlockPageTab(arg){
	var argArr = arg.split("-");
	window.location = 'home-page.php?q=modify&step='+argArr[0]+'&pid='+argArr[1];
}

function pageseoBlockPageTab(arg){
	var argArr = arg.split("-");
	window.location = 'home-page.php?q=modify&step='+argArr[0]+'&pid='+argArr[1];
}


// module pages tabs 

function pagetextModulePageTab(arg){
	var argArr = arg.split("-");
	window.location = 'manage-contents.php?q=pages&step='+argArr[0]+'&pid='+argArr[1];
}

function pageseoModulePageTab(arg){
	var argArr = arg.split("-");
	window.location = 'manage-contents.php?q=pages&step='+argArr[0]+'&pid='+argArr[1];
}



/*
	_helper functions
*/
function checkBlack(fieldId , errorMsg , bool){
		   
		var response = false;
		 var val = $('#'+fieldId).val();
		 var errorLbl = 'errLbl'+fieldId;
		 var errorText = 'This field is required.';
		 if(errorMsg != ''){
			 errorText = errorMsg;
		 }
		 if(val != ""){
			 if ($('#'+errorLbl).length != 0) {
				$('#'+errorLbl).remove();
			 }
			 response = true;
		}else{
			if ($('#'+errorLbl).length == 0) {
				$('<label id="'+errorLbl+'" class="inlineerror">'+errorText+'</label>').appendTo($('#wp-'+fieldId));	
			}
		}
		validArray.add(fieldId, response);
		
		var styles = (response) ? {'background':'#ffffff','border-color':'#D7D7D7'} : {'background':'#feefef','border-color':'#fcb3b3'};
		$('#'+fieldId).css(styles);
		
		if(bool){
			return response;
		}
}
function checkEmailField(emailId , bool){
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var errorLbl = 'errLbl'+emailId;
		var val = $('#'+emailId).val();
		var response = reg.test(val);
		var errorText = "Invalid email address.";
		if(val != "" && response == true){
			if ($('#'+errorLbl).length != 0) {
				$('#'+errorLbl).remove();
			}
			response = true;
		}else{
			if ($('#'+errorLbl).length == 0) {
				$('<label id="'+errorLbl+'" class="error">'+errorText+'</label>').appendTo($('#wp-'+emailId));	
			}
		}
		validArray.add(emailId, response);
		
		if(bool){
				return response;
		}
}

function checkCaptcha(fieldId ,prefix, bool){
		   
		var response = false;
		var val = $('#'+ prefix + fieldId).val();
		var gatevalue = $('#'+ prefix + 'allowcode').val();
		var errorLbl = 'errLbl'+fieldId;
		
		if(val != ""){
			 if(val == gatevalue){
				 if ($('#'+errorLbl).length != 0) {
					$('#'+errorLbl).remove();
				 }
				 response = true; 
			 }else{
				if ($('#'+errorLbl).length == 0) {
					$('<label id="'+errorLbl+'" class="error" style=" display: inline;">Enter valid security code!</label>').appendTo($('#wp-'+fieldId));	
				}
				if ($('#'+errorLbl).length > 0) {
					$('#'+errorLbl).remove();
					$('<label id="'+errorLbl+'" class="error" style=" display: inline;">Enter valid security code!</label>').appendTo($('#wp-'+fieldId));	
				}
			 }
		 }else{
			if ($('#'+errorLbl).length == 0) {
				$('<label id="'+errorLbl+'" class="error" style=" display: inline;">Enter security code!</label>').appendTo($('#wp-'+fieldId));	
			}
		}
		validArray.add(fieldId, response);
		
		if(bool){
			return response;
		}
}


// forgetpassword form
function validateForgetForm(){
	checkEmailField('email',true);
	checkCaptcha('scode' , 'forget', true);
	
	var result = true;
	var obj = validArray.getObj();
	for (var prop in obj) {
		if(obj[prop] == false){
			result = false;
		}
	}
	return result;		
}




// load gallery images based on category
function loadGalleryByCategory(categoryid){
	var loader = '<div class="loader center"><img src="public/images/loader.gif" width="50" height="50" alt="loading" /><br/>Loading...</div>';
	$("#sortableGridView").html(loader);
	
	$.get(
		  "app/ajax/load-gallery-by-category.php",
		  { CATEGORY_ID: categoryid },
		  function(data) { 
			 $("#sortableGridView").html(data).slideDown("slow");
		  },
		  "html"
	);
	
}






// popup window
function drawPortfolioImage(){
	
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);
	
	var width 	= $("#portfolioLargwidth").val();
	var height 	= $("#portfolioLargheight").val();
	
	drawPopup(width, height);
	drawHtml(width, height);
}

function drawPopup( width , height){
	
	var containerWidth = width*1 + 20;
	var containerHeight = height*1 + 50;
	
	var block_page = $('<div class="popup-link-container"></div> <!--@popup-link-container-->');
	$(block_page).prependTo('body');
	var winH = $(window).height();
	var winW = $(window).width();
	
	$(".popup-link-container").css({'width':containerWidth,'height':containerHeight});
	$(".popup-link-container").css('top',  (winH/2 - $('.popup-link-container').height()/2) + 250);
	$(".popup-link-container").css('left', winW/2 - $('.popup-link-container').width()/2);	
	
}

function drawHtml(width, height){
	$(".popup-link-container").html('<div class="loader"><img src="public/images/loader.gif" width="50" height="50"><br/>Loading...</div>');
	var htmlString = '<div class="title-bar"> \
					<div class="title-heading"> \
						Portfolio Large Image Preview \
					</div> \
					<div class="close-popup-form pointer"> \
						<img src="public/images/popup-close.png" width="20" height="21" /> \
					</div> \
					<div class="clear"></div> \
				</div>\
				<div style="padding:10px;"> \
				<div style="width:'+width+'px; height:'+height+'px; background:#666; color:#000;text-align:center;">Current Size of Image</div> \
				</div>\
				';
	$(".popup-link-container").html(htmlString);			
}



function drawPortfolioDisplayStyle(){
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);
	
	var containerWidth = 820;
	var containerHeight = 590;
	
	var block_page = $('<div class="popup-link-container"></div> <!--@popup-link-container-->');
	$(block_page).prependTo('body');
	var winH = $(window).height();
	var winW = $(window).width();
	
	$(".popup-link-container").css({'width':containerWidth,'height':containerHeight});
	$(".popup-link-container").css('top',  (winH/2 - $('.popup-link-container').height()/2) + 400);
	$(".popup-link-container").css('left', winW/2 - $('.popup-link-container').width()/2);	
	
	var htmlString = '<div class="title-bar"> \
					<div class="title-heading"> \
						Portfolio image display style \
					</div> \
					<div class="close-popup-form pointer"> \
						<img src="public/images/popup-close.png" width="20" height="21" /> \
					</div> \
					<div class="clear"></div> \
				</div>\
				<div style="padding:10px;"> \
				<img src="public/images/large-image.jpg" width="800" height="538" /> \
				</div>\
				';
	$(".popup-link-container").html(htmlString);	
}
