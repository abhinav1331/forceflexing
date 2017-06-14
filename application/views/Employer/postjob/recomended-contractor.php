<?php 
/*  echo "<pre>";
  print_r($employerId);
  echo "</pre>";*/

 ?>
<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
        <div class="recommended-freelancers">
        <div class="recommendation-hdr clearfix"><h3>Recommended freelancers for your job - <a class="jobTitle" href="#">Need a Developer</a></h3>
        <a onclick="sendInviteToUser();" href="javascript:void(0)"  class="inviteAllToApply">Invite all to apply </a></div>
        <input type="hidden" class="my_job_id" value="<?php echo $employerId[0]['id']; ?>">
        <div class="recommendatioList">
        <div class="row">
          <?php 
          $i=1;
          foreach ($recommendedData as $key => $value) {
            if($value['profile_img'] == "") {
              $url = "http://placehold.it/54x54&amp;text=No image found";
            } else {
              $url = BASE_URL."static/images/contractor/".$value['profile_img'];
            }
            ?>
              <div class="col-md-3">
              <span class="recommendedUser">
                <figure>
                  <img src="<?php  echo $url;?>" alt="Recommended">
                </figure>
                <a href="javascript:void(0)" onclick="contractorUserData(<?php echo $value['user_id']; ?>);"><?php echo $instance->userDetails($value['user_id']); ?></a>
                <label for="user<?php echo $i; ?>" class="custom-checkbox"> 
                   <input id="user<?php echo $i; ?>" class="checkboxValue" type="checkbox">
                  <input type="hidden" class="user_id_contractor" value="<?php echo $value['user_id']; ?>">
                   <span class="custom-check"></span>
                </label>
              </span>
            </div>
           <?php $i++; } ?>
      </div>
        </div>
        </div>
        <div class="ourdivcontractiorName">
          <div class="profile-details saved" style="display:none;">
            <div class="add-avatar">
              <div class="avatar-set" style="background-image:url('<?php  echo BASE_URL;?>static/images/emp-profile-img.jpg');"></div>
            </div>
            <div class="add-personal-details">
              <h2 class="pro-title">William Bach <span class="pro-price-range">$20 /hr</span></h2>
              <p class="pro-skills">Web Development, Website Design, Mobile App Development</p>
              <p class="pro-location">California, USA</p>
              <p class="pro-industries"><span class="industry-tag">Health care industrie</span> <span class="industry-tag">Mechanical industrie</span> <span class="industry-tag">Automobile industrie</span></p>
              <div>
                <div class="pro-more-content" style="display:none;"> <span class="industry-tag">care industrie</span> <span class="industry-tag">Automobile industrie</span> <span class="industry-tag">Mechanical industrie</span> </div>
                <a id="proMoreToggle" href="javascript:void(0);" class="pro-more-toggle"><span class="sr-only">View More</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a> </div>
            </div>
          </div>
          <div class="more-details" style="display:none;">
            <article class="ff-description">
              <h3>Description</h3>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took when an unknown printer too. Lorem Ipsum is simply dummy.</p>
               
            </article>
            <div class="work-history-feedback">
              <div class="hdr-work-feedback clearfix">
                <h2>Work history and feedback</h2>
                <select class="input medium inline">
                  <option>Newest first</option>
                  <option>Oldest first</option>
                </select>
              </div>
              <div class="in-progress-jobs">
                <h3 id="inProgressJobs">15 jobs in progress <i class="fa fa-angle-down"></i></h3>
                <div class="jobs-list-inProgress" style="display:none;">
                  <div class="feedbackedJob">
                    <div class="fbJobTitles">
                      <h4>Website from scratch for mobile app</h4>
                      <time>Mar 2016  -  jun 2016</time>
                      <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                    </div>
                    <div class="fbJobEarningType">
                      <h4>$450.00 earned</h4>
                      <p>Fixed job</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="feedbackedJobListing">
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="availability-dates"> <img src="<?php  echo BASE_URL;?>static/images/calendar.png" alt="calendar">
              <p class="no-availability">Contractor not available from: Oct 5 to Oct 20</p>
            </div>
             
            <article class="employment-history">
              <h2>Employment History</h2>
              <div class="emp-hitory-bar">
                <h3><span class="designation">Web Designer</span> | <span class="companyName">Lorem ipsum</span></h3>
                <p class="timePeriod">March 2009 - Present</p>
              </div>
            </article>
            <article class="educational-history">
              <h2>Education</h2>
              <div class="edu-history-bar">
                <h3><span class="courseType">Bachelor degree of graphic design</span></h3>
                <p class="timePeriod">2005  - 2008</p>
              </div>
            </article>
            <div class="pro-button-group clearfix">
            <a href="#" class="btn btn-blue">Contact</a>
            <a href="#" class="btn btn-gray">Hire now</a>
            <a href="#" class="btn btn-gray btn-save">Save</a>
            </div>
          </div>
        </div>
        </aside>
        <aside class="pro-overview">
          <div class="work-history" style="display:none;">
            <h4>Work history</h4>
            <div>
              <p class="pro-hours">560 hours worked</p>
              <p class="pro-jobs">70 jobs</p>
            </div>
          </div>
          <div class="profile-link" style="display:none;">
            <h4>Profile link</h4>
            <div>
              <input type="text" value="https://in.search.Lorem.com" class="input" readonly>
            </div>
          </div>
          <div class="last-online" style="display:none;">
            <h4>Last online</h4>
            <div>
              <p>2 days ago</p>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
<script>
/*********************************Recommendation User***********************************************/





  function proMoreToggle(event) {
    jQuery(".pro-more-content").show();
    jQuery(event).hide();
  }

  
  function contractorUserData(event) {
    var job_id = jQuery(".my_job_id").val();
    jQuery(".recommended-user-id").val(event);
    jQuery.ajax({
        type: "POST",
        url: '<?php echo BASE_URL; ?>/employer/getContractorDetails',
        data:{event:event,job_id:job_id,format:'raw'},
        success:function(resp){
          jQuery(".ourdivcontractiorName").empty().append(resp);
        }
      });
    jQuery.ajax({
        type: "POST",
        url: '<?php echo BASE_URL; ?>/employer/getContractorSidebar',
        data:{event:event,format:'raw'},
        success:function(resp){
          jQuery(".pro-overview").empty().append(resp);
        }
      });
  }

  function openMessageBox() {
    jQuery('#contact_contractor').modal('show'); 
  }

  function saveJobContractorRecommended() {
    var recommended_user_id = jQuery(".recommended-user-id").val();
    var employer_id = jQuery(".my_job_id").val();
    jQuery.ajax({
      type: "POST",
      url: '<?php echo BASE_URL; ?>/employer/saveContractorJobRecommended',
      data:{recommended_user_id:recommended_user_id,employer_id:employer_id,format:'raw'},
      success:function(resp){
            toastr.success("Job Successfully Saved");
            jQuery(".btn-save").text("Saved");
            jQuery(".btn-save").removeAttr("onclick");
            jQuery(".btn-save").attr("onclick", "alreadySavedContractor();");
      }
    });
  }

  function alreadySavedContractor() {
            toastr.warning("Job Already Saved");
  }

  function sendInviteToUser() {
    var invitedUsers = ",";
    var employer_id = jQuery(".my_job_id").val();
    jQuery.each(jQuery(".checkboxValue:checked"), function() {
     invitedUsers += (invitedUsers?',':'') + jQuery(this).siblings(".user_id_contractor").val();
    });

    var invitedUsers = invitedUsers. substring(2, invitedUsers.length);
      if (invitedUsers != "") {
        jQuery.ajax({
          type: "POST",
          url: '<?php echo BASE_URL; ?>/employer/inviteContractorRecommended',
          data:{invitedUsers:invitedUsers,employer_id,employer_id,format:'raw'},
          success:function(resp){
                toastr.success(resp);
          }
        });
      };
  }

/*********************************Recommendation User***********************************************/
</script>