<?php 
/*echo "<pre>";
print_r($states);
echo "</pre>";*/
 ?>
<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <section class="post-job-wrapper">
          <h1>Post a Job</h1>
          <form action="" method="post" name="post_job" id="post_job"  enctype="multipart/form-data">
            <?php if (count($url) != 0) {
              ?>
              <div class="emp-job-cat">
                <div class="row">
                  <div class="col-sm-6">
                    <h3>Previous job</h3>
                    <select name="previous-job" class="input" onchange="onchangeSelectPre(this);">
                      <option value="">Select Old Jobs</option>
                     <?php 
                     foreach($url as $ur) {
                      ?>
                      <option value="<?php echo $ur['id'] ?>"><?php echo $ur['job_title']; ?></option>
                      <?php
                     }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <?php
            } ?>
           
            <div class="emp-job-description">
              <h2>Describe the job</h2>
              <h3>Name your job posting</h3>
              <input name="jp_title" id="jp_title" placeholder="Please type name of job" type="text" class="input">
              <h3>Description of job</h3>
              <div class="char-left">5000 characters left</div>
              <textarea id="jp_desc" name="jp_desc" cols="" rows="" class="input resizable large" placeholder="Type message"></textarea>
            </div>
			
            <div class="attach-file">
              <input class="input-file" id="my-file" type="file" name="fileUpload">
              <label tabindex="0" for="my-file" class="input-file-trigger"><i class="attachment-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="214 138 181 375" xml:space="preserve">
                <path d="M263.173,227.048v196.001c0,22.934,18.656,41.59,41.596,41.59c22.939,0,41.589-18.649,41.589-41.59V200.665h-0.188
	c-1.971-34.938-30.942-62.787-66.378-62.787c-35.429,0-64.388,27.849-66.358,62.787h-0.188v218.619
	c0,51.441,40.607,93.3,90.536,93.3c49.921,0,90.529-41.858,90.529-93.3V193.125h-22.866v226.158c0,38.831-30.357,70.44-67.663,70.44
	c-37.312,0-67.677-31.603-67.677-70.44V204.438c0-24.09,19.604-43.688,43.688-43.688c24.097,0,43.701,19.597,43.701,43.688v218.611
	c0,10.323-8.399,18.717-18.716,18.717c-10.323,0-18.73-8.394-18.73-18.717V227.048H263.173z"/>
                </svg></i> Attach file</label>
              <code class="file-return"></code>
              <p>The file can be up to 5 mb in size.</p>
            </div>
			
            <div class="emp-hires">
				<h3>How many employees do you need to hire for this job?</h3>
				<label class="radio-custom">
					<input type="radio" name="jp_reqemp" id="jp_reqemp_one" value="one" id="empRequired_1" checked>
					<span class="radio"></span>One
				</label>
				<label class="radio-custom">
					<input type="radio" name="jp_reqemp" id="jp_reqemp_mul" value="multiple" id="empRequired_2">
					<span class="radio"></span>Multiple
				</label>
        <div class="col-sm-6 selectNumber">
           <select name="jp_mul_emp" id="jp_mul_emp" class="input" min="0">
            <option value="">Select Number</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>
        </div>
       
				<!-- <input type="number" id="jp_mul_emp" name="jp_mul_emp" class="input inline small">  -->
			</div>
			
            <div class="emp-job-activity">
              <h3>Does this Job include one or multiple Activities: </h3>
              <label class="radio-custom">
                <input type="radio" id="jp_activities_one" name="jp_activities" value="one"  checked>
                <span class="radio"></span>One
			</label>
            <label class="radio-custom">
                <input type="radio" id="jp_activities_mul" name="jp_activities" value="multiple">
                <span class="radio"></span>Multiple
			</label>
          <div class="buttons-add-rmove">
          <input onclick="addActivity();" style="display:none !important;" type="button" value="+" id="btn-add-activity" name="btn-add-activity" class="input inline small">
          </div>
              <div class="emp-job-activity-details one">
                <div class="activityReunion" id="activityReunion1">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <th scope="row">1.) Activites Name:</th>
                      <td>
  						<div class="row">
  							<div class="col-md-7">
  							  <input name="jp_activity_name[]" id="jp_activity_name" type="text" class="input small half-width">
  							</div>
  						</div>
  					</td>
                    </tr>
                    <tr>
                      <th scope="row">Select:</th>
                      <td>
  						<label class="radio-custom">
                          <input type="radio" name="jp_start_stop_time1[]" value="fixed" id="jp_start_stop_time_fix" checked onchange="bestWork(this);">
                          <span class="radio"></span>fixed start and stop time <a href="javascript:void(0);" class="calendar-icon">date</a></label>
  						
  						<span class="sep">or</span>
  						
  						<label class="radio-custom">
                          <input type="radio" name="jp_start_stop_time1[]" value="flexible" id="jp_start_stop_time_flex" onchange="bestWork(this);">
                          <span class="radio"></span>flexible start/stop <a href="javascript:void(0);" class="calendar-icon">date</a></label>
  					</td>
                    </tr>
                    <tr>
                      <th scope="row" class="fix1">Fixed:</th>
                      <th scope="row" class="flex1" style="display:none;">Flexibility Between:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input type="text" name="jp_act_start_date[]" id="jp_act_start_date" class="input small calendar-icon jp_act_start_date" placeholder="start date">
                              </div>
                              <div class="col-md-6">
                                <input type="text"  name="jp_act_start_time[]" id="jp_act_start_time" class="input small watch-icon jp_act_start_time" placeholder="start time">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input type="text" name="jp_act_end_date[]" id="jp_act_end_date" class="input small calendar-icon jp_act_start_date" placeholder="finish date">
                              </div>
                              <div class="col-md-6">
                                <input type="text"  name="jp_act_end_time[]" id="jp_act_end_time" class="input small watch-icon jp_act_start_time" placeholder="finish time">
                              </div>
                            </div>
                          </div>
                        </div>
  					</td>
                    </tr>
                    <tr>
                      <th scope="row">Enter address:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <div class="row">
  							<div class="col-md-6">
                                
                                <select onchange="onchangeState(this);" data-attribute='activityReunion1' name="jp_act_state[]" id="sel12 stateId" class="input small states">
                                   <option value="">Select State</option>
                                   <?php 
                                   foreach($states as $state) {
                                    ?>
                                    <option value="<?php echo $state['id']; ?>"><?php echo $state['name']; ?></option>
                                    <?php
                                   }
                                    ?>
                                </select>
                              </div>
  							               <div class="col-md-6">
                                <select onchange="ourChange(this)" name="jp_act_city[]" id="sel13 cityId" class="input small cities">
                                   <option value="">Select City</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
  				                      <input type="text" class="input small" name="jp_act_street[]" id="jp_act_street" placeholder="street" >
                                <!--<select name="" class="input small">
                                  <option>street</option>
                                </select>-->
                              </div>
                              <div class="col-md-6">
                                <input type="text" class="input small" name="jp_act_zip[]" id="jp_act_zip"  placeholder="zip" onkeyup="isValidPostalCode(this)">
                                <div class="messahe-zip"></div>
                              </div>
                            </div>
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Name contact:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <input name="jp_act_cont_fname[]" id="jp_act_cont_fname" type="text" placeholder="first name" class="input small">
                          </div>
                          <div class="col-md-6">
                            <input name="jp_act_cont_lname[]" id="jp_act_cont_lname" type="text" placeholder="last name" class="input small">
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Contact:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <input name="jp_act_cont_phne[]" id="jp_act_cont_phne" type="text" placeholder="phone" class="input small">
                          </div>
                          <div class="col-md-6">
                            <input name="jp_act_cont_email[]" id="jp_act_cont_email" type="text" placeholder="email" class="input small">
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Notes/tasks:</th>
                      <td><textarea name="jp_act_notes[]" id="jp_act_notes" cols="" rows="" class="input small" placeholder="text here"></textarea></td>
                    </tr>
                  </table>
                </div>
                <div class="multiple-selected-option">
                  <h3>Activites completed by contractor</h3>
                  <label for="actCondition1" class="radio-custom">
                    <input id="actCondition1" name="jp_actvty_comp" type="radio" value="yes">
                   <span class="radio"></span> Yes, all activities must be completed by the applying contractor</label>
                  <label for="actCondition2" class="radio-custom">
                    <input id="actCondition2" name="jp_actvty_comp" type="radio" value="no">
                    <span class="radio"></span> No, the contractor can select a minimum of
                    <input type="number" name="jp_num_of_actvty" id="jp_num_of_actvty" class="input inline x-small" min="0" disabled>
                  <!--   <select name="jp_num_of_actvty" id="jp_num_of_actvty" class="input inline x-small">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                  </select> -->
                    Activity(s) </label>
                </div>
              </div>
            </div>
            <div class="emp-distance-area">
              <h3>Distance of the employee from the Job location</h3>
              <div class="distance-table">
                <label class="radio-custom">
                  <input type="radio" name="jp_empDistance" value="5" id="jp_empDistance_5" checked>
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 18 200 164" xml:space="preserve">
                  <g>
                    <path d="M100,18c-45.216,0-82,36.784-82,82c0,45.218,36.784,82,82,82c45.218,0,82-36.782,82-82C182,54.784,145.218,18,100,18
		L100,18z M100,176.528c-42.203,0-76.526-34.327-76.526-76.528c0-42.203,34.324-76.526,76.526-76.526
		c42.201,0,76.528,34.324,76.528,76.526C176.528,142.201,142.201,176.528,100,176.528L100,176.528z M100,176.528"/>
                    <path d="M102.736,89.121V61.402c0-1.506-1.229-2.738-2.736-2.738c-1.506,0-2.736,1.231-2.736,2.738v27.719
		c-3.817,1.001-6.82,3.991-7.808,7.811H69.931c-1.508,0-2.738,1.226-2.738,2.732c0,1.51,1.229,2.738,2.738,2.738h19.526
		c1.216,4.698,5.46,8.194,10.543,8.194c6.032,0,10.931-4.897,10.931-10.932C110.931,94.582,107.434,90.351,102.736,89.121
		L102.736,89.121z M100,105.139c-3.016,0-5.474-2.456-5.474-5.474c0-3.013,2.458-5.455,5.474-5.455c3.017,0,5.472,2.442,5.472,5.455
		C105.472,102.683,103.017,105.139,100,105.139L100,105.139z M100,105.139"/>
                    <path d="M100,44.998c1.508,0,2.736-1.213,2.736-2.733v-2.723c0-1.524-1.229-2.737-2.736-2.737c-1.506,0-2.736,1.213-2.736,2.737
		v2.723C97.264,43.785,98.494,44.998,100,44.998L100,44.998z M100,44.998"/>
                    <path d="M100,154.333c-1.506,0-2.736,1.228-2.736,2.736v2.735c0,1.509,1.229,2.736,2.736,2.736c1.508,0,2.736-1.228,2.736-2.736
		v-2.735C102.736,155.561,101.508,154.333,100,154.333L100,154.333z M100,154.333"/>
                    <path d="M160.139,96.932h-2.736c-1.508,0-2.735,1.226-2.735,2.732c0,1.51,1.228,2.738,2.735,2.738h2.736
		c1.508,0,2.723-1.228,2.723-2.738C162.861,98.158,161.646,96.932,160.139,96.932L160.139,96.932z M160.139,96.932"/>
                    <path d="M42.597,96.932H39.86c-1.507,0-2.72,1.226-2.72,2.732c0,1.51,1.213,2.738,2.72,2.738h2.737c1.51,0,2.736-1.228,2.736-2.738
		C45.333,98.158,44.107,96.932,42.597,96.932L42.597,96.932z M42.597,96.932"/>
                    <path d="M140.586,55.211l-1.938,1.934c-1.064,1.069-1.064,2.801,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.401-0.267,1.936-0.801l1.936-1.937c1.067-1.066,1.067-2.801,0-3.867C143.389,54.142,141.654,54.142,140.586,55.211
		L140.586,55.211z M140.586,55.211"/>
                    <path d="M57.48,138.331l-1.938,1.922c-1.066,1.067-1.066,2.803,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.4-0.267,1.934-0.801l1.937-1.936c1.066-1.067,1.066-2.789,0-3.856C60.281,137.25,58.546,137.263,57.48,138.331
		L57.48,138.331z M57.48,138.331"/>
                    <path d="M142.521,138.331c-1.067-1.081-2.803-1.081-3.874,0c-1.064,1.067-1.064,2.789,0,3.856l1.938,1.936
		c0.534,0.534,1.229,0.801,1.936,0.801c0.694,0,1.401-0.267,1.936-0.801c1.067-1.067,1.067-2.803,0-3.87L142.521,138.331z
		 M142.521,138.331"/>
                    <path d="M59.414,55.211c-1.066-1.069-2.803-1.069-3.872,0c-1.066,1.066-1.066,2.801,0,3.867l1.938,1.937
		c0.532,0.534,1.227,0.801,1.934,0.801c0.694,0,1.401-0.267,1.937-0.801c1.066-1.069,1.066-2.801,0-3.87L59.414,55.211z
		 M59.414,55.211"/>
                  </g>
                  </svg> <span class="label-text">5 miles</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_empDistance" value="30" id="jp_empDistance_30">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 18 200 164" xml:space="preserve">
                  <g>
                    <path d="M100,18c-45.216,0-82,36.784-82,82c0,45.218,36.784,82,82,82c45.218,0,82-36.782,82-82C182,54.784,145.218,18,100,18
		L100,18z M100,176.528c-42.203,0-76.526-34.327-76.526-76.528c0-42.203,34.324-76.526,76.526-76.526
		c42.201,0,76.528,34.324,76.528,76.526C176.528,142.201,142.201,176.528,100,176.528L100,176.528z M100,176.528"/>
                    <path d="M102.736,89.121V61.402c0-1.506-1.229-2.738-2.736-2.738c-1.506,0-2.736,1.231-2.736,2.738v27.719
		c-3.817,1.001-6.82,3.991-7.808,7.811H69.931c-1.508,0-2.738,1.226-2.738,2.732c0,1.51,1.229,2.738,2.738,2.738h19.526
		c1.216,4.698,5.46,8.194,10.543,8.194c6.032,0,10.931-4.897,10.931-10.932C110.931,94.582,107.434,90.351,102.736,89.121
		L102.736,89.121z M100,105.139c-3.016,0-5.474-2.456-5.474-5.474c0-3.013,2.458-5.455,5.474-5.455c3.017,0,5.472,2.442,5.472,5.455
		C105.472,102.683,103.017,105.139,100,105.139L100,105.139z M100,105.139"/>
                    <path d="M100,44.998c1.508,0,2.736-1.213,2.736-2.733v-2.723c0-1.524-1.229-2.737-2.736-2.737c-1.506,0-2.736,1.213-2.736,2.737
		v2.723C97.264,43.785,98.494,44.998,100,44.998L100,44.998z M100,44.998"/>
                    <path d="M100,154.333c-1.506,0-2.736,1.228-2.736,2.736v2.735c0,1.509,1.229,2.736,2.736,2.736c1.508,0,2.736-1.228,2.736-2.736
		v-2.735C102.736,155.561,101.508,154.333,100,154.333L100,154.333z M100,154.333"/>
                    <path d="M160.139,96.932h-2.736c-1.508,0-2.735,1.226-2.735,2.732c0,1.51,1.228,2.738,2.735,2.738h2.736
		c1.508,0,2.723-1.228,2.723-2.738C162.861,98.158,161.646,96.932,160.139,96.932L160.139,96.932z M160.139,96.932"/>
                    <path d="M42.597,96.932H39.86c-1.507,0-2.72,1.226-2.72,2.732c0,1.51,1.213,2.738,2.72,2.738h2.737c1.51,0,2.736-1.228,2.736-2.738
		C45.333,98.158,44.107,96.932,42.597,96.932L42.597,96.932z M42.597,96.932"/>
                    <path d="M140.586,55.211l-1.938,1.934c-1.064,1.069-1.064,2.801,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.401-0.267,1.936-0.801l1.936-1.937c1.067-1.066,1.067-2.801,0-3.867C143.389,54.142,141.654,54.142,140.586,55.211
		L140.586,55.211z M140.586,55.211"/>
                    <path d="M57.48,138.331l-1.938,1.922c-1.066,1.067-1.066,2.803,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.4-0.267,1.934-0.801l1.937-1.936c1.066-1.067,1.066-2.789,0-3.856C60.281,137.25,58.546,137.263,57.48,138.331
		L57.48,138.331z M57.48,138.331"/>
                    <path d="M142.521,138.331c-1.067-1.081-2.803-1.081-3.874,0c-1.064,1.067-1.064,2.789,0,3.856l1.938,1.936
		c0.534,0.534,1.229,0.801,1.936,0.801c0.694,0,1.401-0.267,1.936-0.801c1.067-1.067,1.067-2.803,0-3.87L142.521,138.331z
		 M142.521,138.331"/>
                    <path d="M59.414,55.211c-1.066-1.069-2.803-1.069-3.872,0c-1.066,1.066-1.066,2.801,0,3.867l1.938,1.937
		c0.532,0.534,1.227,0.801,1.934,0.801c0.694,0,1.401-0.267,1.937-0.801c1.066-1.069,1.066-2.801,0-3.87L59.414,55.211z
		 M59.414,55.211"/>
                  </g>
                  </svg> <span class="label-text">30 miles</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_empDistance" value="60" id="jp_empDistance_60">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 18 200 164" xml:space="preserve">
                  <g>
                    <path d="M100,18c-45.216,0-82,36.784-82,82c0,45.218,36.784,82,82,82c45.218,0,82-36.782,82-82C182,54.784,145.218,18,100,18
		L100,18z M100,176.528c-42.203,0-76.526-34.327-76.526-76.528c0-42.203,34.324-76.526,76.526-76.526
		c42.201,0,76.528,34.324,76.528,76.526C176.528,142.201,142.201,176.528,100,176.528L100,176.528z M100,176.528"/>
                    <path d="M102.736,89.121V61.402c0-1.506-1.229-2.738-2.736-2.738c-1.506,0-2.736,1.231-2.736,2.738v27.719
		c-3.817,1.001-6.82,3.991-7.808,7.811H69.931c-1.508,0-2.738,1.226-2.738,2.732c0,1.51,1.229,2.738,2.738,2.738h19.526
		c1.216,4.698,5.46,8.194,10.543,8.194c6.032,0,10.931-4.897,10.931-10.932C110.931,94.582,107.434,90.351,102.736,89.121
		L102.736,89.121z M100,105.139c-3.016,0-5.474-2.456-5.474-5.474c0-3.013,2.458-5.455,5.474-5.455c3.017,0,5.472,2.442,5.472,5.455
		C105.472,102.683,103.017,105.139,100,105.139L100,105.139z M100,105.139"/>
                    <path d="M100,44.998c1.508,0,2.736-1.213,2.736-2.733v-2.723c0-1.524-1.229-2.737-2.736-2.737c-1.506,0-2.736,1.213-2.736,2.737
		v2.723C97.264,43.785,98.494,44.998,100,44.998L100,44.998z M100,44.998"/>
                    <path d="M100,154.333c-1.506,0-2.736,1.228-2.736,2.736v2.735c0,1.509,1.229,2.736,2.736,2.736c1.508,0,2.736-1.228,2.736-2.736
		v-2.735C102.736,155.561,101.508,154.333,100,154.333L100,154.333z M100,154.333"/>
                    <path d="M160.139,96.932h-2.736c-1.508,0-2.735,1.226-2.735,2.732c0,1.51,1.228,2.738,2.735,2.738h2.736
		c1.508,0,2.723-1.228,2.723-2.738C162.861,98.158,161.646,96.932,160.139,96.932L160.139,96.932z M160.139,96.932"/>
                    <path d="M42.597,96.932H39.86c-1.507,0-2.72,1.226-2.72,2.732c0,1.51,1.213,2.738,2.72,2.738h2.737c1.51,0,2.736-1.228,2.736-2.738
		C45.333,98.158,44.107,96.932,42.597,96.932L42.597,96.932z M42.597,96.932"/>
                    <path d="M140.586,55.211l-1.938,1.934c-1.064,1.069-1.064,2.801,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.401-0.267,1.936-0.801l1.936-1.937c1.067-1.066,1.067-2.801,0-3.867C143.389,54.142,141.654,54.142,140.586,55.211
		L140.586,55.211z M140.586,55.211"/>
                    <path d="M57.48,138.331l-1.938,1.922c-1.066,1.067-1.066,2.803,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.4-0.267,1.934-0.801l1.937-1.936c1.066-1.067,1.066-2.789,0-3.856C60.281,137.25,58.546,137.263,57.48,138.331
		L57.48,138.331z M57.48,138.331"/>
                    <path d="M142.521,138.331c-1.067-1.081-2.803-1.081-3.874,0c-1.064,1.067-1.064,2.789,0,3.856l1.938,1.936
		c0.534,0.534,1.229,0.801,1.936,0.801c0.694,0,1.401-0.267,1.936-0.801c1.067-1.067,1.067-2.803,0-3.87L142.521,138.331z
		 M142.521,138.331"/>
                    <path d="M59.414,55.211c-1.066-1.069-2.803-1.069-3.872,0c-1.066,1.066-1.066,2.801,0,3.867l1.938,1.937
		c0.532,0.534,1.227,0.801,1.934,0.801c0.694,0,1.401-0.267,1.937-0.801c1.066-1.069,1.066-2.801,0-3.87L59.414,55.211z
		 M59.414,55.211"/>
                  </g>
                  </svg> <span class="label-text">60 miles</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_empDistance" value="120" id="jp_empDistance_120">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 18 200 164" xml:space="preserve">
                  <g>
                    <path d="M100,18c-45.216,0-82,36.784-82,82c0,45.218,36.784,82,82,82c45.218,0,82-36.782,82-82C182,54.784,145.218,18,100,18
		L100,18z M100,176.528c-42.203,0-76.526-34.327-76.526-76.528c0-42.203,34.324-76.526,76.526-76.526
		c42.201,0,76.528,34.324,76.528,76.526C176.528,142.201,142.201,176.528,100,176.528L100,176.528z M100,176.528"/>
                    <path d="M102.736,89.121V61.402c0-1.506-1.229-2.738-2.736-2.738c-1.506,0-2.736,1.231-2.736,2.738v27.719
		c-3.817,1.001-6.82,3.991-7.808,7.811H69.931c-1.508,0-2.738,1.226-2.738,2.732c0,1.51,1.229,2.738,2.738,2.738h19.526
		c1.216,4.698,5.46,8.194,10.543,8.194c6.032,0,10.931-4.897,10.931-10.932C110.931,94.582,107.434,90.351,102.736,89.121
		L102.736,89.121z M100,105.139c-3.016,0-5.474-2.456-5.474-5.474c0-3.013,2.458-5.455,5.474-5.455c3.017,0,5.472,2.442,5.472,5.455
		C105.472,102.683,103.017,105.139,100,105.139L100,105.139z M100,105.139"/>
                    <path d="M100,44.998c1.508,0,2.736-1.213,2.736-2.733v-2.723c0-1.524-1.229-2.737-2.736-2.737c-1.506,0-2.736,1.213-2.736,2.737
		v2.723C97.264,43.785,98.494,44.998,100,44.998L100,44.998z M100,44.998"/>
                    <path d="M100,154.333c-1.506,0-2.736,1.228-2.736,2.736v2.735c0,1.509,1.229,2.736,2.736,2.736c1.508,0,2.736-1.228,2.736-2.736
		v-2.735C102.736,155.561,101.508,154.333,100,154.333L100,154.333z M100,154.333"/>
                    <path d="M160.139,96.932h-2.736c-1.508,0-2.735,1.226-2.735,2.732c0,1.51,1.228,2.738,2.735,2.738h2.736
		c1.508,0,2.723-1.228,2.723-2.738C162.861,98.158,161.646,96.932,160.139,96.932L160.139,96.932z M160.139,96.932"/>
                    <path d="M42.597,96.932H39.86c-1.507,0-2.72,1.226-2.72,2.732c0,1.51,1.213,2.738,2.72,2.738h2.737c1.51,0,2.736-1.228,2.736-2.738
		C45.333,98.158,44.107,96.932,42.597,96.932L42.597,96.932z M42.597,96.932"/>
                    <path d="M140.586,55.211l-1.938,1.934c-1.064,1.069-1.064,2.801,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.401-0.267,1.936-0.801l1.936-1.937c1.067-1.066,1.067-2.801,0-3.867C143.389,54.142,141.654,54.142,140.586,55.211
		L140.586,55.211z M140.586,55.211"/>
                    <path d="M57.48,138.331l-1.938,1.922c-1.066,1.067-1.066,2.803,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.4-0.267,1.934-0.801l1.937-1.936c1.066-1.067,1.066-2.789,0-3.856C60.281,137.25,58.546,137.263,57.48,138.331
		L57.48,138.331z M57.48,138.331"/>
                    <path d="M142.521,138.331c-1.067-1.081-2.803-1.081-3.874,0c-1.064,1.067-1.064,2.789,0,3.856l1.938,1.936
		c0.534,0.534,1.229,0.801,1.936,0.801c0.694,0,1.401-0.267,1.936-0.801c1.067-1.067,1.067-2.803,0-3.87L142.521,138.331z
		 M142.521,138.331"/>
                    <path d="M59.414,55.211c-1.066-1.069-2.803-1.069-3.872,0c-1.066,1.066-1.066,2.801,0,3.867l1.938,1.937
		c0.532,0.534,1.227,0.801,1.934,0.801c0.694,0,1.401-0.267,1.937-0.801c1.066-1.069,1.066-2.801,0-3.87L59.414,55.211z
		 M59.414,55.211"/>
                  </g>
                  </svg> <span class="label-text">120 miles</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_empDistance" value="250" id="jp_empDistance_250">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 18 200 164" xml:space="preserve">
                  <g>
                    <path d="M100,18c-45.216,0-82,36.784-82,82c0,45.218,36.784,82,82,82c45.218,0,82-36.782,82-82C182,54.784,145.218,18,100,18
		L100,18z M100,176.528c-42.203,0-76.526-34.327-76.526-76.528c0-42.203,34.324-76.526,76.526-76.526
		c42.201,0,76.528,34.324,76.528,76.526C176.528,142.201,142.201,176.528,100,176.528L100,176.528z M100,176.528"/>
                    <path d="M102.736,89.121V61.402c0-1.506-1.229-2.738-2.736-2.738c-1.506,0-2.736,1.231-2.736,2.738v27.719
		c-3.817,1.001-6.82,3.991-7.808,7.811H69.931c-1.508,0-2.738,1.226-2.738,2.732c0,1.51,1.229,2.738,2.738,2.738h19.526
		c1.216,4.698,5.46,8.194,10.543,8.194c6.032,0,10.931-4.897,10.931-10.932C110.931,94.582,107.434,90.351,102.736,89.121
		L102.736,89.121z M100,105.139c-3.016,0-5.474-2.456-5.474-5.474c0-3.013,2.458-5.455,5.474-5.455c3.017,0,5.472,2.442,5.472,5.455
		C105.472,102.683,103.017,105.139,100,105.139L100,105.139z M100,105.139"/>
                    <path d="M100,44.998c1.508,0,2.736-1.213,2.736-2.733v-2.723c0-1.524-1.229-2.737-2.736-2.737c-1.506,0-2.736,1.213-2.736,2.737
		v2.723C97.264,43.785,98.494,44.998,100,44.998L100,44.998z M100,44.998"/>
                    <path d="M100,154.333c-1.506,0-2.736,1.228-2.736,2.736v2.735c0,1.509,1.229,2.736,2.736,2.736c1.508,0,2.736-1.228,2.736-2.736
		v-2.735C102.736,155.561,101.508,154.333,100,154.333L100,154.333z M100,154.333"/>
                    <path d="M160.139,96.932h-2.736c-1.508,0-2.735,1.226-2.735,2.732c0,1.51,1.228,2.738,2.735,2.738h2.736
		c1.508,0,2.723-1.228,2.723-2.738C162.861,98.158,161.646,96.932,160.139,96.932L160.139,96.932z M160.139,96.932"/>
                    <path d="M42.597,96.932H39.86c-1.507,0-2.72,1.226-2.72,2.732c0,1.51,1.213,2.738,2.72,2.738h2.737c1.51,0,2.736-1.228,2.736-2.738
		C45.333,98.158,44.107,96.932,42.597,96.932L42.597,96.932z M42.597,96.932"/>
                    <path d="M140.586,55.211l-1.938,1.934c-1.064,1.069-1.064,2.801,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.401-0.267,1.936-0.801l1.936-1.937c1.067-1.066,1.067-2.801,0-3.867C143.389,54.142,141.654,54.142,140.586,55.211
		L140.586,55.211z M140.586,55.211"/>
                    <path d="M57.48,138.331l-1.938,1.922c-1.066,1.067-1.066,2.803,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.4-0.267,1.934-0.801l1.937-1.936c1.066-1.067,1.066-2.789,0-3.856C60.281,137.25,58.546,137.263,57.48,138.331
		L57.48,138.331z M57.48,138.331"/>
                    <path d="M142.521,138.331c-1.067-1.081-2.803-1.081-3.874,0c-1.064,1.067-1.064,2.789,0,3.856l1.938,1.936
		c0.534,0.534,1.229,0.801,1.936,0.801c0.694,0,1.401-0.267,1.936-0.801c1.067-1.067,1.067-2.803,0-3.87L142.521,138.331z
		 M142.521,138.331"/>
                    <path d="M59.414,55.211c-1.066-1.069-2.803-1.069-3.872,0c-1.066,1.066-1.066,2.801,0,3.867l1.938,1.937
		c0.532,0.534,1.227,0.801,1.934,0.801c0.694,0,1.401-0.267,1.937-0.801c1.066-1.069,1.066-2.801,0-3.87L59.414,55.211z
		 M59.414,55.211"/>
                  </g>
                  </svg> <span class="label-text">250 miles</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_empDistance" value="nopreference" id="jp_empDistance_nopref">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 18 200 164" xml:space="preserve">
                  <g>
                    <path d="M100,18c-45.216,0-82,36.784-82,82c0,45.218,36.784,82,82,82c45.218,0,82-36.782,82-82C182,54.784,145.218,18,100,18
		L100,18z M100,176.528c-42.203,0-76.526-34.327-76.526-76.528c0-42.203,34.324-76.526,76.526-76.526
		c42.201,0,76.528,34.324,76.528,76.526C176.528,142.201,142.201,176.528,100,176.528L100,176.528z M100,176.528"/>
                    <path d="M102.736,89.121V61.402c0-1.506-1.229-2.738-2.736-2.738c-1.506,0-2.736,1.231-2.736,2.738v27.719
		c-3.817,1.001-6.82,3.991-7.808,7.811H69.931c-1.508,0-2.738,1.226-2.738,2.732c0,1.51,1.229,2.738,2.738,2.738h19.526
		c1.216,4.698,5.46,8.194,10.543,8.194c6.032,0,10.931-4.897,10.931-10.932C110.931,94.582,107.434,90.351,102.736,89.121
		L102.736,89.121z M100,105.139c-3.016,0-5.474-2.456-5.474-5.474c0-3.013,2.458-5.455,5.474-5.455c3.017,0,5.472,2.442,5.472,5.455
		C105.472,102.683,103.017,105.139,100,105.139L100,105.139z M100,105.139"/>
                    <path d="M100,44.998c1.508,0,2.736-1.213,2.736-2.733v-2.723c0-1.524-1.229-2.737-2.736-2.737c-1.506,0-2.736,1.213-2.736,2.737
		v2.723C97.264,43.785,98.494,44.998,100,44.998L100,44.998z M100,44.998"/>
                    <path d="M100,154.333c-1.506,0-2.736,1.228-2.736,2.736v2.735c0,1.509,1.229,2.736,2.736,2.736c1.508,0,2.736-1.228,2.736-2.736
		v-2.735C102.736,155.561,101.508,154.333,100,154.333L100,154.333z M100,154.333"/>
                    <path d="M160.139,96.932h-2.736c-1.508,0-2.735,1.226-2.735,2.732c0,1.51,1.228,2.738,2.735,2.738h2.736
		c1.508,0,2.723-1.228,2.723-2.738C162.861,98.158,161.646,96.932,160.139,96.932L160.139,96.932z M160.139,96.932"/>
                    <path d="M42.597,96.932H39.86c-1.507,0-2.72,1.226-2.72,2.732c0,1.51,1.213,2.738,2.72,2.738h2.737c1.51,0,2.736-1.228,2.736-2.738
		C45.333,98.158,44.107,96.932,42.597,96.932L42.597,96.932z M42.597,96.932"/>
                    <path d="M140.586,55.211l-1.938,1.934c-1.064,1.069-1.064,2.801,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.401-0.267,1.936-0.801l1.936-1.937c1.067-1.066,1.067-2.801,0-3.867C143.389,54.142,141.654,54.142,140.586,55.211
		L140.586,55.211z M140.586,55.211"/>
                    <path d="M57.48,138.331l-1.938,1.922c-1.066,1.067-1.066,2.803,0,3.87c0.537,0.534,1.245,0.801,1.938,0.801
		c0.708,0,1.4-0.267,1.934-0.801l1.937-1.936c1.066-1.067,1.066-2.789,0-3.856C60.281,137.25,58.546,137.263,57.48,138.331
		L57.48,138.331z M57.48,138.331"/>
                    <path d="M142.521,138.331c-1.067-1.081-2.803-1.081-3.874,0c-1.064,1.067-1.064,2.789,0,3.856l1.938,1.936
		c0.534,0.534,1.229,0.801,1.936,0.801c0.694,0,1.401-0.267,1.936-0.801c1.067-1.067,1.067-2.803,0-3.87L142.521,138.331z
		 M142.521,138.331"/>
                    <path d="M59.414,55.211c-1.066-1.069-2.803-1.069-3.872,0c-1.066,1.066-1.066,2.801,0,3.867l1.938,1.937
		c0.532,0.534,1.227,0.801,1.934,0.801c0.694,0,1.401-0.267,1.937-0.801c1.066-1.069,1.066-2.801,0-3.87L59.414,55.211z
		 M59.414,55.211"/>
                  </g>
                  </svg> <span class="label-text">No preference</span> <span class="radio"></span> </label>
              </div>
            </div>
            <div class="emp-req-skills">
              <h3>Required skills and training</h3>
              <div class="row">
                <div class="col-md-7">
                  <input placeholder="Enter skills" name="jp_tr_skills" id="jp_tr_skills" type="text" class="input">
                </div>
              </div>
              <div class="row">
                <div class="col-md-7">
                  <select name="jp_tr_skills_courses"  id="jp_tr_skills_courses" class="input">
                    <option>Select multiple courses</option>
                    <option>Please select</option>
                    <option>Please select</option>
                    <option>Please select</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-7">
                  <div class="row">
                    <div class="col-md-6">
                      <select name="jp_tr_com_date" id="jp_tr_com_date" class="input calendar-icon">
                        <option>Completion date</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <select name="jp_tr_pass_score" id="jp_tr_pass_score" class="input pass-score-icon">
                        <option>Required Pass Score</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="emp-pay-rate">
              <h3>Rate of Pay</h3>
              <div class="pay-toggle">
                <div class="row">
                  <div class="col-sm-6">
                    <label for="jp_payRate_hourly" class="radio-custom active">
                      <input type="radio" name="jp_payRate" value="hourly" id="jp_payRate_hourly" checked>
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 10 200 180" xml:space="preserve" fill="#a1a1a1">
                      <g>
                        <g>
                          <defs>
                            <rect id="SVGID_1_" x="402.768" y="-185.04" width="188.385" height="185.04"/>
                          </defs>
                          <clipPath id="SVGID_2_">
                            <use xlink:href="#SVGID_1_"  overflow="visible"/>
                          </clipPath>
                          <path clip-path="url(#SVGID_2_)" d="M549.277-164.741v-20.135h-8.018v20.135h-38.105v-20.135h-8.018v20.135h-38.103v-20.135
			h-8.019v20.135h-46.248v164.28h188.424v-164.28H549.277z M449.016-156.723v11.859h8.019v-11.859h38.103v11.859h8.018v-11.859
			h38.105v11.859h8.018v-11.859h33.896v29.81H410.785v-29.81H449.016z M410.785-8.48v-110.416h172.389V-8.48H410.785z M410.785-8.48
			"/>
                        </g>
                        <rect x="472.855" y="-103.231" width="8.02" height="15.858"/>
                        <rect x="513.084" y="-103.231" width="8.018" height="15.858"/>
                        <rect x="553.312" y="-103.231" width="8.018" height="15.858"/>
                        <rect x="432.629" y="-70.929" width="8.018" height="15.86"/>
                        <rect x="472.855" y="-70.929" width="8.02" height="15.86"/>
                        <rect x="513.084" y="-70.929" width="8.018" height="15.86"/>
                        <rect x="553.312" y="-70.929" width="8.018" height="15.86"/>
                        <rect x="432.629" y="-38.622" width="8.018" height="15.856"/>
                        <rect x="472.855" y="-38.622" width="8.02" height="15.856"/>
                        <rect x="513.084" y="-38.622" width="8.018" height="15.856"/>
                      </g>
                      <g>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.073,96.328c0.289-2.672,0.534-5.351,0.873-8.016
		c1.956-15.42,7.691-29.322,16.872-41.829c1.141-1.553,2.346-3.059,3.602-4.69c-3.316-3.476-6.602-6.922-9.904-10.384
		c8.726,0,17.346,0,26.114,0c-0.255,8.846-0.502,17.436-0.763,26.474c-3.455-3.589-6.635-6.893-9.817-10.199
		c-18.234,20.378-27.116,56.518-10.361,89.623c17.705,34.986,58.083,52.598,95.825,41.85c37.959-10.81,62.982-46.977,59.46-86.25
		c-1.896-21.157-10.671-39.117-26.33-53.511c-15.622-14.359-34.262-21.314-55.606-21.705c0-2.592,0-5.123,0-7.654
		c1.225,0,2.448,0,3.673,0c0.292,0.075,0.577,0.184,0.874,0.222c4.59,0.571,9.253,0.796,13.762,1.753
		c22.037,4.682,39.951,16.04,53.569,33.991c9.476,12.489,15.191,26.586,17.191,42.156c0.361,2.817,0.6,5.65,0.895,8.476
		c0,2.244,0,4.487,0,6.731c-0.094,0.698-0.192,1.395-0.282,2.093c-0.548,4.286-0.786,8.638-1.688,12.847
		c-4.837,22.588-16.592,40.807-35.217,54.474c-12.202,8.952-25.891,14.366-40.922,16.287c-2.822,0.361-5.659,0.603-8.489,0.897
		c-2.243,0-4.486,0-6.73,0c-0.702-0.094-1.404-0.192-2.107-0.283c-4.285-0.548-8.635-0.791-12.844-1.69
		c-21.974-4.688-39.857-15.986-53.467-33.871c-9.527-12.518-15.274-26.65-17.288-42.271c-0.35-2.72-0.596-5.45-0.891-8.176
		C10.073,101.224,10.073,98.775,10.073,96.328z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M45.801,104.928c0-2.649,0-5.035,0-7.574c13.049,0,26.028,0,39.088,0
		c0.767-3.114,1.91-5.978,4.287-8.011c1.675-1.434,3.595-2.656,5.579-3.625c1.095-0.535,1.494-0.967,1.49-2.175
		c-0.039-15.96-0.027-31.92-0.027-47.881c0-0.654,0-1.308,0-2.205c2.409,0,4.679-0.023,6.947,0.036
		c0.234,0.006,0.568,0.478,0.653,0.789c0.113,0.429,0.036,0.91,0.036,1.369c0,15.96,0.013,31.92-0.025,47.881
		c-0.004,1.192,0.295,1.722,1.496,2.154c7.188,2.586,11.286,10.195,9.652,17.677c-1.592,7.288-8.617,12.567-16.146,11.877
		c-5.883-0.539-10.189-3.611-12.701-8.984c-0.496-1.062-1.034-1.358-2.148-1.355c-12.085,0.043-24.17,0.027-36.256,0.027
		C47.125,104.928,46.523,104.928,45.801,104.928z M100.017,107.653c4.211,0.009,7.684-3.466,7.674-7.677
		c-0.009-4.146-3.427-7.596-7.562-7.632c-4.236-0.036-7.725,3.385-7.747,7.598C92.36,104.164,95.808,107.646,100.017,107.653z"/>
                      </g>
                      </svg> <span class="label-text">Pay by the hour</span> </label>
                    <div class="pay-figure">
                      <input name="jp_payRate_hourly_val" id="jp_payRate_hourly_val" type="text" Placeholder="$40/hr">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="jp_payRate_fixed" class="radio-custom">
                      <input type="radio" name="jp_payRate" value="fixed" id="jp_payRate_fixed">
                      <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="58.559 166.161 486.625 494.839" xml:space="preserve" fill="#a1a1a1">
                      <g>
                        <g>
                          <g>
                            <path d="M542.768,168.582c-3.223-3.229-8.418-3.229-11.641,0l-30.605,30.563H308.497l-241.4,243.411
				c-5.517,5.521-8.538,12.81-8.538,20.582s3.021,15.104,8.538,20.581l168.787,168.787c5.676,5.676,13.087,8.494,20.532,8.494
				c7.497,0,14.991-2.818,20.707-8.577l235.076-241.4V210.825l30.566-30.569C545.989,177.04,545.989,171.798,542.768,168.582
				L542.768,168.582z M495.729,404.303L265.4,640.865c-4.908,4.911-12.919,4.911-17.837,0L78.737,472.039
				c-2.372-2.365-3.66-5.521-3.66-8.901c0-3.333,1.288-6.524,3.7-8.93l236.605-238.551h168.664l-39.868,39.872
				c-6.524-4.348-14.297-6.885-22.715-6.885c-22.715,0-41.242,18.486-41.242,41.24c0,22.754,18.527,41.24,41.242,41.24
				c22.757,0,41.243-18.485,41.243-41.24c0-8.372-2.54-16.184-6.849-22.714l39.872-39.866V404.303L495.729,404.303z M446.23,289.88
				c0,13.65-11.114,24.734-24.768,24.734c-13.61,0-24.728-11.08-24.728-24.734s11.117-24.725,24.728-24.725
				c3.825,0,7.368,0.927,10.634,2.451l-16.433,16.438c-3.222,3.216-3.222,8.452,0,11.68c1.609,1.61,3.706,2.412,5.802,2.412
				c2.133,0,4.229-0.802,5.839-2.412l16.432-16.432C445.267,282.509,446.23,286.055,446.23,289.88L446.23,289.88z"/>
                          </g>
                        </g>
                        <path d="M387.757,340.465c3.223-3.216,3.223-8.458,0-11.674c-3.225-3.228-8.418-3.228-11.64,0l-6.163,6.157
		c-14.74-11.793-33.428-17.598-52.559-15.985c-13.296,1.129-25.337,7.525-33.874,18.079c-8.813,10.875-12.886,25.092-11.157,38.99
		l6.292,50.215l-73.789,73.779c-16.91-22.582-15.178-54.801,5.361-75.353c3.216-3.222,3.216-8.415,0-11.634
		c-3.228-3.226-8.452-3.226-11.68,0c-26.983,26.94-28.794,69.67-5.477,98.795l-9.425,9.425c-3.229,3.213-3.229,8.406,0,11.641
		c1.609,1.601,3.706,2.405,5.838,2.405c2.09,0,4.223-0.805,5.845-2.405l9.425-9.434c13.124,10.511,29.394,16.278,46.27,16.278
		c2.053,0,4.144-0.088,6.239-0.25c13.29-1.13,25.331-7.521,33.911-18.085c8.785-10.863,12.858-25.077,11.126-38.981l-6.288-50.221
		l73.823-73.776c16.876,22.589,15.144,54.808-5.398,75.346c-3.222,3.223-3.222,8.428,0,11.641c3.226,3.234,8.461,3.234,11.681,0
		c26.979-26.983,28.794-69.673,5.477-98.789L387.757,340.465z M285.905,484.48c1.209,9.465-1.576,19.141-7.576,26.54
		c-5.71,7.05-13.694,11.315-22.464,12.044c-14.177,1.126-28.115-2.98-39.357-11.355l64.728-64.719L285.905,484.48z M288.723,373.969
		c-1.169-9.468,1.576-19.128,7.577-26.54c5.71-7.004,13.688-11.279,22.467-12.044c14.177-1.166,28.112,2.94,39.388,11.361
		l-64.765,64.72L288.723,373.969z"/>
                      </g>
                      </svg> <span class="label-text">Pay a fixed price</span> </label>
                    <div class="pay-figure">
                      <input name="jp_payRate_fixed_val" id="jp_payRate_fixed_val" type="text" Placeholder="$200">
                    </div>
                  </div>
                </div>
              </div>
              <div class="emp-flex-rates">
                <label for="jp_flexRate" class="custom-checkbox">
                  <input id="jp_flexRate" name="jp_flexRate" type="checkbox" checked>
                  <span class="custom-check"></span> flex the rate</label>
                <div class="flex_rate_details">
				<h3>If you want to flex the rate choose from the following options</h3>
                <h4>Flex frequency</h4>
                <ul>
                  <li>
                    <label class="radio-custom">
                      <input type="radio" name="jp_flex_freq" value="1" id="jp_flex_freq_1" checked>
                      <span class="radio"></span> Flex 1 times</label>
                  </li>
                  <li>
                    <label class="radio-custom">
                      <input type="radio" name="jp_flex_freq" value="2" id="jp_flex_freq_2">
                      <span class="radio"></span> Flex 2 times</label>
                  </li>
                  <li>
                    <label class="radio-custom">
                      <input type="radio" name="jp_flex_freq" value="3" id="jp_flex_freq_3">
                      <span class="radio"></span> Flex 3 times</label>
                  </li>
                </ul>
                <div class="emp-flex-rate-details clearfix">
                  <div class="row">
                    <div class="col-sm-6">
                      <label>Date the flex</label>
                      <div class="emp-flex-interval emp-flex-date-interval" name="jp_flex_interval" id="jp_flex_interval">
                        <input type="text" name="flex-month-date[]" class="input calendar-icon flex-date-picker">
                        <!-- <select name="flex-month-date[]" class="input calendar-icon">
                          <option>2 months out</option>
                        </select> -->
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label>Flex amount</label>
                      <div class="emp-flex-interval emp-flex-completion-interval" id="jp_flex_amount" name="jp_flex_amount">
                        <input type="text" name="flex-month-completion[]" class="input percent-icon">
                        <!-- <select name="flex-month-completion[]" class="input percent-icon">
                          <option>Completion date</option>
                        </select> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			</div>
            <div class="emp-additional-pay-options">
              <h3>Additional payment questions:</h3>
              <ul>
                <li>
                  <table>
                    <tr>
                      <td>Will employer pay for additional hours worked</td>
                      <td><label for="jp_pay_additonal_hours_yes" class="radio-custom">
                          <input id="jp_pay_additonal_hours_yes" name="jp_pay_additonal_hours" value="yes" type="radio" checked>
                          <span class="radio"></span> Yes</label>
                        <label for="jp_pay_additonal_hours_no" class="radio-custom">
                          <input id="jp_pay_additonal_hours_no" name="jp_pay_additonal_hours" value="no" type="radio">
                          <span class="radio"></span> No</label></td>
                    </tr>
                   <tr class="allowable_time">
                      <td>
						<p>Set allowable time before activity</p>
                        <p>Set allowable time after activity</p>
					 </td>
                      <td><select name="jp_allw_time_bfr_acti" id="jp_allw_time_bfr_acti" class="input inline small">
                          <option>15 min</option>
                          <option>30 min</option>
                          <option>60 min</option>
                        </select>
                        
                        <select name="jp_allw_time_aftr_acti" id="jp_allw_time_aftr_acti" class="input inline small">
                          <option>15 min</option>
                          <option>30 min</option>
                          <option>60 min</option>
                        </select>
                        <input type="text" placeholder="Allowable Overages" class="input small" name="allowwable_overages">
                      </td>
                    </tr>
                  </table>
                </li>
                <li>
                  <table>
                    <tr>
                      <td>Is travel cost covered (we will use standard US reimbursement rate on miles)</td>
                      <td><label for="jp_travelCost_Yes" class="radio-custom">
                          <input id="jp_travelCost_Yes" name="jp_travelCost" value="yes" type="radio" checked>
                          <span class="radio"></span> Yes</label>
                        <label for="jp_travelCost_No" class="radio-custom">
                          <input id="jp_travelCost_No" name="jp_travelCost" value="no" type="radio">
                          <span class="radio"></span> No</label></td>
                    </tr>
                  </table>
                </li>
                <li>
                  <table>
                    <tr>
                      <td>Experience Level</td>
                      <td><label for="jp_experianced_level_entry" class="radio-custom">
                          <input id="jp_experianced_level_entry" name="experianceEntry" value="entry_level" type="radio" checked>
                          <span class="radio"></span> Entry Level</label>
                          <label for="jp_experianced_intermediate" class="radio-custom">
                          <input id="jp_experianced_intermediate" name="experianceEntry" value="intermediate" type="radio">
                          <span class="radio"></span> Intermediate</label>
                        <label for="jp_experianced_level_expert" class="radio-custom">
                          <input id="jp_experianced_level_expert" name="experianceEntry" value="expert" type="radio">
                          <span class="radio"></span> Expert</label></td>
                    </tr>
                  </table>
                </li>
                <li>
                  <table>
                    <tr>
                      <td>Hours Per Week</td>
                      <td><label for="hours_parttyme" class="radio-custom">
                          <input id="hours_parttyme" name="hours_per_week" value="part_time" type="radio" checked>
                          <span class="radio"></span>Part Time</label>
                          <label for="hours_fullTime" class="radio-custom">
                          <input id="hours_fullTime" name="hours_per_week" value="full_time" type="radio">
                          <span class="radio"></span>Full Time</label></td>
                    </tr>
                  </table>
                </li>
                <li>
                  <table>
                    <tr>
                      <td>Overnight Travel</td>
                      <td>
                        <label for="over_night_yes" class="radio-custom">
                        <input id="over_night_yes" name="over_night_travel" value="yes" type="radio" checked>
                        <span class="radio"></span>Yes</label>
                        <label for="over_night_no" class="radio-custom">
                        <input id="over_night_no" name="over_night_travel" value="no" type="radio">
                        <span class="radio"></span>No</label>
                        <label for="over_night_no_specified" class="radio-custom">
                        <input id="over_night_no_specified" name="over_night_travel" value="not_specified" type="radio">
                        <span class="radio"></span>Not Specified</label>
                      </td>
                    </tr>
                  </table>
                </li>



                <li>
                  <table>
                    <tr>
                      <td>Job Speciality</td>
                      <td>
                        <select name="job_speciality" id="" class="input">
                          <option value="Brand ambassador">Brand ambassador</option>
                          <option value="Events Staff">Events Staff</option>
                          <option value="Retail sales merchandiser">Retail sales merchandiser</option>
                          <option value="Product demonstrator/Promoter">Product demonstrator/Promoter</option>
                          <option value="Sales Consultant">Sales Consultant</option>
                          <option value="Field Technician">Field Technician</option>
                          <option value="Field sales/marketing representative">Field sales/marketing representative</option>
                          <option value="Trainer">Trainer</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </li>



                <li>
                  <table>
                    <tr>
                      <td>Hours Billed</td>
                      <td>
                        <input class="input small" id="hours_billed" name="hours_billed" placeholder="Hours Billed" type="text">                        
                      </td>
                    </tr>
                  </table>
                </li>
                <li>
                  <table>
                    <tr>
                      <td> What other expenses are covered </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="jp_other_expenses_food" class="custom-checkbox">
                          <input id="jp_other_expenses_food" value="food" name="jp_other_expenses[]" type="checkbox">
                          <span class="custom-check"></span> Food
                        </label>
                      </td>
                      <td>
                        <input type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Food Expences" disabled>
                      </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="jp_other_expenses_parking" class="custom-checkbox">
                        <input id="jp_other_expenses_parking" value="parking" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Parking
                      </label>
                      </td>
                      <td>
                        <input type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Parking Expences" disabled>
                      </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="jp_other_expenses_tolls" class="custom-checkbox">
                          <input id="jp_other_expenses_tolls" value="tolls" name="jp_other_expenses[]" type="checkbox">
                          <span class="custom-check"></span> Tolls
                        </label>
                      </td>
                      <td>
                        <input type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Tolls Expences" disabled>
                      </td>
                    </tr>
                    <tr>
                        <td>
                         <label for="jp_other_expenses_tips" class="custom-checkbox">
                        <input id="jp_other_expenses_tips" value="tips" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Tips
                      </label>
                      </td>
                      <td>
                        <input type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Tips Expences" disabled>
                      </td>
                    </tr>
                    <tr>
                        <td>
                       <label for="jp_other_expenses_other" class="custom-checkbox">
                        <input id="jp_other_expenses_other"  value="other" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Other
                      </label>
                      </td>
                      <td>
                        <input type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Other Expences" disabled>
                      </td>
                    </tr>
                      <!-- <label for="jp_other_expenses_parking" class="custom-checkbox">
                        <input id="jp_other_expenses_parking" value="parking" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Parking
                      </label>
                      
                      <label for="jp_other_expenses_tolls" class="custom-checkbox">
                        <input id="jp_other_expenses_tolls" value="tolls" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Tolls
                      </label>
                      
                      <label for="jp_other_expenses_tips" class="custom-checkbox">
                        <input id="jp_other_expenses_tips" value="tips" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Tips
                      </label>
                      
                      <label for="jp_other_expenses_other" class="custom-checkbox">
                        <input id="jp_other_expenses_other"  value="other" name="jp_other_expenses[]" type="checkbox">
                        <span class="custom-check"></span> Other
                      </label>
                                  
                       </td>
                                          </tr> -->
                  </table>
                </li>
              </ul>
            </div>
			
            <div class="emp-preferences">
              <h3>Employee Preferences</h3>
              <div class="employee-type">
                <h4>Employee Type</h4>
                <div class="emp-pref-table"><label class="radio-custom">
                  <input type="radio" name="jp_preferences" value="no_preference" id="jp_preferences_no" checked="">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 29 200 142" xml:space="preserve">
                  <g>
                    <path d="M46.534,134.936c0.542,0.43,1.183,0.625,1.819,0.625c0.856,0,1.707-0.365,2.273-1.094c1.013-1.247,0.798-3.08-0.455-4.093
		c-2.327-1.844-4.586-3.861-6.718-5.993l-21.529-21.529l22.209-22.2C65.477,59.309,96.53,52.018,125.148,61.64
		c1.521,0.499,3.169-0.313,3.694-1.833c0.512-1.521-0.313-3.183-1.833-3.697c-30.737-10.317-64.075-2.483-86.999,20.424
		l-26.316,26.317l25.65,25.65C41.619,130.775,44.035,132.936,46.534,134.936L46.534,134.936z M46.534,134.936"/>
                    <path d="M159.907,76.534c-3.937-3.922-8.228-7.462-12.789-10.518c-1.336-0.896-3.155-0.539-4.051,0.797
		c-0.895,1.32-0.54,3.139,0.796,4.034c4.249,2.84,8.271,6.155,11.923,9.803l21.529,21.529l-22.211,22.2
		c-21.486,21.497-53.876,28.759-82.508,18.502c-1.521-0.544-3.183,0.238-3.722,1.759c-0.538,1.506,0.24,3.169,1.761,3.727
		c9.251,3.311,18.887,4.913,28.453,4.913c22.181,0,44.008-8.654,60.137-24.779l26.318-26.321L159.907,76.534z M159.907,76.534"/>
                    <path d="M102.567,136.048c-6.267,0-12.364-1.737-17.663-5.034c-1.35-0.835-3.155-0.423-4.006,0.941
		c-0.856,1.364-0.43,3.151,0.935,4.007c6.213,3.862,13.388,5.912,20.734,5.912c21.672,0,39.292-17.621,39.292-39.292
		c0-7.819-2.287-15.363-6.622-21.828c-0.895-1.335-2.699-1.707-4.035-0.812c-1.336,0.895-1.691,2.714-0.796,4.038
		c3.681,5.512,5.642,11.937,5.642,18.602C136.048,121.041,121.027,136.048,102.567,136.048L102.567,136.048z M102.567,136.048"/>
                    <path d="M102.567,69.116c6.012,0,11.909,1.604,17.039,4.661c1.393,0.806,3.169,0.352,3.993-1.023
		c0.824-1.393,0.369-3.172-1.023-3.995c-6.039-3.58-12.945-5.471-20.009-5.471c-21.655,0-39.292,17.637-39.292,39.294
		c0,6.067,1.35,11.876,4.005,17.294c0.499,1.023,1.535,1.62,2.617,1.62c0.424,0,0.869-0.085,1.279-0.284
		c1.434-0.711,2.03-2.462,1.336-3.894c-2.26-4.604-3.413-9.563-3.413-14.736C69.1,84.123,84.121,69.116,102.567,69.116
		L102.567,69.116z M102.567,69.116"/>
                    <path d="M172.654,30.675c-1.137-1.135-2.971-1.135-4.107,0L33.221,166c-1.135,1.141-1.135,2.987,0,4.121
		c0.566,0.568,1.309,0.855,2.048,0.855c0.753,0,1.49-0.287,2.059-0.855L172.654,34.796
		C173.791,33.657,173.791,31.813,172.654,30.675L172.654,30.675z M172.654,30.675"/>
                  </g>
                  </svg> <span class="label-text">No preference</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_preferences" value="agency" id="jp_preferences_agency">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" viewBox="154 116 304 575" xml:space="preserve">
                  <g>
                    <g>
                      <g>
                        <path d="M407.662,265.785v-53.014h-92.519v-96.75h-21.42v96.75h-90.925v55.233h-48.908v421.901h303.992v-424.12H407.662z      M222.624,232.652h165.213v33.133H222.624V232.652z M438.058,670.028H173.716V285.667h264.341V670.028z"/>
                      </g>
                    </g>
                    <rect x="205.02" y="568.24" width="21.42" height="55.08"/>
                    <rect x="266.22" y="568.24" width="21.42" height="55.08"/>
                    <rect x="327.42" y="568.24" width="21.42" height="55.08"/>
                    <rect x="388.62" y="568.24" width="21.42" height="55.08"/>
                    <rect x="205.02" y="451.96" width="21.42" height="55.08"/>
                    <rect x="266.22" y="451.96" width="21.42" height="55.08"/>
                    <rect x="327.42" y="451.96" width="21.42" height="55.08"/>
                    <rect x="388.62" y="451.96" width="21.42" height="55.08"/>
                    <rect x="205.02" y="335.68" width="21.42" height="55.08"/>
                    <rect x="266.22" y="335.68" width="21.42" height="55.08"/>
                    <rect x="327.42" y="335.68" width="21.42" height="55.08"/>
                    <rect x="388.62" y="335.68" width="21.42" height="55.08"/>
                  </g>
                  </svg> <span class="label-text">Agency</span> <span class="radio"></span> </label>
                <label class="radio-custom">
                  <input type="radio" name="jp_preferences" value="independent" id="jp_preferences_independent">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 13 200 175" xml:space="preserve">
<g>
  <path d="M129.464,115.834l-0.445-0.201c-1.063-0.483-2.135-0.94-3.226-1.367c-1.192-0.479-2.4-0.919-3.628-1.332
		c-0.967-0.32-1.944-0.615-2.93-0.905c-1.502-0.435-3.02-0.827-4.582-1.174c-0.529-0.11-1.069-0.218-1.608-0.312l-0.365-0.072
		c-0.924-0.18-1.851-0.338-2.785-0.476l-0.269-0.043c-0.011,0-0.019-0.008-0.029-0.008l-0.269-0.035
		c-0.776-0.115-1.553-0.226-2.358-0.316l-2.742-0.226c-3.373-0.224-6.848-0.218-10.181,0.026l-0.972,0.081l-0.451,0.034
		l-1.136,0.105c-1.042,0.107-2.079,0.252-3.113,0.419l-0.306,0.043c-0.707,0.115-1.413,0.247-2.108,0.378
		c-0.776,0.138-1.539,0.29-2.302,0.457c-1.477,0.33-2.946,0.71-4.366,1.122c-1.131,0.325-2.248,0.683-3.298,1.046l-0.728,0.261
		c-2.226,0.788-4.439,1.691-6.583,2.685l-0.572,0.273l-0.01,0.014c-25.052,11.991-41.206,37.537-41.206,65.258
		c0,0.637,0.042,1.259,0.081,1.891l0.188,4.396H171.37l0.193-4.349c0.043-0.641,0.084-1.283,0.084-1.938
		C171.647,153.405,155.099,127.635,129.464,115.834L129.464,115.834z M33.967,180.787c0.306-24.89,15.021-47.72,37.448-58.236
		l0.723-0.282l0.083-0.083c1.756-0.8,3.572-1.534,5.409-2.182l0.583-0.206c1.001-0.347,1.998-0.666,3.035-0.958
		c1.289-0.38,2.604-0.718,3.923-1.017c0.682-0.142,1.364-0.281,2.068-0.412c0.556-0.104,1.112-0.207,1.673-0.296l0.276-0.051
		c1.01-0.159,2.025-0.305,3.038-0.414l2.313-0.202c3.023-0.223,5.918-0.252,9.426,0l2.248,0.189c0.553,0.063,1.108,0.14,1.785,0.232
		l0.809,0.115c0.846,0.127,1.689,0.269,2.538,0.43l0.395,0.073c0.479,0.089,0.953,0.183,1.402,0.276
		c1.391,0.304,2.761,0.663,4.116,1.058c0.897,0.261,1.789,0.53,2.659,0.82c1.102,0.368,2.191,0.768,3.269,1.194
		c1.036,0.406,2.057,0.841,3.069,1.306l0.175,0.081c22.938,10.521,37.835,33.428,38.138,58.556h-130.6V180.787z M81.797,100.282
		c0.532,0.222,1.08,0.415,1.628,0.611l1.147,0.417c0.443,0.169,0.889,0.336,1.34,0.478c4.213,1.324,8.621,2.007,13.103,2.042
		l0.357,0.007c1.539,0,3.08-0.08,4.558-0.232c0.508-0.05,1.004-0.13,1.501-0.216l1.106-0.166c0.623-0.08,1.246-0.172,1.896-0.302
		c0.445-0.09,0.875-0.206,1.308-0.324l1.209-0.307c0.596-0.145,1.194-0.289,1.785-0.476c0.368-0.115,0.729-0.249,1.085-0.384
		l0.973-0.349c0.692-0.242,1.391-0.488,2.092-0.782c0.271-0.117,0.537-0.244,0.798-0.37l0.314-0.156
		c0.959-0.43,1.915-0.867,2.86-1.375l0.614-0.368c1.062-0.602,2.119-1.225,3.188-1.956c0.556-0.376,1.085-0.794,1.608-1.21
		l1.81-1.408c10.407-8.616,16.388-21.256,16.388-34.368l0.021-0.374c0-24.879-20.238-45.115-45.118-45.115
		c-23.746,0-43.504,18.568-44.976,42.274l-0.04,0.585c-0.057,0.747-0.103,1.494-0.103,2.256
		C54.243,76.887,65.058,93.2,81.797,100.282L81.797,100.282z M61.403,56.952l0.041-0.637C62.689,36.327,79.346,20.67,99.367,20.67
		c20.983,0,38.047,17.066,38.066,37.724l-0.021,0.371c-0.014,11.32-5.063,21.977-13.882,29.297l-1.679,1.278
		c-0.373,0.306-0.752,0.609-1.222,0.935c-0.849,0.577-1.748,1.096-2.659,1.611l-0.505,0.309c-0.766,0.409-1.565,0.773-2.4,1.144
		l-0.938,0.454c-0.537,0.222-1.104,0.416-1.662,0.609l-1.166,0.422c-0.226,0.086-0.456,0.177-0.68,0.244
		c-0.435,0.134-0.878,0.236-1.321,0.346l-1.409,0.36c-0.304,0.081-0.604,0.167-0.873,0.223c-0.47,0.091-0.951,0.156-1.429,0.223
		l-1.343,0.202c-0.325,0.056-0.65,0.113-1.016,0.148c-1.176,0.124-2.36,0.19-3.563,0.201l-0.331-0.003h-0.008
		c-3.87-0.002-7.67-0.58-11.304-1.719c-0.314-0.096-0.623-0.223-0.929-0.338l-1.33-0.483c-0.414-0.145-0.824-0.288-1.219-0.454
		c-14.11-5.967-23.231-19.733-23.231-35.055C61.314,58.12,61.36,57.535,61.403,56.952L61.403,56.952z M61.403,56.952"/>
</g>
</svg>  <span class="label-text">independent</span> <span class="radio"></span> </label></div>
              </div>
              <div class="hours-billed">
                <h4>Jobs completed on Site</h4>
                <label class="radio-custom">
                  <input type="radio" name="jp_jobs_completed" value="no_preference" id="jp_jobs_completed_nopref" checked>
                  <span class="radio"></span> no preference</label>
                <label class="radio-custom">
                  <input type="radio" name="jp_jobs_completed" value="1-50" id="jp_jobs_completed_1-50">
                  <span class="radio"></span>1-50</label>
                <label class="radio-custom">
                  <input type="radio" name="jp_jobs_completed" value="50-100" id="jp_jobs_completed_50-100">
                  <span class="radio"></span>50-100</label>
                <label class="radio-custom">
                  <input type="radio" name="jp_jobs_completed" value="100-500" id="jp_jobs_completed_100-500">
                  <span class="radio"></span>100-500</label>
                <label class="radio-custom">
                  <input type="radio" name="jp_jobs_completed" value="above_500" id="jp_jobs_completed_above500">
                  <span class="radio"></span> Above 500</label>
              </div>
              <div class="row language-industry">
                <div class="col-md-6">
                  <h4>Language</h4>
                  <input type="text"  name="jp_language" id="jp_language" class="input" Placeholder="Select language">
                 <!--  <select name="jp_language" id="jp_language" class="input">
                   <option>English</option>
                   <option>Please select</option>
                   <option>Please select</option>
                   <option>Please select</option>
                 </select> -->
                </div>
                <div class="col-md-6">
                  <h4>Industry Knowledge</h4>
                  <input name="industry_knowledge" placeholder="Industry Knowledge" id="industry_knowledge" type="text" class="input">
                </div>
              </div>
              <div class="ask-questions">
                <h4>Ask question(s)</h4>
                <div class="new-ques-added">
                <textarea name="quiestions[]" cols="" rows="" class="input" placeholder="type question"></textarea></div>
               
                <a href="javascript:void(0)" onclick="functionToAddQuiestions();" class="add-ques-btn btn btn-blue">Add question</a> </div>
              <div class="type-of-employees">
                <h4>Type of employees </h4>
                <label class="radio-custom">
                  <input type="radio" name="jp_emp_type" value="anyone" id="jp_emp_type_anyone" checked>
                  <span class="radio"></span> anyone</label>
                <label class="radio-custom">
                  <input type="radio" name="jp_emp_type" value="invite_only" id="jp_emp_type_anyone_invite">
                  <span class="radio"></span> invite only</label>
              </div>
            </div>
            <div class="job-post-btns">
              <button type="button" class="btn btn-blue preview-jobpost">Preview</button>
              <button type="submit" class="btn btn-gray" onclick="postingAJob();">Post job</button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
<?php 
if(isset($postedJob)) {
  ?>
  <?php
}
 ?>