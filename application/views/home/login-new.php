<?php 
$_SESSION['linkedin'] = "Login"
?>
<main role="main">
  <section class="login-wrap">
  <div class="container">
  <aside class="login-box">
  <h1>Login to your Account</h1>
    <div class="signin-btns"><a href="<?php echo $url; ?>" class="linked-in-btn"><i class="fa fa-linkedin" aria-hidden="true"></i><span>Sign in with LinkedIn</span></a></div> 
   <?php 
   if(isset($error)) {
    ?>
    <div class="alert alert-danger">
      <strong>Error</strong> <?php echo $error; ?>
    </div>
    <?php
   }
    ?>

  <form class="login-form clearfix" action="" method="post" id="login-user">
  <label for="username">Username</label>
  <input id="username" name="username" type="text" class="input">
  <label for="password">Password</label>
  <input id="password" name="password" type="password" class="input">
  <input name="submit" type="submit" value="Submit" class="btn btn-blue">
  <p class="form-options">
  <label for="rememberMe" class="custom-checkbox pull-left">
                  <input id="rememberMe" type="checkbox" name="remember" value="true">
                  <span class="custom-check"></span>
                  Remember me</label>
                  
                 <a href="javascript:void(0);" data-toggle="modal" data-target="#forgot" class="pull-right">Forgot password?</a>
  </p>
  </form> 
  <h3>Don’t have an account, sign-up here!</h3>
 <div class="signup-redirect-btns"> <a href="<?php echo BASE_URL; ?>registration/employer" class="btn btn-blue">I want to Hire</a>
  <a href="<?php echo BASE_URL; ?>registration/contractor" class="btn btn-blue">I want to Work</a></div>
  </aside>
  </div>
  </section>
</main>
<!-- Forgot password Modal -->
<div class="modal fade" id="forgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
   <div class="forgotPassword" style="padding:40px; position:relative">
		<a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
		<h2>Please enter your Registered Email</h2>
			<div class="message"></div>
		<form class="login-form clearfix" id="forget_password_email">
		  <label for="forgotemail">Enter email</label>
		  <input id="forgotemail" name="forgotemail" type="text" class="input">
		  <input name="" type="submit" value="Submit" class="btn btn-blue">
        </form>
	</div>
 
    </div>
  </div>
</div>
