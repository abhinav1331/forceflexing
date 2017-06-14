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
			$table .='<tr>';
				$table .='<td>'.$username["username"].'</td>';
				$table .='<td>'.$keys["job_title"].'</td>';
				$table .='<td>'.$keys["job_activity"].'</td>';
				$table .='<td>'.$keys["jobjob_status"].'</td>';
				$table .='<td><a href="'.BASE_URL.'admin/allJobs/?pop=on&viewJob='.$keys["job_slug"].'">View Job</td>';
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
		
		$title = '<h2>'.$Data['job_title'].'</h2>';
		
		$content = '';
		$content .= '<h3>Description</h3>';
		$content .= '<p>'.$Data['job_description'].'</p>';
		$content .= '<hr>';
		$content .= '<h3>Preferred Qualifications</h3>';
		$content .= '<p> Language : '.$Data['job_language'].'</p>';
		$content .= '<p> Employee type : '.$Data['job_employee_type'].'</p>';
		$content .= '<p> Hours billed : '.$Data['job_additional_hours'].'</p>';
		$content .= '<hr>';
		$content .= '<h3>Activities Details</h3>';
		$content .= '<p>'.$this->getActivity($Data['id']).'</p>';
		$content .= '<hr>';
		$content .= '<h3>Payment Information</h3>';
		$content .= '<p> Fixed or Hourly : '.$Data['job_type'].'</p>';
		$content .= '<p> Allowable Expenses : '.$Data['job_employee_type'].'</p>';
		$content .= '<p> Allowable Overages : '.$Data['job_additional_hours'].'</p>';
		
		$profile = $this->getProfile($Data['job_author']);
		return array('title'=>$title,'content'=>$content,'profile'=>$profile);
		
		
	}
	
	private function getActivity($value)
	{
		$Activ =  $this->get_table_data('flex_job_activities','job_id',$value,'ASC');
		
		$table ='';
		$table .= '<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">';		
		$table .= '<thead>
					<tr>
						<th>Activity Name</th>
						<th>Start Time</th>						
						<th>End Time</th>						
						<th>Flexibility</th>
						<th>Location</th>														
					</tr>
				</thead>';	
		$table .='<tbody>';			
			foreach($Activ as $keys):
			$table .='<tr>';	
				$table .='<td>'.$keys["activity_name"].'</td>';	
				$table .='<td>'.$keys["start_datetime"].'</td>';	
				$table .='<td>'.$keys["end_datetime"].'</td>';	
				$table .='<td>'.$keys["activity_type"].'</td>';	
				$table .='<td>'.$keys["city"].'</td>';	
			$table .='</tr>';	
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
