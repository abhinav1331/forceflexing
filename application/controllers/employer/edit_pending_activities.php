<?php 
/*echo "<pre>";
	print_r($_POST);
echo "</pre>";*/
$getActivityRecord = $this->Model->get_Data_table(PREFIX.'job_activities','id',$_POST['activityId']);
/*echo "<pre>";
	print_r($getActivityRecord);
echo "</pre>";*/
$arrayStart = explode(" " , $getActivityRecord[0]['start_datetime']);
$startDate = $arrayStart[0];
$startTime = $arrayStart[1];

$arrayEnd = explode(" " , $getActivityRecord[0]['end_datetime']);
$endDate = $arrayEnd[0];
$endTime = $arrayEnd[1];
$all_results=$this->Model->Get_all_with_cond('country_id',231,PREFIX.'states');
 ?>


                <div class="activityReunion" id="activityReunion1">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <th scope="row">Activites Name:</th>
                      <td>
  						<div class="row">
  							<div class="col-md-7">
  							  <input name="jp_activity_name[]" id="jp_activity_name" type="text" class="input small half-width" value="<?php echo $getActivityRecord[0]['activity_name'] ?>">
  							</div>
  						</div>
  					</td>
                    </tr>
                    <tr>
                      <th scope="row">Select:</th>
                      <td>
  						<label class="radio-custom">
                          <input <?php if($getActivityRecord[0]['activity_type'] == "fixed") { echo "checked"; } ?> type="radio" name="jp_start_stop_time1[]" value="fixed" id="jp_start_stop_time_fix" checked>
                          <span class="radio"></span>fixed start and stop time <a href="javascript:void(0);" class="calendar-icon">date</a></label>
  						
  						<span class="sep">or</span>
  						
  						<label class="radio-custom">
                          <input <?php if($getActivityRecord[0]['activity_type'] == "fixed") { echo "flexible"; } ?> type="radio" name="jp_start_stop_time1[]" value="flexible" id="jp_start_stop_time_flex">
                          <span class="radio"></span>flexible start/stop <a href="javascript:void(0);" class="calendar-icon">date</a></label>
  					</td>
                    </tr>
                    <tr>
                      <th scope="row">Fixed:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input value="<?php echo $startDate; ?>" type="text" name="jp_act_start_date[]" id="jp_act_start_date" class="input small calendar-icon jp_act_start_date" placeholder="start date">
                              </div>
                              <div class="col-md-6">
                                <input value="<?php echo $startTime; ?>" type="text"  name="jp_act_start_time[]" id="jp_act_start_time" class="input small watch-icon jp_act_start_time" placeholder="start time">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input value="<?php echo $endDate; ?>" type="text" name="jp_act_end_date[]" id="jp_act_end_date" class="input small calendar-icon jp_act_start_date" placeholder="finish date">
                              </div>
                              <div class="col-md-6">
                                <input value="<?php echo $endTime; ?>" type="text"  name="jp_act_end_time[]" id="jp_act_end_time" class="input small watch-icon jp_act_start_time" placeholder="finish time">
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
                                   foreach($all_results as $state) {
                                    ?>
                                    <option <?php if($getActivityRecord[0]['state'] == $state['id']) { echo "selected"; } ?> value="<?php echo $state['id']; ?>"><?php echo $state['name']; ?></option>
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
  				                      <input value="<?php echo $getActivityRecord[0]['street']; ?>" type="text" class="input small" name="jp_act_street[]" id="jp_act_street" placeholder="street" >
                                <!--<select name="" class="input small">
                                  <option>street</option>
                                </select>-->
                              </div>
                              <div class="col-md-6">
                                <input value="<?php echo $getActivityRecord[0]['zip']; ?>" type="text" class="input small" name="jp_act_zip[]" id="jp_act_zip"  placeholder="zip" onkeyup="isValidPostalCode(this)">
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
                            <input value="<?php echo $getActivityRecord[0]['first_name']; ?>" name="jp_act_cont_fname[]" id="jp_act_cont_fname" type="text" placeholder="first name" class="input small">
                          </div>
                          <div class="col-md-6">
                            <input value="<?php echo $getActivityRecord[0]['last_name']; ?>" name="jp_act_cont_lname[]" id="jp_act_cont_lname" type="text" placeholder="last name" class="input small">
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Contact:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <input value="<?php echo $getActivityRecord[0]['phone']; ?>" name="jp_act_cont_phne[]" id="jp_act_cont_phne" type="text" placeholder="phone" class="input small">
                          </div>
                          <div class="col-md-6">
                            <input value="<?php echo $getActivityRecord[0]['email']; ?>" name="jp_act_cont_email[]" id="jp_act_cont_email" type="text" placeholder="email" class="input small">
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Notes/tasks:</th>
                      <td><textarea value="<?php echo $getActivityRecord[0]['notes']; ?>" name="jp_act_notes[]" id="jp_act_notes" cols="" rows="" class="input small" placeholder="text here"></textarea></td>
                    </tr>
                  </table>
                </div><input type="hidden" class="state_Activity" value="<?php echo $getActivityRecord[0]['state']; ?>">
                <input type="hidden" class="city_Activity" value="<?php echo $getActivityRecord[0]['city']; ?>">
                <a href="#" class="btn btn-blue">Save Changes</a>
                <a href="javascript:void(0)" onclick="deleteActivity(<?php echo $_POST['activityId']; ?>)" class="btn btn-blue">Delete Activity</a>
                <a href="javascript:void(0)" class="btn btn-blue" onclick="closeviewActivity(this);">Close</a>