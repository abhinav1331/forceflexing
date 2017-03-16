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
 
//validate function for sign up employer, contractor
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

//decrement the character limit post a jobs
var i=5000;
jQuery('#post-job-desc').keypress(function(){
	i--;
	jQuery('.char-left').html(i+' characters left');

});


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
jQuery(".newsletter-div input[type='text'], .newsletter-div input[type='email']").focus(function() {
jQuery(".newsletter-div .fake-btn").addClass("focused");
});
jQuery(".newsletter-div input[type='text'], .newsletter-div input[type='email']").blur(function() {
jQuery(".newsletter-div .fake-btn").removeClass("focused");
});

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

jQuery('.selectpicker').selectpicker({
	style 		: null,
	tickIcon 	: 'fa fa-check'
});

//Add to Favorite
jQuery('.favorite-it i').click(function(){
	jQuery(this).toggleClass('fa-heart-o fa-heart');
});



//browse jobs page
jQuery(document).ready(function(e) {
	var winWidth = jQuery(window).width();
if (winWidth > 1400) {
	jQuery('#isMosaic').mosaicflow({ itemSelector: 'article', minItemWidth: 400 });
} else if (winWidth < 1400) {
	jQuery('#isMosaic').mosaicflow({ itemSelector: 'article', minItemWidth: 300 });
};
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
var Slider = document.getElementById('ff-range-slider'),
	marginMin = document.getElementById('ff-slider-value-min'),
	marginMax = document.getElementById('ff-slider-value-max');

noUiSlider.create(Slider, {
	start: [ 0, 200 ],
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


var fixedSlider = document.getElementById('ff-range-slider-fixed'),
	fixedMin = document.getElementById('ff-slider-value-min-fixed'),
	fixedMax = document.getElementById('ff-slider-value-max-fixed');

noUiSlider.create(fixedSlider, {
	start: [ 0, 50000 ],
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
		
