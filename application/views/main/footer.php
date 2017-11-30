<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-1 col-md-12">
        <div class="footer-top">
          <div class="row">
            <div class="col-md-5">
              <h3>About Us</h3>
              <p>ForceFlexing is an on-demand,  job matching service that provides a more flexible approach to staffing sales and marketing personnel. Our unique solutions allow established companies to augment their commercial staffing or start-ups to “flex” with an on-call team.</p>
              <div> <a href="#" class="btn btn-blue">Hire</a> <a href="#" class="btn btn-blue">Work</a> </div>
            </div>
            <div class="col-md-3 col-md-offset-1">
              <h3>Company Info</h3>
              <ul>
			    <li><a href="/about-us">About Us</a></li>
                <li><a href="/how-it-works">How it Works</a></li>
                <li><a href="/careers">Careers</a></li>
                <li><a href="/terms-conditions">Terms of Service</a></li>                
                <li><a href="/partner">Partners</a></li>
              </ul>
            </div>
            <div class="col-md-3">
              <h3>Connect with us</h3>
              <ul>
                <li><a href="/technical-support">Technical Support</a></li>
                <li><a href="/contact-us">Contact Us</a></li>
                <li><a href="#">Newsletter</a></li>
              </ul>
              <div class="follow-div">
                <h3>Follow us</h3>
                <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a> </div>
            </div>
          </div>
        </div>
        <div class="copyright-pk">
          <p>© 2017 ForceFlexing.com</p>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="<?php  echo BASE_URL;?>static/js/jquery.min.js"></script>
<!--https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"-->
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php  echo BASE_URL;?>static/js/bootstrap.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/4.0.1/placeholders.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.6/owl.carousel.min.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/jquery.validate.min.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/form.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<script src="<?php  echo BASE_URL;?>static/js/jquery.timepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/toastr.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/9.2.0/nouislider.min.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/nicescroll.min.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/bootstrap-toggle.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/aes.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/on-off-switch.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/on-off-switch-onload.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>


<?php  if(isset($additional)){	echo $additional;	} ?>
<!--<script src="<?php  //echo BASE_URL;?>static/js/location.js"></script>-->
<?php 
if(isset($job_activities)){
  $i = 1;
  foreach($job_activities as $job_activi) {
  ?>
  <script>
  // getReadySession('activityReunion<?php echo $i; ?>','<?php echo $job_activi["state"]; ?>','<?php echo $job_activi["city"]; ?>');
  </script>
  <?php
  $i++;
  }
}
 ?>
<?php 

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

//load the calender script
if (strpos($url,'contractor_profile/') !== false) 
{
	?>
	<script src="<?php  echo BASE_URL;?>static/js/jquery.datepicker.js"></script>
	<?php
}
//load emojis script
if (strpos($url,'inbox') !== false) 
{
	?>
	<script src="<?php  echo BASE_URL;?>static/emojis/js/jquery.emojipicker.js"></script>
	<script src="<?php  echo BASE_URL;?>static/emojis/js/jquery.emojis.js"></script>
	<?php
}
if (strpos($url,'view_report') !== false || strpos($url,'submit_report') !== false) 
{
	?>
	<script src="<?php  echo BASE_URL;?>static/js/jquery-ui-timepicker-addon.min.js"></script>
	<?php
}
?>
<script src="<?php  echo BASE_URL;?>static/js/custom.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/custom-abhinav.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/custom-chhavi.js"></script>

<svg style="display:none !important;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">
<symbol id="close-x" viewBox="160 160 192 192">
  <polygon fill="#010101" points="340.2,160 255.8,244.3 171.8,160.4 160,172.2 244,256 160,339.9 171.8,351.6 255.8,267.8 340.2,352
	352,340.3 267.6,256 352,171.8 "/>
</symbol>
</svg>
</body>
</html>