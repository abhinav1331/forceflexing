<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <div class="profile-details saved">
            <div class="add-avatar">
				<?php if(!empty($userdata) && !empty($userdata['profile_img']))
				{
					?>
					<div class="avatar-set" style="background-image:url('<?php echo BASE_URL?>static/images/contractor/<?php echo $userdata['profile_img']; ?>');"></div>
					<?php
				}
				else
				{?>
					<div class="avatar-set" style="background-image:url('<?php echo BASE_URL?>static/images/avatar-icon.png');"></div>
		<?php   } ?>
            </div>
            <div class="add-personal-details">
              <h2 class="pro-title"><?php if(!empty($user)) echo $user['first_name'].' '. $user['last_name'];?> <?php if(!empty($userdata) && !empty($userdata['hourly_wages'])) {?><span class="pro-price-range">$<?php echo $userdata['hourly_wages'];?>/hr</span><?php } ?></h2>
              <p class="pro-skills"><?php if(!empty($userdata) && !empty($userdata['skills'])) echo implode(',',unserialize($userdata['skills']));  ?></p>
              <p class="pro-location">
				<?php 
					if(!empty($user))
					{
						$country=$user['country'];
						$country=($country == "us")?"USA":"Canada";
					}
					else
					{
						$country="";
					}
					if(!empty($userdata) && !empty($userdata['location'])) echo implode(',',unserialize($userdata['location'])).' '.$country;
				?>
			  </p>
			  
			  <?php if(!empty($userdata) && !empty($userdata['speciality'])){ ?>
			  <p class="pro-speciality">
				<span class="industry-tag"><?=$userdata['speciality'];?></span>
			  </p>
			  <?php } ?>
              <p class="pro-industries">
				<?php
				if(!empty($userdata) && !empty($userdata['industries']))
				{
					$industries=explode(',',$userdata['industries']);
					$i=1;
					foreach($industries as $industry)
					{
						?>
						<span class="industry-tag"><?=$industry;?></span> 
						<?php
						if($i== 3)
							break;
						$i++;
					}
				
				?>
			</p>
              <div>
                <div class="pro-more-content" style="display:none;"> 
					<?php 
						for($i=3;$i<count($industries);$i++)
						{
							?>
							<span class="industry-tag"><?=$industries[$i];?></span>
							<?php
						}
					?>
				</div>
                <a id="proMoreToggle" href="javascript:void(0);" class="pro-more-toggle"><span class="sr-only">View More</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a> 
			  </div>
				<?php } ?>
			  <?php if($user['role'] == 2) {?>
              <div class="pro-action-btns">
					<a href="#" class="btn btn-gray">Save</a> 
					<a href="#" class="btn btn-blue">Invite to job</a> 
			  </div>
			  <?php } ?>
            </div>
          </div>
          <div class="more-details">
            <article class="ff-description">
				 <h3>Description</h3>
				  <?php 
					$description=$userdata['description'];
					$desc=explode(PHP_EOL,$description);
					 $length=strlen($description);
				  ?>
				  <p><?php  echo $desc[0];?></p>
				  <?php if($length >= 500){ ?>
				  <div align="right">
					<div class="more-content" style="display:none;">
					 <?php for($i=1;$i<count($desc); $i++){ ?>
						 <p><?=$desc[$i];?></p>
					 <?php } ?>
					</div>
					<a id="moreToggle" href="javascript:void(0);" class="view-more-toggle"><span class="sr-only">View More</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
				 </div>
				  <?php } ?>
            </article>
			
            <div class="work-history-feedback">
              <div class="hdr-work-feedback clearfix">
                <h2>Work history and feedback</h2>
                <select class="input medium inline">
                  <option>Newest first</option>
                  <option>Oldest first</option>
                </select>
              </div>
              <div class="in-progress-jobs">
                <h3 id="inProgressJobs">15 jobs in progress <i class="fa fa-angle-down"></i></h3>
                <div class="jobs-list-inProgress" style="display:none;">
                  <div class="feedbackedJob">
                    <div class="fbJobTitles">
                      <h4>Website from scratch for mobile app</h4>
                      <time>Mar 2016  -  jun 2016</time>
                      <div class="fbRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"/> <span>5.00</span> </div>
                    </div>
                    <div class="fbJobEarningType">
                      <h4>$450.00 earned</h4>
                      <p>Fixed job</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="feedbackedJobListing">
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="availability-dates"> <img src="<?php echo BASE_URL; ?>/static/images/calendar.png" alt="calendar">
              <p class="no-availability">Contractor not available from: Oct 5 to Oct 20</p>
            </div>
            <div class="pro-skill-tests">
              <h2>Test</h2>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Score (out of 5)</th>
                  <th scope="col">Time to Complete</th>
                </tr>
                <tr>
                  <td>English vocabulary test <small>(U.S version)</small></td>
                  <td>5.00 <span class="pro-test-rank">1st Place</span></td>
                  <td>10min <a href="#">Details</a></td>
                </tr>
                <tr>
                  <td>English vocabulary test <small>(U.S version)</small></td>
                  <td>5.00 <span class="pro-test-rank">1st Place</span></td>
                  <td>10min <a href="#">Details</a></td>
                </tr>
                <tr>
                  <td>English vocabulary test <small>(U.S version)</small></td>
                  <td>5.00 <span class="pro-test-rank">1st Place</span></td>
                  <td>10min <a href="#">Details</a></td>
                </tr>
                <tr>
                  <td>English vocabulary test <small>(U.S version)</small></td>
                  <td>5.00 <span class="pro-test-rank">1st Place</span></td>
                  <td>10min <a href="#">Details</a></td>
                </tr>
              </table>
            </div>
            <article class="employment-history">
              <h2>Employment History</h2>
			<?php if(!empty($userdata) && !empty($userdata['employment_history'] ))
							{
								$employment_history=$userdata['employment_history'];
								$all_emp=unserialize($employment_history);
								if(is_array($all_emp))
								{
									foreach($all_emp as $em)
									{
										?>
										<div class="emp-hitory-bar">
											<h3><span class="designation"><?php echo $em[0]; ?></span> | <span class="companyName"><?php echo $em[1]; ?></span></h3>
											<p class="timePeriod">
												<?php 
													 $present=date('F Y');
													 if($present == $em[3] )
														 $pr='Present';
													 else
														 $pr=$em[3];
												?>
												<?php echo $em[2];?> - <?php echo $pr;?>
											</p>
										</div>
										<?php
									}
								}
							}?>
            </article>
			
            <article class="educational-history">
              <h2>Education</h2>
               <?php
					 if(!empty($userdata) && !empty($userdata['education'] ))
					{
						$education=$userdata['education'];
						$education_array=unserialize($education);
						if(is_array($education_array))
						{
							foreach($education_array as $edu)
							{?>
								<div class="edu-history-bar">
									<h3><span class="courseType"><?php echo $edu[0];?></span></h3>
									<p class="timePeriod"><?php echo $edu[1] . "-".$edu[2]; ?></p>
								</div>
					<?php	}
						}
					}
					?>
			 </article>
          </div>
        </aside>
        <aside class="pro-overview">
          <div class="job-success-bar">
            <h3>Job Sucess</h3>
            <div class="progress">
              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 92%;"> <span class="progress-number">92%</span> </div>
            </div>
          </div>
          <div class="work-history">
            <h4>Work history</h4>
            <div>
              <p class="pro-hours">560 hours worked</p>
              <p class="pro-jobs">70 jobs</p>
            </div>
          </div>
          <div class="profile-link">
            <h4>Profile link</h4>
            <div>
              <input type="text" value="<?php echo BASE_URL ;?>contractor/contractor_profile/<?=$user['username'];?>" class="input" readonly>
            </div>
          </div>
          <div class="last-online">
            <h4>Last online</h4>
            <div>
              <p>
				  <?php
					  $ip = $_SERVER['REMOTE_ADDR'];
					  $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
					  $timezone = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $details->country);
					  date_default_timezone_set($timezone[0]); 
					  
					  $last_login_time=$user['last_login_time'];
					  if(strtotime($last_login_time) < strtotime("-10 minutes"))
					  {
						$instance->settimezone();
						echo $instance->time_elapsed_string($last_login_time); 
					  }
					  else
					  {
						echo "Active Now";
					  }
				  ?>
			  </p>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
