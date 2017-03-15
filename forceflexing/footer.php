
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-1 col-md-12">
        <div class="footer-top">
          <div class="row">
            <div class="col-md-3">
              <h3>Company Info</h3>
              <ul>
                <li><a href="#">Press</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">ForceFlexing Blog</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Trust & Safety</a></li>
                <li><a href="#">Online Work Report</a></li>
              </ul>
            </div>
            <div class="col-md-3">
              <h3>About</h3>
              <ul>
                <li><a href="#">About us</a></li>
                <li><a href="#">How it Works</a></li>
                <li><a href="#">Team</a></li>
              </ul>
            </div>
            <div class="col-md-3">
              <h3>Browse</h3>
              <ul>
                <li><a href="#">Freelancers by Skill</a></li>
                <li><a href="#">Freelancers in USA</a></li>
                <li><a href="#">Freelancers in UK</a></li>
                <li><a href="#">Freelancers in Canada</a></li>
                <li><a href="#">Freelancers in Australia</a></li>
                <li><a href="#">Find Jobs</a></li>
              </ul>
            </div>
            <div class="col-md-3">
              <h3>Connect with us</h3>
              <ul>
                <li><a href="#">Contact & Support</a></li>
                <li><a href="#">Live chat</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="copyright-pk">
          <p>© 2016 ForceFlexing .com</p>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/4.0.1/placeholders.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.6/owl.carousel.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.BlackAndWhite/0.3.6/jquery.BlackAndWhite.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.3.8/SmoothScroll.min.js"></script>  
<script>
    wow = new WOW(
    {
    boxClass:     'wow',     
    animateClass: 'animated', 
    offset:       100,     
    mobile:       true,      
    live:         true       
    }
    )
    wow.init();

	//Fixed header on scroll
	$(document).ready(function() {
	var stickyNavTop = $('body').offset().top;
	var stickyNav = function(){
	var scrollTop = $(window).scrollTop();
	if (scrollTop > 0) {
	$('.header').addClass('stick');
	} else {
	$('.header').removeClass('stick');
	}
	};
	stickyNav();
	$(window).scroll(function() {
	stickyNav();
	});
	});
	

	$('.owl-carousel').owlCarousel({
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
	
	$('#banner-slider').carousel({
	interval: 5000
	})
	
	$('.bwWrapper').BlackAndWhite({
        hoverEffect : true,
        onImageReady:function(img) {
        }
    });
	
	//custom jquery
	$(".newsletter-div input[type='text'], .newsletter-div input[type='email']").focus(function() {
	$(".newsletter-div .fake-btn").addClass("focused");
	});
	$(".newsletter-div input[type='text'], .newsletter-div input[type='email']").blur(function() {
	$(".newsletter-div .fake-btn").removeClass("focused");
	});
	
	$(".newsletter-div input[type='submit']").hover(function(e) {
	$(".newsletter-div .fake-btn").toggleClass("hovered");
	});
	
	jQuery(window).on('scroll', function() {
		var st = jQuery(this).scrollTop();
		jQuery('.banner-image').css({ 'opacity' : (1 - st/850) });
	});
	//Stop dropdown from closing on click inside
	$('ul.dropdown-menu').on('click', function(event){event.stopPropagation();});
</script>
</body>
</html>