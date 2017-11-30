<?php
class jobs extends Model
{
	
	/* Get All Jobs */
	public function Get_all_jobs($table)
	{
		$Data = $this->get_all($table);
		
		$table ='';
		$table .= '<table width="100%" class="table table-striped table-bordered table-hover dataTables">';		
		$table .= '<thead>
					<tr>
						<th>Contractor Username</th>
						<th>Job Title</th>						
						<th>Activities</th>
						<th>Job Status</th>					
						<th>View Job</th>									
					</tr>
				</thead>';	
		$table .='<tbody>';		
		foreach($Data as $keys)
		{
			$username = $this->get_single_row_columns('username','id', $keys["job_author"],'flex_users');	
			/* Job Status */
								
			switch ($keys["jobjob_status"]) {
				case 1:
					$status = 'Job Open';
					break;
				case 2:
					$status = 'Hired';
					break;
				case 3:
					$status = 'Completed';
					break;
				case 4:
					$status = 'Deleted';
					break;
				case 5:
					$status = 'Suspended';
					break;
				default:
					$status = 'Unknown';;
			}


			
			$table .='<tr>';
				$table .='<td>'.$username["username"].'</td>';
				$table .='<td>'.$keys["job_title"].'</td>';
				$table .='<td>'.$keys["job_activity"].'</td>';
				$table .='<td>'.$status.'</td>';
				$table .='<td><a href="'.BASE_URL.'admin/viewJob/'.$keys["job_slug"].'">View Job</td>';
			$table .='</tr>';	
			
		}
		$table .='</tbody>';	
		$table .='</table>';	
		return 	$table;
	}
	
	/* Get Single Row Data */
	public function Get_row($wherekey,$whereval,$table)
	{
		return $this->get_single_row($wherekey,$whereval,$table);
	}
	
	/* Get Single Row Data */
	public function getJob($wherekey,$whereval,$table)
	{
		$Data = $this->get_single_row($wherekey,$whereval,$table);
		//print_r($Data);
		
		$title = $Data['job_title'];
		$Attachment = $this->get_single_row('id',$Data['job_attachment'],PREFIX.'attachments');
		
		$content = '';
		$payment = '';
		$Description = '';
		$Description .= '<h3><u>Description</u></h3>';
		$Description .= '<p>'.$Data['job_description'].'</p>';
		$Description .= '<h3><u>Industries Knowledge Required :- </u></h3>';
		$Description .= '<p>'.$Data['job_industry_knowledge'].'</p>';
		
		
		if($Data['job_employee_type'] == 'no_preference')
			{	$Emplyee_Type = 'No Prefrenece'; 'No Prefrenece'; }
		else
			{ $Emplyee_Type = $Data['job_employee_type'];	}
		
		$payment .= '<h3><u>Other Information</u></h3>';
		$payment .= '<p> <strong>Job Price</strong>  : $'.$Data['job_price'].'</p>';
		$payment .= '<p> <strong>Language</strong> : '.$Data['job_language'].'</p>';
		$payment .= '<p> <strong>Employee type</strong> : '.$Emplyee_Type.'</p>';
		//$payment .= '<p> Additional Hours billed : '.$Data['job_additional_hours'].'</p>';	
		$payment .= '<p> <strong>Fixed or Hourly</strong> : '.$Data['job_type'].'</p>';
		//$payment .= '<p> <strong>Employee Type Prefer</strong> : '.$Data['job_employee_type'].'</p>';
		$payment .= '<p> <strong>Allowable Overages</strong> : '.$Data['job_additional_hours'].'</p>';
		
		$profile = $this->getProfile($Data['job_author']);
		return array('title'=>$title,'content'=>$content,'profile'=>$profile,'Activity'=>$this->getActivity($Data['id']),'Payment'=>$payment,'Description'=>$Description,'Attachment'=>$Attachment['url']);
		
		
	}
	
	private function getActivity($value)
	{
		$Activ =  $this->get_table_data('flex_job_activities','job_id',$value,'ASC');
		
		$table ='';
		$table .= '<h4>Activity Details :</h4>';
		$table .= '<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">';		
		$table .= '<thead>
					<tr>
						<th>Sr.No</th>
						<th>Activity Name</th>
						<th>Start Time</th>						
						<th>End Time</th>						
						<th>Flexibility</th>
						<th>Location</th>														
					</tr>
				</thead>';	
		$table .='<tbody>';$x=1;			
			foreach($Activ as $keys):
			$table .='<tr>';	
				$table .='<td>'.$x.'</td>';	
				$table .='<td>'.$keys["activity_name"].'</td>';	
				$table .='<td>'.$keys["start_datetime"].'</td>';	
				$table .='<td>'.$keys["end_datetime"].'</td>';	
				$table .='<td>'.$keys["activity_type"].'</td>';	
				$table .='<td>'.$keys["city"].'</td>';	
			$table .='</tr>';
			$x++;	
			endforeach;
		$table .='</tbody>';	
		$table .='</table></div>';	
		return $table;
	}

	private function getProfile($userId)
	{
		$data = $this->get_single_row('id',$userId,'flex_users');
		
		$join_date = date_create($data["created_date"]);
		$login_date = date_create($data["last_login_time"]);
		
		$profile = '';
		
		$profile .= '<div class="table-responsive"><table class="table">';
		$profile .= '<tr class="bg-primary text-white"><td><h2>'.$data["first_name"].' '.$data["last_name"].'</h2></td></tr>';
		$profile .= '<tr><td> Review : Pending !</td></tr>';
		$profile .= '<tr><td> Payment Verified : Pending !</td></tr>';
		$profile .= '<tr><td> Member Since : '.date_format($join_date,"Y/m/d").'</td></tr>';
		$profile .= '<tr><td> Last Active  : '.date_format($login_date,"Y/m/d").'</td></tr>';
		$profile .= '<tr><td> <button class="btn btn-info"> View Profile</td></tr>';
		$profile .= '</table>';	
		
		return $profile;
		
	}	

	
	/* Public Add New Job */
	public function InsertJob()
	{
		
	}
}
