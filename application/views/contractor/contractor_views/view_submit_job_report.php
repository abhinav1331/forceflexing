<main role="main">
  <section class="page-wrap report edit">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2>Unpaid Job Expenses Report</h2>
          <div class="reportHdr">
            <div class="userDetails">
			<?php if(!empty($employer_details)) {?>
              <figure><img src="<?php echo $employer_details['image']; ?>" alt="<?php basename($employer_details['image']); ?>"></figure>
              <div class="userTitle">
                <h3><?php echo $employer_details['first_name']." ".$employer_details['last_name']; ?></h3>
                <p><?php echo $employer_details['country']; ?></p>
              </div>
			<?php  }?>
            </div>
            <div class="userActivities">
              <div class="activitiesInner">
				<?php if(!empty($activity_details)){ ?>
					<div class="nothing">
					  <p class="actHdr">Activity Name</p>
					  <p class="actValue"><?php echo $activity_details['activity_name']; ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Activity Time</p>
					  <p class="actValue"><?php echo date('m/d/y h:m A',strtotime($activity_details['start_datetime'])); ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Location</p>
					  <p class="actValue"><?php echo $activity_details['city']; ?></p>
					</div>
					<div class="nothing">
					  <p class="actHdr">Contract Name</p>
					  <p class="actValue"><?php echo $activity_details['job_title']; ?></p>
					</div>
				<?php } ?>
              </div>
            </div>
          </div>
          <div class="clientNotes">
            <h3>Client Notes</h3>
           <?php if(!empty($activity_details))
			{
				$description=$activity_details['notes'];
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
			
			<?php
				if($activity_status == 1)
					$disabled="disabled";
				else
					$disabled="";
			?> 
			
			<!--<a href="#" class="moreLink">More</a>-->
			<form id="submit-job-report" id="submit-job-report" action="" method="post" enctype="multipart/form-data">
				<div class="iConfirmation">
				  <label for="iConfirm" class="custom-checkbox">
					<input id="iConfirm" type="checkbox" name="report_confirmation" checked <?php echo $disabled;?>>
					<span class="custom-check"></span> I confirm that the notes were followed and activity was completed.</label>
				</div>
					<div class="report_submission">
					   
						<?php
							//check for the details
							if(!empty($already_exist))
							{
								?>
								<input type="hidden" id="ival" value="<?php echo count($already_exist['day_details']); ?>">
								<?php 
								$all_activities=json_decode($already_exist['day_details']);
								$i=0;
								foreach($all_activities as $activity)
								{
									?>
									<div class="replicate">
										<div class="timeDuration"> 
											<span>Date -
												<input type="text" name="activity_date[<?php echo $i; ?>]" value="<?php echo $activity->activity_date;?>" class="input datepick repl" placeholder="" <?php echo $disabled;?>/>
											</span>
											<span class="arrived">Time Arrived -
												<input type="text" name="arrival_time[<?php echo $i; ?>]" value="<?php echo $activity->arrival_time;?>" class="input timepick repl" placeholder="" data-timepicker <?php echo $disabled;?>/>
											</span> 
											<span class="departed">Time Departed -
												<input type="text" name="depart_time[<?php echo $i; ?>]"  value="<?php echo $activity->depart_time;?>" class="input timepick repl" placeholder="" data-timepicker <?php echo $disabled;?>/>
											</span> 
										</div>
										<div class="otherDetails">
											<div class="otDetails">
												<p>Call Summary -</p>
												<textarea cols="" rows="" name="call_summary[<?php echo $i; ?>]" class="input repl" <?php echo $disabled;?>><?php echo $activity->call_summary;?></textarea>
											</div>
											<div class="otDetails">
												<p>Follow-Up Tasks -</p>
												<textarea cols="" rows="" name="follow_up_tasks[<?php echo $i; ?>]"  class="input repl" <?php echo $disabled;?>><?php echo $activity->follow_up_tasks;?></textarea>
											</div>
											<div class="otDetails">
												<p>Other Notes -</p>
												<textarea  cols="" rows="" class="input repl"  name="other_notes[<?php echo $i; ?>]" <?php echo $disabled;?>><?php echo $activity->other_notes;?></textarea>
											</div>
										</div>
									</div>
									<?php 
									$i++;
								}
							}
							else
							{
							?>
								<input type="hidden" id="ival" value="0">
								<div class="replicate">
									<div class="timeDuration"> 
										<span>Date -
											<input type="text" name="activity_date[0]" value="" class="input datepick repl" placeholder=""/>
										</span>
										<span class="arrived">Time Arrived -
											<input type="text" name="arrival_time[0]" value="" class="input timepick repl" placeholder="" data-timepicker/>
										</span> 
										<span class="departed">Time Departed -
											<input type="text" name="depart_time[0]"  value="" class="input timepick repl" placeholder="" data-timepicker/>
										</span> 
									</div>
									<div class="otherDetails">
										<div class="otDetails">
											<p>Call Summary -</p>
											<textarea cols="" rows="" name="call_summary[0]" class="input repl"></textarea>
										</div>
										<div class="otDetails">
											<p>Follow-Up Tasks -</p>
											<textarea cols="" rows="" name="follow_up_tasks[0]"  class="input repl"></textarea>
										</div>
										<div class="otDetails">
											<p>Other Notes -</p>
											<textarea  cols="" rows="" class="input repl"  name="other_notes[0]"></textarea>
										</div>
									</div>
								</div>
					<?php 	} ?>
					</div>
				
				<?php if(empty($disabled)){ ?>
				<div class="add-more-report"><a href="javascript:void(0);">Add Another</a></div>
				<?php } ?>
				
				<div class="ratingOptions"> 
					<span>Rate Your Performance 
						<?php 
							if(!empty($already_exist) && !empty($already_exist['self_performance_rating']))
								$perf_rating=$already_exist['self_performance_rating'];
							else
								$perf_rating=0;
						?>
						<span class="starRates" data-rating="<?php echo $perf_rating; ?>">
							<input type="hidden" id="rating-performance" name="rating_performance" value="<?php echo $perf_rating; ?>" />
						</span>
					</span> 
					<span>Rate Activity Success 
						<?php 
							if(!empty($already_exist) && !empty($already_exist['activity_success_rating']))
								$act_rating=$already_exist['activity_success_rating'];
							else
								$act_rating=0;
						?>
						<span class="starRates" data-rating="<?php echo $act_rating; ?>">
							<input type="hidden" id="rating-activity" name="rating_activity" value="<?php echo $act_rating; ?>" />
						</span>
					</span> 
					<span>If applicable, rate location
						<?php 
							if(!empty($already_exist) && !empty($already_exist['location_rating']))
								$loc_rating=$already_exist['location_rating'];
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
					<?php if(!empty($pricing_details['job_type']))
					{
					?>
					<div class="row">
						<?php
						$job_type=strtolower($pricing_details['job_type']);
						if($job_type == "hourly")
						{ 
						?>
							<div class="col-md-6">
								<div class="payDetails hourlyPay">
								
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										
										<tbody>
											<!--check if fixed or hourly-->
											<tr>
												<td>Contracted rate of pay</td>
												<td width="60" align="center">-</td>
												<td width="90"><input type="text" value="<?php echo $pricing_details['job_price']; ?>" class="input small" name="contracted_rate_pay" id="contracted_rate_pay" disabled placeholder=""/></td>
											</tr>
											<tr>
												<td>Hours per contract</td>
												<td width="60" align="center">-</td>
												<td width="90"><input type="text" value="<?php if(!empty($already_exist) && !empty($already_exist['hours_per_contract'])) echo $already_exist['hours_per_contract'];?>" class="input small" name="hours_per_contract" id="hours_per_contract" placeholder="" <?php echo $disabled;?>/></td>
											</tr>
											<?php if(!empty($pricing_details['overage'])){
												$overages=json_decode($pricing_details['overage']);
												
												$overage_price=$overages[2];
												?>
												<input type="hidden" data-overage_min-time="<?php echo str_replace(" min","",$overages[0]); ?>" data-overage_max-time="<?php echo str_replace(" min","",$overages[1]); ?>" <?php echo $disabled;?> id="overage_price" value="<?php echo $overage_price ?>">
											<tr>
												<td>Overage (if allowed)</td>
												<td width="60" align="center">-</td>
												<td width="90">
													<select name="overage" id="overage" placeholder="" class="input small" <?php echo $disabled;?>>
														<option value="0">Select Minutes</option>
														<option value="15" <?php  if(!empty($already_exist) && !empty($already_exist['overage']) && $already_exist['overage']== 15) echo 'selected';?>>15 min</option>
														<option value="30" <?php  if(!empty($already_exist) && !empty($already_exist['overage']) && $already_exist['overage']== 30) echo 'selected';?>>30 min</option>
														<option value="60" <?php  if(!empty($already_exist) && !empty($already_exist['overage']) && $already_exist['overage']== 60) echo 'selected';?>>60 min</option>
													</select>
													<!--<input type="text" value="<?php // if(!empty($already_exist) && !empty($already_exist['overage'])) echo $already_exist['overage'];?>" class="input small" <?php //echo $disabled;?> id="overage" name="overage" placeholder=""/>-->
												</td>
											</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td><strong>Total Hours  X  $<?php  echo $pricing_details['job_price'];?>+Overage(if any) </strong></td>
												<td width="60" align="center">=</td>
												
												<td width="90">
												
												<input type="text" value="<?php if(!empty($already_exist) && !empty($already_exist['total_activity_amount'])) echo $already_exist['total_activity_amount'];?>" class="input small total_payment_price" disabled name="total_payment_price" id="total_payment_price" placeholder=""/>
												<input type="hidden" value="<?php if(!empty($already_exist) && !empty($already_exist['total_activity_amount'])) echo $already_exist['total_activity_amount'];?>" class="input small total_payment_price"  name="total_payment_price"  placeholder=""/>
												</td>
											</tr>
										</tfoot>
									</table>
						  
								</div>
							</div>
				<?php 	} elseif($job_type == "fixed")
						{	?>
							<div class="col-md-6">
							  <div class="payDetails fixedPay">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td>Contracted rate of pay</td>
											<td width="60" align="center">-</td>
											<td width="135">
												<input type="text" value="<?php echo $pricing_details['job_price']; ?>" class="input small" name="contracted_rate_pay" id="contracted_rate_pay" placeholder=""/>
											</td>
										</tr>
										<tr>
											<td>Total events/activities within contract</td>
											<td width="60" align="center">-</td>
											<td width="135">
												<input type="text" value="<?php echo $pricing_details['total_activities']; ?>" id="total_activities" class="input small" disabled name="total_activities" placeholder=""/>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td><strong>Fixed rate <sup>1</sup>/<sub><?php echo $pricing_details['total_activities'];?></sub> (1 of <?php echo $pricing_details['total_activities'];?> events completed) X $<?php echo $pricing_details['job_price']; ?></strong></td>
											<td width="60" align="center">=</td>
											<?php
											$payment_total=(1/$pricing_details['total_activities'])*$pricing_details['job_price']; ?>
											<td width="135">
												<input type="text" value="<?php echo $payment_total; ?>" class="input small total_payment_price" id="total_payment_price" disabled name="total_payment_price" placeholder=""/>
											
												<input type="hidden" value="<?php echo $payment_total; ?>" class="input small"  name="total_payment_price"  placeholder=""/>
											</td>
										</tr>
									</tfoot>
								</table>
							  </div>
							</div>
				<?php   }?>
					</div>
			  <?php }?>
					<div class="additionaExpenses"><h3>Allowable Expenses</h3>
						<?php 
						if(!empty($already_exist) && !empty($already_exist ['expense_details']))
						{?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<?php 
								$expenses=json_decode($already_exist['expense_details']);
								foreach($expenses as $expense)
								{	
									$keys = array_keys((array)$expense);
									$attachmentval=	"";
								 ?>
									<tr>
										<td><?php echo ucfirst(str_replace("expense_","",$keys[0])); ?></td>
										<td>
											<?php 
											foreach($expense as $kk=>$vv) 
											{
												
												if (strpos($kk, 'attachment') !== false)
												{?>
													<input type="file" name="<?php echo $kk;?>" class="upload_rec" <?php echo $disabled;?> value="" style="display:none">
													<?php
													$attachmentval=	$vv;
												}
												else
												{
													?>
													<input type="text" name="<?php echo $kk;?>" id="<?php echo $kk;?>" <?php echo $disabled;?> value="<?php echo $vv;?>" class="input small expense" placeholder=""/><?php
												}
											} ?>
												
											<?php if(empty($disabled)){ ?>
											<a href="javascript:void(0);" id="<?php echo $activity_status_id;?>" <?php echo $disabled;?> class="uploadReceipt"><?php echo (!empty($attachmentval))?'Re-upload':'Upload';?> Receipt</a>
											<?php } ?>
											<span class="file_name"></span>
											<?php if (!empty($attachmentval))
											{?>
											<a target="_blank" href="<?php echo $attachmentval; ?>" id="<?php echo $activity_status_id;?>" class="viewReceipt">View Reciept</a>
											<?php } ?>
											
										</td>
									</tr>
						<?php 	} ?>
							</table>
				<?php 	} 
						elseif(!empty($pricing_details['expenses'])){ ?>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<?php 
							$expenses=json_decode($pricing_details['expenses']);
							foreach($expenses as $e)
							{							?>
								<tr>
									<td><?php echo ucfirst($e->name); ?></td>
									<td>
										<input type="text" name="expense_<?php echo $e->name;?>" id="expense_<?php echo $e->name;?>" value="" class="input small expense" placeholder=""/>
										<input type="file" name="expense_<?php echo $e->name;?>_attachment" class="upload_rec" value="" style="display:none">
										<a href="javascript:void(0);" id="<?php echo $activity_status_id;?>" class="uploadReceipt">Upload Reciept</a>
										<span class="file_name"></span>
									</td>
								</tr>
					<?php   } ?>
						</table>
						<?php } ?>
					</div>
					<div class="payStatus">
						<h3>Paid Status</h3>
						 <p>$000.00 Paid<br>
						 12-8-2016</p>
					</div>
				</div>
				<div class="actionBtns">
					<button type="submit" <?php echo $disabled; ?> id="submit_report" name="submit_report" class="btn btn-blue">Submit</button>
					<button type="submit" <?php echo $disabled; ?> id="save_report" name="save_report" class="btn btn-blue"><?php echo (!empty($already_exist))?'Update':'Save';  ?></button>
					<button type="submit" data-id="<?php echo $contract_id; ?>" id="cancel_report" name="cancel" class="btn btn-blue">Cancel</button>
				</div>
			</form>
            
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>

<script type="text/html" id="addChild">
	<div class="replicate">
		<div class="timeDuration"> 
			<span>Date -
				<input type="text" name="activity_date[{0}]" value="" class="input repl datepick" placeholder=""/>
			</span>
			<span class="arrived">Time Arrived -
				<input type="text" name="arrival_time[{0}]" value="" class="input repl timepick" placeholder="" data-timepicker/>
			</span> 
			<span class="departed">Time Departed -
				<input type="text" name="depart_time[{0}]"  value="" class="input repl timepick" placeholder="" data-timepicker/>
			</span> 
			<span>
				<a href="javascript:void(0)" class="remove_report">Remove</a>
			</span>
		</div>
		<div class="otherDetails">
			<div class="otDetails">
				<p>Call Summary -</p>
				<textarea cols="" rows="" name="call_summary[{0}]" class="input repl"></textarea>
			</div>
			<div class="otDetails">
				<p>Follow-Up Tasks -</p>
				<textarea cols="" rows="" name="follow_up_tasks[{0}]"  class="input repl"></textarea>
			</div>
			<div class="otDetails">
				<p>Other Notes -</p>
				<textarea  cols="" rows="" class="input repl"  name="other_notes[{0}]"></textarea>
			</div>
		</div>
	</div>
</script>