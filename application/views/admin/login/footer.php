<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-1 col-md-12">        
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
<script src="<?php echo BASE_URL;?>static/js/bootstrap.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/4.0.1/placeholders.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.6/owl.carousel.min.js"></script>
<script src="<?php echo BASE_URL;?>static/js/nicescroll.min.js"></script> 
<script src="<?php echo BASE_URL;?>static/js/jquery.validate.min.js"></script> 
<script>
var common_url = "<?php echo SITEURL; ?>/admin/";
</script>

<script src="<?php echo BASE_URL;?>static/admin/js/admin.js"></script> 
<?php 
if(isset($additional_js))
{
	echo $additional_js;
}

if(isset($_GET['recover'])): 
	echo "<script type='text/javascript'>$(document).ready(function(){ $('#forgot').modal('show');   });</script>";
endif;


?>
<!-- Modal -->
<div class="modal fade" id="forgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
   <div class="forgotPassword" style="padding:40px; position:relative">
		<a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
		<?php 
		if(isset($_GET['recover'])): 
		
			if($valid == 'Yes'):
				echo '
		<form class="login-form clearfix" id="resetPass" method="POST">
		  <label for="newPass">Enter New Password</label>
			<input id="newPass" name="newPass" type="password" class="input">
		  <label for="confirmPass">Enter Confirm Password</label>
			<input id="confirmPass" name="confirmPass" type="password" class="input">
			<input name="token" value="'.$_GET["recover"].'" type="hidden" class="input">
		  <input name="" type="submit" value="Submit" class="btn btn-blue resetMe">
        </form>';	
			
			else:
				echo "<h2>Token Invalid !</h2>";
			endif;
		
		else:
		echo '<h2>Please enter your Registered Email</h2>
			<div class="message"></div>
		<form class="login-form clearfix" id="recoverEmail" method="POST">
		  <label for="emailForgot">Enter email</label>
		  <input id="emailForgot" name="emailForgot" type="email" class="input">
		  <input name="" type="submit" value="Submit" class="btn btn-blue forgotme">
        </form>';
		
		endif;
		?>
		
		
	</div>
 
    </div>
  </div>
</div>

</body>
</html>
