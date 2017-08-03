<main role="main">
  <section class="page-wrap job-description contractor">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2><?php if(isset($job_data['job_title']) && $job_data['job_title']!="") 
			  echo $job_data['job_title'];?></h2>
          <div class="more-details">
            <article class="ff-description">
              <h3>Description</h3>
              <p><?php if(isset($job_data['job_description']) && $job_data['job_description']!="") 
				  echo $job_data['job_description'];?></p>
            </article>
            <div class="emp-job-information">
              <div class="job-info-hdr clearfix">
                <h2 class="pull-left">Preferred Qualifications</h2>
                </div>
              <div class="job-info-body">
                <div class="info-tabular-data small">
                  <ul>
                    <li>
                      <p><strong>Language:</strong></p>
                      <p><?php  
					  if(isset($job_data['job_language']) && $job_data['job_language']!="") 
					  {
						$languages=$job_data['job_language'];
						$lang=rtrim($languages);
						echo rtrim($lang, ',');
					  }
					  ?></p>
                    </li>
                    <li>
                      <p><strong>Employee type:</strong></p>
                      <p><?php
					  if(isset($job_data['job_employee_type']) && $job_data['job_employee_type']!="") 
					  {
						echo $job_data['job_employee_type'];
					  }
					  ?></p>
                    </li>
                    <li>
                      <p><strong>Hours billed:</strong></p>
                      <p>200 hours</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="emp-job-information activities">
              <div class="job-info-hdr clearfix">
                <h2 class="pull-left">Events/activities and location</h2>
              </div>
              <div class="job-info-body">
                <div class="info-tabular-data">
                  <ul>
				  <li>
                      <p><strong>Activity Name</strong></p>
                      <p><strong>Activity Time</strong></p>
                      <p><strong>Flexibility</strong></p>
                      <p><strong>Location</strong></p>
                    </li>
					
				  <?php
						 if(isset($job_activities) && !empty($job_activities)) 
						 {
						foreach($job_activities as $activity){  ?>
							<li>
							  <p><?php echo $activity['activity_name']; ?></p>
							  <p><?php echo date("m/d/y", strtotime($activity['start_datetime'])); ?></p>
							  <p><?php echo $activity['activity_type']; ?></p>
							  <p><?php echo $activity['city']; ?></p>
							</li>
					<?php } 
						 }?>
                    
                  </ul>
                </div>
              </div>
            </div>
            <div class="emp-job-information payment-info">
              <div class="job-info-hdr clearfix">
                <h2 class="pull-left">Payment Information</h2>
                 </div>
              <div class="job-info-body">
                <div class="info-tabular-data">
                  <ul>
                    <li>
                      <p><strong>Fixed or Hourly:</strong></p>
                      <p><?php 
					    if(isset($job_data['job_type']) && $job_data['job_type']!="") 
							echo $job_data['job_type'];  
					  ?></p>
                    </li>
                    <li>
                      <p><strong>Allowable Expenses:</strong></p>
                      <p>	<?php 
								if(isset($job_data['jp_other_expenses']) && $job_data['jp_other_expenses']!="") 
								{
									$expenses=json_decode($job_data['jp_other_expenses']);
									if( is_array($expenses) && !empty($expenses) )
									{
										echo implode(',',$expenses);
									}
									else
										{
											echo $expenses;
										}
								}
							?>
					  </p>
                    </li>
                    <li>
                      <p><strong>Allowable Overages:</strong></p>
                      <p>$<?php if(isset($job_data['jp_other_expenses']) && $job_data['jp_other_expenses']!="") 
								{
									echo $job_data['job_additional_hours'];
								}
								?></p>
                    </li>
                    <li>
						<?php 
							if(isset($flexed_data) && !empty($flexed_data)) 
							{
							?>
								<p><strong>Notation if the price has been flexed higher:</strong></p>
								<p>Employer has increased the compensation on <?php echo date('m/d/Y',strtotime($flexed_data[0]['flex_date'])); ?>.</p>
					  <?php }
						?>
                     
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="emp-job-information activities">
              <div class="job-info-hdr clearfix">
                <h2 class="pull-left">Training Requirements</h2>
              </div>
              <div class="job-info-body">
                <div class="info-tabular-data training-info">
                  <ul>
                    <li>
                      <p><strong>Course Names</strong></p>
                      <p><strong>Description</strong></p>
                      <p><strong>Due Date</strong></p>
                      <p><strong>Needed Score</strong></p>
                    </li>
                    <li>
                      <p>Selling BBQs</p>
                      <p>Course 1708 on basic selling</p>
                      <p>1/00/00</p>
                      <p>75%</p>
                    </li>
                    <li>
                      <p>Selling BBQs</p>
                      <p>Course 1708 on basic selling</p>
                      <p>1/00/00</p>
                      <p>80%</p>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="activity-on-job">
                <div class="row">
                  <div class="col-sm-5">
                    <h3>Activity on this Job</h3>
                  </div>
                  <div class="col-sm-7">
                    <ul>
                      <li> <strong>Proposals</strong> <?php echo $applied_jobs?> </li>
                      <li> <strong>Interviewing</strong> 0 </li>
                      <li> <strong>Hired</strong> 0 </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="emp-work-history">
            <h2>Client Work History and Feedback</h2>
            <div class="workDetails">
                  <div class="workJobTitles">
                    <h4>Website Design</h4>
                    <p>Another quality job client love their site!</p>
                    
                    <div class="workJobRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"></div>
                  </div>
                  <div class="workJobEarningType">
                  <time>Jul 2015 - Feb 2016 </time>
                    <h4>241 hrs @ $6.67/hr</h4>
                    <p>Billed: $1,755.26</p>
                  </div>
            </div>
            <div class="workDetails">
                  <div class="workJobTitles">
                    <h4>Website Design</h4>
                    <p>Another quality job client love their site!</p>
                    
                    <div class="workJobRatings"> <img src="<?php echo BASE_URL; ?>/static/images/5-star-rating.png"></div>
                  </div>
                  <div class="workJobEarningType">
                  <time>Jul 2015 - Feb 2016 </time>
                    <h4>241 hrs @ $6.67/hr</h4>
                    <p>Billed: $1,755.26</p>
                  </div>
            </div>
            </div>
            <div class="similar-jobs">
            <h2>Similar Jobs</h2>
			<?php
			if(isset($similar_job) && !empty($similar_job))
			{
				foreach($similar_job as $jobs)
				{
					if($job_data['id'] != $jobs['id'])
					{
			?>
				<div class="smJob">
					<h4><a href="<?php echo BASE_URL;?>contractor/job_description/<?php echo $jobs['job_slug'];?>"><?php echo $jobs['job_title'];?></a></h4>
					<p><?php echo $instance->truncate($jobs['job_description'],'300','....');?></p>
				</div>
			<?php }
				}
			}
			?>
            
            </div>
          </div>
        </aside>
        <aside class="emp-client-overview">
          <div class="emp-client-name"><?php echo $company_name;?></div>
          <div class="emp-client-job-history">
            <p><strong>About the Client </strong><br>
              <strong>Payment Certified</strong><br>
              <strong>Rating:(5.00) 2 reviews </strong></p>
            <p><strong><?php  if(isset($emp_country)) echo $emp_country;?></strong><br>
              <?php if(isset($emp_city)) echo $emp_city;?>/ 10-19Am</p>
            <p><strong><?php if(isset($posted_jobs)) 
								{ 
									$multi=($posted_jobs>1) ?'s':'';
									echo $posted_jobs .'Job'.$multi.' Posted';
								} ?></strong><br>
              58% Hire Rate <?php if(isset($open_jobs_count)) { echo ','. $open_jobs_count?>  Open Job<?php if($open_jobs_count>1) echo 's'; } ?></p>
            <p><strong>$6.55/hr Avg Hourly Rate Paid </strong><br>
              255 Hours</p>
            <p>Member Since <?php if(isset($member_since)) echo date('M d, Y',strtotime($member_since)); ?></p>
          </div>
          <div class="contract-apply-actions">
          <form>
          <!--<textarea name="" cols="" rows="" class="input" placeholder="Type message"></textarea>-->
          <div class="action-btns clearfix">
			   <p class="col-xs-8">
					<?php 
					if($already_applied == "yes")
					{
						if($applied_job_status == 1)
							$button_text="Reapply for Job";
						else
							$button_text="Edit Job";
						?>
						<input type="submit" data-applied-job-id="<?php echo $applied_job_id;?>" id="apply_for_job" data-already_applied="yes" value="<?php echo $button_text;?>" class="btn btn-blue">
						<?php
					}
					else
					{
					?>
					<input type="submit" id="apply_for_job" data-already_applied="no" value="Apply for Job" class="btn btn-blue">
				<?php } ?>
			   </p>
			   <input type="hidden" name="job_slug" id="job_slug" value="<?php echo $job_data['job_slug'];?>">
			   <input type="hidden" name="job_id" id="job_id" value="<?php echo $job_data['id'];?>">
			   <input type="hidden" name="contractor_id" id="contractor_id" value="<?php echo $instance->userid; ?>">
			   <?php 
			   if(isset($job_saved) && $job_saved == 'yes') 
			   {
				   $class="btn-gray";
				   $disable="disabled";
			   }
			   else
			    {
				   $class="btn-blue";
				   $disable="";
				}
				
			 if(isset($alert) && $alert == 'yes') 
			   {
				   $class_al="btn-gray";
				   $disable_al="disabled";
			   }
			   else
			    {
				   $class_al="btn-blue";
				   $disable_al="";
				}	
			   ?>
			   <p class="col-xs-4"><input type="submit" name="save_job" <?php echo $disable;?> id="save_job" value="Save" class="btn <?php echo $class;?> btn-sm"></p>
			   <p class="col-xs-12"><button name="flex_alert"  <?php echo $disable_al; ?>  class="btn <?php echo $class_al; ?> btn-block flex_alert">Alert when Price Flexed</button></p>
			  <!--<p class="col-xs-4"><input type="submit" name="save_job" value="Save" class="btn btn-gray btn-sm"></p>
			  <p class="col-xs-12"><button class="btn btn-gray btn-block">Alert when Pay Flexed</button></p>-->
		  </div>
		  
          </form>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
