<?php 
if(isset($querystring))
	$filterarray=$querystring;
else
	$filterarray=array();


?>
<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <section class="find-jobs-wrapper">
          <hgroup class="job-search-hdr clearfix">
            <h2>Job Search</h2>
            <form class="job-search-form clearfix" method="get" action="/contractor/find_job/">
              <div class="search-field">
				<input name="searchItem" type="text" class="input" placeholder="Search jobs" value="<?php echo (array_key_exists("searchItem", $filterarray) ? $filterarray['searchItem'][0] : '');?>">
                <!--<p><a href="#">Advanced Search</a></p>-->
              </div>
              <div class="search-btn">
                <input type="hidden" name="securityKey" value="<?php echo $securityKey; ?>">
               <!-- <input type="hidden" name="userId" value="<?php //echo base64_encode($userdata['user_id']); ?>">-->
                <input name="submit" type="submit" class="submit" placeholder="Search">
              </div>
            </form>
          </hgroup>
          <?php 
         if(isset($results) && !empty($results)) {
			?>
          <div class="jobs-list-wrap clearfix">
            <aside class="job-filters">
			 <div class="job-categories">
				<select class="selectpicker show-tick filter" name="category" data-width="100%">
					  <option value="">Select</option>
					  <option value="Brand ambassador" <?php echo $instance->in_array_r("Brand ambassador", $filterarray) ? 'selected' : '';?>>Brand ambassador</option>
					  <option value="Events Staff" <?php echo $instance->in_array_r("Events Staff", $filterarray) ? 'selected' : '';?>>Events Staff</option>
					  <option value="Retail sales merchandiser" <?php echo $instance->in_array_r("Retail sales merchandiser", $filterarray) ? 'selected' : '';?>>Retail sales merchandiser</option>
					  <option value="Product demonstrator/Promoter" <?php echo $instance->in_array_r("Product demonstrator/Promoter", $filterarray) ? 'selected' : '';?>>Product demonstrator/Promoter</option>
					  <option value="Sales Consultant" <?php echo $instance->in_array_r("Sales Consultant", $filterarray) ? 'selected' : '';?>>Sales Consultant</option>
					  <option value="Field Technician" <?php echo $instance->in_array_r("Field Technician", $filterarray) ? 'selected' : '';?>>Field Technician</option>
					  <option value="Field sales/marketing representative" <?php echo $instance->in_array_r("Field sales/marketing representative", $filterarray) ? 'selected' : '';?>>Field sales/marketing representative</option>
					  <option value="Trainer" <?php echo $instance->in_array_r("Trainer", $filterarray) ? 'selected' : '';?>>Trainer</option>
				</select>
			  </div>
              <div class="job-type clearfix with-checkbox">
                <h3>Job Type</h3>
                <label for="fixed" class="custom-checkbox">
                  <input id="fixed" type="checkbox" <?php echo $instance->in_array_r("fixed", $filterarray) ? 'checked' : '';?> class="filter" name="job_type" value="fixed">
                  <span class="custom-check"></span> Fixed</label>
                <label for="hourly" class="custom-checkbox">
                  <input id="hourly" type="checkbox" <?php echo $instance->in_array_r("hourly", $filterarray) ? 'checked' : '';?> class="filter" name="job_type" value="hourly">
                  <span class="custom-check"></span> Hourly</label>
              </div>
			  
              <div class="pay-rate-fixed clearfix" style="<?php echo $instance->in_array_r("fixed", $filterarray) ? 'display:block;' : 'display:none;';?>">
                <h3>Pay Rate (for fixed)</h3>
				<?php 
					if($instance->in_array_r("fixedRange", $filterarray))
					{
							$value=$filterarray['fixedRange'][0];
							$range=explode(',',$value);
					}
					else
					{
						$range=array("0",10000);
					}
					?>
				<input type="hidden" name="fixedRange" id="fixedRange" class="filter" value="">
                <p>$<span id="ff-slider-value-min-fixed"><?=$range[0];?></span> to $<span id="ff-slider-value-max-fixed"><?=$range[1];?></span></p>
                <div id="ff-range-slider-fixed"></div>
              </div>
			  
              <div class="pay-rate-hourly clearfix" style="<?php echo $instance->in_array_r("hourly", $filterarray) ? 'display:block;' : 'display:none;';?>">
                <h3>Pay Rate (for hourly)</h3>
				<?php 
					if($instance->in_array_r("hourlyRange", $filterarray))
					{
							$value=$filterarray['hourlyRange'][0];
							$rangee=explode(',',$value);
					}
					else
					{
						$rangee=array("0",300);
					}
					?>
				<input type="hidden" name="hourlyRange" id="hourlyRange" class="filter" value="">
                <p>$<span id="ff-slider-value-min"><?=$rangee[0];?></span> to $<span id="ff-slider-value-max"><?=$rangee[1];?></span> Per Hour</p>
                <div id="ff-range-slider"></div>
              </div>
			  
              <div class="experience-level clearfix with-checkbox">
                <h3>Experience Level</h3>
                <label for="entry_level" class="custom-checkbox">
                  <input id="entry_level" type="checkbox" <?php echo $instance->in_array_r("entry_level", $filterarray) ? 'checked' : '';?> class="filter" name="experience_level" value="entry_level">
                  <span class="custom-check"></span> Entry Level</label>
                <label for="intermediate" class="custom-checkbox">
                  <input id="intermediate" type="checkbox" <?php echo $instance->in_array_r("intermediate", $filterarray) ? 'checked' : '';?> class="filter" name="experience_level" value="intermediate">
                  <span class="custom-check"></span> Intermediate</label>
                <label for="expert" class="custom-checkbox" >
                  <input id="expert" type="checkbox" <?php echo $instance->in_array_r("expert", $filterarray) ? 'checked' : '';?> class="filter" name="experience_level" value="expert">
                  <span class="custom-check"></span> Expert</label>
              </div>
			  
              <div class="travel-distance clearfix with-checkbox">
                <h3>Travel Distance</h3>
                <label for="1" class="custom-checkbox">
                  <input id="1" type="checkbox" class="filter" <?php echo $instance->in_array_r("0-15", $filterarray) ? 'checked' : '';?>  name="travel_distance" value="0-15">
                  <span class="custom-check"></span> 15 Miles or less</label>
                <label for="2" class="custom-checkbox">
                  <input id="2" type="checkbox" class="filter" <?php echo $instance->in_array_r("16-30", $filterarray) ? 'checked' : '';?> name="travel_distance" value="16-30">
                  <span class="custom-check"></span> 16 to 30 Miles</label>
                <label for="3" class="custom-checkbox">
                  <input id="3" type="checkbox" class="filter" <?php echo $instance->in_array_r("31-60", $filterarray) ? 'checked' : '';?> name="travel_distance" value="31-60">
                  <span class="custom-check"></span> 31 to 60 Miles</label>
                <label for="4" class="custom-checkbox">
                  <input id="4" type="checkbox" class="filter" <?php echo $instance->in_array_r("61-180", $filterarray) ? 'checked' : '';?> name="travel_distance" value="61-180">
                  <span class="custom-check"></span> 61 Miles to 180</label>
                <label for="5" class="custom-checkbox">
                  <input id="5" type="checkbox"  class="filter"  <?php echo $instance->in_array_r("180-above", $filterarray) ? 'checked' : '';?> name="travel_distance" value="180-above">
                  <span class="custom-check"></span> Over 180 Miles</label>
              </div>
			  
              <div class="project-duration clearfix with-checkbox">
                <h3>Project Duration</h3>
                <label for="11" class="custom-checkbox">
                  <input id="11" type="checkbox" class="filter" <?php echo $instance->in_array_r("hours", $filterarray) ? 'checked' : '';?> name="project_duration" value="hours">
                  <span class="custom-check"></span> Hours</label>
                <label for="22" class="custom-checkbox">
                  <input id="22" type="checkbox" class="filter" <?php echo $instance->in_array_r("days", $filterarray) ? 'checked' : '';?> name="project_duration" value="days">
                  <span class="custom-check"></span> Days</label>
                <label for="33" class="custom-checkbox">
                  <input id="33" type="checkbox" class="filter" <?php echo $instance->in_array_r("weeks", $filterarray) ? 'checked' : '';?> name="project_duration" value="weeks">
                  <span class="custom-check"></span> Weeks</label>
                <label for="44" class="custom-checkbox">
                  <input id="44" type="checkbox" class="filter" <?php echo $instance->in_array_r("months", $filterarray) ? 'checked' : '';?> name="project_duration" value="months">
                  <span class="custom-check"></span> Months</label>
                <label for="55" class="custom-checkbox">
                  <input id="55" type="checkbox" class="filter" <?php echo $instance->in_array_r("above-6-months", $filterarray) ? 'checked' : '';?> name="project_duration" value="above-6-months">
                  <span class="custom-check"></span> Over 6 Months</label>
              </div>
			  
              <div class="training-req clearfix with-checkbox">
                <h3>Training Requirements</h3>
                <label for="111" class="custom-checkbox">
                  <input id="111" type="checkbox" class="filter" <?php echo $instance->in_array_r("1-course", $filterarray) ? 'checked' : '';?> name="training_req" value="1-course">
                  <span class="custom-check"></span> 1 Course</label>
                <label for="222" class="custom-checkbox">
                  <input id="222" type="checkbox" class="filter" <?php echo $instance->in_array_r("2-3", $filterarray) ? 'checked' : '';?> name="training_req" value="2-3">
                  <span class="custom-check"></span> 2 to 3 Courses</label>
                <label for="333" class="custom-checkbox">
                  <input id="333" type="checkbox" class="filter" <?php echo $instance->in_array_r("3-5", $filterarray) ? 'checked' : '';?> name="training_req" value="3-5">
                  <span class="custom-check"></span> 3 to 5 Courses</label>
                <label for="444" class="custom-checkbox" >
                  <input id="444" type="checkbox" class="filter" <?php echo $instance->in_array_r("over-5", $filterarray) ? 'checked' : '';?> name="training_req" value="over-5">
                  <span class="custom-check"></span> Over 5 Courses</label>
              </div>
			  
              <div class="hpw clearfix with-checkbox">
                <h3>Hours Per Week</h3>
				  <label for="part" class="custom-checkbox">
                  <input id="part" type="checkbox" class="filter" <?php echo $instance->in_array_r("part_time", $filterarray) ? 'checked' : '';?> name="weeklyhours" value="part_time">
                  <span class="custom-check"></span> Part Time</label>
                <label for="full" class="custom-checkbox">
                  <input id="full" type="checkbox" class="filter" <?php echo $instance->in_array_r("full_time", $filterarray) ? 'checked' : '';?> name="weeklyhours" value="full_time">
                  <span class="custom-check"></span> Full Time</label>
              </div>
			  
              <div class="client-history clearfix with-checkbox">
                <h3>Client History</h3>
                <label for="aa" class="custom-checkbox">
                  <input id="aa" type="checkbox" class="filter" <?php echo $instance->in_array_r("none", $filterarray) ? 'checked' : '';?> name="client_history" value="none">
                  <span class="custom-check"></span> No Hires</label>
                <label for="bb" class="custom-checkbox">
                  <input id="bb" type="checkbox" class="filter" <?php echo $instance->in_array_r("1-20", $filterarray) ? 'checked' : '';?> name="client_history" value="1-20">
                  <span class="custom-check"></span> 1 to 20 Hires</label>
                <label for="cc" class="custom-checkbox">
                  <input id="cc" type="checkbox" class="filter" <?php echo $instance->in_array_r("20+", $filterarray) ? 'checked' : '';?> name="client_history" value="20+">
                  <span class="custom-check"></span> 20+ Hires</label>
              </div>
			  
              <div class="overnight-travel clearfix with-checkbox">
                <h3>Overnight Travel</h3>
				<label for="aaa" class="custom-checkbox">
                  <input id="aaa" type="checkbox" class="filter" <?php echo $instance->in_array_r("yes", $filterarray) ? 'checked' : '';?> name="overnight_travel" value="yes">
                  <span class="custom-check"></span> Yes</label>
                <label for="sss" class="custom-checkbox">
                  <input id="sss" type="checkbox" class="filter" <?php echo $instance->in_array_r("no", $filterarray) ? 'checked' : '';?>  name="overnight_travel" value="no">
                  <span class="custom-check"></span> No</label>
                <label for="ddd" class="custom-checkbox">
                  <input id="ddd" type="checkbox" class="filter" <?php echo $instance->in_array_r("not_specified", $filterarray) ? 'checked' : '';?> name="overnight_travel" value="not_specified">
                  <span class="custom-check"></span> Not specified</label>
              </div>
			</aside>
			
            <aside class="jobs-list-wrapper">
			
              <div class="jobs-found-hdr clearfix">
                <div class="sort-by">
                  <label>Sort By</label>
                  <select class="selectpicker filter" name="sorting">
                    <option value="desc" <?php echo $instance->in_array_r("desc", $filterarray) ? 'selected' : '';?>>Newest</option>
                    <option value="asc" <?php echo $instance->in_array_r("asc", $filterarray) ? 'selected' : '';?>>Older</option>
                  </select>
                </div>
                <div class="jobs-found-count">
                  <p><?php echo $total_records; ?> Jobs Found</p>
                </div>
              </div>
			 
              
			  <div class="jobs-list clearfix">
			   <div class="loader" style="display:none;"><img src="<?php echo BASE_URL;?>static/images/loading.gif"></div>
				<?php 
				//set timezone based upon ip
				$instance->settimezone();
				foreach($results as $job){ ?>
                <article class="ff-job">
					<summary>
						<h3>
							<a href="<?php echo SITEURL;?>contractor/job_description/<?php echo $job['job_slug'];?>" class="job_title" data-jobid="<?php  echo $job['id'];?>"><?php  echo $job['job_title'];?></a> 
							<?php $industries= $job['job_industry_knowledge'];
								$all_industries=explode(',',$industries);
								foreach($all_industries as $industry)
								{
								?>
									<span class="category-tag"><?php echo $industry; ?></span>
						  <?php } ?>
						
						</h3>
						<p class="job-details"><?php echo ucfirst($job['job_type']); ?> - Budget: $<?php echo number_format($job['job_price']); ?> - Posted <?php echo $instance->time_elapsed_string('@'.$job['job_created'].'');?></p>
						<p>
							<?php 
							$job_desc= $job['job_description']; 
							echo $instance->truncate($job_desc,'300','....');
							?>
						</p>
					</summary>
					<hr class="vr-divider">
					<div class="job-location">
					<span><?php $instance->get_location_of_job($job['id']); ?></span> 
					<a href="javascript:void(0);" class="favorite-it"><i class="fa fa-heart-o" aria-hidden="true"></i></a></div>
                </article>
				<?php } ?>
               
              </div>
			  
            </aside>
          </div>
          <?php 
            }
			else
			{
           ?>
			   <div class="jobs-list-wr	clearfix">
					<?php echo "No jobs found!!" ?>
			   </div>
	 <?php  } ?>
	 
		<?php 
			$record_per_page=$instance->item_per_page;
			$total_pages=ceil($total_records / $record_per_page); 
		?>
		<div class="contractor-pagination">
			<?php echo $instance->paginate_function($record_per_page,1,$total_records,$total_pages); ?>
		</div>
		<input type="hidden" class="filter" id="page_num" name="page" value="">
        </section>		
      </div>
    </div>
  </section>
</main>