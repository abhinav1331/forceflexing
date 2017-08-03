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
                          
                        </div>
                        <div class="job-contracted-tabs" style="display:none;">
                            <ul class="tabs-nav" role="tablist">
                                <li role="presentation" class="active"><a href="#applied" aria-controls="past-hired" role="tab" data-toggle="tab" aria-expanded="false">Applied</a></li>
                               
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="applied">
                            
                            <div class="sort-by-cover">
                                  
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
                                              
                                            </ul>
                                        </div>
                                    </div>
                                </article> 
                                <?php endforeach ?>
                                <div class="contractor-pagination">
                                    <div class="pagination nextjobs">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>