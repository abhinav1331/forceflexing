<?php 
if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
{
	$role=$this->udata['role'];
	$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
	if($role_name['role_name'] == 'contractor')
	{
		$this->loadview('main/header')->render();
		
		/* Navigation */
		$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
		$this->navigation($data);
		$template = $this->loadview('contractor/contractor_views/view_my_job_reports');
		
		/*Get the active and closed activities*/
		$active_activities=array();
		$closed_activities=array();
		
		/*get current user contracts*/
		$user_contracts=$this->Model->Get_all_with_cond('contractor_id',$this->userid,PREFIX.'hire_contractor');
		
		/*get the contract activities*/
		foreach($user_contracts as $contract)
		{
			$contract_activities=$this->Model->Get_all_with_cond('contract_id',$contract['id'],PREFIX.'hired_contractor_activity_status');
			
			/*Get the Job Details*/
			$job_details=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
			
			/*Get the employer Detials*/
			$employer=$this->Model->Get_row('id',$job_details['job_author'],PREFIX.'users');
			
			foreach($contract_activities as $activity)
			{
				$activity_id=$activity['activity_id'];
				$array=array();
				$array['contract_id']=$contract['id'];
				$array['employer']=$employer['first_name']." ".$employer['last_name'];
				$array['job_name']=$job_details['job_title'];
				
				/*get the activity Details*/
				$activity_details=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
				$array['activity_name']=$activity_details['activity_name'];
				$array['activity_location']=$activity_details['city'];
				$array['contact_name']=$activity_details['first_name']." ".$activity_details['last_name'];
				
				if($activity['status'] == 1 && $activity['job_report_status'] == 2)
				{
					//activity is completed 
					$array['date']=$activity['modified_date'];;
					$array['job_report']='<a href="'.BASE_URL.'contractor/submit_report/?id='.$activity['id'].'">View</a>';
					
					/*Get feedback from feedback table*/
					
					$array['feedback']="";
					$array['amount_paid']="";
					$closed_activities[]=$array;
				}
				else
				{
					//activity is active currently
					$array['activity_time']=$activity_details['start_datetime'];
					$text="";
					if($activity['job_report_status'] == 0)
						$text="N/A";
					elseif($activity['job_report_status'] == 1)
						$text="View";
						
					$array['job_report']='<a href="'.BASE_URL.'contractor/submit_report/?id='.$activity['id'].'">'.$text.'</a>';
					
					if($activity['status'] == 0)
						$status="Pending";
					else
						$status="Completed";
					
					$array['status']=$status;
					$active_activities[]=$array;
				}
			}
		}
		$template->set('active',$active_activities);
		$template->set('closed',$closed_activities);
		$template->render();
		$this->loadview('main/footer')->render();	
	}
	else
	{
		$this->no_access();
	}
}
else
{
	$this->redirect('login');
}
?>