<main role="main">
	<section class="contracts-wrap">
		<div class="container">
			<div class="contracts-main">
				<h3>My Job Reports</h3>
				<div class="contracts-tabs"> 
					<!-- Nav tabs -->
					<ul class="tabs-nav" role="tablist">
						<li role="presentation" class="active"><a href="#active" aria-controls="hired" role="tab" data-toggle="tab">Active Activities</a></li>
						<li role="presentation"><a href="#closed" aria-controls="past-hired" role="tab" data-toggle="tab">Closed Activities</a></li>
					</ul>
				  <!--- Sorting buttons ---->
				  <!-- <div class="sorting-btns clearfix"><a class="btn btn-blue" href="#">Sort 1</a><a class="btn btn-blue" href="#">Sort 2</a><a class="btn btn-blue" href="#">Sort 3</a></div> -->
				  <!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="active">
							<div class="job-activities-table">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="datatable-job-report">
									<thead>
									<tr>
									  <th scope="col">Employer</th>
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
										<?php if(!empty($active))
										{
											foreach($active as $a)
											{?>
												<tr>
													<td><?php echo $a['employer']; ?></td>
													<td>Product launch</td>
													<td><a href="<?php echo BASE_URL;?>/contractor/activity_detail/?contract_id=<?php echo $a['contract_id'];?>">Minneapolis Show</a></td>
													<td><span class="ac-date"><?php echo date('m/d/y',strtotime($a['activity_time'])); ?></span> <span class="ac-time"><?php echo date('h:i A',strtotime($a['activity_time'])); ?></span></td>
													<td><?php echo $a['activity_location'];?></td>
													<td><?php echo $a['contact_name'];?></td>
													<td><?php echo $a['job_report'];?></td>
													<td><?php echo $a['status'];?></td>
												</tr>
									<?php	}
										} ?>
									</tbody>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="closed">
							<div class="job-activities-table">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="datatable-job-report">
									<thead>
										<tr>
											<th scope="col">Employer</th>
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
										<?php
											if(!empty($closed)) 
											{
												foreach($closed as $c)
												{?>
													<tr>
														<td><?php echo $c['employer']; ?></td>
														<td><?php echo $c['job_name']; ?></td>
														<td><?php echo $c['activity_name']; ?></td>
														<td>
														<span class="ac-date"><?php echo date('m/d/y',strtotime($c['date'])); ?></span> 
														<span class="ac-time"><?php echo date('h:i A',strtotime($c['date'])); ?></span></td>
														<td><?php echo $c['activity_location']; ?></td>
														<td><?php echo $c['contact_name']; ?></td>
														<td><?php echo $c['job_report']; ?></td>
														<td><?php echo $c['feedback']; ?></td>
														<td>$<?php echo $c['amount_paid']; ?></td>
													</tr>
									<?php 		}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</section>
</main>