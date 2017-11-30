<?php
class contracts extends Model
{
	
	/* Get All Jobs */
	public function Get_all_contracts($table)
	{
		$Data = $this->get_all($table);
		//print_r($Data);
		// Get User Details
		// employer_id	
		// contractor_id
		
		$table ='';
		$table .= '<table width="100%" class="table table-striped table-bordered table-hover dataTables">';		
		$table .= '<thead>
					<tr>
						<th>Job Title</th>
						<th>Employee Email ID</th>						
						<th>Contractor Email ID</th>
						<th>Contract Status</th>					
						<th>View Contract</th>									
					</tr>
				</thead>';	
		$table .='<tbody>';		
		foreach($Data as $keys)
		{
			switch ($keys["status"]) {
				case 0:
					$status = 'Contract Created';
					break;
				case 1:
					$status = 'Contract Accepted';
					break;
				case 2:
					$status = 'Contract Declined';
					break;
				case 3:
					$status = 'Contract Ended';
					break;					
				default:
					$status = 'Unknown';;
			}
			
			$Contractor = $this->get_single_row_columns('email','id', $keys["contractor_id"],PREFIX.'users');	
			$employee = $this->get_single_row_columns('email','id', $keys["employer_id"],PREFIX.'users');
			$JobDetails = $this->get_single_row_columns('job_title','id', $keys["job_id"],PREFIX.'jobs');
			
			$table .='<tr>';
				$table .='<td>'.$JobDetails["job_title"].'</td>';
				$table .='<td>'.$employee["email"].'</td>';
				$table .='<td>'.$Contractor["email"].'</td>';
				$table .='<td>'.$status.'</td>';
				$table .='<td><a href="'.BASE_URL.'admin/Viewcontracts/'.base64_encode($keys["id"]).'">View Contract</td>';
			$table .='</tr>';
			
		}
		$table .='</tbody>';	
		$table .='</table>';	
		return 	$table;
	}
	
	/* Get Single Row Data */
	/* public function Get_row($wherekey,$whereval,$table)
	{
		return $this->get_single_row($wherekey,$whereval,$table);
	} */
	
	public function getActivity($value,$InArray = array())
	{
		$Activ =  $this->get_table_data(PREFIX.'job_activities','job_id',$value,'ASC');
		
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
						<th>Status</th>														
						<th>Reports</th>														
					</tr>
				</thead>';	
		$table .='<tbody>';$x=1;			
			foreach($Activ as $keys):
			if(in_array($keys['id'],$InArray)):
			$table .='<tr>';	
				$table .='<td>'.$x.'</td>';	
				$table .='<td>'.$keys["activity_name"].'</td>';	
				$table .='<td>'.$keys["start_datetime"].'</td>';	
				$table .='<td>'.$keys["end_datetime"].'</td>';	
				$table .='<td>'.$keys["activity_type"].'</td>';	
				$table .='<td>'.$keys["city"].'</td>';	
				$table .='<td>'.$keys["city"].'</td>';	
				$table .='<td>'.$keys["city"].'</td>';	
			$table .='</tr>';
			$x++;
			endif;	
			endforeach;
		$table .='</tbody>';	
		$table .='</table></div>';	
		return $table;
	}
	
	
	public function getContractsDetails($value)
	{
		return $this->get_table_data(PREFIX.'hire_contractor','id',$value,'ASC');
	}
	
	public function getpaymentDetails($JobID,$ContractID)
	{
		$Job =  $this->get_single_row('id',$JobID,PREFIX.'jobs');
		$Contract =  $this->get_single_row('id',$ContractID,PREFIX.'hire_contractor');
			
		$expanditure = json_decode($Contract['external_expanditure']);
		$overage = json_decode($Contract['overage']);
		
		$overageslist = '';
		$overageslist .='<p>Before : '.$overage[0].'</p>';
		$overageslist .='<p>After  : '.$overage[1].'</p>';
		$overageslist .='<p>Price  : $'.$overage[2].'</p>';
		
		$expand = '';
		foreach($expanditure as $keys):
			$expand .='<p>'.$keys->name.' : Yes</p>';
		endforeach;
		
		
		$payment = '';
		$payment .= '<h2>Payment Terms</h2>';
		$payment .= '<p>Fixes or Hourly:  '.$Job["job_type"].'</p>';
		$payment .= '<p>Rate of Pay:  $'.$Contract["flex_amount"].' </p>';
		$payment .= '<h4>Allowable Work Hour Overages:</h4>';
		$payment .= $overageslist;
		$payment .= '<h4>Allowable Expenses:</h4>';
		$payment .= $expand;
		
		return $payment;
	}
	
	private function getProfile($userId)
	{
		
		
	}	

}
