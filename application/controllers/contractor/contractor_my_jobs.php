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
		
		$template = $this->loadview('contractor/contractor_views/view_my_jobs');
		
		/*ALL JOBS OF CONTRACTOR FOR WHICH CONTRACTOR IS HIRED*/
		$all_jobs=array();
		$pending_jobs=array();
		$pending_job_report=array();
		$completed_activities=array();
		
		$where_cond=array('contractor_id'=>$this->userid, 'status'=>1);
		$contracts_contr=$this->Model->Get_all_with_multiple_cond($where_cond,PREFIX.'hire_contractor',$order="desc");
		foreach($contracts_contr as $c)
		{
			$job=array();
			$job['contract_id']=$c['id'];
			
			//get the employer of the job
			$employer=$this->Model->Get_row('id',$c['job_id'],PREFIX.'jobs');
			$employer_details=$this->Model->Get_row('id',$employer['job_author'],PREFIX.'users');
			$employer_company_info=$this->Model->Get_row('company_id',$employer['job_author'],PREFIX.'company_info');
			
			//employer image
			$employer_image=$employer_company_info['company_image'];
			if(!empty($employer_image))
				$emp_image_uri=BASE_URL.'static/images/employer/'.$employer_image;
			else
				$emp_image_uri=BASE_URL.'static/images/avatar-icon.png';
			
			$job['emp_image']=$emp_image_uri;
			
			//employer name
			//$job['emp_name']=$employer_details['company_name'];
			$job['emp_name']=$employer_details['first_name']." ".$employer_details['last_name'];
			
			//country
			$country=$employer_details['country'];
			$emp_country=$this->Model->Get_row('sortname',$country,PREFIX.'countries');
			$job['emp_country']=$emp_country['name'];
			
			//city
			$city=$employer_company_info['company_city'];
			$emp_city=$this->Model->Get_row('id',$city,PREFIX.'cities');
			$job['emp_city']=$emp_city['name'];
			
			//all activities count
			$all_acti=$c['activity_id'];
			$count_activities=count(json_decode($all_acti));
			$job['total_activities']=$count_activities;
			
			//done activities count
			$done_activities=$this->Model->get_count_with_multiple_cond(array('status'=>1,'contract_id'=>$c['id']),PREFIX.'hired_contractor_activity_status');
			$job['done_activity_count']=$done_activities;
			
			$all_jobs[]=$job;
			
			/*PENDING ACTIVITIES OF CONTRACTOR*/
			//get the pending activities of contractor
			$pending_acti=$this->Model->Get_all_with_multiple_cond(array('contract_id'=>$c['id'],'status'=>0),PREFIX.'hired_contractor_activity_status');
			if(!empty($pending_acti))
			{
				//no activity done so far , get activity id which has to be done
				$activity_id=$pending_acti[0]['activity_id'];
				
				//get the detials of current activity
				$activity_data=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
				$activity_start_date=strtotime($activity_data['start_datetime']);
				$activity_end_date=strtotime($activity_data['end_datetime']);
				
				$activity_state=$activity_data['state'];
				$activity_state=$this->Model->Get_row('id',$activity_state,PREFIX.'states');
				$activity_state=$activity_state['name'];
				
				$activity_city=$activity_data['city'];
				$activity_street=$activity_data['street'];
				
				$job['activity_location']=$activity_street.",".$activity_city.",".$activity_state;
				
				$job['activity_title']=$activity_data['activity_name'];
				
				$this->settimezone();
				$today=strtotime(date('Y-m-d h:m:s')); 
				if($today <= $activity_start_date)
				{
					$time =$this->time_elapsed_string($activity_data['start_datetime']);
					$time=str_replace('ago','',$time);
					$job['days']=$time;
					$job['daystext']="To ".$job['activity_title'];
				} 
				else
				{
					 $ago =$this->time_elapsed_string($activity_data['start_datetime']);
					 $job['days']=$ago;
					 $job['daystext']="was ".$job['activity_title'];
				}
				
				$pending_jobs[]=$job;
			}
			
			/*PENDING JOB REPORT*/
			//get the activities who has a pending job report
			$pend_job_report=$this->Model->Get_all_with_multiple_cond(array('contract_id'=>$c['id'],'status'=>1, 'job_report_status '=>0),PREFIX.'hired_contractor_activity_status');
			if(!empty($pend_job_report))
			{
				//store only if all activities are completed 
				if(count($pend_job_report) == $count_activities)
					$pending_job_report[]=$job;
			}
			
			/*COMPLETED JOBS*/
			//get the contracts with everythig completed
			$comp_acti=$this->Model->Get_all_with_multiple_cond(array('contract_id'=>$c['id'],'status'=>1, 'job_report_status '=>2),PREFIX.'hired_contractor_activity_status');
			if(!empty($comp_acti))
			{
				if(count($comp_acti) == $count_activities) 
				{
					//get the contract completed date
					$job['completed_date']=$c['modified_date'];
					$completed_activities[]=$job;
				}
			}
		}
		
		
		$template->set('all_jobs',$all_jobs);
		$template->set('pending_jobs',$pending_jobs);
		$template->set('pending_job_report',$pending_job_report);
		$template->set('completed_jobs',$completed_activities);
		
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
