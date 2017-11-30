<?php
	if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			$role=$this->udata['role'];
			$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
			if($role_name['role_name'] == 'contractor')
			{
				$applied_job_id=$_GET['applied_job'];
				$this->loadview('main/header')->render();
				$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
				$this->navigation($data);
				$template = $this->loadview('contractor/contractor_views/result_after_applying');
				
				/*get applied job data*/
				$results=$this->Model->Get_row('id',$applied_job_id,PREFIX.'applied_jobs');
				$template->set('app_job',$results);
				
				$jobid=$results['job_id'];
				
				/*get job related data*/
				$jobs=$this->Model->Get_row('id',$jobid,PREFIX.'jobs');
				$template->set('job',$jobs);
				
				$emp_id=$jobs['job_author'];
				
				/*COMPANY PROFILE INFORMATION*/
				/*get the company name*/
				$emp_details=$this->Model->Get_row('id',$emp_id,PREFIX.'users');
				$template->set('company_name',$emp_details['company_name']);
				
				/*get the country of employer*/
				$country_name=$this->Model->Get_column('name','sortname',$emp_details['country'],PREFIX.'countries');
				if(!empty($country_name))
					$template->set('emp_country',$country_name['name']);
				
				/*get the company information*/
				$company_info=$this->Model->Get_row('company_id',$emp_id,PREFIX.'company_info');
				if(!empty($company_info))
				{
					//get the city
					$cityname=$this->Model->Get_column('name','id',$company_info['company_city'],PREFIX.'cities');
					if(!empty($country_name))
						$template->set('emp_city',$cityname['name']);
					
					if(!empty($country_name))
							$template->set('emp_city',$cityname['name']);
						
						/*get the current local time of employer*/
						$current_time_emp=$this->local_time_of_employer($country_name['name'],$cityname['name']);
						$template->set('emp_curr_time',$current_time_emp);
				}
				
				/*get member since info */
				$template->set("member_since",$emp_details['created_date']);
				
				/*get the count of posted jobs*/
				$posted_jobs=$this->Model->get_count_with_multiple_cond(array('job_author'=>$emp_id,'job_visibility'=>'none'),PREFIX.'jobs');
				if(!empty($open_jobs))
					$template->set('posted_jobs',$posted_jobs);
				
				/*get the count of open jobs*/
				$open_jobs=$this->Model->get_count_with_multiple_cond(array('job_author'=>$emp_id,'job_visibility'=>'anyone'),PREFIX.'jobs');
				if(!empty($open_jobs))
					$template->set('open_jobs_count',$open_jobs);
				
				/*COMPANY PROFILE INFORMATION ENDS*/
				
				/*GET ACTIVITY ON THE JOB*/
				$job_proposals=array();
				$active_proposals=array();
				//get the job proposals
				$job_proposals=$this->Model->get_count_with_multiple_cond(array('job_id'=>$jobid,'status'=>0),PREFIX.'applied_jobs');
				if($job_proposals > 0)
					$template->set('proposals',$job_proposals);
				
				//get the active proposal list
				$conversation=$this->Model->Get_row('job_id',$jobid,PREFIX.'conversation_set');
				if(!empty($conversation))
				{
					//get the chat 
					$id=$conversation['id'];
					$sender=$conversation['conv_to'];
					$rec=$conversation['conv_from'];
					$active=$this->Model->get_count_with_multiple_cond(array('conv_id'=>$id,'from_id'=>$emp_id),PREFIX.'message_set');
					if($active > 0)
					{
						$template->set('active_prop',$active);
					}
				}
				
				/*get saved activities*/
				$saved_activities=explode(',',$results['activity_id']);
				$template->set('saved_activities',$saved_activities);
				
				/*get all activities*/
				$all_activities=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_activities');
				$template->set('all_activities',$all_activities);
				
				/*job overages*/
				$job_overages=$this->Model->Get_row('job_id',$jobid,PREFIX.'job_additional_hours');
				$template->set('job_overages',$job_overages);
				
				/*set allowable expenses*/
				$job_expenses=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_expenditure');
				$template->set('job_expenses',$job_expenses);
				
				/*get applied job answers if any*/
				$job_answers=$this->Model->Get_all_with_cond('applied_job_id',$applied_job_id,PREFIX.'applied_answers');
				$question_ans=array();
				if(!empty($job_answers))
				{
					foreach($job_answers as $j)
					{
						$array=array();
						$question_id=$j['question_id'];
						$questiodata=$this->Model->Get_row('id',$question_id,PREFIX.'job_questions');
						$array['question']=$questiodata['job_questions'];
						$array['answer']=$j['answer'];
						$array['id']=$j['id'];
						$question_ans[]=$array;
					}
				}
				/*set question-answers*/
				$template->set('question_answer',$question_ans);
				
				$template->set('instance',$this);
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