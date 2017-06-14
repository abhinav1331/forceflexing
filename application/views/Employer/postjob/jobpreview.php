<?php 
/*echo "<pre>";
print_r($params);
echo "</pre>";*/
 ?>
<main role="main">
  <section class="page-wrap job-description">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2><?php echo $params['jp_title']; ?></h2>
          <div class="more-details">
            <article class="ff-description">
              <h3>Description</h3>
              <p><?php echo $params['jp_desc']; ?></p>
            </article>
            <div class="emp-job-information">
              <div class="job-info-hdr clearfix">
                <h2 class="pull-left">Preferred Qualifications</h2>
                <!-- <a class="pull-right btn btn-blue btn-small">Edit</a> --></div>
              <div class="job-info-body">
                <div class="info-tabular-data small">
                  <ul>
                    <li>
                      <p><strong>Language:</strong></p>
                      <p><?php echo $params['jp_language']; ?></p>
                    </li>
                    <li>
                      <p><strong>Employee type:</strong></p>
                      <p><?php echo $params['jp_preferences']; ?></p>
                    </li>
                    <li>
                      <p><strong>Hours billed:</strong></p>
                      <?php 
                      if ($params['jp_payRate_hourly_val'] != "") {
                       ?><p><?php echo $params['jp_payRate_hourly_val']; ?></p><?php
                      } else {
                       ?><p><?php echo $params['jp_payRate_fixed_val']; ?></p><?php
                      }
                      
                       ?>
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
                      $i=0;
                      foreach($params['jp_activity_name'] as $jobs_Activities) {
                        $startTime = $params['jp_act_start_time'][$i];
                        $dateTime = $params['jp_act_start_date'][$i];
                        $date = new DateTime($params['jp_act_start_date'][$i]);
                        $now = new DateTime();
                        $date1 = $startTime." ".$dateTime; 
                        $ifin = $i+1;
                        ?>
                        <li class="<?php if($date < $now) { ?> past-activity <?php; } ?>">
                          <p><?php echo $jobs_Activities; ?></p>
                          <p><?php echo date('h:i a Y-m-d', strtotime($date1)); ?></p>
                          <p><?php echo $params['jp_start_stop_time'.$ifin.''][0]; ?> Time</p>
                          <p><?php echo $params['jp_act_city'][$i]; ?></p>
                        </li>
                        <?php
                        $i++;
                      }
                     ?><!-- 
                      <li class="past-activity">
                        <p>Minneapolis Show</p>
                        <p>12/20/16 6:00 PM</p>
                        <p>Fixed Time</p>
                        <p>Minneapolis</p>
                      </li> -->
                  </ul>
                </div>
              </div>
            </div>
            <div class="emp-job-information payment-info">
              <div class="job-info-hdr clearfix">
                <h2 class="pull-left">Payment Information</h2>
                <!-- <a class="pull-right btn btn-blue btn-small">Edit</a> --> </div>
              <div class="job-info-body">
                <div class="info-tabular-data">
                  <ul>
                    <li>
                      <p><strong>Fixed or Hourly:</strong></p>
                      <p><?php echo $params['jp_payRate']; ?></p>
                    </li>
                    <li>
                      <p><strong>Allowable Expenses:</strong></p>
                      <p><?php echo implode("," , $params['jp_other_expenses']); ?></p>
                    </li>
                    <li>
                      <p><strong>Allowable Overages:</strong></p>
                      <p>$<?php echo $params['allowwable_overages']; ?></p>
                    </li>
                    <li>
                      <p><strong>Notation if the price has been flexed higher:</strong></p>
                      <p><?php if($params['jp_flexRate'] == 'on') { echo "Yes"; } else { echo "No"; } ?></p>
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
              <!-- <div class="activity-on-job">
                <div class="row">
                  <div class="col-sm-5">
                    <h3>Activity on this Job</h3>
                  </div>
                  <div class="col-sm-7">
                    <ul>
                      <li> <strong>Proposals</strong> 50 </li>
                      <li> <strong>Interviewing</strong> 0 </li>
                      <li> <strong>Hired</strong> 0 </li>
                    </ul>
                  </div>
                </div>
              </div> -->
            </div>
            <!-- <div class="emp-work-history">
            <h2>Client Work History and Feedback</h2>
            <div class="workDetails">
                  <div class="workJobTitles">
                    <h4>Website Design</h4>
                    <p>Another quality job client love their site!</p>
                    
                    <div class="workJobRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"></div>
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
                    
                    <div class="workJobRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"></div>
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
            <div class="smJob">
            <h4><a href="#">Website from scratch for mobile app</a></h4>
            <p>I am looking for Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
            </div>
            <div class="smJob">
            <h4><a href="#">Website from scratch for mobile app</a></h4>
            <p>I am looking for Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
            </div>
            <div class="smJob">
            <h4><a href="#">Website from scratch for mobile app</a></h4>
            <p>I am looking for Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
            </div>
            </div>
                      </div> -->
        </aside>
        <aside class="emp-client-overview">
          <div class="emp-client-name"><?php echo $userDate['first_name']." ".$userDate['last_name']; ?></div>
          <div class="emp-client-job-history">
            <p><strong>About the Client </strong><br>
              <strong>Payment Certified</strong><br>
              <strong>Rating:(5.00) 2 reviews </strong></p>
            <p><strong>United States</strong><br>
              Centerville/ 10-19Am</p>
            <p><strong>7 Jobs Posted </strong><br>
              58% Hire Rate, 1 Open Job</p>
            <p><strong>$6.55/hr Avg Hourly Rate Paid </strong><br>
              255 Hours</p>
            <p>Member Since Jul 24, 2015</p>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>