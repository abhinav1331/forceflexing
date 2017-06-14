<header class="header logged-in">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topnav" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar top-bar"></span> <span class="icon-bar middle-bar"></span> <span class="icon-bar bottom-bar"></span> </button>
    </div>
    <div class="logo"> <a href="<?php echo SITEURL; ?>"><img src="<?php echo BASE_URL;?>static/images/logo.png"></a> </div>
    <div class="hdr-user-area">
      <div class="hdr-search-box">
        <form class="search-container">
          <input id="search-box" type="text" class="search-box" name="q" placeholder="Type here..">
          <label for="search-box"><span class="search-icon"></span></label>
          <input type="submit" id="search-submit">
        </form>
      </div>
      <div class="notifications"><a class="notif-area new dropdown" href="javascript:void(0);" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="sr-only">Notification</span></a>
        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="notifications">
          <li class="notif-hdr">Notifications <span class="badge">4</span></li>
          <li class="divider"></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xlink:href="#close-x"></use>
            </svg> </a></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xlink:href="#close-x"></use>
            </svg> </a></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xlink:href="#close-x"></use>
            </svg> </a></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xlink:href="#close-x"></use>
            </svg> </a></li>
          <li class="divider"></li>
          <li class="see-all-notifs"><a href="#">See All Notifications</a></li>
        </ul>
      </div>
      <div class="user-login-area">
        <figure class="user-img">
			<?php 
				if(isset($profile_img) && $profile_img != "")
				{
					?>
					<img src="<?php  echo BASE_URL;?>static/images/contractor/<?php echo $profile_img;?>">
					<?php
				}
				else
				{
					?>
					<img src="<?php  echo BASE_URL;?>static/images/avatar-icon.png">
					<?php
				}	
			?>
			
		</figure>
        <a class="user-name dropdown" href="#" id="account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php if(isset($first_name) && $first_name!="") echo $first_name;?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="account">
          <li>
            <div class="toggle-avialability"> <a href="javascript:void(0);" class="active">Online</a> <a href="javascript:void(0);">Invisible</a> </div>
          </li>
		  
          <li><a href="<?php echo BASE_URL;?>contractor/contractor_profile"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
          <li><a href="#"><i class="fa fa-info-circle" aria-hidden="true"></i> Help</a></li>
          <li><a href="<?php echo BASE_URL;?>contractor/contractor_profile_settings"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
          <li><a href="<?php echo BASE_URL; ?>logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
      </div>
    </div>
    <nav class="topnav logged-in center">
      <div class="collapse navbar-collapse" id="topnav">
        <ul>
          <li><a id="find-job" href="javascript:void(0);">Find Jobs</a></li>
          <li><a id="my-jobs" href="javascript:void(0);">My Jobs</a></li>
          <li><a href="javascript:void(0);">Training</a></li>
          <li><a href="javascript:void(0);">Reports</a></li>
          <li><a href="javascript:void(0);">Messages</a></li>
          <li><a href="<?php echo BASE_URL;?>contractor/contractor_profile">Profile</a></li>
        </ul>
      </div>
    </nav>
  </div>
</header>
<?php 
 $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
 if( strpos($actual_link, 'find_job') != false || strpos($actual_link, 'job_proposals') != false)
	$sub="display:block";
 else
	$sub="display:none";
 
 if(strpos($actual_link, 'my_jobs') != false)
	$submyjob="display:block";
 else
	$submyjob="display:none";

if( strpos($actual_link, 'find_job') != false || strpos($actual_link, 'my_jobs') != false || strpos($actual_link, 'job_proposals') != false)
	$main="display:block";
else
	$main="display:none";
 
?>
<nav class="sub-navigation" style="<?=$main;?>">
  <div class="container">
    <ul class="find-job-sub-nav" style="<?=$sub;?>">
      <li><a href="<?php echo BASE_URL; ?>contractor/find_job/">Find Jobs</a></li>
      <li><a href="#">Saved Jobs</a></li>
      <li><a href="<?php echo BASE_URL; ?>contractor/job_proposals/">Proposals</a></li>
      <li><a href="#">My Stats</a></li>
    </ul>
	<ul class="my-jobs-sub-nav" style="<?=$submyjob;?>">
       <li><a href="#">My Jobs</a></li>
      <li><a href="#">My Job Reports</a></li>
    </ul>
  </div>
</nav>