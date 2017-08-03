 <main role="main">
        <section class="page-wrap">
            <div class="container">
                <div class="page-main">
					
                    <aside class="main-page-body activity-status-comp">
						<?php if(isset($error) && !empty($error))
						{
							echo $error;
						}
						else
						{
						?>
							<input type="hidden" id="contract_id" value="<?php echo $contract_id;?>">
							<h2><?php 
									if(isset($job['job_title']) && !empty($job['job_title']))
										echo $job['job_title'];
									?>
								<small> Id: <?php if(isset($job['job_id']) && !empty($job['job_id']))
										echo $job['job_id'];?></small>
							</h2>
							<div class="user-top-cover">
								<ul>
									<li>
										<?php
											if(isset($job['emp_image']))
												$img=$job['emp_image'];
											else
												$img="";
										?>
										<div class="avatar-set" style="background-image:url('<?php echo $img;?>');"></div>
									</li>
									<li>
										<h4>
											<?php if(isset($job['emp_name']) && !empty($job['emp_name']))
											echo $job['emp_name']; ?>
											<small>
												<?php if(isset($job['emp_country']) && !empty($job['emp_country']))
												echo $job['emp_country'];?>
											</small>
										</h4>
									</li>
								</ul>
							</div>
							<div class="activity-associated-cover">
								<h3>Activities Associated with Job</h3>
								<?php
									if(isset($completed_activites) && !empty($completed_activites))
									{
										?>
										<h4>Completed</h4>
										<div class="job-activities-table">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<th scope="col">Activity Name</th>
														<th scope="col">Activity Time</th>
														<th scope="col">Location</th>
														<th scope="col">Contact Name</th>
														<th scope="col">Job Report </th>
														<th scope="col">Amount Due</th>
														<th scope="col">Amount Paid</th>
														<th scope="col"></th>
													</tr>
													<?php foreach($completed_activites as $completed) { ?>
													<tr>
														<td><?php if(!empty($completed['activity_name'])) echo $completed['activity_name']; ?></td>
														<td>
														<?php if(isset($completed['start_time'])) echo date('m/d/y',strtotime($completed['start_time']));?>
															<br><?php if(isset($completed['start_time'])) echo date('h:m A',strtotime($completed['start_time']));?>
														</td>
														<td>
															<?php if(isset($completed['city'])) echo $completed['city'];?>
														</td>
														<td><?php if(isset($completed['contact_name'])) echo $completed['contact_name'];?></td>
														<td><?php if(isset($completed['job_report_status'])) echo $completed['job_report_status'];  ?></td>
														<td>$<?php if(isset($completed['amount_due'])) echo $completed['amount_due']; ?></td>
														<td>$<?php if(isset($completed['amount_paid'])) echo $completed['amount_paid']; ?></td>
														<td><a href="javascript:void(0);" class="view_activity" data-id="<?php echo $completed['id'] ?>">View Activity</a></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<?php
									}
									/*Get the pending activities*/
									if(isset($pending_activities) && !empty($pending_activities))
									{
										?>
										<h4>Pending</h4>
										<div class="job-activities-table">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<th scope="col">Activity Name</th>
														<th scope="col">Activity Time</th>
														<th scope="col">Location</th>
														<th scope="col">Contact Name</th>
														<th scope="col">Job Report </th>
														<th scope="col">Amount Due</th>
														<th scope="col">Amount Paid</th>
														<th scope="col"></th>
													</tr>
													<?php foreach($pending_activities as $pending) { ?>
													<tr>
														<td><?php if(!empty($pending['activity_name'])) echo $pending['activity_name']; ?></td>
														<td>
														<?php if(isset($pending['start_time'])) echo date('m/d/y',strtotime($pending['start_time']));?>
															<br><?php if(isset($pending['start_time'])) echo date('h:m A',strtotime($pending['start_time']));?>
														</td>
														<td>
															<?php if(isset($pending['city'])) echo $pending['city'];?>
														</td>
														<td><?php if(isset($pending['contact_name'])) echo $pending['contact_name'];?></td>
														<td><?php if(isset($pending['job_report_status'])) echo $pending['job_report_status'];  ?></td>
														<td>$<?php if(isset($pending['amount_due'])) echo $pending['amount_due']; ?></td>
														<td>$<?php if(isset($pending['amount_paid'])) echo $pending['amount_paid']; ?></td>
														<td><a href="javascript:void(0);" class="view_activity" data-id="<?php echo $pending['id'] ?>">View Activity</a></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<?php
									}?>
									
								<div class="activity-divider"></div>
								
								<div class="activity-section" style="display:none">
									<h3>View Activity</h3>
									<p><b>Activity name:</b> Minneapolis event</p>
									<p><b>Select: </b>
										<label class="radio-custom">
											<input type="radio" name="one" value="one1" id="viewPost"> <span class="radio"></span>fixed start and stop time</label>
										<label class="radio-custom">
											<input type="radio" name="one" value="one2" id="viewPost"> <span class="radio"></span>flexible start/stop</label>
									</p>
									<ul class="activity-list">
										<li>
											<div>
												<svg viewBox="0 8 200 185">
													<use xlink:href="#calendar-icon"></use>
												</svg> Start Day: 12-6-2016</div>
											<div>
												<svg viewBox="0 10 200 180">
													<use xlink:href="#clock-icon"></use>
												</svg> Start Time: 9am </div>
										</li>
										<li>
											<div>
												<svg viewBox="0 8 200 185">
													<use xlink:href="#calendar-icon"></use>
												</svg> Finish Day: 22-6-2016</div>
											<div>
												<svg viewBox="0 10 200 180">
													<use xlink:href="#clock-icon"></use>
												</svg> Finish Time: 5pm </div>
										</li>
									</ul>
									
									<div class="activity-divider"></div>
									
									<p><b>Address:</b> Street:  000 Elm Street</p>
									<p><b>City:</b> Minneapolis</p>
									<p><b>State:</b> Mn</p>
									<p><b>Zip:</b> 55419</p>
									
									<div class="activity-divider"></div>
									
									<p>Contact Name: </p>
									
									<ul class="activity-list">
										<li>First: John</li>
										<li>Last: Johnson</li>
									</ul>
									
									<div class="activity-divider"></div>
									
									<p><b>Contact Information:</b> </p>
									<ul  class="activity-list">
										<li>Phone number: <a href="tel:000-000-0000">000-000-0000</a></li>
										<li>Email: <a href="mailto:email@email.com">email@email.com </a></li>
									</ul>
									
									<div class="activity-divider"></div>
									
									<p><b>Notes/tasks :</b></p>
									
									<div class="row">
										<div class="col-xs-12">
										  <textarea name="" cols="" rows="" class="input" placeholder="Type text"></textarea>
										</div>
									</div>
									
									
									<p><b>Activity Status:</b> Completed</p>
									
									<p><b>Job Expense Report:</b> <a href="#">View</a> <a href="#">Create</a></p>
										
									<p><b>Amount Due:</b> $100</p>
									<p><b>Amount Paid:</b> $100	</p>
								
									<div class="activity-associated-post-btns">
									  <button type="button" class="btn btn-blue">Close</button>
									  <button type="submit" class="btn btn-blue">Create Job Report</button>
									  <button type="button" class="btn btn-blue">Move to Pending</button>
									</div>
								
								</div>
							</div>
							
				<?php   } ?>
                    </aside>
                </div>
            </div>
        </section>
    </main>
	<!--Withdraw Activity Popup-->
	<div id="withdraw_activity" class="modal fade custom-modal in" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body"> <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                   <h3>Withdraw Activity</h3>
                   <h4>Withdrawing of activity will negatively impact your pay and your rating. Are you sure you want to withdraw from this activity?</h4>
				   <button type="button" id="withdraw_activity_button" class="btn btn-blue" >Confirm</button>
				   <button type="button" data-dismiss="modal" class="btn btn-blue">Cancel</button>
                </div>
            </div>
        </div>
    </div>
	
    