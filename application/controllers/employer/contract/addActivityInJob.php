<?php 
echo $_POST['user_id'];
echo $_POST['job_id'];
/*	echo "<pre>";
		print_r($_POST);
	echo "</pre>";*/
	if (isset($_POST['activity_pricee'])) {
			$activity_pricee = $_POST['activity_pricee'];
		} else {
			$activity_pricee = "";
		}
		$index = 0;
		$indexactivityType = $index+1;
		$dateTime = $_POST['jp_act_start_date']." ".$_POST['jp_act_start_time'];
		$enddateTime = $_POST['jp_act_end_date']." ".$_POST['jp_act_end_time'];
		$datarecord = $this->Model->get_Data_table(PREFIX.'states','id',$_POST['jp_act_state']);
		$state = $datarecord[0]['name'];
		$state_slug = create_url_slug($state);
		$city_slug = create_url_slug($_POST['jp_act_city']);
		$finalLocation = $state_slug.",".$city_slug;
		$latlng = latlong($finalLocation);
		$latlngArray = explode("," , $latlng);
		$lat = $latlngArray[0];
		$fileName = time();
		$lng = $latlngArray[1];
		$data2 =  array(
		'job_id'=>$_POST['job_id'], 
		'activity_name'=>$_POST['jp_activity_name'], 
		'activity_type'=>$_POST['jp_start_stop_time'.$indexactivityType],
		'start_datetime'=>$dateTime,
		'end_datetime'=>$enddateTime,
		'state'=>$_POST['jp_act_state'],
		'city'=>$_POST['jp_act_city'],
		'street'=>$_POST['jp_act_street'],
		'zip'=>$_POST['jp_act_zip'],
		'first_name'=>$_POST['jp_act_cont_fname'],
		'last_name'=>$_POST['jp_act_cont_lname'],
		'phone'=>$_POST['jp_act_cont_phne'],
		'email'=>$_POST['jp_act_cont_email'],
		'notes'=>$_POST['jp_act_notes'],
		'latitude'=>$lat,
		'longitude'=>$lng,
		'created_date'=>$fileName,
		'job_price'=>$activity_pricee,
		'modified_date'=>$fileName
		);
		echo $Results2 = $this->Model->Insert_users($data2,PREFIX.'job_activities');


		$data=array(
			'contractor_id'=>$_POST['user_id'],
			'job_id'=>$_POST['job_id'],
			'activity_id'=>$Results2,
			'company_proposal_rate'=>"",
			'payment_terms'=>"accepted",
			'proposal_type'=>"",
			'proposal_rate'=>"",
			'training_acceptance'=>1,
			'cover_letter'=>"",
			'message'=>"",
			'created_date'=>date('Y-m-d h:m:s')
			);
$applied_job_id=$this->Model->Insert_users($data,PREFIX.'applied_jobs'); 

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
?>