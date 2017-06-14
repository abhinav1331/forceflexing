<?php 
	echo "<pre>";
		print_r($_POST);
	echo "</pre>";
	$ActivityId = $_POST['ActivityId'];
	$status = $_POST['status'];
	$data = array("job_status" => $status);
	$dataToBeUpdatec = $this->Model->update_record($data,"id",$ActivityId,PREFIX.'job_activities');
 ?>