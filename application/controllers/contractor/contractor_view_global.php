<?php 
if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
{
	/*Get the login user details*/
	$current_user=$this->Model->Get_row('username',$this->udata['username'],PREFIX.'users');
	
	/*get user data of contractor profile which has to be viewed*/
	$user=$this->Model->Get_row('username',$username,PREFIX.'users');
	
	if(!empty($user))
	{
		$this->loadview('main/header')->render();
		if($current_user['role'] == 3)
		{
			$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
			$this->navigation($data);
		}
		elseif($current_user['role'] == 2)
		{
			$data=$this->Model->Get_row('company_id',$this->userid,PREFIX.'company_info'); 
			
			//employer navigation
			$getUserNavigation = $this->loadview('Employer/main/navigation');
			$getUserNavigation->set("nameEmp" , $data['company_slug']);
			$getUserNavigation->set("profile_img" , $data['company_image']);
			$getUserNavigation->render();
			
		}
		
		if($current_user['id'] != $user['id'])
		{
			/*get the previous profile view count*/
			$prof=$this->Model->Get_column('profile_views','user_id',$user['id'],PREFIX.'contractor_profile');
			$count=$prof['profile_views'] + 1;
			
			/*update the profile view count*/
			$this->Model->Update_row(array('profile_views'=>$count),'user_id',$user['id'],PREFIX.'contractor_profile');
		}
		
		
		$template = $this->loadview('contractor/contractor_views/global_contractor_profile');
		$template->set('instance',$this);
		$template->set('user',$user);
		
		/*get the user profile data*/
		$user_data=$this->Model->Get_row('user_id',$user['id'],PREFIX.'contractor_profile');
		$template->set('userdata',$user_data);
		
		/*get the user availability record*/
		$contractors_list=$this->Model->Get_all_with_multiple_cond(array('contractor_id'=>$user['id'],'status'=>1),PREFIX.'hire_contractor');
		
		//get the activity details
		//inactive dates
		$inactive_dates=array();
		if(!empty($contractors_list))
		{
			foreach($contractors_list as $contractor)
			{
				$activities=json_decode($contractor['activity_id']);
				//get the start and end date for the activity
				if(!empty($activities))
				{
					foreach($activities as $activity)
					{
						$range=array();
						//get the start and date for each activity
						$acti=$this->Model->Get_row('id',$activity,PREFIX.'job_activities');
					$range['startdate']=date('Y-m-d',strtotime($acti['start_datetime']));
					$range['enddate']=date('Y-m-d',strtotime($acti['end_datetime']));
						$inactive_dates[]=$range;
					}
				}
			}
		}
		
		/*get total numbr of jobs completed*/
		$arguments=array('contractor_id'=>$user['id'],'status'=>3);
		$completed_jobs=$this->Model->Get_all_with_multiple_cond($arguments,PREFIX.'hire_contractor');
		
		$count_completed_jobs=count($completed_jobs);
		
		/*getting total hours worked*/
		$contracts=array();
		
		/*get the contract_id for completed jobs*/
		if(!empty($completed_jobs))
		{
			foreach($completed_jobs as $comp)
			{
				$contracts[]=$comp['id'];
			}
		}
		/*get all status ids for the completed contracts*/
		$statusids=array();
		if(!empty($contracts))
		{
			$contractids=implode(',',$contracts);
			$qry='select * from '.PREFIX.'hired_contractor_activity_status where contract_id IN("'.$contractids.'")';
			$res=$this->Model->filter_data($qry);
			if(!empty($res))
			{
				foreach($res as $r)
				{
					$statusids[]=$r['id'];
				}
			}
		}
		
		$hours=0;
		if(!empty($statusids))
		{
			$sid=implode(',',$contracts);
			$qry='select * from '.PREFIX.'hired_contractor_activity_report where activity_status_id IN("'.$sid.'")';
			$res=$this->Model->filter_data($qry);
			if(!empty($res))
			{
				foreach($res as $r)
				{
					$activity_details=json_decode($r['day_details']);
					if(!empty($activity_details))
					{
						foreach($activity_details as $det)
						{
							$arrival_time=$det->arrival_time;
							$depart_time=$det->depart_time;
							$time1 = strtotime($arrival_time);
							$time2 = strtotime($depart_time);
							$difference = round(abs($time2 - $time1) / 3600,2);
							$hours += $difference;
						}
					}
				}
			}
		}
		
		/*work history and job feedback section*/
		$history_feedback=array();
		if(!empty($contracts))
		{
			$contractids=implode(',',$contracts);
			
			/*get the feedback*/
			$qry='select * from '.PREFIX.'user_feedback where contract_id IN ("'.$contractids.'") and feedback_to='.$user['id'].' order by id desc limit 5';
			$res=$this->Model->filter_data($qry);
			if(!empty($res))
			{
				foreach($res as $r)
				{
					$array=array();
					/*Get the rating*/
					$average=$r['total_average_score'];
					$array['rating']=$average/2;
					
					$co_id=$r['contract_id'];
					
					//get the contract Details
					$con_det=$this->Model->Get_row('id',$co_id,PREFIX.'hire_contractor');
					$job=$con_det['job_id'];
					$activities=implode(',',json_decode($con_det['activity_id']));
					$applied_job_id=$con_det['applied_job_id'];
					
					/*get the job details*/
					$job_data=$this->Model->Get_row('id',$job,PREFIX.'jobs');
					$job_type=$job_data['job_type'];
					$array['job_title']=$job_data['job_title'];
					$array['start_date']=$con_det['created_date'];
					$array['end_date']=$con_det['modified_date'];
					
					
					/*check whether same was accepted*/
					$applied_job_data=$this->Model->Get_row('id',$applied_job_id,PREFIX.'applied_jobs');
					if($applied_job_data['payment_terms'] == "new_terms")
					{
						$job_type=$applied_job_data['proposal_type'];
					}
					$array['job_type']=$job_type;
					
					/*get the earning from the contract*/
					$que='select * from '.PREFIX.'hired_contractor_activity_status where activity_id IN("'.$activities.'")';
					$rslt=$this->Model->filter_data($que);
					if(!empty($rslt))
					{
						$status_idarray=array();
						foreach($rslt as $rs)
						{
							$status_idarray[]=$rs['id'];
						}
						$statusids=implode(',',$status_idarray);
						$ear_que='SELECT (sum(total_activity_amount) + sum(total_expense_amount) ) as total FROM flex_hired_contractor_activity_report where activity_status_id IN ("'.$statusids.'")';
						
						$result_earning=$this->Model->filter_data($ear_que);
						$array['total_earning']=$result_earning[0]['total'];
					}
				}
				$history_feedback[]=$array;
			}
		}
		
		/*get contractor in progress jobs*/
		$inprogressquery='SELECT COUNT(id) AS totaljobs FROm flex_hire_contractor where contractor_id="'.$user['id'].'"';
		$inprogress=$this->Model->filter_data($inprogressquery);
		
		$template->set('inprogress',$inprogress[0]['totaljobs']);
		$template->set('completed_jbs',$count_completed_jobs);
		$template->set('total_hours',$hours);
		
		$template->set('inactive_dates',json_encode($inactive_dates));
		$template->set('instance',$this);
		
		$template->set('working_history',$history_feedback);
		$template->render();
		$this->loadview('main/footer')->render();
	}
	
}
else
{
	$this->redirect('login');
}
?>