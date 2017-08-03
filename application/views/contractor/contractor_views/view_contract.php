<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body viewContractWrap">
          <h2><?php if(isset($employer['job_title']) && !empty($employer['job_title'])) echo $employer['job_title'] ?></h2>
          <div class="contractHeader contractDiv">
            <h3><a href="#"><?php if(isset($employer['job_title']) && !empty($employer['job_title'])) echo $employer['job_title'] ?></a></h3>
            <article class="contract-div">
              <div class="contractor-img">
                <figure><a href="#"><img src="<?php if(isset($employer['employer_image']) && !empty($employer['employer_image'])) echo $employer['employer_image']; ?>"></a></figure>
              </div>
              <div class="name-place">
                <h4><?php  if(isset($employer['emp_name']) && !empty($employer['emp_name'])) echo $employer['emp_name'];?></h4>
                <p><?php  if(isset($employer['country']) && !empty($employer['country'])) echo $employer['country'];?></p>
              </div>
              
			  <!--<div class="actions">
                <div class="dropdown">
                  <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                  <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                    <li><a href="#">Message</a></li>
                    <li><a href="#">View Training Detail</a></li>
                    <li><a href="#">View Activity Detail</a></li>
                    <li><a href="#">View Job Report Detail</a></li>
                  </ul>
                </div>
              </div>-->
			  
            </article>
          </div>
          <div class="contractDiv activities">
            <h3>Activities</h3>
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
                      <th scope="col">Amount Paid</th>
                    </tr>
                    <?php
					if(isset($completed_acti) && !empty($completed_acti))
					{
						foreach($completed_acti as $completed)
						{
							?>
							<tr>
							  <td><?php echo $completed['activity_name']; ?></td>
							  <td>
								  <span class="actDate"><?php echo date('m/d/Y',strtotime($completed['start_time']));  ?></span> 
								  <span class="actTime"><?php echo date('h:i A',strtotime($completed['start_time']));   ?></span>
							  </td>
							  <td><?php echo $completed['city'];?></td>
							  <td><?php echo $completed['contact_name'];?></td>
							  <td><a href="#"><?php echo $completed['job_report_status'];?></a></td>
							  <td>$<?php echo $completed['amount_due'];?></td>
							  <td>$<?php echo $completed['amount_paid'];?></td>
							</tr>
						<?php
						}
					}
					?>
                    
                  </table>
                </div>
               <!-- <div class="activityAction"> <a href="#" class="btn btn-blue">Create</a> </div>-->
              </div>
            </div>
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
                      <th scope="col">Budgeted</th>
                    </tr>
                     <?php
					if(isset($pending_acti) && !empty($pending_acti))
					{
						foreach($pending_acti as $pending)
						{
							?>
							<tr>
							  <td><?php echo $pending['activity_name']; ?></td>
							  <td>
								  <span class="actDate"><?php echo date('m/d/Y',strtotime($pending['start_time']));  ?></span> 
								  <span class="actTime"><?php echo date('h:i A',strtotime($pending['start_time']));   ?></span>
							  </td>
							  <td><?php echo $pending['city'];?></td>
							  <td><?php echo $pending['contact_name'];?></td>
							  <td><a href="#"><?php echo $pending['job_report_status'];?></a></td>
							  <td>$<?php echo $pending['amount_due'];?></td>
							</tr>
						<?php
						}
					}
					?>
                  </table>
                  <!--<a href="#" class="btn btn-blue">Withdraw</a>--></div>
                <!--<div class="activityAction"> <a href="#" class="btn btn-blue">View</a> </div>-->
              </div>
            </div>
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
               <!-- <div class="activityAction"> <a href="#" class="btn btn-blue">Re-Take</a> <a href="#" class="btn btn-blue">Complete</a> </div>-->
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
					<?php if(isset($all_history) && !empty($all_history))
					{
						foreach($all_history as $history)
						{
							?>
							<tr>
							  <td><?php echo date('d M',strtotime($history['date'])); ?></td>
							  <td><?php echo $history['description']; ?></td>
							</tr>
							<?php
						}
					}
						?>
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
					<div class="job-info-body">
					<div class="proposal-terms">
						 <div class="company-proposal">
							<p><strong>Fixed or hourly:</strong> <?php echo ucfirst($job_data['job_type']); ?></p>
							<?php
							if(isset($rate_of_pay) && !empty($rate_of_pay))
								$rate= $rate_of_pay; 
							else
								$rate="";
							?>
							<p><strong>Rate of Pay: </strong>$<?php if(!empty($rate)) echo number_format($rate,2); echo ' '.($job_data['job_type'] == 'hourly' ?'hour':'per Activity')?>  </p>
						  
						   <!--allowable overages-->
						    <p>
								<strong>Allowable Work Hour Overages</strong>
								<?php 
								if(!empty($job_overages))
								{
									 ?>
									<strong>Before Time:</strong> Yes (<?php echo $job_overages['before_time'];?>)
									<strong>After Time:</strong> Yes (<?php echo $job_overages['after_time'];?>)
						  <?php }  
								else 
								{
									?>
									<strong>Before Time:</strong> No 
									<strong>After Time:</strong> No 
									<?php
								}
							?>
							</p>
							
							<!-- get additional job expenses-->
							<p><strong>Allowable Expenses:</strong> </p>
							<div class="reimb-rates">
								<?php if(!empty($job_expenses))
								{
								?>
								<table border="0" cellpadding="0" cellspacing="0">
								  <?php foreach($job_expenses as $expense) {
									  if($expense['name'] == "mileage")
										  $price='Reimbursed at standard US mileage rates';
									  else
										  $price=$expense['price'];
									 ?>
									<tr>
									  <td><?php echo ucfirst($expense['name']) ;?>:</td>
									  <td>$<?php echo $price; ?></td>
									</tr>
								  <?php } ?>
								</table>
						<?php   } else
								{
									echo "None";
								}
							?>
							</div>
						</div>
					</div>
				  </div>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
