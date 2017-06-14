<main role="main">
  <section class="login-wrap">
    <div class="container">
      <aside class="login-box">
        <h1>Please enter new password</h1>
		<?php  if(isset($success) && $success != ""){?>
			<div class="alert alert-success"><?php echo $success; ?></div>
		<?php } ?>
		<?php  if(isset($error) && $error != ""){?>
			<div class="alert alert-danger"> <?php echo $error;?></div>
		<?php } ?>
		
         <form class="login-form clearfix" id="change_password" method="post">
			  <label for="new_password">New Password</label>
			  <input id="new_password" name="new_password" type="password" class="input">
			  <label for="confirm_password">Confirm Password</label>
			  <input id="confirm_password" name="confirm_password" type="password" class="input">
			  <input name="" type="submit" value="Submit" class="btn btn-blue">
        </form>
         
         
      </aside>
    </div>
  </section>
</main>