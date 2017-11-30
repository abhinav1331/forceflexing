<?php 
echo "<pre>";
  print_r($jobs);
echo "</pre>";
 ?>

<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body viewContractWrap">
          <h2>Mechanical industrie</h2>
          <div class="contractHeader contractDiv">
            <h3><a href="javascript:void(0)"><?php echo $job['job_title']; ?></a></h3>
            <article class="contract-div">
              <div class="contractor-img">
                <figure><a href="javascript:void(0)"><img src="<?php echo BASE_URL; ?>/static/images/contracts-user-img.jpg"></a></figure>
              </div>
              <div class="name-place">
                <h4>William kev</h4>
                <p>Canada</p>
              </div>
              <div class="actions">
                <div class="dropdown">
                  <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                  <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                    <li><a href="#">Message</a></li>
                    <li><a href="#">View Training Detail</a></li>
                    <li><a href="#">View Activity Detail</a></li>
                    <li><a href="#">View Job Report Detail</a></li>
                  </ul>
                </div>
              </div>
            </article>
          </div>
          <div class="contractDiv activities">
            <h3>Activities</h3>
            <?php if(!empty($CompleteJobs)) { ?>
            <div class="activityArea">
              <div class="activityStatus">Completed</div>
              <div class="activityDetails">
                <div class="table-responsive">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <th scope="col">Activity Name</th>
                      <th scope="col">Activity Time</th>
                      <th scope="col">Location</th>
                      <th scope="col">Contact Name</th>
                      <th scope="col" align="center">Job Report</th>
                      <th scope="col">Amount Due</th>
                      <?php if($jobType == "fixed") { ?>
                      <th scope="col">Amount Paid</th>
                      <?php } ?>
                    </tr>
                    <?php foreach($CompleteJobs as $CompleteJob) { ?>
                     <?php 
                      $datTimeArre1y = explode(" " , $CompleteJob['start_datetime']);
                      $dateold1 = $datTimeArre1y[0];
                      $timeold1 = $datTimeArre1y[1];

                      $dateFin1 = date("d/m/y" , strtotime($dateold1));
                      $timeFin1 = date("h:i A", strtotime($timeold1));
                     ?>
                    <tr>
                       <td><?php echo $CompleteJob['activity_name']; ?></td>
                      <td><span class="actDate"><?php echo $dateFin1; ?></span> <span class="actTime"><?php echo $timeFin1; ?></span></td>
                      <td><?php echo $CompleteJob['city']; ?></td>
                      <td><?php echo $CompleteJob['first_name'] ?> <?php echo $CompleteJob['last_name']; ?></td>
                      <td><a href="javascripti:void(0)">Pending</a></td>
                      <?php if($jobType == "fixed") { ?>
                      <td>$<?php echo $CompleteJob['price']; ?>.00</td>
                      <?php } ?>
                      <td>$00.00</td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
                <!-- <div class="activityAction"> <a href="#" class="btn btn-blue">Create</a> </div> -->
              </div>
            </div>
            <?php } ?>
            <?php if(!empty($pendingJobs)) { ?>
            <div class="activityArea">
              <div class="activityStatus">Pending</div>
              <div class="activityDetails">
                <div class="table-responsive">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <th scope="col">Activity Name</th>
                      <th scope="col">Activity Time</th>
                      <th scope="col">Location</th>
                      <th scope="col">Contact Name</th>
                      <th scope="col">Job Report</th>
                      <?php if($jobType == "fixed") { ?>
                      <th scope="col">Budgeted</th>
                      <?php } ?>
                    </tr>
                    <?php foreach($pendingJobs as $pendingJob) { ?>
                    <?php 
                      $datTimeArrey = explode(" " , $pendingJob['start_datetime']);
                      $dateold = $datTimeArrey[0];
                      $timeold = $datTimeArrey[1];

                      $dateFin = date("d/m/y" , strtotime($dateold));
                      $timeFin = date("h:i A", strtotime($timeold));
                     ?>
                    <tr>
                      <td><?php echo $pendingJob['activity_name']; ?></td>
                      <td><span class="actDate"><?php echo $dateFin; ?></span> <span class="actTime"><?php echo $timeFin; ?></span></td>
                      <td><?php echo $pendingJob['city']; ?></td>
                      <td><?php echo $pendingJob['first_name'] ?> <?php echo $pendingJob['last_name']; ?></td>
                      <td><a href="javascript:void(0)" onclick="editPendingJobs(<?php echo $pendingJob['id']; ?>)">View</a></td>
                      <?php if($jobType == "fixed") { ?>
                      <td>$<?php echo $pendingJob['price']; ?>.00</td>
                      <?php } ?>
                    </tr>
                    <?php } ?>
                  </table>
                  <a href="#" class="btn btn-blue">Withdraw</a></div>
                <div class="activityAction"> <a href="#" class="btn btn-blue">Manage Activities</a> </div>
              </div>
            </div>
            <?php } ?>
            <div class="emp-job-activity-details one"></div>
          </div>
          <div class="contractDiv coursesEducation">
            <h3>Courses and Education</h3>
            <div class="activityArea">
              <div class="activityStatus">Completed</div>
              <div class="activityDetails">
                <div class="table-responsive">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <th scope="col">Course Names</th>
                      <th scope="col">Description</th>
                      <th scope="col">Status</th>
                      <th scope="col">Date Completed</th>
                      <th scope="col">Score</th>
                    </tr>
                    <tr>
                      <td>Selling BBQs</td>
                      <td><a href="#">Course 1708 on basic selling</a></td>
                      <td>Passed</td>
                      <td>11/00/00</td>
                      <td>98%</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="activityArea">
              <div class="activityStatus">Pending</div>
              <div class="activityDetails">
                <div class="table-responsive">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <th scope="col">Course Names</th>
                      <th scope="col">Description</th>
                      <th scope="col">Status</th>
                      <th scope="col">Date</th>
                      <th scope="col">Score</th>
                    </tr>
                    <tr>
                      <td>Selling BBQs</td>
                      <td><a href="#">Course 1708 on basic selling</a></td>
                      <td>Failed</td>
                      <td>11/00/00</td>
                      <td>98%</td>
                    </tr>
                  </table>
                </div>
                <div class="activityAction"> <a href="#" class="btn btn-blue">Re-Take</a> <a href="#" class="btn btn-blue">Complete</a> </div>
              </div>
            </div>
          </div>
          <div class="contractDiv history">
            <h3>History</h3>
            <div class="activityArea">
              <div class="activityDetails">
                <div class="table-responsive">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">Description <span class="paidDate">Paid to date <strong>$0.00</strong></span></th>
                    </tr>
                    <tr>
                      <td>Jun 21</td>
                      <td>You activated milestone <a href="#">Explainer video script</a> for $50.00 for Erin Taylor</td>
                    </tr>
                    <tr>
                      <td>Jun 21</td>
                      <td>Erin Taylor accepted Your offer for a $50.00 fixed-price project</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="contractDiv history">
            <h3>Terms and Setting</h3>
            <div class="activityArea">
              <div class="activityDetails">
                <div class="termsSettingsArea">
                  <ul>
                    <li>
                      <p>Original job opening</p>
                      <p>Lorem ipsume</p>
                    </li>
                    <li>
                      <p>Offer information</p>
                      <p>Copy from original job post would go in this area.  At the end there would be a more button to display all of the copy. <a href="#">More</a></p>
                    </li>
                  </ul>
                  <ul>
                    <li>
                      <p>Company contact</p>
                      <p>Barry Resnik</p>
                    </li>
                    <li>
                      <p>Contract ID</p>
                      <p>16569267 </p>
                    </li>
                  </ul>
                  <ul>
                    <li>
                      <p>Rate of pay</p>
                      <p>$12.00  or fixed amount</p>
                    </li>
                    <li>
                      <p>Allowable Expenses</p>
                      <p>List of all expenses allowed from job post</p>
                    </li>
                    <li>
                      <p>Additional Hours allowed</p>
                      <p>No or yes, if yes list how much additional time</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>