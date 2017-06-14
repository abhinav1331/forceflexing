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
                <li><a href="#">About Us</a></li>
                <li><a href="#">How it Works</a></li>
                <li><a href="#">Press</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Confidentiality and IP</a></li>
              </ul>
            </div>
            <div class="col-md-3">
              <h3>Connect with us</h3>
              <ul>
                <li><a href="#">Technical Support</a></li>
                <li><a href="#">Contact Us</a></li>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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

<script src="<?php  echo BASE_URL;?>static/js/custom.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/custom-abhinav.js"></script>
<script src="<?php  echo BASE_URL;?>static/js/custom-chhavi.js"></script>

</body>
</html>