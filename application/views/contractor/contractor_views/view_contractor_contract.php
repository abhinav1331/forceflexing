<main role="main">
  <section class="page-wrap contract accept">
    <div class="container">
      <div class="page-main">
	  <?php if(isset($error) && !empty($error))
	  {
		  echo $error;
	  }
	  else
	  {
		  ?>
        <aside class="main-page-body">
          <h2>William Bach</h2>
          <div class="relJobsListing">
            <h3>Related job Listing</h3>
            <p class="relJobsTags"><a class="jobsTag" href="#">Health care industrie</a> <a class="jobsTag" href="#">Mechanical industrie</a> <a class="jobsTag" href="#">Automobile industrie</a></p>
          </div>
          <div class="more-details">
            <div class="activitiesAssociated">
              <h3>Activities Associated with Job</h3>
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <th scope="col">Activity Name</th>
                      <th scope="col">Activity Time</th>
                      <th scope="col">Location</th>
                      <th scope="col">Contact Name </th>
                    </tr>
					<?php if(isset($activities) && !empty($activities))
					{
						foreach($activities as $activity)
						{
						?>
							<tr>
							  <td><a href="#"><?php echo $activity['activity_name'];?></a></td>
							  <td><?php echo date('m/d/y h:m A',strtotime($activity['start_datetime'])); ?></td>
							  <td><a href="#"><?php echo $activity['city']; ?></a></td>
							  <td>Becky Cobalt</td>
							</tr>
					<?php }
					}	 ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="requiredTraining">
              <h3>Required Training and Courses</h3>
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <th scope="col">Course Names </th>
                      <th scope="col">Description</th>
                      <th scope="col">Due Date </th>
                      <th scope="col">Needed Score</th>
                    </tr>
                    <tr>
                      <td>Selling BBQs</td>
                      <td><a href="#">Course 1708 on basic selling</a></td>
                      <td>11/00/00</td>
                      <td>75%</td>
                    </tr>
                    <tr>
                      <td>Selling BBQs</td>
                      <td><a href="#">Course 1708 on basic selling</a></td>
                      <td>11/00/00</td>
                      <td>80%</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="paymentInformation">
              <h3>Payment Terms</h3>
			  <p><strong>Fixed or hourly: </strong><?php echo ucfirst($jobdata['job_type']); ?></p>
			  <p><strong>Rate of Pay: </strong> $<?php echo $jobdata['job_price'];?></p>
            <?php if(isset($additional_hours) && !empty($additional_hours)){?>
				<p>
				<strong>Allowable Work Hour Overages : </strong>
				Before:  <?php echo (!empty ($additional_hours['before_time'])) ? 'Yes (' .str_replace('min','minutes',$additional_hours['before_time']).')':'No';?>
				<span class="sep">I</span> After: <?php echo (!empty ($additional_hours['after_time'])) ? 'Yes (' .str_replace('min','minutes',$additional_hours['before_time']).')':'No';?>
			  </p>
			<?php } ?>
            </div>
			
			<div class="availableExpenses">
              <h3>Available Expenses:</h3>
              <p>Mileage (Standard Reimbursement Rate)</p>
              <div class="expensesTable">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <?php if(isset($expenses) && !empty($expenses)) 
				 {
					 foreach($expenses as $expense)
					 {
					 ?>
					  <tr>
						<td><?php echo ucfirst($expense['name']); ?>:</td>
						<td>$<?php echo $expense['price']; ?></td>
					  </tr>
				 <?php 
					  }
				  } ?>
                </table>
              </div>
            </div>
            <div class="additionalInformation">
              <h3>Additional Information</h3>
              <p><?php if(isset($additionalinfo)) echo $additionalinfo; ?></p>
            </div>
		
			<?php if(isset($attachment_url) && !empty($attachment_url)) {?>
				<div class="attach-file attached">
					<label tabindex="0" for="my-file" class="input-file-trigger">
						<i class="attachment-icon">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="214 138 181 375" xml:space="preserve">
								<path d="M263.173,227.048v196.001c0,22.934,18.656,41.59,41.596,41.59c22.939,0,41.589-18.649,41.589-41.59V200.665h-0.188
								c-1.971-34.938-30.942-62.787-66.378-62.787c-35.429,0-64.388,27.849-66.358,62.787h-0.188v218.619
								c0,51.441,40.607,93.3,90.536,93.3c49.921,0,90.529-41.858,90.529-93.3V193.125h-22.866v226.158c0,38.831-30.357,70.44-67.663,70.44
								c-37.312,0-67.677-31.603-67.677-70.44V204.438c0-24.09,19.604-43.688,43.688-43.688c24.097,0,43.701,19.597,43.701,43.688v218.611
								c0,10.323-8.399,18.717-18.716,18.717c-10.323,0-18.73-8.394-18.73-18.717V227.048H263.173z"/>
							</svg>
						</i> Attach file
					</label>
					<?php
					//get the content length
						$head = array_change_key_case(get_headers($attachment_url, TRUE));
						$filesize = $head['content-length'];
					?>
				  <p class="attachedFileName"><a href="<?php echo $attachment_url; ?>"><?php echo basename($attachment_url);?> <span class="fileSize">(<?php echo number_format($filesize);?>K)</span></a></p>
				</div>
			<?php } ?>
            <div class="termsAccpt">
				<label for="terms" class="custom-checkbox">
                <input id="terms" type="checkbox" checked>
                <span class="custom-check"></span> Yes, I understand and agree to the <a href="#">ForceFlexing</a> <a href="#">Terms of Service</a>, including the <a href="#">User Agreement</a> and <a href="#">Privacy Policy</a>.</label>
            </div>
			<input type="hidden" id="contract_id" name="contract_id" value="<?=(isset($contract_id) && !empty($contract_id))?$contract_id :''; ?>">
            <div class="job-post-btns">
			  <button type="submit" id="accept_contract" class="btn btn-blue">Accept</button>
              <button type="submit" id="decline_contract" class="btn btn-gray">Decline</button>
            </div>
          </div>
        </aside>
	<?php } ?>
	  </div>
    </div>
  </section>
</main>