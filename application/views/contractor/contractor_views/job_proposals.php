<main role="main">
  <section class="contracts-wrap">
    <div class="container">
      <div class="contracts-main jobProposals">
        <h3>Job Proposals</h3>
        <div class="contracts-tabs"> 
          <!-- Nav tabs -->
          <ul class="tabs-nav" role="tablist">
            <li role="presentation" class="active"><a href="#submApp" aria-controls="sa" role="tab" data-toggle="tab">Submitted Applications</a></li>
            <li role="presentation"><a href="#activeApp" aria-controls="aa" role="tab" data-toggle="tab">Active Applications</a></li>
            <li role="presentation"><a href="#empProp" aria-controls="ep" role="tab" data-toggle="tab">Employer Proposals</a></li>
          </ul>
          
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="submApp">
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php if(!empty($submitted_jobs)) { ?>
                  <tr>
                    <th scope="col">Sent</th>
                    <th scope="col">Job</th>
                    <th scope="col">Company</th>
                    <th scope="col">&nbsp;</th>
                  </tr>
				  <?php foreach($submitted_jobs as $jobs){ ?>
                  <tr>
					<td><?php echo date('m-d-Y', strtotime($jobs['created_date'])); ?></td>
                    <td><?php echo $jobs['job_title']; ?></td>
                    <td><a href="<?php echo (isset($jobs['company_url']))? $jobs['company_url']:'javascript:void(0);';?>"><?php echo $jobs['company_name'] ;?></a></td>
                    <td class="noBorder">
					<div class="dropdown">
                        <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                          <li><a href="<?php echo BASE_URL?>contractor/job_description/<?php echo $jobs['job_slug']; ?>">View Job Post</a></li>
                          <li><a href="<?php echo BASE_URL?>contractor/view_posted_job/?applied_job=<?php echo $jobs['applied_job_id'];?>">Withdraw Application</a></li>
                          <li><a href="<?php echo BASE_URL?>contractor/view_posted_job/?applied_job=<?php echo $jobs['applied_job_id'];?>">View Submitted Application</a></li>
                          <?php if(empty($jobs['message'])) { ?>
							<li><a href="<?php echo BASE_URL?>contractor/view_posted_job/?applied_job=<?php echo $jobs['applied_job_id'];?>/#msgArea">Message</a></li>
						  <?php } ?>
                        </ul>
                      </div>
					 </td>
                  </tr>
				  <?php }
				}
				else
				{
					echo "No Submitted Applications";
				}
				?>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="activeApp">
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php if(!empty($active_jobs)) { ?>
                  <tr>
                    <th scope="col">Sent</th>
                    <th scope="col">Job</th>
                    <th scope="col">Company</th>
                    <th scope="col">Message</th>
                    <th scope="col">&nbsp;</th>
                  </tr>
				 <?php foreach($active_jobs as $jobs){ ?>
                  <tr>
                    <td><?php echo date('m-d-Y', strtotime($jobs['created_date'])); ?></td>
                    <td><?php echo $jobs['job_title']; ?></td>
                    <td><a href="<?php echo (isset($jobs['company_url']))? $jobs['company_url']:'javascript:void(0);';?>"><?php echo $jobs['company_name'] ;?></a></td>
                    <td><a href="#"><?php echo $jobs['message']; ?></a></td>
                    <td class="noBorder">
							<div class="dropdown">
								<button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
								 <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
								  <li><a href="<?php echo BASE_URL?>contractor/job_description/<?php echo $jobs['job_slug']; ?>">View Job Post</a></li>
								  <li><a href="<?php echo BASE_URL?>contractor/view_posted_job/?applied_job=<?php echo $jobs['id'];?>">Withdraw Application</a></li>
								  <li><a href="<?php echo BASE_URL?>contractor/view_posted_job/?applied_job=<?php echo $jobs['id'];?>">View Submitted Application</a></li>
								  <li><a href="<?php echo BASE_URL?>contractor/view_posted_job/?applied_job=<?php echo $jobs['id'];?>">Message</a></li>
								</ul>
						  </div>
					 </td>
                  </tr>
				 <?php }
				 } else
					{
						echo "No Active Applications";
					}?>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="empProp">
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php if(!empty($employer_proposals)){ ?>
                  <tr>
                    <th scope="col">Create date</th>
                    <th scope="col">Job</th>
                    <th scope="col">Company</th>
                    <th scope="col">Job Type</th>
                    <th scope="col">Number of events</th>
                    <th scope="col">Sent date</th>
                    <th scope="col">&nbsp;</th>
                  </tr>
				  <?php foreach($employer_proposals as $ep){?>
                  <tr>
                    <td><?php echo date('m-d-Y',$ep['job_created']); ?></td>
                    <td><?php echo $ep['job_title']; ?></td>
                    <td><a href="<?php echo (isset($ep['company_url']))? $ep['company_url']:'javascript:void(0);';?>"><?php echo $ep['company_name'] ;?></a></td>
                    <td><?php echo $ep['job_type']; ?>: $<?php echo $ep['job_price'];?></td>
                    <td><?php echo sprintf('%02d', $ep['job_activities']);?></td>
                    <td><?php echo date('m-d-Y',$ep['created_date']); ?></td>
                    <td class="noBorder"><div class="dropdown">
                        <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                        <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
							 <li><a href="<?php echo BASE_URL?>contractor/job_description/<?php echo $ep['job_slug']; ?>">View Job post</a></li>
							 <!--<li> <a href="<?php echo BASE_URL?>">View Company Invite</a>
							 <li><a href="#">Decline Company Invite</a></li>-->
							 <li><a href="<?php echo BASE_URL?>contractor/job_description/<?php echo $ep['job_slug']; ?>">Message</a></li>
						</ul>
                      </div></td>
                  </tr>
				<?php } }
				else
				{
					echo "No Employer Proposals Found";
				}
				?>
                  
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>