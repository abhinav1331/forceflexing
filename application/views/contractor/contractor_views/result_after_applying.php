<?php 
/* echo '$app_job Applied Jobs Data';
if(!empty($app_job))
{
	echo '<pre>';
	print_r($app_job);
	echo '</pre>';
}

echo '$job Jobs Data';
if(!empty($job))
{
	echo '<pre>';
	print_r($job);
	echo '</pre>';
}

echo '$saved_activities Saved Activities';
if(!empty($saved_activities))
{
	echo '<pre>';
	print_r($saved_activities);
	echo '</pre>';
}

echo '$all_activities All Activities';
if(!empty($all_activities))
{
	echo '<pre>';
	print_r($all_activities);
	echo '</pre>';
}


echo '$job_overages Job Overages';
if(!empty($job_overages))
{
	echo '<pre>';
	print_r($job_overages);
	echo '</pre>';
}

echo '$job_expenses job_expenses';
if(!empty($job_expenses))
{
	echo '<pre>';
	print_r($job_expenses);
	echo '</pre>';
}

echo '$question_answer question_answer';
if(!empty($question_answer))
{
	echo '<pre>';
	print_r($question_answer);
	echo '</pre>';
} */


?>



<main role="main">
  <section class="page-wrap applying-job posted">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2><?php echo $job['job_title']?></h2>
          <p class="postedDate">Posted: <?php echo date('m/d/Y',$job['job_created']);?></p>
          <!--<a href="#" class="viewPosting">View job posting</a>-->
          <div class="more-details">
            <article class="ff-description">
              <h3>Description</h3>
              <p><?php echo $job['job_description'];?></p>
            </article>
            <div class="emp-job-information activities">
              <div class="job-info-hdr clearfix">
                <h2>Events/activities Applied To</h2>
              </div>
              <div class="job-info-body">
                <div class="info-tabular-data">
                  <ul>
                    <li>
                      <p><strong>Activity Name</strong></p>
                      <p><strong>Activity Time</strong></p>
                      <p><strong>Flexibility</strong></p>
                      <p><strong>Location</strong></p>
                      <p><strong>Applied</strong></p>
                    </li>
					<?php
					if(isset($all_activities) && !empty($all_activities))
					{
						foreach($all_activities as $activity)
						{
							if(in_array($activity['id'],$saved_activities))
								$checked="checked";
							else
								$checked="";
							?>
							 <li>
							  <p><?php echo $activity['activity_name'];?></p>
							  <p><?php echo date('m/d/y h:m A',strtotime($activity['start_datetime']));?></p>
							  <p><?php echo $activity['activity_type'];?> Time</p>
							  <p><?php echo $activity['city'];?></p>
							  <p>
								<label for="<?php echo $activity['id']?>" class="custom-checkbox">
								  <input id="<?php echo $activity['id']?>" class="activity" type="checkbox" disabled <?php echo $checked;?>>
								  <span class="custom-check"></span></label>
							  </p>
							</li>
							<?php
							
						}
					}

					?>
					
                  </ul>
                </div>
               	<?php if($job['job_contractior_activity'] != "" && $job['job_contractior_activity'] == "no") 
					{
						if($job['job_minimum_contractor'] != "")
						{
							$num_cont=$job['job_minimum_contractor'];
						?>
						<input type="hidden" id="minimum_activities" name="minimum_activities" value="<?php echo $num_cont;?>">
					<p>Contractor must select a minimum of <?php echo $num_cont;?> of activities to apply to this job. The <?php echo $num_cont;?> is the number set by the employer.  The page should not be able to be submitted if the correct number of activities is not chosen.</p>
			   <?php     }
					} ?>
                <a href="javascript:void(0);" id="edit_activities" class="btn btn-blue editChangeBtn">Edit/Change Applied Events</a> </div>
            </div>
			<input type="hidden" id="applied_job_id" value="<?php echo $_GET['applied_job'];?>">
            <div class="emp-job-information payment-info">
              <div class="job-info-hdr clearfix">
                <h2>Payment Terms</h2>
              </div>
              <div class="job-info-body">
                <div class="proposal-terms">
                  <div class="company-proposal">
						<h3>Company Proposal</h3>
						
						<p><strong>Fixed or hourly:</strong><?php echo ucfirst($job['job_type']); ?></p>
						<p><strong>Rate of Pay:</strong>
						   $<?php echo number_format($app_job['company_proposal_rate'],2); echo ' '.($job['job_type'] == 'hourly' ?'hour':'per Activity')?>  </p>
					   
					   <!--allowable overages-->
					   <p><strong>Allowable Work Hour Overages</strong>
						<?php 
						if(!empty($job_overages))
						{
							 ?>
							<strong>Before Time:</strong> Yes (<?php echo $job_overages['before_time'];?>)
							<strong>After Time:</strong> Yes (<?php echo $job_overages['after_time'];?>)
				  <?php }  else 
						{
							?>
							<strong>Before Time:</strong> No 
							<strong>After Time:</strong> No 
							<?php
						}
					?>
						</p>
						
						<!-- get additional job expenses-->
						
						<p><strong>Allowable Expenses:</strong> 
						<?php  if($job['job_travel_cost'] == "yes")
							echo 'Mileage (Standard Reimbursement Rate)';?></p>
						<div class="reimb-rates">
						<?php if(!empty($job_expenses))
							{
								?>
							<table border="0" cellpadding="0" cellspacing="0">
							  <?php foreach($job_expenses as $expense) {?>
								<tr>
								  <td><?php echo ucfirst($expense['name']) ;?>:</td>
								  <td>$<?php echo $expense['price']; ?></td>
								</tr>
							  <?php } ?>
							</table>
							
					<?php } else
							{
								echo "None";
							}
							?>
						</div>
						
					</div>	
					
					<?php 
						$payment_terms=$app_job['payment_terms']; 
						if($payment_terms == 'new_terms')
						{
							$proposal_type=$app_job['proposal_type'];
							$proposal_rate=$app_job['proposal_rate'];
						}
						else
						{
							$proposal_type="";
							$proposal_rate="";
						}
					?>
					<div class="payment-terms">
						<div class="row">
							<div class="col-xs-12">
								<p>
									<label class="radio-custom">
										<input name="payment_terms" disabled value="accepted" <?php echo ($payment_terms == "accepted") ?'checked' : '';?> class="payment_terms new-terms" type="radio">
										<span class="radio"></span> Accept Company’s Payment Terms
									</label>
									<label class="radio-custom">
										<input name="payment_terms" disabled id="payment_terms" value="new_terms" <?php echo ($payment_terms == "new_terms") ?'checked' : '';?> class="payment_terms new-terms" type="radio">
										<span class="radio"></span> Propose New Terms
									 </label>
								</p>
							</div>
						</div>
					</div>
					
					<div class="your-proposal" style="display: <?php echo ($payment_terms == "new_terms")?'block':'none';?>">
						  <h3>Your Proposal</h3>
						  <div class="row">
								<div class="col-xs-6">
								   <p>Select Pay Type::</p>
								</div>
								<div class="col-xs-6">
									<p>
										<label class="radio-custom">
											<input class="new-terms" name="payRate" disabled value="Hourly" <?php echo ($proposal_type != "" && $proposal_type=="Hourly")?'checked':'';?> id="payRate_1"  type="radio">
											<span class="radio"></span> Hourly
										</label>
										<label class="radio-custom">
											<input class="new-terms" name="payRate" disabled value="Fixed" <?php echo ($proposal_type != "" && $proposal_type=="Fixed")?'checked':'';?> id="payRate_2"  type="radio">
											<span class="radio"></span> Fixed
										 </label>
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
								  <p>Select amount:</p>
								</div>
								<div class="col-xs-6">
								  <input id="proposed_amount" disabled value="<?php echo $proposal_rate?>" name="proposed_amount" class="input small new-terms" type="text">
								</div>
							</div>
						</div>
						<a href="javascript:void(0)" id="edit_terms" class="btn btn-blue editChangeBtn">Edit/Change Terms</a>
					</div> <!-- proposal terms end-->
				</div>
            </div>
            <div class="coverLetter">
              <h2>Cover Letter</h2>
				  <article class="ff-description filled">
					<p><?php echo $app_job['cover_letter'];?></p>
				  </article>
                <a href="javascript:void(0)" id="edit_cover_letter" class="btn btn-blue editChangeBtn">Edit</a> 
            </div>
			
			<?php if(isset($question_answer) && !empty($question_answer)) {?>
            <div class="additionQues">
              <h2>Questions</h2>
			  <?php foreach($question_answer as $qa) {?>
               <article class="ff-description filled">
					<h3><?php echo $qa['question'];?></h3>
					<p><?php echo $qa['answer']?></p>
					<input type="hidden" class="applied-answer-id" value="<?php echo $qa['id'];?>">
				</article>
				<a href="javascript:void(0)" class="btn btn-blue editChangeBtn edit_answer">Edit</a> 
			  <?php } ?>
            </div>
            <?php } ?>
			
			<?php ?>
            <div class="msgArea" id="msgArea">
            <h2>Messages</h2>
			<article class="ff-description filled">
				<?php if(!empty($app_job['message'] )){?>
				<p><?php echo $app_job['message']?></p>
				<?php } 
				else
				{
				?>
				<p data-placeholder="Click on Add"></p>
		   <?php } ?>
			</article>
			<?php if(empty($app_job['message'] )) {?>
				<a href="javascript:void(0)" id="edit_message" class="btn btn-blue editChangeBtn">Add</a> 
			<?php } ?>
			<!--<textarea name="" cols="" rows="" class="input"></textarea>-->
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
		
          <div class="activityOnJob">
            <h2>Activity for This Job</h2>
            <p><strong>Proposals: <?php 
									if(isset($proposals)) 
										echo $proposals; 
									else 
										echo 0; 
									?></strong></p>
            <p><strong>Active: <?php 
									if(isset($active_prop)) 
										echo $active_prop; 
									else 
										echo 0; 
									?></strong></p>
          </div>
		  <?php if($app_job['status'] == 0) 
		  {
			  $buttontext="Withdraw Proposal";
			  $statusonclick=1;
			  ?>
			  <a href="javascript:void(0);" data-toggle="modal" data-target="#withdraw-proposal" data-status="<?php echo $statusonclick;?>" id="withdraw_proposal" class="btn btn-blue withdrawProposalBtn"><?php echo $buttontext;?></a> </aside>
			  <?php
		  }
		  else
		  {
			  $buttontext="Reapply";
			  $statusonclick=0;
			  ?>
			   <a href="javascript:void(0);"  data-status="<?php echo $statusonclick;?>" id="reapply" class="btn btn-blue withdrawProposalBtn"><?php echo $buttontext;?></a> </aside>
			   <?php
		  }
		  ?>
		
      </div>
    </div>
  </section>
</main>
<!-- modal for reason for withdraw-->
<div class="modal fade" id="withdraw-proposal" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Reason for Withdrawl</h2>
				<div class="proposal-withdrawl">
					<input type="hidden" id="proposal_status" value=""> 
					<textarea class="input" id="withdraw-prop"></textarea>
				</div>
                <button type="button" class="btn-blue" id="withdraw_reason">Withdraw</button>
			</div>
			
		</div>
    </div>
</div>