<?php 
echo $activityId = $_POST['activityId'];
$FlexPrice = $this->Model->Delete_all_Records(PREFIX.'job_activities','id',$activityId);
$FlexDelete = $this->Model->Delete_all_Records(PREFIX.'applied_answers','activity_id',$activityId);
echo "<pre>";
	print_r($FlexPrice);
echo "</pre>";
 ?>