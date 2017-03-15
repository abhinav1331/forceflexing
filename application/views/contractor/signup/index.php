<?php
  unset($_SESSION['linkedin']);
  $_SESSION['linkedin'] = "contractor";
?>
<main role="main">
  <section class="create-account-wrap">
    <div class="container">
      <aside class="ca-box">
        <h1>Create a Free Contractors Account</h1>
        <p>Looking for work? <a href="#">Sign up as a Contractors</a></p>
       <h4>You can also sign up with <a href="<?php echo $url; ?>">Linkedin</a></h4>
        <?php
		//display error if any
			if(isset($errors))
			{
				if(is_array($errors))
				{ 
					echo "<pre>";
					print_r($errors);
					echo "</pre>";
				}
				else
				{
					?>
					<div class="alert alert-danger alert-dismissable"><?php echo $errors;?></div>
					<?php 
				}
			}
			if(isset($success))
			{
			?>
				<div class="alert alert-success alert-dismissable"><?php echo $success;?></div>
			<?php			
			}
		?>

		<div class="signup-form">
          <h2>contractors Sign Up</h2>
          <form method="POST" id="contrator-sign-up" action="<?php  echo BASE_URL;?>registration/contractor">
            <div class="row">
              <div class="col-sm-6">
                <input name="first_name" value="<?php if(isset($_POST['first_name'])): echo $_POST['first_name']; endif; ?>" type="text" placeholder="First Name">
              </div>
              <div class="col-sm-6">
                <input name="last_name" value="<?php if(isset($_POST['last_name'])): echo $_POST['last_name']; endif; ?>" type="text" placeholder="Last Name">
              </div>
			  <div class="col-sm-6">
                <input name="user_name" value="<?php if(isset($_POST['user_name'])): echo $_POST['user_name']; endif; ?>" type="text" placeholder="User Name">
              </div>
              <div class="col-sm-6">
                <input name="company_name" value="<?php if(isset($_POST['cmpy_name'])): echo $_POST['cmpy_name']; endif; ?>" type="text" placeholder="Company Name">
              </div>
              <div class="col-sm-6">
                <select name="country">
                  <option>India</option>
                  <option>Australia</option>
                  <option>Canada</option>
                </select>
              </div>
              <div class="col-sm-6">
                <input name="email" value="<?php if(isset($_POST['email'])): echo $_POST['email']; endif; ?>" type="text" placeholder="Email">
              </div>
              <div class="col-sm-6">
                <input name="password" id="password" value="<?php if(isset($_POST['pass'])): echo $_POST['pass']; endif; ?>" type="text" placeholder="Password">
              </div>
			  <div class="col-sm-6">
                <input name="con_pass" value="<?php if(isset($_POST['con_pass'])): echo $_POST['con_pass']; endif; ?>" type="text" placeholder="Confirm Password">
              </div>
			   <input type="hidden" name="user_role" value="contractor">
              <!--<div class="col-sm-12">
                 <textarea name="" cols="" rows="" placeholder="Type the letters below"></textarea>
              </div>-->
              <div class="col-sm-12">
                <label for="emails-newsletter" class="custom-checkbox">
                  <input id="emails-newsletter" type="checkbox" checked>
                  <span class="custom-check"></span>
                  Yes! Send me genuinely useful emails every now and then to help me get the most out of ForceFlexing.</label>
                <label for="terms" class="custom-checkbox">
                  <input id="terms" name="terms" type="checkbox">
                  <span class="custom-check"></span>
                  Yes, I understand and agree to the <a href="#">ForceFlexing</a> <a href="#">Terms of Service</a>, including the <a href="#">User Agreement</a> and <a href="#">Privacy Policy</a>.</label>
                <input name="registration" type="submit" value="Get Started">
              </div>
            </div>
          </form>
        </div>
      </aside>
    </div>
  </section>
</main>
