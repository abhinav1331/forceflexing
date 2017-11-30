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
		 
		$contract_id=$_GET['contract_id'];
		$template = $this->loadview('contractor/contractor_views/view_activity_detail');
		if(isset($contract_id))
		{
			$job=array();
			//get the contract related data
			$contract_data=$this->Model->Get_row('id',$contract_id,PREFIX.'hire_contractor');
			if($contract_data['status'] == 1)
			{
				/**JOB RELATED DATA*/
				
				$job_id=$contract_data['job_id'];
				$job['job_id']=$job_id;
				
				/*get the job data*/
				$jobdata=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
				$job['job_title']=$jobdata['job_title'];
				
				/*get the employer details*/
				$employer_details=$this->Model->Get_row('id',$jobdata['job_author'],PREFIX.'users');
				$employer_company_info=$this->Model->Get_row('company_id',$jobdata['job_author'],PREFIX.'company_info');
				
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
				
				/*ACTIVITIES RELATED DATA*/
				$activities=$this->Model->Get_all_with_cond('contract_id',$contract_id,PREFIX.'hired_contractor_activity_status','asc');
					
				//completed activities
				$completed_acti=array();
				$pending_acti=array();
				
				foreach($activities as $activity)
				{
					$activity_id=$activity['activity_id'];
					if($activity['status'] != 2)
					{
						$acti=array();
						//activity details
						$activity_detail=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
						if(!empty($activity_detail))
						{
							$acti['id']=$activity_detail['id'];
							$acti['activity_name']=$activity_detail['activity_name'];
							$acti['start_time']=$activity_detail['start_datetime'];
							
							/* $activity_city=$this->Model->Get_column('name','id',$activity_detail['city'],PREFIX.'cities');
							$acti['city']=$activity_city['name']; */
							
							$acti['city']=$activity_detail['city'];
							
							
							$acti['contact_name']=$activity_detail['first_name']." ".$activity_detail['last_name'];
							
							/*Job Report Status and price status*/
							$intial=0;
							if($activity['status'] == 0 && $activity['job_report_status'] == 0)
							{
								$acti['job_report_status']="N/A";
								$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
								$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
							}
							elseif($activity['status'] == 0 && $activity['job_report_status'] == 1)
							{
								$acti['job_report_status']="Received";
								$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
								$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
							}
							elseif($activity['status'] == 1 && $activity['job_report_status'] == 0)
							{
								$acti['job_report_status']="Not Received";
								$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
								$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
							}
							elseif($activity['status'] == 1 && $activity['job_report_status'] == 1)
							{
								$acti['job_report_status']="Not Paid";
								$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
								$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
							}
							elseif($activity['status'] == 1 && $activity['job_report_status'] == 2)
							{
								$acti['job_report_status']="Paid";
								$acti['amount_due']=number_format((float)$intial, 2, '.', '');
								$acti['amount_paid']=number_format((float)$activity_detail['job_price'], 2, '.', '');
							}
							
							
							
							
							if($activity['status'] == 0)
								$pending_acti[]=$acti;
							elseif($activity['status'] == 1)
								$completed_acti[]=$acti;
						}
					}
				}
				/*END OF ACTIVITIES*/
				$template->set('contract_id',$contract_id);
				$template->set('job',$job);
				$template->set('completed_activites',$completed_acti);
				$template->set('pending_activities',$pending_acti);
			}
			else
			{
				$template->set('error','No Activity Details to view, either the contract has ended or contract does not exist.');
			}
		}
		else
		{
			$template->set('error','No Activity Details to View.');
		}
		
		
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