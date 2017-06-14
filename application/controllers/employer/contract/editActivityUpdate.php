<?php 
$jp_activity_name = $_POST['jp_activity_name'];
$activity_id = $_POST['activity_id'];
$jp_start_stop_time1 = $_POST['jp_activity_name'];
$jp_act_start_date = $_POST['jp_act_start_date'];
$jp_act_start_time = $_POST['jp_act_start_time'];
$jp_act_end_date = $_POST['jp_act_end_date'];
$jp_act_end_time = $_POST['jp_act_end_time'];
$jp_act_state = $_POST['jp_act_state'];
$jp_act_city = $_POST['jp_act_city'];
$jp_act_street = $_POST['jp_act_street'];
$jp_act_zip = $_POST['jp_activity_name'];
$jp_act_cont_fname = $_POST['jp_act_cont_fname'];
$jp_act_cont_lname = $_POST['jp_act_cont_lname'];
$jp_act_cont_phne = $_POST['jp_act_cont_phne'];
$jp_act_cont_email = $_POST['jp_act_cont_email'];
$jp_act_notes = $_POST['jp_act_notes'];
$date = time();
$data = array(
		"activity_name" => $jp_activity_name,
		"activity_type" => $jp_start_stop_time1,
		"start_datetime" => $jp_act_start_date." ".$jp_act_start_time,
		"end_datetime" => $jp_act_end_date." ".$jp_act_end_date,
		"state" => $jp_act_state,
		"city" => $jp_act_city,
		"street" => $jp_act_street,
		"zip" => $jp_act_zip,
		"first_name" => $jp_act_cont_fname,
		"last_name" => $jp_act_cont_lname,
		"phone" => $jp_act_cont_phne,
		"email" => $jp_act_cont_email,
		"notes" => $jp_act_notes,
		"modified_date" => $date
		);
	$dataToBeUpdatec = $this->Model->update_record($data,"id",$activity_id,PREFIX.'job_activities');

$jobApplication = $this->Model->Get_column_Double('*','contractor_id',$_POST['user_id'],'job_id',$_POST['job_id'],PREFIX.'applied_jobs');
 ?>

  <tr>
<th scope="col">Activity Name</th>
<th scope="col">Activity Time</th>
<th scope="col">Location</th>
<th scope="col">Contact Name </th>
<th scope="col">Close Job</th>
<th class="noBorder" scope="col">&nbsp;</th>
</tr>
<?php 
$i = 0;
foreach ($jobApplication as $key => $value) {
$contractor_id = $value['activity_id'];
$emplyerdetails = $this->Model->Get_column1('*','id',$contractor_id,PREFIX.'job_activities');

?>
<tr>
<td><input type="hidden" class="activityId" value="<?php echo $emplyerdetails[0]['id']; ?>"> <input type="hidden" class="appliedId" value="<?php echo $value['id']; ?>"><?php echo $emplyerdetails[0]['activity_name']; ?></td>;
<td><?php echo $emplyerdetails[0]['start_datetime']; ?></td>
<td><a href="#"><?php echo $emplyerdetails[0]['city']; ?></a></td>
<td><?php echo $emplyerdetails[0]['first_name']; ?> <?php echo $emplyerdetails[0]['last_name']; ?></td>
<td align="center"><label for="closeJob_<?php echo $i; ?>" class="custom-checkbox noLabel" >
  <input type="hidden" class="thisJobIdActivity" value="<?php echo $emplyerdetails[0]['id']; ?>">
  <input <?php if($emplyerdetails[0]['job_status'] ==1) { echo "checked"; } ?> id="closeJob_<?php echo  $i; ?>" type="checkbox" onclick="JobStatus(this);">
  <span class="custom-check"></span></label></td>
<td class="noBorder"><div class="actionButtons"> <a href="javascript:void(0)" onclick="viewActivity(this);" class="btn btn-blue">View</a> <a href="javascript:void(0)" onclick="deleteActivity(this);" class="btn btn-gray">Delete </a> <a href="javascript:void(0)" onclick="editActivity(this);" class="btn btn-blue">Edit</a> </div></td>
</tr>
<?php 
$i++; }