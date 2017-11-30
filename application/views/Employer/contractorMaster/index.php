<?php 
/*echo "<pre>";
    print_r($applied_jobs);
echo "</pre>";*/
error_reporting(0);
ini_set('display_errors', 0);
$recoCount = count($userrecommendedData);
$appliedCount = count($applied_jobs);
$inviteCount = count($job_invite);
$hire_contractor = count($hire_contractor);
$messagedCount = count($conversation_set);
$hiredCount = count($hired);
// $userrecommendedData = array_slice( $userrecommendedData, 1, 10 );
// $applied_jobs = array_slice( $applied_jobs, 1, 10 );
// $job_invite = array_slice( $job_invite, 1, 10 );
if(!empty($hire_contractor)) {
    // $hire_contractor = array_slice( $hire_contractor, 1, 10 );
}
// $conversation_set = array_slice( $conversation_set, 1, 10 );
// $hired = array_slice( $hired, 1, 10 );

 ?>
 <main role="main">
        <section class="page-wrap open-jobs-pending-activites contractor-master-page">
            <div class="container">
                <div class="page-main">
                    <div class="job-contracted-cover">
                        <div class="pd-heading-with-select">
                            <div class="pd-left-side">
                                <h2><?php echo $jobTitle; ?></h2> </div>
                            <div class="pd-right-side">
                                <input type="hidden" name="viewEdit" value="<?php echo BASE_URL; ?>employer/editJob/<?php echo $job_Array['job_slug']; ?>">
                                <input type="hidden" name="currentJobId" value="<?php echo $job_Array['id']; ?>">
                                <input type="hidden" name="jobSLUG" value="<?php echo $job_Array['job_slug']; ?>">
                                <select name="previous-job" class="input myActivityJob" onchange="contractorMasterJob(this);">
                                    <option value="">Activity</option>
                                    <option value="viewEdit">View/Edit Job</option>
                                    <option value="Archive">Archive</option>
                                    <option value="removeJob">Remove Job</option>
                                </select>
                            </div>
                        </div>
                        <div class="job-contracted-tabs">
                            <ul class="tabs-nav" role="tablist">
                                <li role="presentation" class="active"><a href="#recommended" aria-controls="hired" role="tab" data-toggle="tab" aria-expanded="true">Recommended </a></li>
                                <li role="presentation" class=""><a href="#applied" aria-controls="past-hired" role="tab" data-toggle="tab" aria-expanded="false">Applied</a></li>
                                <li role="presentation" ><a href="#messaged" aria-controls="past-hired" role="tab" data-toggle="tab" aria-expanded="false">Messaged</a></li>
                                <li role="presentation" class=""><a href="#offered" aria-controls="past-hired" role="tab" data-toggle="tab" aria-expanded="false">Offered</a></li>
                                <li role="presentation" class=""><a href="#hired " aria-controls="past-hired" role="tab" data-toggle="tab" aria-expanded="false">Hired </a></li>
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
                                           {

                                        $str = substr($value['description'], 0, 200) . '...';
                                    } else {
                                        if($value['description'] != "") {
                                                 $str = $value['description'];
                                            } else {
                                               $str = "";
                                            }
                                    }
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
                                                    <input type="hidden" name="myId" value="<?php echo $myId; ?>">
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
                                
                                
                               <?php if ($recoCount > 10): ?>
                                <a href="<?php echo BASE_URL; ?>employer/recommend/<?php echo  $job_Array['job_slug']; ?>" class="pro-more-toggle">View all Recommended Contractors <i class="fa fa-angle-down" aria-hidden="true"></i></a>    
                               <?php endif ?>
                                
                            
                            
                            
                            
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="applied">
                            
                            <div class="sort-by-cover">
                                   <!--  <form>
                                       <label>Sort by:</label>
                                       <select name="previous-job" class="input">
                                           <option>View Archive</option>
                                           <option>You </option>
                                           <option>Can't See</option>
                                           <option>Me`</option>
                                       </select>
                                   </form> -->
                                </div>
                                <?php foreach ($applied_jobs as $key => $value): ?>
                                <?php 
                                    $getUserRecord = $model->get_Data_table(PREFIX.'users','id',$value['contractor_id']);

                                    $countryCode = $getUserRecord[0]['country'];
                                    $contractor_profile = $model->get_Data_table(PREFIX.'contractor_profile','user_id',$value['contractor_id']);
                                      /*echo "<pre>";
                                        print_r($contractor_profile);
                                    echo "</pre>";*/
                                    if (strlen($contractor_profile[0]['description']) > 200)
                                    $str = substr($contractor_profile[0]['description'], 0, 200) . '...';
                                    $saved_jobs = $model->Get_column_Double('*','job_id',$job_Array['id'],'contractor_id',$value['contractor_id'],PREFIX.'saved_jobs');
                                 ?>
                                    
                                <article>
                                    <div class="client-info">
                                        <div class="client-left">
                                            <a href="<?php echo BASE_URL; ?>contractor/contractor_profile/<?php echo $getUserRecord[0]['username'] ?>">
                                                <figure class="contractor-avatar"> 
                                                   <?php 
                                                    if ($contractor_profile[0]['profile_img'] != "") {
                                                        $imagee = $contractor_profile[0]['profile_img'];
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
                                                <h4><?php echo $instance->userDetails($value['contractor_id']); ?></h4>
                                                 <p><?php if($countryCode == "US") { echo "USA"; } else { echo "Canada"; } ?></p>
                                            </div>
                                        </div>
                                         <div class="client-extra-info">
                                            <ul>
                                                <li><big>3</big> Completed tests</li>
                                                <li><big>$<?php echo $contractor_profile[0]['hourly_wages']; ?> / hour  </big>Hours Worked 4.5 star rating</li>
                                            </ul>
                                        </div>
                                        <!-- <div class="client-right">
                                                    <p><?php echo $str; ?></p>
                                            <p class="tag-cover"> <span class="industry-tag"><?php echo $contractor_profile[0]['speciality']; ?></span> </p>
                                            <div class="pro-action-btns">
                                            <?php if(count($saved_jobs) == 0) { ?>
                                                 <a href="javascript:void(0)" onclick="SaveContractorJob(this,<?php echo $value['user_id']; ?>,<?php echo $job_Array['id']; ?>);" class="btn btn-gray">Save</a>
                                                <?php } ?>
                                             <a href="#" class="btn btn-gray btn-gray-transparent">Archive</a> </div>
                                        </div> -->
                                    </div>
                                    <div class="client-extra-info">
                                        <div class="coverletter" style="display:none;">
                                            <p><?php echo $value['cover_letter']; ?></p>
                                        </div>
                                        <ul>
                                            <li><big><i class="fa fa-check-square" aria-hidden="true"></i></big>3 Completed tests</li>
                                            <li><big><i class="fa fa-file-text-o" aria-hidden="true"></i></big><a href="javascript:void(0)" onclick="viewCoverLetter(this)">Cover Letter</a></li>
                                                    <li><big>$<?php echo $contractor_profile[0]['hourly_wages']; ?> / hour  </big>Hours Worked 4.5 star rating</li>
                                        </ul>
                                    </div>
                                    <div class="client-action-btn">
                                        <div class="dropdown">
                                             <input type="hidden" name="contractorNameId" value="<?php echo $value['contractor_id']; ?>">
                                              <input type="hidden" name="myId" value="<?php echo $myId; ?>">
                                             <input type="hidden" name="userName" value="<?php echo $getUserRecord[0]['username']; ?>">
                                             <input type="hidden" name="appliedId" value="<?php echo $value['id']; ?>">
                                            <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                            <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="two" value="message" id="viewPost"> <span class="radio"></span>Message</label>
                                                </li>
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="two" value="Decline" id="removePost"> <span class="radio"></span>Decline</label>
                                                </li>
                                               <li>
                                                   <label class="radio-custom">
                                                       <input type="radio" name="two" value="CreateCon" id="createContract"> <span class="radio"></span>Create Contract</label>
                                               </li>
                                              <!--   <li>
                                                   <label class="radio-custom">
                                                       <input type="radio" name="one" value="one1" id="makePrivate"> <span class="radio"></span>Make Private</label>
                                               </li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </article> 
                                <?php endforeach ?>

                                <?php if ($appliedCount > 10): ?>
                                    <a href="<?php echo BASE_URL; ?>employer/applied/<?php echo  $job_Array['job_slug']; ?>" class="pro-more-toggle">View all Applied Contractors <i class="fa fa-angle-down" aria-hidden="true"></i></a> 
                                <?php endif ?>
                                
                            
                            
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="messaged">
                                
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
                                <?php foreach ($conversation_set as $key => $value): ?>
                                <?php 
                                    $getUserRecord = $model->get_Data_table(PREFIX.'users','id',$value['conv_to']);
                                    $countryCode = $getUserRecord[0]['country'];
                                    $contractor_profile = $model->get_Data_table(PREFIX.'contractor_profile','user_id',$value['conv_to']);
                                    if (strlen($contractor_profile[0]['description']) > 200)
                                    $str = substr($contractor_profile[0]['description'], 0, 200) . '...';
                                    $saved_jobs = $model->Get_column_Double('*','job_id',$job_Array['id'],'contractor_id',$value['conv_to'],PREFIX.'saved_jobs');
                                    
                                 ?>

                                    
                               <article>
                                    <div class="client-info">
                                        <div class="client-left">
                                            <a href="<?php echo BASE_URL; ?>contractor/contractor_profile/<?php echo $getUserRecord[0]['username'] ?>">
                                                <figure class="contractor-avatar"> 
                                                   <?php 
                                                    if ($contractor_profile[0]['profile_img'] != "") {
                                                        $imagee = $contractor_profile[0]['profile_img'];
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
                                                <h4><?php echo $instance->userDetails($value['conv_to']); ?></h4>
                                                 <p><?php if($countryCode == "US") { echo "USA"; } else { echo "Canada"; } ?></p>
                                            </div>
                                        </div>
                                        <div class="client-right">
                                                    <p><?php echo $str; ?></p>
                                            <p class="tag-cover"> <span class="industry-tag"><?php echo $contractor_profile[0]['speciality']; ?></span> </p>
                                            <div class="pro-action-btns">
                                            <?php if(count($saved_jobs) == 0) { ?>
                                                 <a href="javascript:void(0)" onclick="SaveContractorJob(this,<?php echo $value['conv_to']; ?>,<?php echo $job_Array['id']; ?>);" class="btn btn-gray">Save</a>
                                                <?php } ?>
                                             <a href="#" class="btn btn-gray btn-gray-transparent">Archive</a> </div>
                                        </div>
                                    </div>
                                    <div class="client-extra-info">
                                        <ul>
                                            <li><big><i class="fa fa-check-square" aria-hidden="true"></i></big>3 Completed tests</li>
                                            <li><big><i class="fa fa-file-text-o" aria-hidden="true"></i></big>Cover Letter</li>
                                                    <li><big>$<?php echo $contractor_profile[0]['hourly_wages']; ?> / hour  </big>Hours Worked 4.5 star rating</li>
                                        </ul>
                                    </div>
                                    <div class="client-action-btn">
                                        <div class="dropdown">

                                            <input type="hidden" name="contractorNameId" value="<?php echo $getUserRecord[0]['id']; ?>">
                                            <input type="hidden" name="myId" value="<?php echo $myId; ?>">
                                            <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                            <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="three" value="Message" id="viewPost"> <span class="radio"></span>Message</label>
                                                </li>
                                               <!--  <li>
                                                   <label class="radio-custom">
                                                       <input type="radio" name="one" value="one1" id="editPost"> <span class="radio"></span>Edit Post</label>
                                               </li>
                                               <li>
                                                   <label class="radio-custom">
                                                       <input type="radio" name="one" value="one1" id="removePost"> <span class="radio"></span>Remove Post</label>
                                               </li>
                                               <li>
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
                                
                               
                                <?php endforeach ?>
                               

                                <?php if ($messagedCount > 10): ?>
                                    <a href="<?php echo BASE_URL; ?>employer/message/<?php echo  $job_Array['job_slug']; ?>" class="pro-more-toggle">View all Applied Contractors <i class="fa fa-angle-down" aria-hidden="true"></i></a> 
                                <?php endif ?>
                            
                            
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="offered">
                            
                            <div class="sort-by-cover">
                                   <!--  <form>
                                       <label>Sort by:</label>
                                       <select name="previous-job" class="input">
                                           <option>View Archive</option>
                                           <option>You </option>
                                           <option>Can't See</option>
                                           <option>Me`</option>
                                       </select>
                                   </form> -->
                                </div>
                                <?php foreach ($job_invite as $key => $value): ?>
                                <?php 
                                    $getUserRecord = $model->get_Data_table(PREFIX.'users','id',$value['contractor_id']);
                                    $countryCode = $getUserRecord[0]['country'];
                                    $contractor_profile = $model->get_Data_table(PREFIX.'contractor_profile','user_id',$value['contractor_id']);

                                    if (strlen($contractor_profile[0]['description']) > 200)
                                    $str = substr($contractor_profile[0]['description'], 0, 200) . '...';
                                    $saved_jobs = $model->Get_column_Double('*','job_id',$job_Array['id'],'contractor_id',$value['contractor_id'],PREFIX.'saved_jobs');
                                 ?>
                                    
                                <article>
                                    <div class="client-info">
                                        <div class="client-left">
                                            <a href="<?php echo BASE_URL; ?>contractor/contractor_profile/<?php echo $getUserRecord[0]['username'] ?>">
                                                <figure class="contractor-avatar">
                                                    <?php 
                                                if ($contractor_profile[0]['profile_img'] != "") {
                                                    $imagee = $contractor_profile[0]['profile_img'];
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
                                                 <h4><?php echo $instance->userDetails($value['contractor_id']); ?></h4>
                                                 <p><?php if($countryCode == "US") { echo "USA"; } else { echo "Canada"; } ?></p>
                                            </div>
                                        </div>
                                        <div class="client-right">
                                            <p><?php echo $str; ?></p>
                                            <p class="tag-cover"> <span class="industry-tag"><?php echo $contractor_profile[0]['speciality']; ?></span> </p>
                                             <?php if(count($saved_jobs) == 0) { ?>
                                                <div class="pro-action-btns"> <a href="javascript:void(0)" onclick="SaveContractorJob(this,<?php echo $value['user_id']; ?>,<?php echo $job_Array['id']; ?>);" class="btn btn-gray">Save</a> </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="client-extra-info">
                                        <ul>
                                            <li><big><i class="fa fa-check-square" aria-hidden="true"></i></big>3 Completed tests</li>
                                            <li><big>$<?php echo $contractor_profile[0]['hourly_wages']; ?> / hour  </big>Hours Worked 4.5 star rating</li>
                                        </ul>
                                    </div>
                                    <div class="client-action-btn">
                                        <div class="dropdown">
                                             <input type="hidden" name="inviteID" value="<?php echo $value['id']; ?>">
                                            <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                            <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="four" value="message" id="message"> <span class="radio"></span>Message</label>
                                                </li>
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="four" value="recind" id="recind"> <span class="radio"></span>Rescind</label>
                                                </li>
                                                <!-- <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="one" value="one1" id="removePost"> <span class="radio"></span>Remove Post</label>
                                                </li>
                                                <li>
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
                                <?php endforeach ?>
                               
                                <?php if ($inviteCount > 10): ?>
                                    <a href="<?php echo BASE_URL; ?>employer/offered/<?php echo  $job_Array['job_slug']; ?>" class="pro-more-toggle">View all Applied Contractors <i class="fa fa-angle-down" aria-hidden="true"></i></a> 
                                <?php endif ?>
                            
                            
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="hired">
                            
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
                                <?php
                                if(empty($hire_contractor)) {
                                    $hire_contractor = array();
                                }
                                 foreach ($hire_contractor as $key => $value): ?>
                                                                <?php 
                                    $getUserRecord = $model->get_Data_table(PREFIX.'users','id',$value['contractor_id']);
                                    $countryCode = $getUserRecord[0]['country'];
                                    $contractor_profile = $model->get_Data_table(PREFIX.'contractor_profile','user_id',$value['contractor_id']);
                                    if (strlen($contractor_profile[0]['description']) > 200)
                                    $str = substr($contractor_profile[0]['description'], 0, 200) . '...';
                                    $saved_jobs = $model->Get_column_Double('*','job_id',$job_Array['id'],'contractor_id',$value['contractor_id'],PREFIX.'saved_jobs');
                                 ?>
                                    
                                <article>
                                    <div class="client-info">
                                        <div class="client-left">
                                             <a href="<?php echo BASE_URL; ?>contractor/contractor_profile/<?php echo $getUserRecord[0]['username'] ?>">
                                                <figure class="contractor-avatar">
                                                    <?php 
                                                if ($contractor_profile[0]['profile_img'] != "") {
                                                    $imagee = $contractor_profile[0]['profile_img'];
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
                                                 <h4><?php echo $instance->userDetails($value['contractor_id']); ?></h4>
                                                 <p><?php if($countryCode == "US") { echo "USA"; } else { echo "Canada"; } ?></p>
                                            </div>
                                        </div>
                                        <div class="client-right">
                                            <p><?php echo $str; ?></p>
                                            <p class="tag-cover"> <span class="industry-tag"><?php echo $contractor_profile[0]['speciality']; ?></span> </p>
                                             <?php if(count($saved_jobs) == 0) { ?>
                                                <div class="pro-action-btns"> <a href="javascript:void(0)" onclick="SaveContractorJob(this,<?php echo $value['user_id']; ?>,<?php echo $job_Array['id']; ?>);" class="btn btn-gray">Save</a> </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="client-extra-info">
                                        <ul>
                                            <li><big><i class="fa fa-check-square" aria-hidden="true"></i></big>3 Completed tests</li>
                                            <li><big><i class="fa fa-file-text-o" aria-hidden="true"></i></big>Cover Letter</li>
                                            <li><big>$<?php echo $contractor_profile[0]['hourly_wages']; ?> / hour  </big>Hours Worked 4.5 star rating</li>
                                        </ul>
                                    </div>
                                    <div class="client-action-btn">
                                        <div class="dropdown">
                                            <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                            <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="five" value="message" id="message"> <span class="radio"></span>Message</label>
                                                </li>
                                                <li>
                                                    <label class="radio-custom">
                                                        <input type="radio" name="five" value="viewContract" id="viewContract"> <span class="radio"></span>View Contract</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </article> 
                                <?php endforeach ?>
                                <a href="javascript:void(0);" class="pro-more-toggle">View all Saved Contractors <i class="fa fa-angle-down" aria-hidden="true"></i></a> 
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>