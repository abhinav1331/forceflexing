<?php 
/*echo "<pre>";
    print_r($job_Array);
echo "</pre>";*/

 ?>
 <main role="main">
        <section class="page-wrap open-jobs-pending-activites contractor-master-page">
            <div class="container">
                <div class="page-main">
                    <div class="job-contracted-cover">
                        <div class="pd-heading-with-select">
                            <div class="pd-left-side">
                                <h2><?php echo $jobTitle; ?></h2> </div>
                            <!-- <div class="pd-right-side">
                                <input type="hidden" name="viewEdit" value="<?php echo BASE_URL; ?>employer/editJob/<?php echo $job_Array['job_slug']; ?>">
                                <input type="hidden" name="currentJobId" value="<?php echo $job_Array['id']; ?>">
                                <input type="hidden" name="jobSLUG" value="<?php echo $job_Array['job_slug']; ?>">
                                <select name="previous-job" class="input myActivityJob" onchange="contractorMasterJob(this);">
                                    <option value="">Activity</option>
                                    <option value="viewEdit">View/Edit Job</option>
                                    <option value="Archive">Archive</option>
                                    <option value="removeJob">Remove Job</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="job-contracted-tabs" style="display:none;">
                            <ul class="tabs-nav" role="tablist">
                                <li role="presentation" class="active"><a href="#recommended" aria-controls="hired" role="tab" data-toggle="tab" aria-expanded="true">Recommended </a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="recommended">
                            <?php if (count($userrecommendedData) != 0): ?>
                            <div class="sort-by-cover">
                                    <!-- <form>
                                        <label>Sort by:</label>
                                        <select name="previous-job" class="input">
                                            <option>View Archive</option>
                                            <option>You </option>
                                            <option>Can't See</option>
                                            <option>Me`</option>
                                        </select>
                                    </form> -->
                                </div>
                            <?php endif ?>
                                <?php 
                                    foreach ($userrecommendedData as $key => $value) {
                                       
                                        $getUserRecord = $model->get_Data_table(PREFIX.'users','id',$value['user_id']);
                                        $countryCode = $getUserRecord[0]['country'];
                                        if (strlen($value['description']) > 200)
                                        $str = substr($value['description'], 0, 200) . '...';
                                        $saved_jobs = $model->Get_column_Double('*','job_id',$job_Array['id'],'contractor_id',$value['user_id'],PREFIX.'saved_jobs');
                                        $job_invite = $model->Get_column_Double('*','job_id',$job_Array['id'],'contractor_id',$value['user_id'],PREFIX.'job_invite');
                                        
                                        ?>
                                        <article>
                                            <div class="client-info">
                                                <div class="client-left">
                                                    <a href="<?php echo BASE_URL; ?>contractor/contractor_profile/<?php echo $getUserRecord[0]['username'] ?>">
                                                        <figure class="contractor-avatar">

                                                            <?php 
                                                            if ($value['profile_img'] != "") {
                                                                $imagee = $value['profile_img'];
                                                                ?>
                                                                <img src="<?php  echo BASE_URL;?>static/images/contractor/<?php echo $imagee; ?>" alt="Contractor Picture">
                                                                <?php
                                                            } else {
                                                                $imagee = "http://placehold.it/199x199&amp;text=No image found";
                                                                ?>
                                                                    <img src="<?php  echo $imagee; ?>" alt="Contractor Picture">
                                                                <?php
                                                            }

                                                             ?>
                                                        </figure>
                                                    </a>
                                                    <div class="client-name">
                                                        <h4><?php echo $instance->userDetails($value['user_id']); ?></h4>
                                                        <p><?php if($countryCode == "US") { echo "USA"; } else { echo "Canada"; } ?></p>
                                                    </div>
                                                </div>
                                                <div class="client-right">
                                                    <p><?php echo $str; ?></p>
                                                    <p class="tag-cover"> <span class="industry-tag"><?php echo $value['speciality']; ?></span> </p>
                                                    <?php if(count($saved_jobs) == 0) { ?>
                                                        <div class="pro-action-btns"> <a href="javascript:void(0)" onclick="SaveContractorJob(this,<?php echo $value['user_id']; ?>,<?php echo $job_Array['id']; ?>);" class="btn btn-gray">Save</a> </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="client-extra-info">
                                                <ul>
                                                    <li><big>3</big> Completed tests</li>
                                                    <li><big>$<?php echo $value['hourly_wages']; ?> / hour  </big>Hours Worked 4.5 star rating</li>
                                                </ul>
                                            </div>
                                            <div class="client-action-btn">
                                                <div class="dropdown">
                                                    <input type="hidden" name="contractorNameId" value="<?php echo $value['user_id']; ?>">
                                                    <input type="hidden" name="userName" value="<?php echo $getUserRecord[0]['username']; ?>">
                                                    <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                                    <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                                        <li>
                                                            <label class="radio-custom">
                                                            <input type="radio" name="one" value="invitetojob" id="invitetojob" <?php if(count($job_invite) != 0) { echo "disabled"; } ?>> <span class="radio"></span>Invite to Job</label>
                                                        </li>
                                                        <li>
                                                            <label class="radio-custom">
                                                            <input type="radio" name="one" value="messagecontractor" id="messagecontractor"> <span class="radio"></span>Message Contratcor</label>
                                                        </li>
                                                        <!-- <li>
                                                            <label class="radio-custom">
                                                            <input type="radio" name="one" value="one1" id="duplicatePost"> <span class="radio"></span>Duplicate Post</label>
                                                        </li>
                                                        <li>
                                                            <label class="radio-custom">
                                                            <input type="radio" name="one" value="one1" id="makePrivate"> <span class="radio"></span>Make Private</label>
                                                        </li> -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </article>     
                                    <?php
                                    }
                                 ?>
                            
                            
                            
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>