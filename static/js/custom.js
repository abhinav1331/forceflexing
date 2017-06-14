/* Nice scroll */
jQuery(document).ready(function() 
{
	"use strict";
	jQuery("body").niceScroll({
			cursoropacitymax: 0.8,
			cursorcolor: "#666",
			cursorborder: "none",
			cursorwidth: 4,
			autohidemode: true,
			background: "#fff",
			horizrailenabled: true,
			/*touchbehavior : true*/
	});	
	jQuery("div[id*='ascrail'], div[id*='ascrail']>div").css({'box-shadow':'inset 0 0 3px #000','z-index':'9999'});
	
	jQuery('.bootstrap-select .dropdown-menu').niceScroll({
			cursoropacitymax: 0.7,
			cursorcolor: "#666",
			cursorborder: "none",
			cursorwidth: 4,
			autohidemode: false,
			background: "#fff",
	});
	
	//on click smooth scroll to anchor
	$('.header a[href*="#"]').on('click',function (e) {
	//e.preventDefault();
	var target = this.hash;
	var $target = $(target);

	$('html, body').stop().animate({
		'scrollTop': $target.offset().top
	}, 900, 'swing', function () {
		window.location.hash = target;
	});
	});
 
//******REGISTRATION MODULE ************
jQuery('#contrator-sign-up,#employer-sign-up').validate({
	ignore: "",
	rules: {
		first_name:{required: true},
		last_name:{required: true},
		user_name:{required: true},
		company_name:{required: true},
        country:{required: true},
		email:
		{
			required: true,
			email: true		 
		},
		password: 
		{
			required: true,
			minlength: 6	
		},
		con_pass:
		{
			required: true,
			minlength: 6,
			equalTo : "#password"
			
		},
		terms: {required : true}
	}
});

/****************FORGET PASSWORD************/

jQuery('#forget_password_email').validate({
	rules: {
		forgotemail:
		{
			required: true,
			email: true		 
		}
	}
});

/*ajax request to check whether email exists or not*/
jQuery('#forget_password_email').submit(function(e){
	if (jQuery("#forget_password_email").valid()) 
	{
		e.preventDefault();
		var emailadd=jQuery('#forgotemail').val();
		jQuery.ajax({
			type: "POST",
			url:"/login/verify_email",
			data:{"email":emailadd},
			success:function(resp)
			{
				if(resp == 1)
					jQuery(".forgotPassword .message").addClass('alert alert-success').text("Kindly click on the link we've just sent you.");
				else
					jQuery(".forgotPassword .message").addClass('alert alert-danger').text("Invalid email Address. ");
			}
		});
	}
});


/****change password ***/
jQuery('#change_password').validate({
	rules: {
		new_password:
		{
			required: true,
			minlength: 6	
		},
		confirm_password:
		{
			required: true,
			minlength: 6,
			equalTo : "#confirm_password"
		}
	}
});



/****************FORGET PASSWORD ENDS************/

// /*****JOB POSTING MODULE**********////
//decrement the character limit post a jobs
if (jQuery(".char-left").length > 0) 
{
var max = jQuery(".charcterLimit").val();
if(max != "") {
	max = max;
} else {
	max= 5000;
}
jQuery('.char-left').html(max + ' characters left');
	jQuery('#jp_desc').keyup(function(e) 
	{
		var maxx = 5000;
		var text_length = jQuery('#jp_desc').val().length;
		var text_remaining = maxx - text_length;
		if(text_length >= maxx)
		{
			e.preventDefault();
			this.value = this.value.substring(0, maxx);
			jQuery('.char-left').html('You have reached the limit');
		}
		else
		{
			jQuery('.char-left').html(text_remaining + ' characters left');
		}
	});
}

//show hide div's
//Employers needed
/*jQuery('#jp_mul_emp').hide();
jQuery("input[name=jp_reqemp]").change(function() {
	var req_emp = jQuery(this).val();
	if(req_emp == 'multiple')
		jQuery("#jp_mul_emp").show();
	else
		jQuery("#jp_mul_emp").hide();
}); */

//Activities
jQuery('.multiple-selected-option').hide();
jQuery("input[name='jp_activities']").bind('change', function() {
	var activities = jQuery(this).val();
	if(activities == 'multiple')
	{
		jQuery("input[id='btn-add-activity']").show();
		jQuery('.multiple-selected-option').show();
		jQuery(".emp-job-activity-details").removeClass('one');
		jQuery(".emp-job-activity-details").addClass('multiple');
	}
	else
	{

		jQuery("input[id='btn-add-activity']").css("cssText", "display: none !important;");
		jQuery('.multiple-selected-option').hide();
		jQuery(".emp-job-activity-details").removeClass('multiple');
		jQuery(".emp-job-activity-details").addClass('one');
		var i = 1;
		jQuery(".activityReunion").each(function(){
			if(i != 1) {
				jQuery(this).remove();
			}
			i++;
		});
		jQuery("#btn-remove-activity").remove();
	}
}); 

//payrate
jQuery("input[name=jp_payRate]").change(function() 
{
    jQuery('input[name=jp_payRate]:not(:checked)').parent().removeClass("active");
    jQuery('input[name=jp_payRate]:checked').parent().addClass("active");
	var ourcheckedValue = jQuery('input[name=jp_payRate]:checked').val();
	if (ourcheckedValue == "hourly") 
	{
		jQuery(".emp-additional-pay-options>ul>li:first").show();
		jQuery(".activityReunion").each(function(){
			if (jQuery(this).find("table>tbody>tr:last").hasClass("activity_price")) {
				jQuery(this).find("table>tbody>tr:last").remove();
			} else {
				
			}
		});
	} 
	else
	{
		jQuery(".activityReunion").each(function(){
			if (jQuery(this).find("table>tbody>tr:last").hasClass("activity_price")) {
			} else {
				jQuery(this).find("table>tbody").append("<tr class='activity_price'> <th scope='row'>Activity Price:</th> <td><input class='input small' type='text' placeholder='Activity Price' name='activity_pricee[]'></td></tr>");
			}
		});
		jQuery(".emp-additional-pay-options>ul>li:first").hide();

	}
});

//flexrate
jQuery('input[name="jp_flexRate"]').on('change', function() {
  jQuery('.flex_rate_details').toggle(this.checked);
});

//additional hours payment
jQuery("input[name=jp_pay_additonal_hours]").change(function() {
  if(jQuery(this).val()== "no")
  {
	  jQuery('.allowable_time').hide();
  }
  if(jQuery(this).val()== "yes")
  {
	  jQuery('.allowable_time').show();
  }
});

//ask questions
//add
var counter = 1;
/*jQuery(".add-ques-btn").click(function(){
	if(counter > 10)
	{
		alert("Only 10 textboxes allow");
		return false;
	}
	var newTextBoxDiv = jQuery(document.createElement('div')).attr({'id':'newquestion' + counter,'class':'new-ques-added'});
	newTextBoxDiv.after().html('<a class="remove-ques-btn'+counter+'"><span class="sr-only">Remove this question</span><i class="fa fa-times" aria-hidden="true"></i></a><textarea name="textbox' + counter +'" id="textbox' + counter + '" value="" class="input" placeholder="type question"></textarea>');
	newTextBoxDiv.appendTo(".ask-questions");
	counter++;
});*/
});

/* Wow Js */
wow = new WOW(
{
	boxClass:'wow',     
	animateClass:'animated', 
	offset:100,     
	mobile:false,            
}
)
wow.init();

/*Fixed header on scroll*/
jQuery(document).ready(function() {
	var stickyNavTop = jQuery('body').offset().top;
	var stickyNav = function(){
	var scrollTop = jQuery(window).scrollTop();
	if (scrollTop > 0) {
	jQuery('.header').addClass('stick');
	jQuery('body').addClass('hdrSticked');
	} else {
	jQuery('.header').removeClass('stick');
	jQuery('body').removeClass('hdrSticked');
	}
	};
	stickyNav();
	jQuery(window).scroll(function() {
	stickyNav();
	});
});

/* Owl Carousel */
jQuery('#demo').owlCarousel({
	loop:true,
	margin:0,
	autoHeight:true,
	nav:false,
	dots:true,
	autoplay:true,
	autoplayTimeout:5000,
	autoplayHoverPause:true,
	responsive:{
	0:{
	items:1
	},
	600:{
	items:1
	},
	1000:{
	items:1
	}
	}
});

jQuery('#banner-slider').carousel({
interval: 5000
});

//custom jquery
// jQuery(".newsletter-div input[type='text'], .newsletter-div input[type='email']").focus(function() {
// jQuery(".newsletter-div .fake-btn").addClass("focused");
// });
// jQuery(".newsletter-div input[type='text'], .newsletter-div input[type='email']").blur(function() {
// jQuery(".newsletter-div .fake-btn").removeClass("focused");
// });

jQuery(".newsletter-div input[type='submit']").hover(function(e) {
jQuery(".newsletter-div .fake-btn").toggleClass("hovered");
});


jQuery('.header').append('<div class="scrollProgressBar"></div>');
jQuery(window).on('scroll', function() {
	var scrolltop 	= jQuery(window).scrollTop(),
		winHeight 	= jQuery(window).height(), 
		docHeight	= jQuery(document).height(),
		max 		= docHeight - winHeight,
		width 		= (scrolltop/max) * 100,
		tValue 		= (1 + scrolltop / 3);
		
	jQuery('.banner-image').css({ 'opacity' : (1 - scrolltop/850) });
	jQuery('.home-banner .container').css('transform', 'translateY(' + tValue + 'px)');
	jQuery('.scrollProgressBar').css('width',width + '%');
});
	

//Stop dropdown from closing on click inside
jQuery('ul.dropdown-menu').on('click', function(event){
	event.stopPropagation();
});

//Inbox page
jQuery('.msg-type-area textarea').focus(function() {
	jQuery('.msg-type-area').addClass('focused');
});
jQuery('.msg-type-area textarea').blur(function() {
	jQuery('.msg-type-area').removeClass('focused');
});

//For Second level Navigation
jQuery('.topnav ul li:has(ul)').addClass('hassub');


jQuery('.view-more-toggle').click(function(e) {
	jQuery(this).toggleClass('less');
	jQuery(this).siblings('.more-content').slideToggle();
});

if (jQuery(".selectpicker").length > 0) 
{
	jQuery('.selectpicker').selectpicker({
		style 		: null,
		tickIcon 	: 'fa fa-check'
	});
}

//Add to Favorite
jQuery('.favorite-it i').click(function(){
	jQuery(this).toggleClass('fa-heart-o fa-heart');
});



//browse jobs page
jQuery(document).ready(function(e) 
{
	var winWidth = jQuery(window).width();
	if (jQuery("#isMosaic").length > 0) 
	{
		if (winWidth > 1400) 
		{
			jQuery('#isMosaic').mosaicflow({ itemSelector: 'article', minItemWidth: 400 });
		} 
		else if (winWidth < 1400) 
		{
			jQuery('#isMosaic').mosaicflow({ itemSelector: 'article', minItemWidth: 300 });
		};
	}
});

//Custom file input		
jQuery( ".input-file-trigger" ).click(function(e) {
	jQuery( ".input-file" ).click();
	return false; 
});

jQuery(".input-file").change(function(e) {
	inputValue = jQuery(this).val();
	jQuery(".file-return").html(inputValue);  
});


//Range Slider initialize
if (jQuery("#ff-range-slider").length > 0) 
{	
		var Slider = document.getElementById('ff-range-slider'),
		marginMin = document.getElementById('ff-slider-value-min'),
		marginMax = document.getElementById('ff-slider-value-max');
		var oldURL = document.location;
		var oldURL = new String(oldURL);
		if(oldURL.indexOf("hourlyRange") != -1) {
			var getValue = GetParameterValues("hourlyRange");
			var res = getValue.split(",");
			var min = res[0];
			var max = res[1];
		} else {
			var min = 0;
			var max = 300;
		}
		noUiSlider.create(Slider, {
			start: [ min, max ],
			margin: 1,
			connect: true,
			range: {
				'min': 0,
				'max': 300
			}
		});

			
		Slider.noUiSlider.on('update', function ( values, handle ) {
			if ( handle ) {
				marginMax.innerHTML = values[handle];
			} else {
				marginMin.innerHTML = values[handle];
			}
		});
		Slider.noUiSlider.on('set', function(){
			var minValue = jQuery("#ff-slider-value-min").text();
			var maxValue = jQuery("#ff-slider-value-max").text();
			var totalVal = minValue+","+maxValue;
			jQuery('#hourlyRange').val(totalVal);
			var myURL = document.location;
			var myURL = new String(myURL);
			if(myURL.indexOf("hourlyRange") != -1) {
				var newURL = removeURLParameter(myURL,"hourlyRange");
				var myURL1 = newURL+"&hourlyRange="+totalVal;
				window.history.pushState("string", "hourlyRange", myURL1);
			} else {
				var myURL1 = document.location+"&hourlyRange="+totalVal;
				window.history.pushState("string", "hourlyRange", myURL1);
			}

		});
	}

	/* url concatenation of fixed module**/
	if (jQuery("#ff-range-slider-fixed").length > 0) 
	{	
		var fixedSlider = document.getElementById('ff-range-slider-fixed'),
		fixedMin = document.getElementById('ff-slider-value-min-fixed'),
		fixedMax = document.getElementById('ff-slider-value-max-fixed');
		var oldURL = document.location;
		var oldURL = new String(oldURL);
		if(oldURL.indexOf("fixedRange") != -1) {
			var getValue = GetParameterValues("fixedRange");
			var res = getValue.split(",");
			var min = res[0];
			var max = res[1];
		} else {
			var min = 0;
			var max = 100000;
		}

		noUiSlider.create(fixedSlider, {
			start: [ min, max ],
			margin: 1,
			connect: true,
			range: {
				'min': 0,
				'max': 100000
			}
		});
			
		fixedSlider.noUiSlider.on('update', function ( values, handle ) {
			if ( handle ) {
				fixedMax.innerHTML = values[handle];
			} else {
				fixedMin.innerHTML = values[handle];
			}
		});
		fixedSlider.noUiSlider.on('set', function(){
			var minValue = jQuery("#ff-slider-value-min-fixed").text();
			var maxValue = jQuery("#ff-slider-value-max-fixed").text();
			var totalVal = minValue+","+maxValue;
			jQuery('#fixedRange').val(totalVal);
			var myURL = document.location;
			var myURL = new String(myURL);
			if(myURL.indexOf("fixedRange") != -1) {
				var newURL = removeURLParameter(myURL,"fixedRange");
				var myURL1 = newURL+"&fixedRange="+totalVal;
				window.history.pushState("string", "fixedRange", myURL1);
			} else {
				var myURL1 = document.location+"&fixedRange="+totalVal;
				window.history.pushState("string", "fixedRange", myURL1);
			}

		});
	}


function GetParameterValues(param) 
{  
	var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');  
	for (var i = 0; i < url.length; i++) 
	{  
		var urlparam = url[i].split('=');  
		if (urlparam[0] == param) 
		{  
			return urlparam[1];  
		}  
	}  
}  

function removeURLParameter(url, parameter) 
{
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) 
	{
		var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);
		//reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;)
		{    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) 
			{  
                pars.splice(i, 1);
            }
        }
		url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
        return url;
    } 
	else 
	{
        return url;
    }
}

/****************************************Login Module*****************************/
jQuery(function($) {
	jQuery('#login-user').validate({
		rules: {
			username: {
				required: true
			},
			
			password: {
				required: true,
				minlength: 6
			}
		}
	});
});
/****************************************Login Module*****************************/

/*****************************************Upload File validation**********************************/
	jQuery(document).ready(function(){
		jQuery("input[name='fileUpload']").bind('change', function() {
			//this.files[0].size gets the size of your file.
			var sizematter = this.files[0].size;
			if(sizematter >= 5000000) {
				toastr.error("File Must be Less Than 5 MB");
				setTimeout(function(){ jQuery(this).val(""); jQuery(".file-return").empty();}, 1500);
			}
			var fileExtension = ['docs', 'docx', 'doc', 'txt'];
	        if ($.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
	            toastr.error("Only formats are allowed : "+fileExtension.join(', '));
				setTimeout(function(){ jQuery(this).val(""); jQuery(".file-return").empty();}, 1500);
	        }
		});
	});
/*****************************************Upload File validation**********************************/

/**********************************How Many Emplyee In th List*************************/
jQuery(document).ready(function(){
	if(jQuery("input[name='jp_reqemp']:checked").val() == "one"){
		jQuery("input[id='jp_mul_emp']").css("cssText", "display: none !important;");
	}
	jQuery("input[name='jp_reqemp']").bind('change', function() {
		var changedValue = jQuery(this).val();
		if(changedValue == "one") {
			jQuery("input[id='jp_mul_emp']").css("cssText", "display: none !important;");
		} else {
			jQuery("input[id='jp_mul_emp']").show();
		}
	});
});

/**********************************How Many Emplyee In th List*************************/

	function addActivity() {
		var checkedValue = jQuery("input[name='jp_activities']:checked").val();
		var lengthValuePre = jQuery(".activityReunion").length;
		var commingValue = parseInt(lengthValuePre)+1;
		if(checkedValue == "one") {
			var i = 1;
			jQuery(".activityReunion").each(function(){
				if(i != 1) {
					jQuery(this).remove();
				}
				i++;
			});
			jQuery("#btn-remove-activity").remove();
			return false;
		} else {
			var US = "US";
			jQuery( "<div class='activityReunion' id='activityReunion"+commingValue+"'> <table width='100%' border='0' cellspacing='0' cellpadding='0'> <tr> <th scope='row'>Activites Name:</th> <td> <div class='row'> <div class='col-md-7'> <input name='jp_activity_name[]' id='jp_activity_name' type='text' class='input small half-width'> </div></div></td></tr><tr> <th scope='row'>Select:</th> <td> <label class='radio-custom'> <input type='radio' name='jp_start_stop_time"+commingValue+"[]' value='fixed' id='jp_start_stop_time_fix' checked> <span class='radio'></span>fixed start and stop time <a href='javascript:void(0);' class='calendar-icon'>date</a></label> <span class='sep'>or</span> <label class='radio-custom'> <input type='radio' name='jp_start_stop_time"+commingValue+"[]' value='flexible' id='jp_start_stop_time_flex'> <span class='radio'></span>flexible start/stop <a href='javascript:void(0);' class='calendar-icon'>date</a></label> </td></tr><tr> <th scope='row'>Fixed:</th> <td><div class='row'> <div class='col-md-6'> <div class='row'> <div class='col-md-6'> <input type='text' name='jp_act_start_date[]' id='jp_act_start_date"+commingValue+"' class='input small calendar-icon jp_act_start_date'> </div><div class='col-md-6'> <input type='text'  name='jp_act_start_time[]' id='jp_act_start_time' class='input small watch-icon jp_act_start_time'> </div></div></div><div class='col-md-6'> <div class='row'> <div class='col-md-6'> <input type='text' name='jp_act_end_date[]' id='jp_act_end_date"+commingValue+"' class='input small calendar-icon jp_act_start_date'> </div><div class='col-md-6'> <input type='text'  name='jp_act_end_time[]' id='jp_act_end_time' class='input small watch-icon jp_act_start_time'> </div></div></div></div></td></tr><tr> <th scope='row'>Enter address:</th> <td><div class='row'> <div class='col-md-6'> <div class='row'> <div class='col-md-6'> <select  onchange='onchangeState(this);' data-attribute='activityReunion"+commingValue+"' name='jp_act_state[]' id='sel12 stateId' class='input small states'> <option value=''>Select State</option> <option value='3919'>Alabama</option><option value='3920'>Alaska</option><option value='3921'>Arizona</option><option value='3922'>Arkansas</option><option value='3923'>Byram</option><option value='3924'>California</option><option value='3925'>Cokato</option><option value='3926'>Colorado</option><option value='3927'>Connecticut</option><option value='3928'>Delaware</option><option value='3929'>District of Columbia</option><option value='3930'>Florida</option><option value='3931'>Georgia</option><option value='3932'>Hawaii</option><option value='3933'>Idaho</option><option value='3934'>Illinois</option><option value='3935'>Indiana</option><option value='3936'>Iowa</option><option value='3937'>Kansas</option><option value='3938'>Kentucky</option><option value='3939'>Louisiana</option><option value='3940'>Lowa</option><option value='3941'>Maine</option><option value='3942'>Maryland</option><option value='3943'>Massachusetts</option><option value='3944'>Medfield</option><option value='3945'>Michigan</option><option value='3946'>Minnesota</option><option value='3947'>Mississippi</option><option value='3948'>Missouri</option><option value='3949'>Montana</option><option value='3950'>Nebraska</option><option value='3951'>Nevada</option><option value='3952'>New Hampshire</option><option value='3953'>New Jersey</option><option value='3954'>New Jersy</option><option value='3955'>New Mexico</option><option value='3956'>New York</option><option value='3957'>North Carolina</option><option value='3958'>North Dakota</option><option value='3959'>Ohio</option><option value='3960'>Oklahoma</option><option value='3961'>Ontario</option><option value='3962'>Oregon</option><option value='3963'>Pennsylvania</option><option value='3964'>Ramey</option><option value='3965'>Rhode Island</option><option value='3966'>South Carolina</option><option value='3967'>South Dakota</option><option value='3968'>Sublimity</option><option value='3969'>Tennessee</option><option value='3970'>Texas</option><option value='3971'>Trimble</option><option value='3972'>Utah</option><option value='3973'>Vermont</option><option value='3974'>Virginia</option><option value='3975'>Washington</option><option value='3976'>West Virginia</option><option value='3977'>Wisconsin</option><option value='3978'>Wyoming</option></select> </div><div class='col-md-6'>  <select name='jp_act_city[]' id='sel13 cityId' class='input small cities'> <option value=''>Select City</option> </select> </div></div></div><div class='col-md-6'> <div class='row'> <div class='col-md-6'> <input type='text' class='input small' name='jp_act_street[]' id='jp_act_street' placeholder='street' ><!--<select name='' class='input small'> <option>street</option> </select>--> </div><div class='col-md-6'> <input onkeyup='isValidPostalCode(this)' type='text' class='input small' name='jp_act_zip[]' id='jp_act_zip' placeholder='zip'><div class='messahe-zip'></div> </div></div></div></div></td></tr><tr> <th scope='row'>Name contact:</th> <td><div class='row'> <div class='col-md-6'> <input name='jp_act_cont_fname[]' id='jp_act_cont_fname' type='text' placeholder='first name' class='input small'> </div><div class='col-md-6'> <input name='jp_act_cont_lname[]' id='jp_act_cont_lname' type='text' placeholder='last name' class='input small'> </div></div></td></tr><tr> <th scope='row'>Contact:</th> <td><div class='row'> <div class='col-md-6'> <input name='jp_act_cont_phne[]' id='jp_act_cont_phne' type='text' placeholder='phone' class='input small'> </div><div class='col-md-6'> <input name='jp_act_cont_email[]' id='jp_act_cont_email' type='text' placeholder='email' class='input small'> </div></div></td></tr><tr> <th scope='row'>Notes/tasks:</th> <td><textarea name='jp_act_notes[]' id='jp_act_notes' cols='' rows='' class='input small' placeholder='text here'></textarea></td></tr></table> </div>" ).insertAfter( ".activityReunion:last" );
		}

		var lengthValue = jQuery(".activityReunion").length;
		if(lengthValue == 1) {
			jQuery("#btn-remove-activity").remove();
		} else if(lengthValue == 2) {
			jQuery( "<input onclick='removeActivity();' type='button' value='-' id='btn-remove-activity' name='btn-remove-activity' class='input inline small'>" ).insertAfter( "#btn-add-activity" );
		}
		jQuery(".jp_act_start_date").each(function(){
			jQuery(this).datepicker({ dateFormat: 'yy-mm-dd' });
		});
		jQuery('.jp_act_start_time').each(function(){
			jQuery(this).timepicker({ 'timeFormat': 'H:i:s' });;
		});
		jQuery(".activityReunion").each(function(){
			if (jQuery(this).find("table>tbody>tr:last").hasClass("activity_price")) {
			} else {
				jQuery(this).find("table>tbody").append("<tr class='activity_price'> <th scope='row'>Activity Price:</th> <td><input class='input small' type='text' placeholder='Activity Price' name='activity_pricee[]'></td></tr>");
			}
		});
	}

	function removeActivity() {
		var lengthValue = jQuery(".activityReunion").length;
		if(lengthValue == 2) {
			jQuery(".activityReunion:last").remove();
			jQuery("#btn-remove-activity").remove();
		} else {
			jQuery(".activityReunion:last").remove();
		}
	}
	

/***********************************Flex Timings***********************************/
	jQuery(document).ready(function(){
		jQuery("input[name='jp_flex_freq']").bind('change', function() {
			var getValue = jQuery("input[name='jp_flex_freq']:checked").val();
			var ActualLength = jQuery(".emp-flex-date-interval").length;
			var ActualLength1 = jQuery(".emp-flex-date-interval").length;
			if(getValue > ActualLength) {
				
				var getDiffer = parseInt(getValue) - parseInt(ActualLength);
				for(var i= 1; i <= getDiffer; i++) {
					jQuery("<div class='emp-flex-interval emp-flex-date-interval' name='jp_flex_interval' id='jp_flex_interval'> <input type='text' name='flex-month-date[]' class='input calendar-icon flex-date-picker'> </div>").insertAfter( ".emp-flex-date-interval:last" );
					jQuery("<div class='emp-flex-interval emp-flex-completion-interval' id='jp_flex_amount' name='jp_flex_amount'> <input type='text' name='flex-month-completion[]' class='input percent-icon'> </div>").insertAfter( ".emp-flex-completion-interval:last" );
					
				}
			} else {
				var getDiffer = parseInt(ActualLength) - parseInt(getValue);
				for(var i= 1; i <= getDiffer; i++) {
					jQuery(".emp-flex-date-interval:last").remove();
					jQuery(".emp-flex-completion-interval:last").remove();
				}
			}
			jQuery(".flex-date-picker").each(function(){
				jQuery(this).datepicker({ dateFormat: 'yy-mm-dd' });
			});
			
		});


		
	});
/***********************************Flex Timings***********************************/

/***************************************Quiestions Add Remove*****************************/
	function functionToAddQuiestions() {
		jQuery(" <div class='new-ques-added'> <a href='javascript:void(0)' onclick='getRemove(this)' class='remove-ques-btn'><span class='sr-only'>Remove this question</span><i class='fa fa-times' aria-hidden='true'></i></a> <textarea name='quiestions[]' cols='' rows='' class='input' placeholder='type question'></textarea> </div>").insertAfter( ".new-ques-added:last" );
	}
/***************************************Quiestions Add Remove*****************************/

/**********************************Post A Jb Module**********************************/
	jQuery(function($) {
		jQuery('#post_job').validate({
			rules: {
				jp_title: {
					required: true
				},
				jp_desc: {
					required: true,
					minlength: 30
				},
				jp_reqemp: {
					required: true
				},
				"jp_activity_name[]": {
					required: true
				},
				"jp_start_stop_time[]": {
					required: true
				},
				"jp_act_start_date[]": {
					required: true
				},
				"jp_act_start_time[]": {
					required: true
				},
				"jp_act_end_date[]": {
					required: true
				},
				"jp_act_end_time[]": {
					required: true
				},
				"jp_act_state[]": {
					required: true
				},
				"jp_act_city[]": {
					required: true
				},
				"jp_act_street[]": {
					required: true
				},
				"jp_act_zip[]": {
					required: true
				},
				"jp_act_cont_fname[]": {
					required: true
				},
				"jp_act_cont_lname[]": {
					required: true
				},
				"jp_act_cont_phne[]": {
					required: true
				},
				"jp_act_cont_email[]": {
					required: true
				},
				"jp_act_notes[]": {
					required: true
				},
				jp_empDistance: {
					required: true
				},
				jp_flex_freq: {
					required: function(){
                       if(jQuery("input[name='jp_flexRate']").is(':checked')) {
                       	return true;
                       }
                  }
				},
				"flex-month-date[]": {
					required: function(){
                       if(jQuery("input[name='jp_flexRate']").is(':checked')) {
                       	return true;
                       }
                  }
				},
				"flex-month-completion[]": {
					required: function(){
                       if(jQuery("input[name='jp_flexRate']").is(':checked')) {
                       	return true;
                       }
                  }
				},
				jp_pay_additonal_hours: {
					required: true
				},
				jp_allw_time_bfr_acti: {
					required: function(){
                       if(jQuery("input[name='jp_pay_additonal_hours']").is(':checked')) {
                       	return true;
                       }
                  }
				},
				jp_allw_time_aftr_acti: {
					required: function(){
                       if(jQuery("input[name='jp_pay_additonal_hours']").is(':checked')) {
                       	return true;
                       }
                  }
				},
				jp_travelCost: {
					required: true
				},
				jp_preferences: {
					required: true
				},
				jp_jobs_completed: {
					required: true
				},
				jp_language: {
					required: true
				},
				industry_knowledge: {
					required: true
				},
			}
		});
	});
/**********************************Post A Jb Module**********************************/

/***************************DatePicker Module*******************************/
	jQuery(document).ready(function(){
		
		setTimeout(function(){ jQuery(".countries").val('231'); jQuery(".countries").trigger( "change" ); jQuery(".countries").hide(); }, 1000);
		jQuery('.jp_act_start_time').each(function(){
			jQuery(this).timepicker({ 'timeFormat': 'H:i:s' });
		});
		jQuery(".jp_act_start_date").each(function(){
			jQuery(this).datepicker({ dateFormat: 'yy-mm-dd' });
		});
		jQuery(".flex-date-picker").each(function(){
			jQuery(this).datepicker({ dateFormat: 'yy-mm-dd' });
		});
	});
/***************************DatePicker Module*******************************/

/******************Language Multple Ac cept**********************************/
jQuery( function() {
    var availableTags = ["Afar (Djibouti)","Afar (Eritrea)","Afar (Ethiopia)","Afrikaans (South Africa)","Albanian (Albania)","Albanian (Macedonia)","Amharic (Ethiopia)","Arabic (Algeria)","Arabic (Bahrain)","Arabic (Egypt)","Arabic (India)","Arabic (Iraq)","Arabic (Jordan)","Arabic (Kuwait)","Arabic (Lebanon)","Arabic (Libya)","Arabic (Morocco)","Arabic (Oman)","Arabic (Qatar)","Arabic (Saudi Arabia)","Arabic (Sudan)","Arabic (Syria)","Arabic (Tunisia)","Arabic (United Arab Emirates)","Arabic (Yemen)","Aragonese (Spain)","Armenian (Armenia)","Assamese (India)","Asturian (Spain)","Azerbaijani (Azerbaijan)","Azerbaijani (Turkey)","Basque (France)","Basque (Spain)","Belarusian (Belarus)","Bemba (Zambia)","Bengali (Bangladesh)","Bengali (India)","Berber (Algeria)","Berber (Morocco)","Blin (Eritrea)","Bosnian (Bosnia and Herzegovina)","Breton (France)","Bulgarian (Bulgaria)","Burmese (Myanmar [Burma])","Catalan (Andorra)","Catalan (France)","Catalan (Italy)","Catalan (Spain)","Chinese (China)","Chinese (Hong Kong SAR China)","Chinese (Singapore)","Chinese (Taiwan)","Chuvash (Russia)","Cornish (United Kingdom)","Crimean Turkish (Ukraine)","Croatian (Croatia)","Czech (Czech Republic)","Danish (Denmark)","Divehi (Maldives)","Dutch (Aruba)","Dutch (Belgium)","Dutch (Netherlands)","Dzongkha (Bhutan)","English (Antigua and Barbuda)","English (Australia)","English (Botswana)","English (Canada)","English (Denmark)","English (Hong Kong SAR China)","English (India)","English (Ireland)","English (New Zealand)","English (Nigeria)","English (Philippines)","English (Singapore)","English (South Africa)","English (United Kingdom)","English (United States)","English (Zambia)","English (Zimbabwe)","Esperanto","Estonian (Estonia)","Faroese (Faroe Islands)","Filipino (Philippines)","Finnish (Finland)","French (Belgium)","French (Canada)","French (France)","French (Luxembourg)","French (Switzerland)","Friulian (Italy)","Fulah (Senegal)","Galician (Spain)","Ganda (Uganda)","Geez (Eritrea)","Geez (Ethiopia)","Georgian (Georgia)","German (Austria)","German (Belgium)","German (Germany)","German (Liechtenstein)","German (Luxembourg)","German (Switzerland)","Greek (Cyprus)","Greek (Greece)","Gujarati (India)","Haitian (Haiti)","Hausa (Nigeria)","Hebrew (Israel)","Hebrew (Israel)","Hindi (India)","Hungarian (Hungary)","Icelandic (Iceland)","Igbo (Nigeria)","Indonesian (Indonesia)","Interlingua","Inuktitut (Canada)","Inupiaq (Canada)","Irish (Ireland)","Italian (Italy)","Italian (Switzerland)","Japanese (Japan)","Kalaallisut (Greenland)","Kannada (India)","Kashmiri (India)","Kashubian (Poland)","Kazakh (Kazakhstan)","Khmer (Cambodia)","Kinyarwanda (Rwanda)","Kirghiz (Kyrgyzstan)","Konkani (India)","Korean (South Korea)","Kurdish (Turkey)","Lao (Laos)","Latvian (Latvia)","Limburgish (Belgium)","Limburgish (Netherlands)","Lithuanian (Lithuania)","Low German (Germany)","Low German (Netherlands)","Macedonian (Macedonia)","Maithili (India)","Malagasy (Madagascar)","Malay (Malaysia)","Malayalam (India)","Maltese (Malta)","Manx (United Kingdom)","Maori (New Zealand)","Marathi (India)","Mongolian (Mongolia)","Nepali (Nepal)","Northern Sami (Norway)","Northern Sotho (South Africa)","Norwegian Bokm\u00e5l (Norway)","Norwegian Nynorsk (Norway)","Occitan (France)","Oriya (India)","Oromo (Ethiopia)","Oromo (Kenya)","Ossetic (Russia)","Papiamento (Netherlands Antilles)","Pashto (Afghanistan)","Persian (Iran)","Polish (Poland)","Portuguese (Brazil)","Portuguese (Portugal)","Punjabi (India)","Punjabi (Pakistan)","Romanian (Romania)","Russian (Russia)","Russian (Ukraine)","Sanskrit (India)","Sardinian (Italy)","Scottish Gaelic (United Kingdom)","Serbian (Montenegro)","Serbian (Serbia)","Sidamo (Ethiopia)","Sindhi (India)","Sinhala (Sri Lanka)","Slovak (Slovakia)","Slovenian (Slovenia)","Somali (Djibouti)","Somali (Ethiopia)","Somali (Kenya)","Somali (Somalia)","South Ndebele (South Africa)","Southern Sotho (South Africa)","Spanish (Argentina)","Spanish (Bolivia)","Spanish (Chile)","Spanish (Colombia)","Spanish (Costa Rica)","Spanish (Dominican Republic)","Spanish (Ecuador)","Spanish (El Salvador)","Spanish (Guatemala)","Spanish (Honduras)","Spanish (Mexico)","Spanish (Nicaragua)","Spanish (Panama)","Spanish (Paraguay)","Spanish (Peru)","Spanish (Spain)","Spanish (United States)","Spanish (Uruguay)","Spanish (Venezuela)","Swahili (Kenya)","Swahili (Tanzania)","Swati (South Africa)","Swedish (Finland)","Swedish (Sweden)","Tagalog (Philippines)","Tajik (Tajikistan)","Tamil (India)","Tatar (Russia)","Telugu (India)","Thai (Thailand)","Tibetan (China)","Tibetan (India)","Tigre (Eritrea)","Tigrinya (Eritrea)","Tigrinya (Ethiopia)","Tsonga (South Africa)","Tswana (South Africa)","Turkish (Cyprus)","Turkish (Turkey)","Turkmen (Turkmenistan)","Uighur (China)","Ukrainian (Ukraine)","Upper Sorbian (Germany)","Urdu (Pakistan)","Uzbek (Uzbekistan)","Venda (South Africa)","Vietnamese (Vietnam)","Walloon (Belgium)","Welsh (United Kingdom)","Western Frisian (Germany)","Western Frisian (Netherlands)","Wolof (Senegal)","Xhosa (South Africa)","Yiddish (United States)","Yoruba (Nigeria)","Zulu (South Africa)" ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    jQuery( "#jp_language")
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	  
	  /*****Languages selector for contractor profile *******/
	    jQuery( "#contr_language")
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	  
  } );
/******************Language Multple Ac cept**********************************/

/*jQuery(document).ready(function(){
	jQuery(".remove-ques-btn").each(function(){
		jQuery(this).click(function(){
			jQuery(this).parent().remove();
		});
	});
});*/
	function getRemove(event) {
		jQuery(event).parent().remove();
	}
		
function isValidPostalCode(event) {
	var countryCode = "US";
	var postalCode = jQuery(event).val();
    switch (countryCode) {
        case "US":
            postalCodeRegex = /^([0-9]{5})(?:[-\s]*([0-9]{4}))?$/;
            break;
        case "CA":
            postalCodeRegex = /^([A-Z][0-9][A-Z])\s*([0-9][A-Z][0-9])$/;
            break;
        default:
            postalCodeRegex = /^(?:[A-Z0-9]+([- ]?[A-Z0-9]+)*)?$/;
    }
    console.log(postalCodeRegex.test(postalCode));
    if(postalCodeRegex.test(postalCode) == true) {
    	console.log("Success");
    	jQuery(event).siblings(".messahe-zip").empty();
    } else {
    	jQuery(event).siblings(".messahe-zip").empty().append("Invalid Zip");
    }
}


	function onchangeSelectPre(event) {
		var onchangeValue = jQuery(event).val();
		jQuery.ajax({
			type: "POST",
			url:"http://force.imarkclients.com/postjob/onchangeValue",
			data:{onchangeValue:onchangeValue,format:'raw'},
			success:function(resp){
				location.reload();
			}
		});
	}

	/****************************Preview My Job Employer*******************************/
		jQuery(document).ready(function(){
			jQuery(".preview-jobpost").click(function(){
				var formData = jQuery('#post_job').serialize();
				alert(formData);
				jQuery.ajax({
					type: "POST",
					url:"http://force.imarkclients.com/postjob/previewjob",
					data:{formData:formData,format:'raw'},
					success:function(resp){
						window.open('http://force.imarkclients.com/postjob/preview', '_blank');
					}
				});
			});
		});
	/****************************Preview My Job Employer******************************/
	
/***********************Contractor Profile Module******************************/
					
					
jQuery(document).ready(function(){
	/**********Add skill***********/
	jQuery('.add_skill').click(function()
	{
		var html='<div class="add-skill"><input type="text" class="single-skill input" name="skills[]" value=""> <a class="remove" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
		jQuery(html).insertAfter('.add-skill:last');
	});
	
						/**********Remove Skill************/
	jQuery(document).on('click','.remove',function(){
		jQuery(this).parent().remove();
	});
	
						/**********Save Skill function**********/
	jQuery(document).on('click','#save_skills',function()
	{
		
		jQuery('.add-personal-details .pro-skills').html('');
		var skill="";
		var all_skills=[];
		var htmldata="";
		jQuery(".single-skill").each(function(){
			skill=jQuery(this).val();
			htmldata += '<span class="industry-tag">'+skill+'</span>';
			all_skills.push(skill);
		});
		
		/*** Ajax to save skills ***/
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'skills','fieldval': all_skills}
		});
			
		jQuery('.add-personal-details .pro-skills').html(htmldata);
		jQuery('#skill').modal('hide');
	});
	
			/**************Save hourly wages *********/
	jQuery(document).on('click','#save_wages',function(){
		var wages=jQuery('.wages').val();
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'hourly_wages','fieldval': wages}
		});
		htmldata=wages+' $/hr';
		jQuery('.hourly_wage_val').html(htmldata);
		jQuery('#hourly_wage').modal('hide');
	});	
	
		

				/**********Save Languages *********/
	jQuery(document).on('click','#save_lang',function()
	{
		var lan=jQuery('#contr_language').val();
		jQuery('.add-professional-details .all-languages').html('');
		lan = lan.replace(/,\s*$/, "");
		/******** Ajax to save skills *******/
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'languages','fieldval': lan}
		});
			
		jQuery('.add-professional-details .all-languages').html(lan);
		jQuery('#lang').modal('hide');
	});
	
	/****Save freelancer type***/
	jQuery(document).on('click','#save_contractor_type',function()
		{
			var type=jQuery('#type-select').val();
			jQuery.ajax({
				type:"post",
				datatype: "html",
				url : '/contractor/save_data',
				data :{'fieldname':'free_type','fieldval': type}
				});
			jQuery('.freelance-type').html(type);
			jQuery('#contrac-type').modal('hide');
		});
	
	
	
					/*****Save overview*******/
	jQuery('#overview').blur(function(){
		var desc=jQuery(this).val();
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'description','fieldval': desc},
			success : function(response)
			{
				if(response === "updated" || response === "inserted")
				{
					toastr.success('Your data has been Saved!!')
				}
			}
		});
	});
	
	/********Fetch cities from state id*********/
	jQuery(document).on('change','.loc-state',function(){
		var state_id=jQuery(this).val();
		var selected_city=jQuery('#selected-city').val();
		var selectd="";
		if(selected_city != "" || selected_city != null || selected_city != undefined)
			 selectd=selected_city;
		
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/get_cities',
			data :{'stateid':state_id,'selected_city':selectd},
			success : function(response)
			{
				jQuery("#location-city").html(response);
			}
		});
	});
	
	/****case of location***/
	jQuery(document).on('click','#add_location_poup',function(){
		var state_id=jQuery('#selected-state').val();
		var selected_city=jQuery('#selected-city').val();
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/get_cities',
			data :{'stateid':state_id,'selected_city':selected_city},
			success : function(response)
			{
				jQuery("#location-city").html(response);
			}
		});
	});
	
	/*******Save City and state *********/
	jQuery('#save_location').click(function(){
		var state=jQuery('#location-state option:selected').text();
		var city=jQuery('#location-city').val();
		var loc=[];
		loc.push(state);
		loc.push(city);
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'location','fieldval': loc}
		});
		jQuery('.add-personal-details .pro-location').html(state+', '+city);
		jQuery('#location').modal('hide');
	});
	
	
	/**** Add More Employment History****/
	jQuery('#employment_history').click(function(){
		var clone=jQuery('.employment:last').clone();
		clone.find("input").val("");
		clone.find("select").val("");
		
		/*increment the id */
		var last_id=clone.find(".from_eh").attr('id');
		last_id = last_id.replace('dp', '');
		last_id= parseInt(last_id) + 5;
		last_id= 'dp'+last_id;
		clone.find(".from_eh").attr('id',last_id);
		
		/*increment the id by 1*/
		var last_id_to=clone.find(".to_eh").attr('id');
		last_id_to = last_id.replace('dp', '');
		last_id_to= parseInt(last_id) + 5;
		last_id_to= 'dp'+last_id;
		clone.find(".to_eh").attr('id',last_id_to);
		
		/*Add datepicker to cloned elements*/
		clone.find(".from_eh").removeClass('hasDatepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM yy',
			showButtonPanel: true,
			yearRange: "1995:+nn",
			onClose: function(dateText, inst) 
			{ 
				jQuery(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			}
			});
			
		clone.find(".to_eh").removeClass('hasDatepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM yy',
			showButtonPanel: true,
			yearRange: "1995:+nn",
			onClose: function(dateText, inst) 
			{ 
				jQuery(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			}
			});
		clone.insertAfter('.row.employment:last');
	});
	
	jQuery('.from_eh, .to_eh').each(function()
	{
		jQuery(this).datepicker({
			showButtonPanel: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM yy',
			yearRange: "1995:+nn",
			onClose: function(dateText, inst) 
			{ 
				jQuery(this).datepicker('setDate', 
				new Date(inst.selectedYear, inst.selectedMonth, 1));
			}
		});
    });
	
	
	
	
	/**** on click of remove****/
	jQuery(document).on('click','.remove-employment',function(){
		jQuery(this).parents().eq(3).remove();
	});
	
	/**** Save fields of employment history*****/
	jQuery('#save_emp_history').click(function(e)
	{
		var designation="";
		var company_name="";
		var frm ="";
		var to ="";
		var all_emp_hitory=[];
		var temp=1;
		jQuery('.employment').each(function()
		{
				var hist=[];
				designation=jQuery(this).find('#designation').val();
				company_name=jQuery(this).find('#company_name').val();
				if(designation == "")
				{
					toastr.error('Kindly enter a Designation value!!');
					temp=0;
				}
				if(company_name == "")
				{
					toastr.error('Kindly enter a company Name!!');
					temp=0;
				}
				frm=jQuery(this).find('.from_eh').val();
				to=jQuery(this).find('.to_eh').val();
				
				var ret = frm.split(" ");
				var frm1 = ret[0];
				var frm2 = ret[1];
				
				var ret1 = to.split(" ");
				var to1 = ret1[0];
				var to2 = ret1[1];
				
				var date1 = new Date(''+frm2+'-'+frm1+'01 12:00:00');
				var date2 = new Date(''+to2+'-'+to1+'01 12:00:00');
				
				if(date1 > date2 || frm == "" || to =="" )
				{
					jQuery(this).find('.from_eh').addClass('error');
					toastr.error('Kindly enter a valid Date Range');
					temp=0;
				}
				else
				{
					jQuery(this).find('.from_eh').removeClass('error');
				}
				
				hist.push(designation);
				hist.push(company_name);
				hist.push(frm);
				hist.push(to);
				all_emp_hitory.push(hist);
		});
		if(temp==1)
		{
			jQuery.ajax({
				type:"post",
				datatype: "html",
				url : '/contractor/save_data',
				data :{'fieldname':'employment_history','fieldval': all_emp_hitory},
				success : function(response)
				{
					if(response === "updated" || response === "inserted")
					{
						toastr.success('Your data has been Saved!!');
					}
				}
			});
		}
	});
	
	/*******Add more Education*******/
	jQuery('#education').click(function(){
		var clone=jQuery('.education').first().clone();
		clone.find("input").val("");
		clone.find("select").val("");
		clone.insertAfter('.row.education:last');
	});
	
	/**** on click of remove****/
	jQuery(document).on('click','.remove-education',function(){
		jQuery(this).parents().eq(3).remove();
	});
	
	/**** Save fields of Education*****/
	jQuery('#save_education').click(function()
	{
		var qualification="";
		var frm ="";
		var to ="";
		var all_educ=[];
		var temp=1;
		jQuery('.education').each(function()
		{
				var edu=[];
				qualification=jQuery(this).find('#qualification').val();
				if(qualification == "")
				{
					toastr.error('Kindly enter Qualification!!');
					temp= 0;	
				}
				frm=jQuery(this).find('#from-edu').val();
				to=jQuery(this).find('#to-edu').val();
				if(frm == "" || to == "")
				{
					toastr.error('Kindly enter a valid Date Range!!');
					temp= 0;
				}
				if(to < frm)
				{
					jQuery(this).find('#from-edu').addClass('error');
					toastr.error('Kindly enter a valid Date Range!!');
					temp= 0;
				}
				else
				{
					jQuery(this).find('#from-edu').removeClass('error');
				}
				
				edu.push(qualification);
				edu.push(frm);
				edu.push(to);
				all_educ.push(edu);
		});
		if(temp == 1)
		{
			jQuery.ajax({
				type:"post",
				datatype: "html",
				url : '/contractor/save_data',
				data :{'fieldname':'education','fieldval': all_educ},
				success : function(response)
				{
					if(response === "updated" || response === "inserted")
					{
						toastr.success('Your data has been Saved!!')
					}
				}
			});
		}
	});
	
	/*****Add More training ****/
	jQuery('#training').click(function(){
		var clone=jQuery('.training').first().clone();
		clone.find("input").val("");
		clone.find("select").val("");
		clone.insertAfter('.row.training:last');
	});
	
	/**** on click of remove****/
	jQuery(document).on('click','.remove-training',function(){
		jQuery(this).parents().eq(3).remove();
	});
	
	/**** Save fields of Training*****/
	jQuery('#save_training').click(function()
	{
		var trainingtitle="";
		var frm ="";
		var to ="";
		var all_training=[];
		jQuery('.training').each(function()
		{
				var training=[];
				trainingtitle=jQuery(this).find('#training').val();
				frm=jQuery(this).find('#from-trai').val();
				to=jQuery(this).find('#to-trai').val();
				training.push(trainingtitle);
				training.push(frm);
				training.push(to);
				all_training.push(training);
		});
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'training','fieldval': all_training},
			success : function(response)
			{
				if(response === "updated" || response === "inserted")
				{
					toastr.success('Your data has been Saved!!')
				}
			}
		});
	});
	
	/****** Add image **********/
	jQuery('#contractor_avatar').change(function(){
		readURL(this);
	});
	function readURL(input) 
	{
	  if (input.files && input.files[0]) 
	  {
		var reader = new FileReader();
		reader.onload = function(e) 
		{
			jQuery('#previewHolder').attr('src', e.target.result);
			jQuery('.avatar-select').addClass('filled');
			jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_image',
			data :{'image':e.target.result},
			success: function(response)
			{
				if(response === "updated" || response === "inserted")
				{
					toastr.success('Your Image has been Saved!!')
				}
			}
			});
		}
		reader.readAsDataURL(input.files[0]);
	  }
	}
	/***** save availability*****/
	jQuery(document).on('click','#save_avail',function()
	{
		var avail=jQuery('#availability-select').val();
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'availability','fieldval': avail}
			});
		jQuery('.pro-availability').html(avail+'/hrs weekly');
		jQuery('#availability').modal('hide');
	});

	/***Save Industries*****/
	jQuery(document).on('click','#save_industries',function(){
		var indust=jQuery('#indus').val();
		indust = indust.replace(/,\s*$/, "");
		var htmlind="";
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'industries','fieldval': indust}
			});
			var indusarr = indust.split(',');
			
			for (var i = 0; i < indusarr.length; i++) 
			{
				 htmlind += '<span class="industry-tag">'+indusarr[i]+'</span>';
			}
			jQuery('.pro-industries').html(htmlind);
			jQuery('#industries').modal('hide');
	});
	
	/****save speciality**/
	jQuery(document).on('click','#save_speciality',function(){
		var spe=jQuery('#speciality-val').val();
		var htmlind="";
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_data',
			data :{'fieldname':'speciality','fieldval': spe}
			});
			
			htmlind += '<span class="industry-tag">'+spe+'</span>';
			
			jQuery('.pro-speciality').html(htmlind);
			jQuery('#speciality').modal('hide');
	});
	
	/****Toggle div for showing more industries*****/
	jQuery('#show-more-ind').click(function(){
		jQuery('.pro-more-content').toggle();
	});
	
});


/***************Job Search Module*******************/
	jQuery('#fixed').click(function(){
		jQuery('.pay-rate-fixed').toggle();
	});

	jQuery('#hourly').click(function(){
		jQuery('.pay-rate-hourly').toggle();
		
	});

	/*Filter Section*/
	if (jQuery("#ff-range-slider").length > 0) 
	{
		Slider.noUiSlider.on('set', function(){
				/*set pagenumber to 1 each time filter runs*/
				jQuery('#page_num').val(1);
				runfilter();
		});
	}

	if (jQuery("#ff-range-slider-fixed").length > 0)
	{
		
		fixedSlider.noUiSlider.on('set', function(){
			jQuery('#page_num').val(1);
			runfilter();
		});
	}

	jQuery('input.filter , select.filter').change(function(){
		jQuery('#page_num').val(1);
		runfilter();
	});

	/*append page number to query string variable*/
	jQuery(document).on( "click", ".page_filter a", function ()
	{
		var page=jQuery(this).attr("data-page");
		jQuery('#page_num').val(page);
		runfilter();
	});

		

function runfilter()
{
	string='';
	jQuery('.filter').each(function()
	{
		if(jQuery(this).prop('checked') || (jQuery(this).hasClass('selectpicker') && jQuery(this).val() != "")  || (jQuery(this).attr('type') === 'hidden' && jQuery(this).val() != ""))
		{
			var va=jQuery(this).val();
			var nam=jQuery(this).attr('name');
			string += nam +'='+ va + '&';
		}
	});
	if(string != '' && string !=null)
	{
		/*removed last & from the string */
		string=string.slice(0,-1); 
		
		var myURL = document.location;
		var myURL = new String(myURL);
		
		/*if search text then concatenate filter string with it*/
		if( getUrlVars()['searchItem'] !== undefined)
		{
			
			var newuri=myURL.split("submit=")[0]; 
			var myURL1 = newuri+"submit=Submit&"+string;
			window.history.pushState("string", "querystring", myURL1);
		}
		else
		{
			/*clear the previous and then concatenate new*/
			var newuri=myURL.split("?")[0]; 
			var random=Math.random().toString(36).substring(4);
			var myURL1 = newuri+"?securityKey="+random+"&"+string;
			window.history.pushState("string", "querystring", myURL1);
		}
		
	}
	
	var search=getUrlVars()['searchItem'];
	if(search === undefined)
		search ="";
	jQuery('.loader').show();
	jQuery.ajax({
	type:"post",
	datatype: "html",
	url : '/contractor/filter_data',
	data :{'filterstr':string,'searchstring':search},
	success:function(resp){
				var count=jQuery(resp).find('#cnt').val();
				var pagination=jQuery(resp).filter('.paginate').html();
				var filtered_content=jQuery(resp).filter('.main-content-data').html();
				jQuery('.jobs-found-count p').text(count);
				jQuery('.jobs-list').html(filtered_content);
				if(pagination !== 'undefined' || pagination !== '')
					jQuery('.contractor-pagination').html(pagination);
				else
					jQuery('.contractor-pagination').html('');
			},
		complete: function(){
        jQuery('.loader').hide();
      }
	}); 
}


/* filter on runtime*/
jQuery(window).load(function() 
{
	/*check if job search page then execute*/
	if(window.location.href.indexOf("find_job") > -1) 
	{
		var search=getUrlVars()['searchItem'];
		var myURL = document.location;
		var myURL = new String(myURL);
		var newuri=myURL.split("?")[1]; 
		
		if(search === undefined)
			search ="";
		/* if query string variable exists then only run ajax*/
		if(newuri !== undefined)
		{
			jQuery('.loader').show();
			jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/filter_data',
			data :{'filterstr':newuri,'searchstring':search},
			success:function(resp){
						var count=jQuery(resp).find('#cnt').val();
						var pagination=jQuery(resp).filter('.paginate').html();
						var filtered_content=jQuery(resp).filter('.main-content-data').html();
						jQuery('.jobs-found-count p').text(count);
						jQuery('.jobs-list').html(filtered_content);
						if(pagination !== 'undefined' || pagination !== '')
							jQuery('.contractor-pagination').html(pagination);
						else
							jQuery('.contractor-pagination').html('');
					},
			complete: function()
				{
					jQuery('.loader').hide();
				}
			}); 
		}
	}
});

/*function to get the values of query string variables*/
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

										/*********************JOB DESCRIPTION MODULE*****************/
										
/*save job and alert*/										
jQuery('#save_job,.flex_alert').click(function(e){
	e.preventDefault();
	var save_action=jQuery(this).attr('name');
	var job_id=jQuery('#job_id').val();
	var contr_id=jQuery('#contractor_id').val();
	jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/save_job',
			data :{'job_id':job_id,'contr_id':contr_id,'action_performed':save_action},
			success:function(resp)
			{
				if(resp >= 1)
				{
					if(save_action == "save_job")
					{
						jQuery('#save_job').removeClass('btn-blue');
						jQuery('#save_job').addClass('btn-gray');
						jQuery('#save_job').attr('disabled','true');
						toastr.success("Job Saved Successfully!!");
					}
					if(save_action == "flex_alert")
					{
						jQuery('.flex_alert').removeClass('btn-blue');
						jQuery('.flex_alert').addClass('btn-gray');
						jQuery('.flex_alert').attr('disabled','true');
						toastr.success("Alert Saved Successfully!!");
					}
				}
			}
			}); 
	
});
jQuery('#apply_for_job').click(function(e){
	e.preventDefault();
	if(jQuery(this).attr('data-already_applied') === 'yes')
	{
		var applied_jobid=jQuery(this).attr('data-applied-job-id');
		window.location.href = base_url+'contractor/view_posted_job/?applied_job='+applied_jobid+'';
	}
	else
	{
		var slug=jQuery('#job_slug').val();
		window.location.href = base_url+'contractor/apply_for_job/'+slug+'';
	}
});									

										
										/*********************JOB DESCRIPTION MODULE ENDS*****************/
	/******APPLY FOR A JOB *****/
/*validation for apply job*/	
jQuery(document).ready(function(){
	var minimum_activity=1;
	if(jQuery('#minimum_activities').length > 0)
	{
		minimum_activity=jQuery('#minimum_activities').val();
	}
	jQuery('#apply_for_job_contractor').validate({
	ignore: "",
	rules: {
			"selected_activity[]": {
				required: true,
				minlength: minimum_activity
			},
			payment_terms:{required: true},
			proposed_amount: {
					required: {
							depends: function(element) {
								if (jQuery('#payment_terms').is(':checked'))
								{
									return true;
								}
								else
								{
									return false;
								}
							}
						}
					},
			acceptTerms :{required:true},
			cover_letter: {required: true},
			message:{required: false}
			
		}
	});
	
	/*method added to validate all questions*/
	if(jQuery('.input.questions').length >0)
	{
		jQuery(".additionQues").find('textarea').each(function(){
		 jQuery(this).rules("add", {
		   required: true,
		 });   
		});
	}
	
	/*toggle propose terms*/
	jQuery('.payment_terms').click(function(){
		var terms=jQuery(this).val();
		if(terms === "new_terms")
			jQuery('.your-proposal').show();
		if(terms === "accepted")
			jQuery('.your-proposal').hide();
	});
});	
 						
/****APPLY FOR A JOB MODULE ENDS ***/

/*****EDIT POSTED JOB *******/

/*update the activities*/
jQuery(document).on('click','#edit_activities',function(){
	jQuery(this).text('Update Applied Events').addClass('update_activities');
	jQuery('.activity').removeAttr('disabled');
});

jQuery(document).on('click','.update_activities',function(){
	var min_ac=jQuery('#minimum_activities').val();
	if (jQuery('input:checkbox:checked').length >= 2) 
	{
		jQuery('.activity').removeClass('error');
		var activities=[];
		jQuery('input:checkbox:checked').each(function(){
			activities.push(this.id);
		});
		var applied_job_id=jQuery('#applied_job_id').val();
		
		jQuery.ajax({
			type: "POST",
			url:"/contractor/update_posted_job",
			data:{"fieldname":'activity_id',"fieldval":activities,"applied_job_id":applied_job_id},
			success:function(resp)
			{
				jQuery('#edit_activities').text('Edit/Change Applied Events').removeClass('update_activities');
				jQuery('.activity').attr('disabled','disabled');
				toastr.success(resp);
			}
		});
	}
	else
	{
		jQuery('.activity').addClass('error');
	}

});

/*update the proposed terms*/
jQuery(document).on('click','#edit_terms',function(){
	jQuery(this).text('Update Terms').addClass('update_terms');
	jQuery('.new-terms').removeAttr('disabled');
});

jQuery(document).on('click','.update_terms',function(){
	var pay_type="";
	var amount="";
	var payment_terms=jQuery('.payment_terms:checked').val();
	if(payment_terms === "new_terms")
	{
		 pay_type=jQuery("input[name='payRate']:checked").val();
		 amount=jQuery('#proposed_amount').val();
	}
	
	var applied_job_id=jQuery('#applied_job_id').val();
	jQuery.ajax({
		type: "POST",
		url:"/contractor/update_posted_job_proposal",
		data:{"payment_term":payment_terms,"paytype":pay_type,"amount":amount,"applied_job_id":applied_job_id},
		success:function(resp)
		{
			jQuery('#edit_terms').text('Edit/Change Terms').removeClass('update_terms');
			jQuery('.new-terms').attr('disabled','disabled');
			toastr.success(resp);
		}
	});
		
});

//update the cover letter
jQuery(document).on('click','#edit_cover_letter',function(){
	jQuery(this).text('Update').addClass('update_cover');
	jQuery('.coverLetter .ff-description').removeClass('filled');
	jQuery('.coverLetter .ff-description p').attr('contentEditable','true');
});

 jQuery(document).on('click','.update_cover',function(){
	var coverletter=jQuery('.coverLetter .ff-description p').text();
	var applied_job_id=jQuery('#applied_job_id').val();
	jQuery.ajax({
		type: "POST",
		url:"/contractor/update_posted_job",
		data:{"fieldname":'cover_letter',"fieldval":coverletter,"applied_job_id":applied_job_id},
		success:function(resp)
		{
			jQuery('#edit_cover_letter').text('Edit').removeClass('update_cover');
			jQuery('.coverLetter .ff-description').addClass('filled');
			jQuery('.coverLetter .ff-description p').removeAttr('contentEditable');
			toastr.success(resp);
		}
	});
		
}); 

//update answer
jQuery(document).on('click','.edit_answer',function(){
	jQuery(this).text('Update').addClass('update_answer');
	jQuery(this).prev('.ff-description').removeClass('filled');
	jQuery(this).prev('.ff-description').find('p').attr('contentEditable','true');
});

 jQuery(document).on('click','.update_answer',function(){
	var answer=jQuery(this).prev('.ff-description').find('p').text();
	var applied_answer_id=jQuery(this).prev('.ff-description').find('.applied-answer-id').val();
	
	jQuery.ajax({
		type: "POST",
		url:"/contractor/update_posted_job",
		data:{"fieldname":'answer',"fieldval":answer,"applied_answer_id":applied_answer_id},
		success:function(resp)
		{
			jQuery('.update_answer').prev('.ff-description').addClass('filled');
			jQuery('.update_answer').prev('.ff-description').find('p').removeAttr('contentEditable');
			jQuery('.update_answer').text('Edit').removeClass('update_answer');
			toastr.success(resp);
		}
	}); 
		
});

//update the message
jQuery(document).on('click','#edit_message',function(){
	jQuery(this).addClass('update_message');
	jQuery('.msgArea .ff-description').removeClass('filled');
	jQuery('.msgArea .ff-description p').attr('contentEditable','true');
});

 jQuery(document).on('click','.update_message',function(){
	var message=jQuery('.msgArea .ff-description p').text();
	var applied_job_id=jQuery('#applied_job_id').val();
	jQuery.ajax({
		type: "POST",
		url:"/contractor/update_posted_job",
		data:{"fieldname":'message',"fieldval":message,"applied_job_id":applied_job_id},
		success:function(resp)
		{
			//jQuery('#edit_message').text('Edit').removeClass('update_message');
			jQuery('#edit_message').hide();
			jQuery('.msgArea .ff-description').addClass('filled');
			jQuery('.msgArea .ff-description p').removeAttr('contentEditable');
			toastr.success("Message Sent Successfully");
		}
	});
		
}); 

//withdraw proposal

jQuery(document).on('click','#withdraw_reason',function(){
var applied_job_id=jQuery('#applied_job_id').val();
var prop_sta=jQuery('#proposal_status').val();
var reason=jQuery('#withdraw-prop').val();
if(reason == "" || reason== null)
{
	jQuery('#withdraw-prop').addClass('error');
}
else
{
 jQuery('#withdraw-prop').removeClass('error');
 jQuery.ajax({
		type: "POST",
		url:base_url+"/contractor/update_posted_job",
		data:{"fieldname":'status',"fieldval":prop_sta,"applied_job_id":applied_job_id,"reason":reason},
		success:function(resp)
		{
			toastr.success("Proposal successfully withdrawn!!");
			
				jQuery('#withdraw_proposal').text('Reapply');
				jQuery('#withdraw_proposal').attr('data-status',0);
				
				jQuery('#withdraw_proposal').removeAttr('data-toggle');
				jQuery('#withdraw_proposal').removeAttr('data-target');
				
				jQuery('#withdraw_proposal').attr('id','reapply');
				jQuery('#withdraw-proposal').modal('hide');
		}
	}); 
}
});

jQuery('#withdraw_proposal').click(function(){
	var appl_job_status= jQuery(this).attr('data-status');
	jQuery('#proposal_status').val(appl_job_status);
}); 

jQuery(document).on('click','#reapply',function(){
	var applied_job_id=jQuery('#applied_job_id').val();
	var appl_job_status= jQuery(this).attr('data-status');
	jQuery.ajax({
		type: "POST",
		url:base_url+"/contractor/update_posted_job",
		data:{"fieldname":'status',"fieldval":appl_job_status,"applied_job_id":applied_job_id},
		success:function(resp)
		{
			toastr.success("You've successfully applied for the job!!");
			jQuery('#reapply').text('Withdraw Proposal');
			jQuery('#reapply').attr('data-status',1);
			
			jQuery('#reapply').attr('data-toggle','modal');
			jQuery('#reapply').attr('data-target','#withdraw-proposal');
			
			jQuery('#reapply').attr('id','withdraw_proposal');
			
		}
	});
});

/***EDIT POSTED JOB ENDS *****/

/** GLOBAL EMPLOYER PROFILE***/

//Profile view more tags toggle
jQuery('.pro-more-toggle').click(function(e) {
	jQuery(this).toggleClass('less');
	jQuery(this).siblings('.pro-more-content').slideToggle();
});


/*navigation for contractor*/
jQuery('#find-job').click(function(){
	jQuery('.sub-navigation').show();
	jQuery('.find-job-sub-nav').show();
	jQuery('.my-jobs-sub-nav').hide();
});
jQuery('#my-jobs').click(function(){
	jQuery('.sub-navigation').show();
	jQuery('.find-job-sub-nav').hide();
	jQuery('.my-jobs-sub-nav').show();
});


/*update last login time evey 5 mins */
setInterval(function() {
    jQuery.ajax({
		type: "POST",
		url:base_url+"/login/update_last_login_time",
	});
}, 50000);

/* contract accept/decline*/
jQuery('#accept_contract').click(function(){
	if(jQuery('#terms').is(":checked"))
	{
		jQuery('#terms').removeClass('error');
		var contract_id=jQuery('#contract_id').val();
		jQuery.ajax({
			type: "POST",
			data:{"contract_id":contract_id,"status":1},
			url:base_url+"/contractor/contract_status",
			success:function(resp)
			{
				if(resp == "success")
				{
					 toastr.success("Congratulation on getting hired !!");
					 setTimeout(function(){window.location.replace(base_url+"contractor/my_jobs/")}, 2000);
				}
			}
		});
	}
	else
	{
		jQuery('#terms').addClass('error');
		return false;
	}
});

//contract Decline
jQuery('#decline_contract').click(function(){
	var contract_id=jQuery('#contract_id').val();
		jQuery.ajax({
			type: "POST",
			data:{"contract_id":contract_id,"status":2},
			url:base_url+"/contractor/contract_status",
			success:function(resp)
			{
				if(resp == "success")
				{
					 toastr.success("Contract Successfully Declined !!");
					 setTimeout(function(){window.location.replace(base_url+"contractor/job_proposals/")}, 2000);
				}
			}
		});
});

/*Company profile*/
//get the states based on the country
jQuery('#company_country').change(function(){
	var country=jQuery(this).val();
	jQuery.ajax({
			type: "POST",
			data:{"sortname":country},
			url:base_url+"/employer/get_states",
			success:function(resp)
			{
				jQuery('#company_state').html(resp);
			}
		});
});

//get the cities
jQuery('#company_state').change(function(){
	var state=jQuery(this).val();
	jQuery.ajax({
			type: "POST",
			data:{"stateid":state},
			url:base_url+"/employer/get_cities",
			success:function(resp)
			{
				jQuery('#company_city').html(resp);
			}
		});
});

//validate
jQuery('#company_profile').validate({
	ignore: "",
	rules: {
		company_name:{required: true},
		company_country:{required: true},
		company_state:{required: true},
		company_city:{required: true},
        company_zip:{required: true},
		company_first_name:{required: true},
		company_last_name:{required: true},
		company_address_1:{required: true},
		company_email:
		{
			required: true,
			email: true		 
		},
		company_mobile_phone:{required: true},
		company_industries:{required: true},
		employer_old_password:{required: true},
		employer_new_password: 
		{
			required: true,
			minlength: 6	
		},
		employer_confirm_password:
		{
			required: true,
			minlength: 6,
			equalTo : "#employer_new_password"
			
		},
		employer_new_question:{required: true},
		employer_new_answer	:{required: true},
		rememberThis:{required: true},
		employer_sms:{required: true},
		
		team_first_name: {
        required: function(element){
            if(jQuery("#team_last_name").val().length > 0 || jQuery("#team_title").val().length > 0 ||  jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		
		team_last_name: {
			 required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_title").val().length > 0 ||  jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		
		team_title:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		team_email:{
				required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			},
			email: true	
		},
		team_mobile:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		
		team_landline:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		tmem_admin_permission:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		
		tmem_hiring_permission:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		tmem_trainig_permission:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		tmem_feedback_permission:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		tmem_message_permission:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		tmem_activities_permission:{
			required: function(element){
            if(jQuery("#team_first_name").val().length > 0 || jQuery("#team_last_name").val().length > 0 ||  jQuery("#team_title").val().length > 0 || jQuery("#team_email").val().length > 0 || jQuery("#team_mobile").val().length > 0 || jQuery("#team_landline").val().length > 0)  
				return true;
			else
				return false;
			}
		},
		
		company_desktop_notifi:{required: true},
		company_mobile_notifi:{required: true},
		company_email_notifi:{required: true}
	}
});
