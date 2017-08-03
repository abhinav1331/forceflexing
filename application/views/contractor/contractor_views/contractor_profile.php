<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <div class="profile-details saved">
            <div class="add-avatar">
				<?php if(isset($profile_img) && $profile_img != "" )
				{
					?>
					<div class="avatar-set" style="background-image:url('<?php echo BASE_URL?>/static/images/contractor/<?php echo $profile_img; ?>');"></div>
					<?php
				}
				else
				{?>
					<div class="avatar-set" style="background-image:url('<?php echo BASE_URL?>/static/images/avatar-icon.png');"></div>
		<?php   } ?>
            </div>
            <div class="add-personal-details">
              <h2 class="pro-title">
				<?php if(isset($first_name) && $first_name != "") echo $first_name; ?>
				<?php if(isset($last_name) && $last_name != "") echo $last_name; ?>
			 </h2>
			 
              <p class="pro-skills">
				<?php 
				if(isset($skills) && $skills != "")
				{
					$skills_array=unserialize($skills);
					$i=1;
					foreach($skills_array as $s)
					{
						if($i>1)
							echo ','. $s;
						else
							echo $s;
						$i++;
					}
				}
				?>
			  </p>
			  
              <p class="pro-location">
				<?php 
					if(isset($location) && $location != "") 
					{
						$locations=unserialize($location);
						$i=1;
						foreach($locations as $loc)
						{
							if($i>1)
								echo ' , '.$loc;
							else
								echo $loc;
							$i++;
						}
					}
					?>
			 </p>
			 <p class="pro-speciality">
						<?php if(isset($speciality) && $speciality != "")
							{ ?>
								<span class="industry-tag"><?php  echo $speciality;?></span>
					<?php  }
						?>
			</p>
			
			<p class="pro-industries">
				<?php
				if(isset($industries) && $industries != "")
				{
					$industries=explode(',',$industries);
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
			</div>
          </div>
          <div class="add-more-details more-details contractor">
            <div class="pro-info-hdr clearfix">
              <h2 class="pull-left">Overview:</h2>
              <!--<a href="#" class="pull-right edit-this">Edit</a>-->
			</div>
			
            <article class="ff-description">
				<p>
					<?php if(isset($description) && $description != "") echo $description; ?>
				</p>
            </article>
			
            <div class="pro-info-hdr clearfix">
              <h2 class="pull-left">Employment History</h2>
              <!--<a href="#" class="pull-right edit-this">Edit</a>-->
			</div>
			
            <article class="employment-history">
			<?php if(isset($employment_history) && $employment_history != "")
				{
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
			
            <div class="pro-info-hdr clearfix">
              <h2 class="pull-left">Education</h2>
             <!-- <a href="#" class="pull-right edit-this">Edit</a> -->
			</div>
			
            <article class="educational-history">
				<?php
					if(isset($education) && $education!="")
					{
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
            <div class="pro-info-hdr clearfix">
              <h2 class="pull-left">Training Requirements</h2>
              <!--<a href="#" class="pull-right edit-this">Edit</a> </div>-->
            <div class="emp-job-information">
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
            </div>
          </div>
        </aside>
        <aside class="page-sidebar">
          <div class="add-professional-details">
            <p><strong>Hourly Wage:</strong> $<?php 
			if(isset($hourly_wages) and !empty($hourly_wages)){
				echo $hourly_wages;
			}
			?></p>
            <p><strong>Availability:</strong> <?php 
			if(isset($availability) and !empty($availability)){
				echo ucfirst($availability);
			}
			?></p>
            <p><strong>Languages:</strong> <?php 
			if(isset($languages) and !empty($languages)){
				echo $languages;
			}
			?></p>
			
			<p><strong>Type:</strong>
			<?php if(isset($contractor_type) && $contractor_type != "") echo ucfirst($contractor_type); ?>
			</p>
				
			
            <p><a href="<?php echo BASE_URL; ?>contractor/contractor_profile/<?php echo $username;?>">View my profile as others see It</a></p>
            <div><a href="<?php echo BASE_URL; ?>/contractor/contractor_profile_settings" class="view-profile-btn">Profile Settings</a></div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>