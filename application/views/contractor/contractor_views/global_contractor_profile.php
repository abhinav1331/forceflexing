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
				<span class="industry-tag"><?php echo $userdata['speciality'];?></span>
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
						<span class="industry-tag"><?php echo $industry;?></span> 
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
							<span class="industry-tag"><?php echo $industries[$i];?></span>
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
						 <p><?php echo $desc[$i];?></p>
					 <?php } ?>
					</div>
					<a id="moreToggle" href="javascript:void(0);" class="view-more-toggle"><span class="sr-only">View More</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
				 </div>
				  <?php } ?>
            </article>
			
            <div class="work-history-feedback">
              <div class="hdr-work-feedback clearfix">
                <h2>Work history and feedback</h2>
                <!--<select class="input medium inline" id="job_history">
                  <option value="desc">Newest first</option>
                  <option value="asc">Oldest first</option>
                </select>-->
              </div>
              <div class="in-progress-jobs">
				<?php if(!empty($inprogress)){ ?>
                <h3 id="inProgressJobs"><?php echo $inprogress;?> jobs in progress</h3>
				<?php } ?>
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
			  <?php if(!empty($working_history)) 
			  {
				foreach($working_history as $his)
				{?>
					<div class="feedbackedJob">
						<div class="fbJobTitles">
							<h4><?php echo $his['job_title'];  ?></h4>
							<time><?php echo date('M Y',strtotime($his['start_date'])); ?>  -  <?php echo date('M Y',strtotime($his['end_date'])); ?></time>
							<div class="fbRatings"> 
								<?php for($i=0;$i<round($his['rating']);$i++){?>
									<i class="fa fa-star" aria-hidden="true"></i>
								<?php } ?>
								<span><?php echo round($his['rating']); ?></span> 
							</div>
						</div>
						<div class="fbJobEarningType">
							<?php if (array_key_exists("total_earning",$his)){ ?>
								<h4>$<?php echo number_format($his['total_earning'],2); ?> earned</h4>
							<?php } ?>
							<p><?php echo ucfirst($his['job_type']); ?></p>
						</div>
					</div>
	<?php		}
			  }?>
                
              </div>
            </div>
            <div class="availability-dates"> 
				<div class="available_dates"></div>
				<?php 
				$inactive=json_decode($inactive_dates);
				if(!empty($inactive)){
				?>
				<input type='hidden' id='inactive_dates' value='<?php echo $inactive_dates;?>'
				<?php } ?>
				<!--<img src="<?php //echo BASE_URL; ?>/static/images/calendar.png" alt="calendar"-->
				<?php if(!empty($inactive) ){
					?>
					<p class="no-availability">Contractor not available on the dates marked in red.</p>
				<?php } ?>
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
											<p><?php if(count($em) > 4)echo $em[4];?></p>
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
									<p class="timePeriod"><?php echo $edu[1].' '.$edu[2] . "-".$edu[3]; ?></p>
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
            <h3>Job Success</h3>
            <div class="progress">
              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 92%;"> <span class="progress-number">92%</span> </div>
            </div>
          </div>
          <div class="work-history">
			<?php 
			$availability= $userdata['availability'];
			/* if($total_hours > 0 || $completed_jbs > 0)
			{?>
				<h4>Availability</h4>
			<?php }
			else
			{
				?>
				<h4>No Work history</h4>
				<?php
			}
			?>
            <div>
			<?php
			if($total_hours > 0){ ?>
              <p class="pro-hours"><?php echo $total_hours; ?> hours worked</p>
			<?php } ?>
             
			  <?php if($completed_jbs > 0){ ?>
				<p class="pro-jobs"><?php echo $completed_jbs; ?> jobs</p>
			  <?php } ?>
            </div> */
			
			if(!empty($availability))
			{?>
				<h4>Availability</h4>
				<div> <p class="pro-hours"><?php echo ucfirst(str_replace('-',' ',$availability)); ?></p></div> 
			<?php
			}
			?>
          </div>
          <div class="profile-link">
            <h4>Profile link</h4>
            <div>
              <input type="text" value="<?php echo BASE_URL ;?>contractor/contractor_profile/<?php echo $user['username'];?>" class="input" readonly>
            </div>
          </div>
          <div class="last-online">
            <h4>Last online</h4>
            <div>
              <p>
				  <?php
					 /* $ip = $_SERVER['REMOTE_ADDR'];
					  $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
					  $timezone = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $details->country);
					  date_default_timezone_set($timezone[0]); */
					  
					  $timezone=$instance->TimeZone->get_time_zone();
					  date_default_timezone_set($timezone);
					  
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
