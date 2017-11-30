<header class="header logged-in">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topnav" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar top-bar"></span> <span class="icon-bar middle-bar"></span> <span class="icon-bar bottom-bar"></span> </button>
    </div>
    <div class="logo"> <a href="<?php echo SITEURL; ?>"><img src="<?php echo BASE_URL;?>static/images/logo.png"></a> </div>
    <div class="hdr-user-area">
      <div class="hdr-search-box">
        <form class="search-container" action="<?php echo BASE_URL; ?>/contractor/find_job/" method="get">
          <input id="search-box" type="text" class="search-box" name="searchItem" placeholder="Type here..">
          <label for="search-box"><span class="search-icon"></span></label>
          <input type="submit" name="submit" id="search-submit">
        </form>
      </div>
	  <!--Notification Section-->
	  <?php 
		//check oif new notifications are there
		if(!empty($notifications))
			$class="new";
		else
			$class="";
	  ?>
      <div class="notifications"><a class="notif-area <?php echo $class; ?> dropdown" href="javascript:void(0);" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="sr-only">Notification</span></a>
		<ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="notifications">
		
		  <!--new notification count-->
          <li class="notif-hdr">Notifications <?php  if(!empty($notifications)){?> <span class="badge"><?php echo count($notifications);?></span><?php  }?></li>
		  
		  <li class="divider"></li>
		
		<?php 
			if(!empty($notifications))
			{
				foreach($notifications as $noti)
				{
					$link="javascript:void(0)";
					if($noti['noti_type'] == "job_invitation")
					{
						$link=SITEURL."contractor/job_proposals/";
					}
					elseif($noti['noti_type'] == "applied_job_rejected")
					{
						/*job id*/
						 $job_id=$noti['forID'];
						$jobdetails=$instance->Notification->getJobDetails($job_id);
						$link=SITEURL."contractor/job_description/".$jobdetails['job_slug'].""; 
					}
					elseif($noti['noti_type'] == "contract_created")
					{
						$contract_id=$noti['forID'];
						$link=SITEURL."contractor/view_contract/?contract_id=".$contract_id."";
					}
					elseif($noti['noti_type'] == "dispute_by_employer")
					{
						$hired_contractor_activity_status_id=$noti['forID'];
						$link=SITEURL."contractor/submit_report/?id=".$hired_contractor_activity_status_id."&action=dispute";
					}
					elseif($noti['noti_type'] == "payment_completed")
					{
						$link=SITEURL."contractor/my_jobs/";
					}
					elseif($noti['noti_type'] == "contract_completed")
					{
						$link=SITEURL."contractor/my_jobs/";
					}
						
					?>
					<li>
						<a href="<?php echo $link; ?>"><?php echo $noti['noti_message']; ?></a>
						<a class="remove-notif" id="<?php echo $noti['id']; ?>" href="javascript:void(0);">
							<span class="sr-only">Remove Notification</span> 
							<svg viewBox="0 0 192 192">
								<use xlink:href="#close-x"></use>
							</svg> 
						</a>
					 </li>
					<?php 
				}
			}
			else
			{
			?>
				<li class="notif-hdr">No New Notifications</li>
			<?php
			}
		?>
		  
          <li class="divider"></li>
          <li class="see-all-notifs"><a href="<?php echo SITEURL."contractor/notifications/"; ?>">See All Notifications</a></li>
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
        <a class="user-name dropdown" href="<?php echo BASE_URL;?>contractor/contractor_profile" id="account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php if(isset($first_name) && $first_name!="") echo $first_name;?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="account">
          <li>
            <div class="toggle-avialability">
				
				<a href="javascript:void(0);" class='<?php echo ($user_visibility == "available")?"active":"";?>'>Online</a> 
				<a href="javascript:void(0);" class='<?php echo ($user_visibility == "offline")?"active":"";?>'>Invisible</a> 
			</div>
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
          <li><a href="<?php echo BASE_URL;?>contractor/reports/">Reports</a></li>
          <li><a id="msgcnt" href="<?php echo BASE_URL;?>inbox/">Messages</a>
		  <?php if(!empty($unread_msg_count)) {?>
			<span class="msgCount"><?php echo $unread_msg_count;?></span>
		  <?php } ?>
		  </li>
          <!--<li><a href="<?php //echo BASE_URL;?>contractor/contractor_profile">Profile</a></li>-->
        </ul>
      </div>
    </nav>
  </div>
</header>
<?php 
 $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
 if( strpos($actual_link, 'find_job') != false || strpos($actual_link, 'job_proposals') != false || strpos($actual_link, 'mystats') != false || strpos($actual_link, 'Jobs_save') != false )
	$sub="display:block";
 else
	$sub="display:none";
 
 if(strpos($actual_link, 'my_jobs') != false || strpos($actual_link,'my_job_reports') != false)
	$submyjob="display:block";
 else
	$submyjob="display:none";

if( strpos($actual_link, 'find_job') != false || strpos($actual_link, 'my_jobs') != false || strpos($actual_link, 'job_proposals') != false || strpos($actual_link, 'mystats') != false || strpos($actual_link,'my_job_reports') != false || strpos($actual_link, 'Jobs_save') != false)
	$main="display:block";
else
	$main="display:none";
 
?>
<nav class="sub-navigation" style="<?php echo $main;?>">
  <div class="container">
    <ul class="find-job-sub-nav" style="<?php echo $sub;?>">
      <li><a href="<?php echo BASE_URL; ?>contractor/find_job/">Find Jobs</a></li>
      <li><a href="<?php echo BASE_URL; ?>contractor/Jobs_save/">Saved Jobs</a></li>
      <li><a href="<?php echo BASE_URL; ?>contractor/job_proposals/">Proposals</a></li>
      <li><a href="<?php echo BASE_URL; ?>contractor/mystats/">My Stats</a></li>
    </ul>
	<ul class="my-jobs-sub-nav" style="<?php echo $submyjob;?>">
       <li><a href="<?php echo BASE_URL; ?>contractor/my_jobs/">My Jobs</a></li>
      <li><a href="<?php echo BASE_URL; ?>contractor/my_job_reports/">My Job Reports</a></li>
    </ul>
  </div>
</nav>