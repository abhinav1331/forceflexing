<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity</h4>
      </div>
      <div class="modal-body">
              <form action="" method="post" id="addActivity">
                <input type="hidden" class="job_id" name="job_id" value="<?php echo $job_id ?>">
                <input type="hidden" class="user_id" name="user_id" value="<?php echo $user_id ?>">
                <div class="activityReunion" id="activityReunion1">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <th scope="row">Activites Name:</th>
                      <td>
              <div class="row">
                <div class="col-md-7">
                  <input name="jp_activity_name" id="jp_activity_name" type="text" class="input small half-width">
                </div>
              </div>
            </td>
                    </tr>
                    <tr>
                      <th scope="row">Select:</th>
                      <td>
              <label class="radio-custom">
                          <input type="radio" name="jp_start_stop_time1" value="fixed" id="jp_start_stop_time_fix" checked>
                          <span class="radio"></span>fixed start and stop time <a href="javascript:void(0);" class="calendar-icon">date</a></label>
              
              <span class="sep">or</span>
              
              <label class="radio-custom">
                          <input type="radio" name="jp_start_stop_time1" value="flexible" id="jp_start_stop_time_flex">
                          <span class="radio"></span>flexible start/stop <a href="javascript:void(0);" class="calendar-icon">date</a></label>
            </td>
                    </tr>
                    <tr>
                      <th scope="row">Fixed:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input type="text" name="jp_act_start_date" id="jp_act_start_date" class="input small calendar-icon jp_act_start_date" placeholder="start date">
                              </div>
                              <div class="col-md-6">
                                <input type="text"  name="jp_act_start_time" id="jp_act_start_time" class="input small watch-icon jp_act_start_time" placeholder="start time">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input type="text" name="jp_act_end_date" id="jp_act_end_date" class="input small calendar-icon jp_act_start_date" placeholder="finish date">
                              </div>
                              <div class="col-md-6">
                                <input type="text"  name="jp_act_end_time" id="jp_act_end_time" class="input small watch-icon jp_act_start_time" placeholder="finish time">
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
                                
                                <select onchange="onchangeState(this);" data-attribute='activityReunion1' name="jp_act_state" id="sel12 stateId" class="input small states">
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
                                <select onchange="ourChange(this)" name="jp_act_city" id="sel13 cityId" class="input small cities">
                                   <option value="">Select City</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <input type="text" class="input small" name="jp_act_street" id="jp_act_street" placeholder="street" >
                                <!--<select name="" class="input small">
                                  <option>street</option>
                                </select>-->
                              </div>
                              <div class="col-md-6">
                                <input type="text" class="input small" name="jp_act_zip" id="jp_act_zip"  placeholder="zip" onkeyup="isValidPostalCode(this)">
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
                            <input name="jp_act_cont_fname" id="jp_act_cont_fname" type="text" placeholder="first name" class="input small">
                          </div>
                          <div class="col-md-6">
                            <input name="jp_act_cont_lname" id="jp_act_cont_lname" type="text" placeholder="last name" class="input small">
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Contact:</th>
                      <td><div class="row">
                          <div class="col-md-6">
                            <input name="jp_act_cont_phne" id="jp_act_cont_phne" type="text" placeholder="phone" class="input small">
                          </div>
                          <div class="col-md-6">
                            <input name="jp_act_cont_email" id="jp_act_cont_email" type="text" placeholder="email" class="input small">
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <th scope="row">Notes/tasks:</th>
                      <td><textarea name="jp_act_notes" id="jp_act_notes" cols="" rows="" class="input small" placeholder="text here"></textarea></td>
                    </tr>
                  </table>
            </div>
            <input type="submit" class="btn btn-success">
            </form>
      </div>
    </div>

  </div>
</div>







<div id="MyModelActionOtherExpanditure" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Extra Expenditure</h4>
      </div>
      <div class="modal-body">

        <?php 
          $includedExpenditure = array();
          $includedExpenditureIndex = array();
          $otherExpenditure = $Model->Get_column1('*','job_id',$job_id,PREFIX.'job_expenditure');
         ?>
     <form role="form" id="ExtraExpenditure" action="" method="post">
          <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
       <table>
                    <tr>
                      <td> What other expenses are covered </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="jp_other_expenses_food" class="custom-checkbox">
                          <input id="jp_other_expenses_food" value="food" name="jp_other_expenses[]" type="checkbox" <?php if(in_array_r("food", $otherExpenditure) == 1) { echo "checked"; } ?> >
                          <span class="custom-check"></span> Food
                        </label>
                      </td>
                      <td>
                        <input <?php foreach ($otherExpenditure as $key => $value) { if($value['name'] == "food") { ?>value="<?php echo $value['price']; ?>"<?php } } ?> type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Food Expences" <?php if(in_array_r("food", $otherExpenditure) == 0) { echo "disabled"; } ?> >
                      </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="jp_other_expenses_parking" class="custom-checkbox">
                        <input id="jp_other_expenses_parking" value="parking" name="jp_other_expenses[]" type="checkbox" <?php if(in_array_r("parking", $otherExpenditure) == 1) { echo "checked"; } ?> >
                        <span class="custom-check"></span> Parking
                      </label>
                      </td>
                      <td>
                        <input <?php foreach ($otherExpenditure as $key => $value) { if($value['name'] == "parking") { ?>value="<?php echo $value['price']; ?>"<?php } } ?>  type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Parking Expences"  <?php if(in_array_r("parking", $otherExpenditure) == 0) { echo "disabled"; } ?>>
                      </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="jp_other_expenses_tolls" class="custom-checkbox">
                          <input id="jp_other_expenses_tolls" value="tolls" name="jp_other_expenses[]" type="checkbox" <?php if(in_array_r("tolls", $otherExpenditure) == 1) { echo "checked"; } ?> >
                          <span class="custom-check"></span> Tolls
                        </label>
                      </td>
                      <td>
                        <input <?php foreach ($otherExpenditure as $key => $value) { if($value['name'] == "tolls") { ?>value="<?php echo $value['price']; ?>"<?php } } ?>  type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Tolls Expences"  <?php if(in_array_r("tolls", $otherExpenditure) == 0) { echo "disabled"; } ?>>
                      </td>
                    </tr>
                    <tr>
                        <td>
                         <label for="jp_other_expenses_tips" class="custom-checkbox">
                        <input id="jp_other_expenses_tips" value="tips" name="jp_other_expenses[]" type="checkbox" <?php if(in_array_r("tips", $otherExpenditure) == 1) { echo "checked"; } ?> >
                        <span class="custom-check"></span> Tips
                      </label>
                      </td>
                      <td>
                        <input <?php foreach ($otherExpenditure as $key => $value) { if($value['name'] == "tips") { ?>value="<?php echo $value['price']; ?>"<?php } } ?>  type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Tips Expences"  <?php if(in_array_r("tips", $otherExpenditure) == 0) { echo "disabled"; } ?>>
                      </td>
                    </tr>
                    <tr>
                        <td>
                       <label for="jp_other_expenses_other" class="custom-checkbox">
                        <input id="jp_other_expenses_other"  value="other" name="jp_other_expenses[]" type="checkbox" <?php if(in_array_r("other", $otherExpenditure) == 1) { echo "checked"; } ?> >
                        <span class="custom-check"></span> Other
                      </label>
                      </td>
                      <td>
                        <input <?php foreach ($otherExpenditure as $key => $value) { if($value['name'] == "other") { ?>value="<?php echo $value['price']; ?>"<?php } } ?>  type="text" name="ExpenceName[]" class="expences_input_value input small" Placeholder="Other Expences"  <?php if(in_array_r("other", $otherExpenditure) == 0) { echo "disabled"; } ?>>
                      </td>
                    </tr></table>
                    <input type="submit" value="Submit" class="btn btn-primary" name="submitValue">
                    </form>
      </div>
     
    </div>

  </div>
</div>

<div id="ViewJobModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body View_Job">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div id="EditJobModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <form action="" method="post" id="editActivity" novalidate="novalidate">
        <div class="modal-body Edit_Job">
          <p>Some text in the modal.</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>