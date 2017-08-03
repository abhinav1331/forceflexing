<main role="main">
  <section class="page-wrap applying-job">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2><?php echo $job_data['job_title']; ?></h2>
          <div class="more-details">
            <article class="ff-description">
              <h3>Description</h3>
              <p><?php echo $job_data['job_description']; ?></p>
            </article>
			<!--apply for a job form-->
			<form action="" method="post" id="apply_for_job_contractor">
				<div class="emp-job-information activities">
				  <div class="job-info-hdr clearfix">
					<h2>Apply for Events/activities</h2>
				  </div>
				  <div class="job-info-body">
					<div class="info-tabular-data">
					  <ul>
						<li>
						  <p><strong>Activity Name</strong></p>
						  <p><strong>Activity Time</strong></p>
						  <p><strong>Flexibility</strong></p>
						  <p><strong>Location</strong></p>
						  <p><strong>Select Activities Applying For</strong></p>
						</li>
						<?php 
						$i=1;
							if($job_data['job_contractior_activity'] != "" && $job_data['job_contractior_activity'] == "yes") 
							{
								$checked= "checked";
								$disabled="disabled";
							}
							else
							{
								$checked= "";
								$disabled="";
							}
						
							foreach($job_activities as $activity)
							{
								?>
								<li>
								  <p><?php echo $activity['activity_name']; ?></p>
								  <p><?php  echo date( 'm/d/y h:m A',strtotime($activity['start_datetime'])); ?></p>
								  <p><?php echo ucfirst($activity['activity_type']); ?> Time</p>
								  <p><?php echo ucfirst($activity['city']); ?></p>
								  <p>
									<label for="activity_<?php echo $i;?>" class="custom-checkbox">
										<input id="activity_<?php echo $i;?>" name="selected_activity[]" value="<?php echo $activity['id'];?>" <?php echo $disabled;?> <?php echo $checked;?> type="checkbox">
										<?php if($disabled == 'disabled') {?>
										<input type="hidden" name="selected_activity[]" value="<?php echo $activity['id'];?>">
										<?php } ?>
										<span class="custom-check"></span>
									</label>
								  </p>
								</li>
								<?php
								$i++;
							}
						?>
						
					  </ul>
					</div>
					<?php if($job_data['job_contractior_activity'] != "" && $job_data['job_contractior_activity'] == "no") 
					{
						if($job_data['job_minimum_contractor'] != "")
						{
							$num_cont=$job_data['job_minimum_contractor'];
						?>
						<input type="hidden" id="minimum_activities" name="minimum_activities" value="<?php echo $num_cont;?>">
					<p>Contractor must select a minimum of <?php echo $num_cont;?> of activities to apply to this job”.  The <?php echo $num_cont;?> is the number set by the employer.  The page should not be able to be submitted if the correct number of activities is not chosen.</p>
			   <?php     }
					} ?>
				  </div>
				</div>
				<div class="emp-job-information payment-info">
				  <div class="job-info-hdr clearfix">
					<h2>Payment Terms</h2>
				  </div>
				  <div class="job-info-body">
					<div class="proposal-terms">
					  <div class="company-proposal">
						<h3>Company Proposal</h3>
						
						<p><strong>Fixed or hourly:</strong><?php echo ucfirst($job_data['job_type']); ?></p>
						<p><strong>Rate of Pay:</strong>
						   $<?php echo number_format($job_data['job_price'],2); echo ' '.($job_data['job_type'] == 'hourly' ?'hour':'per Activity')?>  </p>
					   <input type="hidden" name="company_rate" value="<?php echo $job_data['job_price']?>">
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
							
					<?php } else
							{
								echo "None";
							}
							?>
						</div>
						
					  </div><hr>
					  
					  <div class="payment-terms">
						<div class="row">
							<div class="col-xs-12">
								<p>
									<label class="radio-custom">
										<input type="radio" name="payment_terms" value="accepted" class="payment_terms">
										<span class="radio"></span> Accept Company’s Payment Terms
									</label>
									<label class="radio-custom">
										<input type="radio" name="payment_terms" id="payment_terms" value="new_terms" class="payment_terms">
										<span class="radio"></span> Propose New Terms
									 </label>
								</p>
							</div>
						</div>
					  </div>
					  
					  <div class="your-proposal" style="display:none;">
						  <h3>Your Proposal</h3>
						  <div class="row">
								<div class="col-xs-6">
								   <p>Select Pay Type:</p>
								</div>
								<div class="col-xs-6">
									<p>
										<label class="radio-custom">
											<input type="radio" name="payRate" value="Hourly" id="payRate_1" checked="">
											<span class="radio"></span> Hourly
										</label>
										<label class="radio-custom">
											<input type="radio" name="payRate" value="Fixed" id="payRate_2" checked="">
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
								  <input id="proposed_amount" name="proposed_amount" type="text" class="input small">
								</div>
							</div>
						</div>
						
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
					<label for="acceptTerms" class="custom-checkbox large">
					  <input id="acceptTerms" name="acceptTerms" type="checkbox">
					  <span class="custom-check"></span> Accept training terms useful emails every now and then to help me get the most out of ForceFlexing</label>
				  </div>
				</div>
				
				<div class="coverLetter"><h2>Cover Letter</h2>
					 <textarea name="cover_letter" id="cover_letter" class="input"></textarea>
				</div>
				<div class="additionQues"><h2>Additional questions if included in job post</h2>
				<?php 
				if(isset($job_questions) && !empty($job_questions))
				{
					foreach($job_questions as $questions)
					{
						?>
						<h3><?php echo $questions['job_questions']; ?></h3>
						<textarea  name="questions[<?php echo $questions['id']?>][answer]" class="input questions"></textarea>
						<?php 
					}
				}
				?>
				</div>
				<div class="msgArea"><h2>Message</h2>
					 <textarea name="message" id="message" class="input"></textarea>
				</div>
			   <input type="hidden" name="job_id" id="job_id" value="<?php echo $job_data['id'];?>">
			   <input type="hidden" name="contractor_id" id="contractor_id" value="<?php echo $instance->userid; ?>">
		   <div class="job-post-btns">
		   <?php 
			 if(isset($alert) && $alert == 'yes') 
			   {
				   $class_al="btn-gray";
				   $disable_al="disabled";
			   }
			   else
				{
				   $class_al="btn-blue";
				   $disable_al="";
				}
			?>
			  <button type="submit"  name="apply_job" class="btn btn-blue">Submit Application</button>
			  <button type="submit" name="flex_alert" <?php echo $disable_al?> class="btn <?php echo $class_al;?> flex_alert">Alert when Pay Flexed</button>
			</div>
       </form>
	  </aside>
      </div>
    </div>
  </section>
</main>