<main role="main">
  <section class="page-wrap report dispute">
  
	<?php 
		//check if dispute is 1 and job report status is also 1, then only view this
		if($asd['job_report_status'] == 1 && $asd['dispute_status'] ==1)
		{
	?>
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2>Job Expense Report – Dispute Form</h2>
           <div class="reportHdr">
            <div class="userDetails">
              <?php  
				if(!empty($employer_details))
				{
			  ?>
			  <figure>
				<?php if(!empty($employer_details['image'])) ?>
				<img src="<?php echo $employer_details['image']; ?>" alt="report user image">
			  </figure>
              <div class="userTitle">
                <h3><?php echo $employer_details['first_name']." ".$employer_details['last_name']?></h3>
                <p><?php echo $employer_details['country']; ?></p>
              </div>
			<?php } ?>
            </div>
            <div class="userActivities">
              <div class="activitiesInner">
				<?php if(!empty($activity_details))
				{?>
					<div class="nothing">
					  <p class="actHdr">Activity Name</p>
					  <p class="actValue"><?php echo $activity_details['activity_name']; ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Activity Time</p>
					  <p class="actValue"><?php echo date('m-d-y h:m A',strtotime($activity_details['start_datetime'])); ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Location</p>
					  <p class="actValue"><?php echo $activity_details['city']; ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Contract Name</p>
					  <p class="actValue"><?php echo $activity_details['job_title']; ?></p>
					</div>
		 <?php  } ?>
              </div>
            </div>
          </div>
		  
          <div class="clientNotes">
  
            <div class="payemtnExpenses">
              <h3>Payment and Expenses</h3>
               <div class="row">
				<?php
				if(!empty($pricing_details))
				{
					$job_type=strtolower($pricing_details['job_type']);
					if($job_type == "hourly")
					{ 
					?>
						<div class="col-md-6">
						  <div class="payDetails hourlyPay">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							  <tbody>
								<tr>
								<td>Contracted rate of pay</td>
								<td width="60" align="center">-</td>
								<td width="90">$<?php echo $pricing_details['job_price'];?> hour</td>
							  </tr>
							  <tr>
								<td>Hours per contract</td>
								<td width="60" align="center">-</td>
								<td width="90"><?php echo $already_exist['hours_per_contract']; ?></td>
							  </tr>
							  <?php 
								if(!empty($rd['overage']))
								{
								  ?>
								  <tr>
									<td>Overage (if allowed)</td>
									<?php $overage_price=json_decode($pd['overage']); ?>
									<td width="60" align="center">-</td>
									<td width="90">$<?php echo $overage_price[2];?> (<?php echo $rd['overage'];?> min)</td>
								  </tr>
						<?php   } ?>
							</tbody>
							  <tfoot>
								<tr>
								<td><strong>Total Hours <?php echo $already_exist['hours_per_contract']; ?> X $<?php echo $pricing_details['job_price'];?> + Overages</strong></td>
								<td width="60" align="center">=</td>
								<td width="90"><strong>$<?php echo number_format($already_exist['total_activity_amount'], 2, '.', ''); ?></strong></td>
							  </tr>
							</tfoot>
							</table>
						  </div>
						</div>
			<?php   }
					elseif($job_type == "fixed")
					{
			?>			<div class="col-md-6">
						  <div class="payDetails fixedPay">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							  <tbody>
								<tr>
								<td>Contracted rate of pay</td>
								<td width="60" align="center">-</td>
								<td width="135">$<?php echo $pricing_details['job_price'];?> fixed rate</td>
							  </tr>
							  <tr>
								<td>Total events/activities within contract</td>
								<td width="60" align="center">-</td>
								<td width="135"><?php echo $pricing_details['total_activitiess'];?></td>
							  </tr>
		 
							</tbody>
							  <tfoot>
								<tr>
								<td><strong>Total events/activities within contract</strong></td>
								<td width="60" align="center">=</td>
								<td width="135"><strong>$<?php echo number_format($already_exist['total_activity_amount'], 2, '.', ''); ?></strong></td>
							  </tr>
							</tfoot>
							</table>
						  </div>
						</div>
			<?php   }
				}
			?>
              </div>
			  
             <div class="additionaExpenses">
				<?php 
				if(!empty($already_exist['expense_details']))
				{?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php 
						$expenses=json_decode($already_exist['expense_details']);
						foreach($expenses as $expense)
						{
							//get the dynamic keys
							$keys = array_keys((array)$expense);
							?>
							<tr>
								<td><?php echo ucfirst(str_replace("expense_","",$keys[0])); ?></td>
								<td>$<?php echo number_format($expense->$keys[0], 2, '.', '');?>	
								<?php if(!empty($expense->$keys[1]))
								{?>
									<a target="_blank" href="<?php echo $expense->$keys[1]; ?>" class="viewReceipt">view receipt</a></td>
						<?php   } ?>
							</tr>
							<?php
						}
						?>
						<tr>
							<td>Amount</td>
							<td><span class="totalAmout">$<?php echo  number_format($already_exist['total_expense_amount'], 2, '.', '');?></span></td>
						</tr>
						<tr>
							<td>Discretionary Bonus</td>
							<td>$45.00</td>
						</tr>
					</table>
		<?php   }?>
              </div>
            </div>
            
			<form name="" action="" method="post">
				<?php if(isset($asd) && !empty($asd['dispute_status']) &&  $asd['dispute_status'] == 1) 
						$disable="disabled";
					else
						$disable="";
					?>
				<div class="disputeForm">
					<p><strong>Detail area of dispute:</strong></p>
					<textarea name="reason_dispute" <?php echo $disable;?> cols="" rows="" class="input"><?php if(isset($asd) && !empty($asd['dispute_reason']) ) echo $asd['dispute_reason'];?>
					</textarea>
				</div>
				<div class="actionBtns">
					
				  <button type="submit" name="resubmit_job_report" class="btn btn-blue">Resubmit Job Report</button>
				  
				  <button type="button" data-toggle="modal" data-target="#dispute-mediation" id="dispute_mediation" class="btn btn-blue">Dispute Mediation</button>
				</div>
			</form>
          </div>
        </aside>
      </div>
    </div>
  <?php }
	else
	{
		?>
		<div class="container">
			<div class="page-main">
				<h2>There is no dispute for this activity!!</h2>
			</div>
		</div>
	<?php 
	}
  ?>
  </section>
</main>
<!--Modal for mediation confirmation-->
<div class="modal fade" id="dispute-mediation" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Dispute Mediation Confirmation</h2>
				<p>
					You should try to work it with the employer prior to submitting to mediation. Are you sure you want to proceed?
				</p>
				<form action="" method="post">
					<button type="submit" class="btn-blue" data-attr-id="<?php echo $activity_status_id; ?>" name="confirm_dispute_mediation"  id="confirm_dispute_mediation">Confirm</button>
					<button type="button" class="btn-blue" data-dismiss="modal" >Cancel</button>
				</form>
				
			</div>
			
		</div>
    </div>
</div>