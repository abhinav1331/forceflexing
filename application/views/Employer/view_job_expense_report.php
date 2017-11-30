<main role="main">
  <section class="page-wrap report">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2>Unpaid Job Expenses Report</h2>
          <div class="reportHdr">
            <div class="userDetails">
              <?php  
				if(!empty($contractor_details))
				{
			  ?>
			  <figure>
				<?php if(!empty($contractor_details)) ?>
				<img src="<?php echo $contractor_details['image']; ?>" alt="report user image">
			  </figure>
              <div class="userTitle">
                <h3><?php echo $contractor_details['first_name']." ".$contractor_details['last_name']?></h3>
                <p><?php echo $contractor_details['country']; ?></p>
              </div>
			<?php } ?>
            </div>
            <div class="userActivities">
              <div class="activitiesInner">
				<?php if(!empty($activity_detail))
				{?>
					<div class="nothing">
					  <p class="actHdr">Activity Name</p>
					  <p class="actValue"><?php echo $activity_detail['activity_name']; ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Activity Time</p>
					  <p class="actValue"><?php echo date('m-d-y h:m A',strtotime($activity_detail['start_datetime'])); ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Location</p>
					  <p class="actValue"><?php echo $activity_detail['city']; ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Contract Name</p>
					  <p class="actValue"><?php echo $activity_detail['job_title']; ?></p>
					</div>
		 <?php  } ?>
              </div>
            </div>
          </div>
		  
		  <!--Check if report is submitted yet or not-->
		  <?php if(!empty($rd))
		  { ?>
		  
          <div class="clientNotes">
            <h3>Client Notes</h3>
			  <?php if(!empty($activity_detail['notes']))
			{
				$description=$activity_detail['notes'];
				$desc=explode(PHP_EOL,$description);
				$length=strlen($description);
				  ?>
				<p><?php  echo $desc[0];?></p>
				<?php if($length >= 500)
				{ ?>
					<div align="right">
						<div class="more-content" style="display:none;">
						 <?php for($i=1;$i<count($desc); $i++)
						 { ?>
							 <p><?php echo $desc[$i];?></p>
					<?php } ?>
						</div>
						 <a href="javascript:void(0)" id="moreToggle" class=" view-more-toggle moreLink">More</a>
					</div>
				  <?php 
				}
			} ?>
			
            <div class="iConfirmation">
              <label for="iConfirm" class="custom-checkbox">
				<?php
					//chck if notes were followed checkbox is ticked or not
					$checked="";
					if($rd['activity_status_id'] == 1)
						$checked="checked";
					else
						$checked="";
				?>
                <input id="iConfirm" type="checkbox" <?php echo $checked; ?> disabled>
                <span class="custom-check"></span> I confirm that the notes were followed and activity was completed.</label>
            </div>
			<div class="report_submission">
		   <?php
				//check for the details
				if(!empty($rd))
				{
					$all_activities=json_decode($rd['day_details']);
					$i=0;
					foreach($all_activities as $activity)
					{
						?>
						<div class="replicate">
							<div class="timeDuration"> 
								<span>Date -
									<input type="text" name="activity_date[<?php echo $i; ?>]" value="<?php echo $activity->activity_date;?>" class="input datepick repl" placeholder="" disabled/>
								</span>
								<span class="arrived">Time Arrived -
									<input type="text" name="arrival_time[<?php echo $i; ?>]" value="<?php echo $activity->arrival_time;?>" class="input timepick repl" placeholder="" data-timepicker disabled/>
								</span> 
								<span class="departed">Time Departed -
									<input type="text" name="depart_time[<?php echo $i; ?>]"  value="<?php echo $activity->depart_time;?>" class="input timepick repl" placeholder="" data-timepicker disabled/>
								</span> 
							</div>
							<div class="otherDetails">
								<div class="otDetails">
									<p>Call Summary -</p>
									<textarea cols="" rows="" name="call_summary[<?php echo $i; ?>]" class="input repl" disabled><?php echo $activity->call_summary;?></textarea>
								</div>
								<div class="otDetails">
									<p>Follow-Up Tasks -</p>
									<textarea cols="" rows="" name="follow_up_tasks[<?php echo $i; ?>]"  class="input repl" disabled><?php echo $activity->follow_up_tasks;?></textarea>
								</div>
								<div class="otDetails">
									<p>Other Notes -</p>
									<textarea  cols="" rows="" class="input repl"  name="other_notes[<?php echo $i; ?>]" disabled><?php echo $activity->other_notes;?></textarea>
								</div>
							</div>
						</div>
						<?php 
						$i++;
					}
				}
				?>
			</div>
			
            <div class="ratingOptions"> 
				<span>Rate Your Performance 
					<?php 
						if(!empty($rd['self_performance_rating']))
							$perf_rating=$rd['self_performance_rating'];
						else
							$perf_rating=0;
					?>
					<span class="starRates" data-rating="<?php echo $perf_rating; ?>">
						<input type="hidden" id="rating-performance" name="rating_performance" value="<?php echo $perf_rating; ?>" />
					</span>
				</span> 
				<span>Rate Activity Success 
					<?php 
						if(!empty($rd['activity_success_rating']))
							$act_rating=$rd['activity_success_rating'];
						else
							$act_rating=0;
					?>
					<span class="starRates" data-rating="<?php echo $act_rating; ?>">
						<input type="hidden" id="rating-activity" name="rating_activity" value="<?php echo $act_rating; ?>" />
					</span>
				</span> 
				<span>If applicable, rate location
					<?php 
						if( !empty($rd['location_rating']))
							$loc_rating=$rd['location_rating'];
						else
							$loc_rating=0;
					?>
					<span class="starRates" data-rating="<?php echo $loc_rating; ?>">
						<input type="hidden" id="rating-location" name="rating_location" value="<?php echo $loc_rating; ?>" />
					</span>
				</span>
			</div>
            
			<div class="payemtnExpenses">
              <h3>Payment and Expenses</h3>
              <div class="row">
				<?php
				if(!empty($pd))
				{
					$job_type=strtolower($pd['job_type']);
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
								<td width="90">$<?php echo $pd['job_price'];?> hour</td>
							  </tr>
							  <tr>
								<td>Hours per contract</td>
								<td width="60" align="center">-</td>
								<td width="90"><?php echo $rd['hours_per_contract']; ?></td>
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
								<td><strong>Total Hours <?php echo $rd['hours_per_contract']; ?> X $<?php echo $pd['job_price'];?> + Overages</strong></td>
								<td width="60" align="center">=</td>
								<td width="90"><strong>$<?php echo number_format($rd['total_activity_amount'], 2, '.', ''); ?></strong></td>
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
								<td width="135">$<?php echo $pd['job_price'];?> fixed rate</td>
							  </tr>
							  <tr>
								<td>Total events/activities within contract</td>
								<td width="60" align="center">-</td>
								<td width="135"><?php echo $pd['total_activitiess'];?></td>
							  </tr>
		 
							</tbody>
							  <tfoot>
								<tr>
								<td><strong>Total events/activities within contract</strong></td>
								<td width="60" align="center">=</td>
								<td width="135"><strong>$<?php echo number_format($rd['total_activity_amount'], 2, '.', ''); ?></strong></td>
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
				if(!empty($rd['expense_details']))
				{?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php 
						$expenses=json_decode($rd['expense_details']);
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
							<td><span class="totalAmout">$<?php echo  number_format($rd['total_expense_amount'], 2, '.', '');?></span></td>
						</tr>
						<tr>
							<td>Discretionary Bonus</td>
							<td>$45.00</td>
						</tr>
					</table>
		<?php   }?>
              </div>
              
            </div>
            <div class="actionBtns">
            <button type="submit" class="btn btn-blue">Pay Contractor</button>
            <button type="submit" id="dispute_charges" data-attr-id="<?php echo $activity_status_id; ?>" class="btn btn-blue">Dispute Charges</button>
            </div>
            
          </div>
	<?php }
		else
		{?>
			 <div class="clientNotes">	
			 No Job Report Submitted Yet.
			 </div>
		 <?php
		}
			
	?>
		</aside>
      </div>
    </div>
  </section>
</main>
