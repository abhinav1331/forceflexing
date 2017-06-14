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