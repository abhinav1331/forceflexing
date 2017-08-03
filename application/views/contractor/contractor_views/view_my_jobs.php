  <main role="main">
        <section class="contracts-wrap">
            <div class="container">
                <div class="contracts-main my-jobs">
                    <h3>My Jobs</h3>
                    <div class="contracts-tabs">
                        <!-- Nav tabs -->
                        <ul class="tabs-nav" role="tablist">
                            <li role="presentation" class="active"><a href="#allJobs" aria-controls="sa" role="tab" data-toggle="tab">All Jobs</a></li>
                            <li role="presentation"><a href="#pendingTraining" aria-controls="aa" role="tab" data-toggle="tab">Pending Training</a></li>
                            <li role="presentation"><a href="#pendingActivities" aria-controls="ep" role="tab" data-toggle="tab">Pending Activities</a></li>
                            <li role="presentation"><a href="#pendingJobReport" aria-controls="ep" role="tab" data-toggle="tab">Pending Job Report</a></li>
                            <li role="presentation"><a href="#completed" aria-controls="ep" role="tab" data-toggle="tab">Completed </a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="allJobs">
                               
								<?php if(!empty($all_jobs)) {
										foreach($all_jobs as $job)
										{
									?>	<article>
											<div class="user-info">
												<figure class="contractor-avatar"> <img src="<?php if(isset($job['emp_image']) && !empty($job['emp_image'])) echo $job['emp_image'] ;?>" alt="Contractor Picture"> </figure>
												<div class="user-name">
													<h4><?php if(isset($job['emp_name']) && !empty($job['emp_name'])) echo $job['emp_name'] ;?></h4>
													<ul>
														<li><?php if(isset($job['emp_country']) && !empty($job['emp_country'])) echo $job['emp_country'] ;?>, <?php if(isset($job['emp_city']) && !empty($job['emp_city'])) echo $job['emp_city'] ;?></li>
														<li>4:10pm local time</li>
													</ul>
												</div>
											</div>
											<div class="user-extra-info">
												<ul>
													<li><big>0/2 Completed</big> Training </li>
													<li>
													<big>
													<?php if(isset($job['done_activity_count'])) 
														echo $job['done_activity_count'] ;?>
													/<?php if(isset($job['total_activities']) )
														echo $job['total_activities'] ;?> Completed
													</big> Activities 
													</li>
												</ul>
											</div>
											<div class="user-action-btn">
												<div class="dropdown">
													<button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
													<ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
														<li>
															<label class="radio-custom">
																<a href="<?php echo BASE_URL."contractor/view_contract/?contract_id=".$job['contract_id']."" ?>">View Contract</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
																<a href="<?php echo BASE_URL.'/contractor/activity_detail/?contract_id='.$job['contract_id'].'';?>">View Activity Detail</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
																 <a href="">View Training Detail</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
															   <a href="">Message</a>
															</label>
														</li>
													 </ul>
												</div>
											</div>
										</article> 
										<a href="#" data-toggle="modal" data-target="#myModal">Open Modal</a> 
										<a href="#" data-toggle="modal" data-target="#myModal2">Open Modal 2</a> 
										<a href="#" data-toggle="modal" data-target="#myModal3">Open Modal 3</a> 
							<?php }} 
								else
								{
									echo "No Job Found!!";
								}
							?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pendingTraining">
                                <article>
                                    <div class="user-info">
                                        <figure class="contractor-avatar"> <img src="<?php echo BASE_URL ;?>/static/images/contracts-user-img.jpg" alt="Contractor Picture"> </figure>
                                        <div class="user-name">
                                            <h4>Lorem Ipsum</h4>
                                            <ul>
                                                <li>California, USA</li>
                                                <li>4:10pm local time</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="user-extra-info">
                                        <ul>
                                            <li><big>1/2 Completed</big> Training </li>
                                            <li><big>0/2 Failed</big> 0/2 Failed </li>
                                            <li><big>1/2 Not Started</big> Training </li>
                                            <li><big>3 Days</big> Time to Complete </li>
                                        </ul>
                                    </div>
                                    <div class="user-action-btn">
                                        <div class="dropdown">
                                            <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                            <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                                <li>
                                                    <label class="radio-custom">
                                                        <a href="javascript:void(0);">View Contract</a>
													</label>
                                                </li>
                                                <li>
                                                    <label class="radio-custom">
                                                        <a href="">View Activity Detail</a>
													</label>
                                                </li>
                                                <li>
                                                    <label class="radio-custom">
														 <a href="">View Training Detail</a>
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="radio-custom">
                                                       <a href="">Message</a>
													</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pendingActivities">
								<?php if(isset($pending_jobs) && !empty($pending_jobs) )
								{
									foreach($pending_jobs as $job)
									{
									?>
							
										<article>
											<div class="user-info">
												<figure class="contractor-avatar"> <img src="<?php if(isset($job['emp_image']) && !empty($job['emp_image'])) echo $job['emp_image'] ;?>"> </figure>
												<div class="user-name">
													<h4><?php if(isset($job['emp_name']) && !empty($job['emp_name'])) echo $job['emp_name'] ;?></h4>
													<ul>
														<li><?php if(isset( $job['emp_city']) && !empty( $job['emp_city'])) echo $job['emp_city'];?>, <?php if(isset($job['emp_country']) && !empty($job['emp_country'])) echo $job['emp_country'];?></li>
														<li>4:10pm local time</li>
													</ul>
												</div>
											</div>
											<div class="user-extra-info">
												<ul>
													<li>
													<big>
														<?php if(isset($job['done_activity_count'])) echo $job['done_activity_count']; ?>
														/
														<?php if(isset($job['total_activities']))
														echo $job['total_activities']; ?> Completed
														</big> Activities 
													</li>
													<li>
														<big>
														<?php if(isset($job['days']) && !empty($job['days'])) echo $job['days']; ?>
														</big> 
														<?php if(isset($job['daystext']) && !empty($job['daystext'])) echo $job['daystext'];?> 
													</li>
													<li>
													<big>Location Name</big> 
													<?php if(isset($job['activity_location']) && !empty($job['activity_location'])) echo ucfirst($job['activity_location']); ?>
													</li>
												</ul>
											</div>
											<div class="user-action-btn">
												<div class="dropdown">
													<button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
													<ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
														 <li>
															<label class="radio-custom">
																<a href="<?php echo BASE_URL."contractor/view_contract/?contract_id=".$job['contract_id']."" ?>">View Contract</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
																<a href="<?php echo BASE_URL.'/contractor/activity_detail/?contract_id='.$job['contract_id'].'';?>">View Activity Detail</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
																 <a href="">View Training Detail</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
															   <a href="">Message</a>
															</label>
														</li>
													</ul>
												</div>
											</div>
										</article>
							<?php 	}
								}
								else
								{
									echo "No pending Activities!!";
								}
								?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pendingJobReport">
                               <?php 
							   if(isset($pending_job_report) && !empty($pending_job_report))
							   { 
									foreach($pending_job_report as $pending)
									{
									?>
									   <article>
											<div class="user-info">
												<figure class="contractor-avatar"> <img src="<?php if(isset($pending['emp_image']) && !empty($pending['emp_image']) ) echo $pending['emp_image'] ;?>" alt="Contractor Picture"> </figure>
												<div class="user-name">
													<h4><?php if(isset($pending['emp_name']) && !empty($pending['emp_name'])) echo $pending['emp_name'];?></h4>
													<ul>
														<li><?php if(isset($pending['emp_city']) && !empty($pending['emp_city'])) echo $pending['emp_city'];?>, <?php if(isset($pending['emp_country']) && !empty($pending['emp_country'])) echo $pending['emp_country']; ?></li>
														<li>4:10pm local time</li>
													</ul>
												</div>
											</div>
											<div class="user-extra-info">
												<ul>
													<li><big><?php if(isset($pending['done_activity_count'])) echo $pending['done_activity_count']; ?> Activities Completed</big> File Job Report </li>
												</ul>
											</div>
											<div class="user-action-btn">
												<div class="dropdown">
													<button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
													<ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
														<li>
															<label class="radio-custom">
																<a href="<?php echo BASE_URL."contractor/view_contract/?contract_id=".$pending['contract_id']."" ?>">View Contract</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
																<a href="<?php echo BASE_URL.'/contractor/activity_detail/?contract_id='.$pending['contract_id'].'';?>">View Activity Detail</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
																 <a href="">View Training Detail</a>
															</label>
														</li>
														<li>
															<label class="radio-custom">
															   <a href="">Message</a>
															</label>
														</li>
													</ul>
												</div>
											</div>
										</article>
						<?php 		}
								}
								else
								{
									echo "No Pending Job Reports!!";
								}
								?>
						  </div>
                            <div role="tabpanel" class="tab-pane fade" id="completed">
							<?php if(isset($completed_jobs) && !empty($completed_jobs))
							{
								foreach($completed_jobs as $completed)
								{
									?>
									<article>
										<div class="user-info">
											<figure class="contractor-avatar"> <img src="<?php if(isset($completed['emp_image']) && !empty($completed['emp_image'])) echo $completed['emp_image']; ?>" alt="Contractor Picture"> </figure>
											<div class="user-name">
												<h4><?php if(isset($completed['emp_name']) && !empty($completed['emp_name'])) echo $completed['emp_name']; ?></h4>
												<ul>
													<li><?php if(isset($completed['emp_city']) && !empty($completed['emp_city'])) echo $completed['emp_city']; ?>, <?php if(isset($completed['emp_country']) && !empty($completed['emp_country'])) echo $completed['emp_country']; ?></li>
													<li>4:10pm local time</li>
												</ul>
											</div>
										</div>
										<div class="user-extra-info">
											<ul>
												<li><big>Completed/<?php if(isset($completed['completed_date']) && !empty($completed['completed_date'])) echo date('m-d-Y',strtotime($completed['completed_date'])) ;?></big>
													<div class="rating"> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> </div>
												</li>
											</ul>
										</div>
										<div class="user-action-btn">
											<div class="dropdown">
												<button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
												<ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
													 <li>
														<label class="radio-custom">
															<a href="<?php echo BASE_URL."contractor/view_contract/?contract_id=".$completed['contract_id']."" ?>">View Contract</a>
														</label>
													</li>
													<li>
														<label class="radio-custom">
															<a href="<?php echo BASE_URL.'/contractor/activity_detail/?contract_id='.$completed['contract_id'].'';?>">View Activity Detail</a>
														</label>
													</li>
													<li>
														<label class="radio-custom">
															 <a href="">View Training Detail</a>
														</label>
													</li>
													<li>
														<label class="radio-custom">
														   <a href="">Message</a>
														</label>
													</li>
												</ul>
											</div>
										</div>
									</article>
									<?php
								}
							} 
							else
							{
								echo "No Completed Jobs!!";
							}
							?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Activity Detail  Modal -->
    <div id="myModal" class="modal fade custom-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body"> <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <h3>Add and Manage Activities</h3>
                    <h4>Completed</h4>
                    <div class="action-detail-table">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="col">Activity Name	</th>
                                <th scope="col">Activity Time</th>
                                <th scope="col">Location </th>
                                <th scope="col">Contact Name </th>
                                <th scope="col">Job Report </th>
                                <th scope="col">Amount Due</th>
                                <th scope="col">Amount Paid </th>
                                <th>&nbsp;</th>
                            </tr>
                             <tr>
                                <td scope="col">Minneapolis Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Minneapolis </td>
                                <td scope="col">Drew Connor	 </td>
                                <td scope="col">Not Received</td>
                                <td scope="col">$200.00</td>
                                <td scope="col">$0.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 
                                 </td>
                            </tr>
                            
                            <tr>
                                <td scope="col">Des Moines Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Des Moines </td>
                                <td scope="col">Bob Fairly	 </td>
                                <td scope="col">Paid	</td>
                                <td scope="col">$250.00	</td>
                                <td scope="col">$250.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 <a href="#" class="btn btn-blue">View Job Report</a>
                                 </td>
                            </tr>
                            
                            
                        </table>
                    </div>
                    
                     <h4>Completed</h4>
                    <div class="action-detail-table">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="col">Activity Name	</th>
                                <th scope="col">Activity Time</th>
                                <th scope="col">Location </th>
                                <th scope="col">Contact Name </th>
                                <th scope="col">Job Report </th>
                                <th scope="col">Amount Due</th>
                                <th scope="col">Amount Paid </th>
                                <th>&nbsp;</th>
                            </tr>
                             <tr>
                                <td scope="col">Minneapolis Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Minneapolis </td>
                                <td scope="col">Drew Connor	 </td>
                                <td scope="col">Not Received</td>
                                <td scope="col">$200.00</td>
                                <td scope="col">$0.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 
                                 </td>
                            </tr>
                            
                            <tr>
                                <td scope="col">Des Moines Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Des Moines </td>
                                <td scope="col">Bob Fairly	 </td>
                                <td scope="col">Paid	</td>
                                <td scope="col">$250.00	</td>
                                <td scope="col">$250.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 <a href="#" class="btn btn-blue">View Job Report</a>
                                 </td>
                            </tr>
                            
                            
                        </table>
                    </div>
                    
                    <a href="#" class="btn btn-blue">Add Activities</a>
                    
                    
                </div>
            </div>
        </div>
        
        
    </div>
        
    
     <div id="myModal2" class="modal fade custom-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body"> <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <h3>Add and Manage Activities</h3>
                    <h4>Completed</h4>
                    <div class="action-detail-table">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="col">Course Names</th>
                                <th scope="col">Description</th>
                                <th scope="col">Status </th>
                                <th scope="col">Date Completed </th>
                                <th scope="col">Score </th>
                               
                                <th>&nbsp;</th>
                            </tr>
                             <tr>
                                <td scope="col">Selling BBQs</td>
                                <td scope="col">Course 1708 on basic selling</td>
                                <td scope="col">Completed </td>
                                <td scope="col">11/00/00	 </td>
                                <td scope="col">98%</td>
                             
                                <td>
                                 <a href="#" class="btn btn-blue"> View</a>
                                 
                                 </td>
                            </tr>
                            
                        </table>
                    </div>
                    
                     <h4>Pending</h4>
                    <div class="action-detail-table">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="col">Course Names</th>
                                <th scope="col">Description</th>
                                <th scope="col">Status </th>
                                <th scope="col">Date Completed </th>
                                <th scope="col">Score </th>
                               
                                <th>&nbsp;</th>
                            </tr>
                             <tr>
                                <td scope="col">Selling BBQs</td>
                                <td scope="col">Course 1708 on basic selling</td>
                                <td scope="col">Completed </td>
                                <td scope="col">11/00/00	 </td>
                                <td scope="col">98%</td>
                             
                                <td>
                                 <a href="#" class="btn btn-blue"> View</a>
                                 <a href="#" class="btn btn-blue"> Delete</a>
                                    <a href="#" class="btn btn-blue"> Edit</a>
                                 
                                 </td>
                            </tr>
                            
                             <tr>
                                <td scope="col">Selling BBQs</td>
                                <td scope="col">Course 1708 on basic selling</td>
                                <td scope="col">Not taken </td>
                                <td scope="col">-- </td>
                                <td scope="col">--</td>
                             
                                <td>
                                 <a href="#" class="btn btn-blue"> View</a>
                                 <a href="#" class="btn btn-blue"> Delete</a>
                                    <a href="#" class="btn btn-blue"> Edit</a>
                                 </td>
                            </tr>
                            
                        </table>
                    </div>
                    <a href="#" class="btn btn-blue">Add Activities</a>
                    
                    
                </div>
            </div>
        </div>
        
        
    </div>
        
    <div id="myModal3" class="modal fade custom-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body"> <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <h3>Add and Manage Activities</h3>
                    <h4>Completed</h4>
                    <div class="action-detail-table">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="col">Activity Name	</th>
                                <th scope="col">Activity Time</th>
                                <th scope="col">Location </th>
                                <th scope="col">Contact Name </th>
                                <th scope="col">Job Report </th>
                                <th scope="col">Amount Due</th>
                                <th scope="col">Amount Paid </th>
                                <th>&nbsp;</th>
                            </tr>
                             <tr>
                                <td scope="col">Minneapolis Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Minneapolis </td>
                                <td scope="col">Drew Connor	 </td>
                                <td scope="col">Not Received</td>
                                <td scope="col">$200.00</td>
                                <td scope="col">$0.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 
                                 </td>
                            </tr>
                            
                            <tr>
                                <td scope="col">Des Moines Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Des Moines </td>
                                <td scope="col">Bob Fairly	 </td>
                                <td scope="col">Paid	</td>
                                <td scope="col">$250.00	</td>
                                <td scope="col">$250.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 <a href="#" class="btn btn-blue">View Job Report</a>
                                 </td>
                            </tr>
                            
                            
                        </table>
                    </div>
                    
                     <h4>Completed</h4>
                    <div class="action-detail-table">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="col">Activity Name	</th>
                                <th scope="col">Activity Time</th>
                                <th scope="col">Location </th>
                                <th scope="col">Contact Name </th>
                                <th scope="col">Job Report </th>
                                <th scope="col">Amount Due</th>
                                <th scope="col">Amount Paid </th>
                                <th>&nbsp;</th>
                            </tr>
                             <tr>
                                <td scope="col">Minneapolis Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Minneapolis </td>
                                <td scope="col">Drew Connor	 </td>
                                <td scope="col">Not Received</td>
                                <td scope="col">$200.00</td>
                                <td scope="col">$0.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 
                                 </td>
                            </tr>
                            
                            <tr>
                                <td scope="col">Des Moines Show</td>
                                <td scope="col">11/20/16 6:00 PM</td>
                                <td scope="col">Des Moines </td>
                                <td scope="col">Bob Fairly	 </td>
                                <td scope="col">Paid	</td>
                                <td scope="col">$250.00	</td>
                                <td scope="col">$250.00 </td>
                                <td>
                                 <a href="#" class="btn btn-blue"> View Activity</a>
                                 <a href="#" class="btn btn-blue">View Job Report</a>
                                 </td>
                            </tr>
                            
                            
                        </table>
                    </div>
                    
                    <a href="#" class="btn btn-blue">Add Activities</a>
                    
                    
                </div>
            </div>
        </div>
        
        
    </div>
     
    