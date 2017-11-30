<nav class="sub-navigation">
  <div class="container">
    <ul>
      <li><a href="#">My jobs</a></li>
      <li><a href="#">Contracts</a></li>
      <li><a href="#">Post a job</a></li>
    </ul>
  </div>
</nav>
<main role="main">
  <section class="contracts-wrap">
    <div class="container">
      <div class="contracts-main">
        <h3>My Job Reports</h3>
        <div class="contracts-tabs"> 
          <!-- Nav tabs -->
          <ul class="tabs-nav" role="tablist">
            <li role="presentation" class="active"><a href="#active" aria-controls="hired" role="tab" data-toggle="tab">Active Jobs</a></li>
            <li role="presentation"><a href="#closed" aria-controls="past-hired" role="tab" data-toggle="tab">Closed Jobs</a></li>
          </ul>
          <!--- Sorting buttons ---->
          <!-- <div class="sorting-btns clearfix"><a class="btn btn-blue" href="#">Sort 1</a><a class="btn btn-blue" href="#">Sort 2</a><a class="btn btn-blue" href="#">Sort 3</a></div> -->
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="active">
              <div class="job-activities-table"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="datatable-job-report">
                <thead>
                <tr>
                  <th scope="col">Contractor</th>
                  <th scope="col">Job Name</th>
                  <th scope="col">Activity Name</th>
                  <th scope="col">Activity Time</th>
                  <th scope="col">Location</th>
                  <th scope="col">Contact Name</th>
                  <th scope="col">Job Report</th>
                  <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($jobs as $key => $value): ?>
                  <?php 
                 
                    $job_id = $value['id'];
                    $hire_contractor = $Model->get_Data_table(PREFIX.'hire_contractor','job_id',$job_id);
                    if(!empty($hire_contractor)) {
                      foreach ($hire_contractor as $key => $value1) {
                        $contractIDArray = json_decode($value1['activity_id']);
                        $stringd = "";
                        foreach ($contractIDArray as $key => $contractIDArra) {
                          $job_activities = $Model->Get_column('*','id',$contractIDArra,PREFIX.'job_activities');
                            $contractor_id = $value1['contractor_id'];
                            $users = $Model->Get_column('*','id',$contractor_id,PREFIX.'users');
                            $contractor_activity_status = $Model->Get_column_Double('*','contract_id',$value1['id'],'activity_id',$job_activities['id'],PREFIX.'hired_contractor_activity_status');
                            $startDate = date("y-m-d" , strtotime($job_activities['start_datetime']));
                            $startay = date("H:i a" , strtotime($job_activities['start_datetime']));;
                            if($contractor_activity_status[0]['status'] == 0) {
                              $status = "Pending";
                            } elseif($contractor_activity_status[0]['status'] == 1) {
                              $status = "Complete";
                            } else {
                              $status = "Withdrawn";
                            }
                            if($contractor_activity_status[0]['job_report_status'] != 2) {
                          ?>
                          <tr>
                            <td><?php echo $users['first_name'] ?> <?php echo $users['last_name']; ?></td>
                            <td><a href="<?php echo BASE_URL; ?>employer/viewContract/?contract=<?php echo $value1['id']; ?>" ><?php echo $value['job_title']; ?></a></td>
                            <td><?php echo $job_activities['activity_name']; ?></td>
                            <td><span class="ac-date"><?php echo $startDate ?></span> <span class="ac-time"><?php echo $startay; ?></span></td>
                            <td><?php echo $job_activities['city']; ?></td>
                            <td><?php echo $job_activities['first_name'] ?> <?php echo $job_activities['last_name']; ?></td>
                            <td><a href="<?php echo BASE_URL; ?>employer/view_report/?id=<?php echo $contractor_activity_status[0]['id']; ?>">N/A</a></td>
                            <td><?php echo $status; ?></td>
                          </tr>
                        <?php
                           }
                        }
                      }
                    }
                 ?>
                <?php endforeach ?>
                </tbody>
              </table></div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="closed">
            <div class="job-activities-table"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="datatable-job-report">
              <thead>
                <tr>
                  <th scope="col">Contractor</th>
                  <th scope="col">Job Name</th>
                  <th scope="col">Activity Name</th>
                  <th scope="col">Date</th>
                  <th scope="col">Location</th>
                  <th scope="col">Contact Name</th>
                  <th scope="col">Job Report</th>
                  <th scope="col">Feedback</th>
                  <th scope="col">Paid</th>
                </tr>
                </thead>
                <tbody>
               <?php foreach ($jobs as $key => $value): ?>
              <?php 
             
                $job_id = $value['id'];
                $hire_contractor = $Model->get_Data_table(PREFIX.'hire_contractor','job_id',$job_id);
                if(!empty($hire_contractor)) {
                  foreach ($hire_contractor as $key => $value1) {
                    $contractIDArray = json_decode($value1['activity_id']);
                    $stringd = "";
                    foreach ($contractIDArray as $key => $contractIDArra) {
                      $job_activities = $Model->Get_column('*','id',$contractIDArra,PREFIX.'job_activities');
                        $contractor_id = $value1['contractor_id'];
                        $users = $Model->Get_column('*','id',$contractor_id,PREFIX.'users');
                        $contractor_activity_status = $Model->Get_column_Double('*','contract_id',$value1['id'],'activity_id',$job_activities['id'],PREFIX.'hired_contractor_activity_status');
                        $startDate = date("y-m-d" , strtotime($job_activities['start_datetime']));
                        $startay = date("H:i a" , strtotime($job_activities['start_datetime']));;
                        if($contractor_activity_status[0]['status'] == 0) {
                          $status = "Pending";
                        } elseif($contractor_activity_status[0]['status'] == 1) {
                          $status = "Complete";
                        } else {
                          $status = "Withdrawn";
                        }
                        if($contractor_activity_status[0]['job_report_status'] == 2) {
                        ?>
                            <tr>
                               <td><?php echo $users['first_name'] ?> <?php echo $users['last_name']; ?></td>
                              <td><a href="<?php echo BASE_URL; ?>employer/viewContract/?contract=<?php echo $value1['id']; ?>" ><?php echo $value['job_title']; ?></a></td>
                               <td><?php echo $job_activities['activity_name']; ?></td>
                              <td><span class="ac-date"><?php echo $startDate ?></span> <span class="ac-time"><?php echo $startay; ?></span></td>
                              <td><?php echo $job_activities['city']; ?></td>
                              <td><?php echo $job_activities['first_name'] ?> <?php echo $job_activities['last_name']; ?></td>
                              <td><a href="#">View</a></td>
                              <td>Show stars</td>
                              <td>$200</td>
                            </tr>
                          <?php
                          }
                        }
                      }
                    }
                 ?>
                <?php endforeach ?>
                </tbody>
              </table></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>