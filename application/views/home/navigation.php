<header class="header">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topnav" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar top-bar"></span> <span class="icon-bar middle-bar"></span> <span class="icon-bar bottom-bar"></span> </button>
    </div>
    <div class="logo"><a href="<?php echo SITEURL; ?>"><img src="<?php  echo BASE_URL;?>static/images/logo.png"></a></div>
    <nav class="topnav">
      <div class="collapse navbar-collapse" id="topnav">
        <ul>
          <li><a href="#work">how it works</a></li>
          <li><a href="#">employers</a></li>
          <li><a href="#">contractor</a></li>
		  <li class="login">
		  <?php
		  if(!isset($user_role)) {
		  	$user_role = "";
		  }
			if($user_role == 3)
			{?>
				<a href="<?php BASE_URL?>contractor/contractor_profile"><i class="fa fa-sign-in" aria-hidden="true"></i> My Profile</a> 
			<?php
			}	
			elseif($user_role == 2)
			{
				?>
				<a href="<?php BASE_URL?>employer/company_profile_settings"><i class="fa fa-sign-in" aria-hidden="true"></i> My Profile</a>
				<?php
			}
		   else
		  {
			  ?>
			<a href="<?php BASE_URL?>login"><i class="fa fa-sign-in" aria-hidden="true"></i> log-in</a>
			<?php
		  }
		  ?>
		  </li>
        
        </ul>
      </div>
    </nav>
  </div>
</header>