
<header class="header logged-in">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topnav" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar top-bar"></span> <span class="icon-bar middle-bar"></span> <span class="icon-bar bottom-bar"></span> </button>
    </div>
    <div class="logo"><a href="<?php echo SITEURL; ?>"><img src="<?php echo BASE_URL;?>static/images/logo.png"></a></div>
    <div class="hdr-user-area">
      <div class="hdr-search-box">
        <form class="search-container">
          <input id="search-box" type="text" class="search-box" name="q" placeholder="Type here..">
          <label for="search-box"><span class="search-icon"></span></label>
          <input type="submit" id="search-submit">
        </form>
      </div>
      <div class="notifications"><a class="notif-area new dropdown" href="javascript:void(0);" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sr-only">Notification</span></a>
        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="notifications">
          <li class="notif-hdr">Notifications <span class="badge">4</span></li>
          <li class="divider"></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close-x"></use>
            </svg> </a></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close-x"></use>
            </svg> </a></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close-x"></use>
            </svg> </a></li>
          <li><a href="#">Consectetur adipiscing elit, sed do eiusmod tempor</a><a class="remove-notif" href="#!"><span class="sr-only">Remove Notification</span> <svg viewBox="0 0 192 192">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close-x"></use>
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
              <img src="<?php  echo BASE_URL;?>static/images/employer/<?php echo $profile_img;?>">
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
        <a class="user-name dropdown" href="#" id="account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $dataFull['first_name']; ?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="account">
          <li>
            <div class="toggle-avialability"> 
              <a href="javascript:void(0);" class='<?php echo ($visibility_status == "available")?"active":"";?>'>Online</a> 
              <a href="javascript:void(0);" class='<?php echo ($visibility_status == "offline")?"active":"";?>'>Invisible</a>
           </div>
          </li>
          <li><a href="<?php echo BASE_URL; ?>employer/company_profile/<?php echo $nameEmp; ?>"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
          <li><a href="#"><i class="fa fa-info-circle" aria-hidden="true"></i> Help</a></li>
          <li><a href="<?php echo BASE_URL; ?>employer/company_profile_settings/"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
          <li><a href="<?php echo BASE_URL; ?>logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
      </div>
    </div>
    <nav class="topnav logged-in center">
      <div class="collapse navbar-collapse" id="topnav">
        <ul>
          <li><a href="#">Jobs</a>
            <ul>
              <li><a href="<?php echo BASE_URL; ?>employer/openJob">My Jobs</a>
                <ul>
                  <li><a href="#">Active Jobs</a></li>
                  <li><a href="#">Pending-training</a></li>
                  <li><a href="#">Pending-activities</a></li>
                  <li><a href="#">Pending-payment</a></li>
                  <li><a href="#">completed</a></li>
                </ul>
              </li>
              <li><a href="<?php echo BASE_URL; ?>/postjob">Post a Job</a></li>
            </ul>
          </li>
          <li class="active"><a href="#">Contractors</a>
            <ul>
              <li><a href="#">My Contractor</a></li>
              <li><a href="<?php echo BASE_URL; ?>employer/searchContractor">Search Contractors</a></li>
              <li><a href="<?php echo BASE_URL; ?>employer/job_report/">Job Reports</a></li>
            </ul>
          </li>
          <li><a href="#">Training</a></li>
          <li><a href="<?php echo BASE_URL ?>/employer/reports/">Reports</a></li>
          <li><a id="msgcnt" href="<?php echo BASE_URL; ?>inbox">Messages</a>
    		  <?php if(!empty($unread_msg_count)) {?>
           <span class="msgCount"><?php echo $unread_msg_count;?></span>
          <?php } ?>
		 </li>
          <!-- <li><a href="<?php echo BASE_URL; ?>employer/company_profile/<?php echo $nameEmp; ?>">Profile</a></li> -->
        </ul>
      </div>
    </nav>
  </div>
</header>