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
		$template = $this->loadview('contractor/contractor_views/view_contractor_stats');
		//get the applied jobs
		$applied_jobs_count=$this->Model->get_count('contractor_id',$this->userid,PREFIX.'applied_jobs');
		$template->set('appl_job_count',$applied_jobs_count);
		
		//get the won jobs (number of times contractor is hired)
		$count_hired_jobs=$this->Model->get_count_with_multiple_cond(array('contractor_id'=>$this->userid, 'status'=> 1),PREFIX.'hire_contractor');
		$template->set('won_job_count',$count_hired_jobs);
		
		//get the employer proposals
		$employer_proposal=$this->Model->get_count('contractor_id',$this->userid,PREFIX.'job_invite');
		$template->set('employer_proposals',$employer_proposal);
		
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