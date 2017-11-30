<?php 
if (isset($_COOKIE['force_username']) || isset($_SESSION['force_username'])) {
	if (isset($_COOKIE['force_username'])) {
		$username = $_COOKIE['force_username'];
	} else {
		$username = $_SESSION['force_username'];
	}
	$current_user_data=$this->Model->login_user($username);

	//if user is logged in
	if(isset($current_user_data) && !empty($current_user_data))
	{
		$current_user_data['role'];
		
		$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
		//if employed then load page 
		if($role_name['role_name'] == 'employer') {
			$url=$_SERVER['REQUEST_URI'];
			$pos = strrpos($url, '/');
			$slug = $pos === false ? $url : substr($url, $pos + 1);
			$job_id=$this->Model->Get_column('*','job_slug',$slug,PREFIX.'jobs');
			if ($job_id['job_type'] == "hourly") {
				$jobType = "hourly";
			} else {
				$jobType = "fixed";
			}
			
			if ($job_id['job_author'] == $current_user_data['id']) {
				
				$emplyerdetails=$this->Model->Get_column('*','id',$job_id['job_author'],PREFIX.'users');
				
				$getUserRecord = $this->Model->get_Data_table(PREFIX.'job_activities','job_id',$job_id['id']);
				$jobActivityStatusPending = $this->EModel->jobActivityStatus($job_id['id'],0);

				$jobActivityStatusComplete = $this->EModel->jobActivityStatus($job_id['id'],1);

				$this->loadview('main/header')->render();
				
				$dataNavi = $this->Model->get_Data_table(PREFIX.'company_info','company_id',$current_user_data['id']);
				if (!empty($dataNavi)) {
					$profileImg = $dataNavi[0]['company_image'];
				} else {
					$profileImg = "";
				}
				$c=array('to_id'=>$current_user_data['id'],'is_read'=>0);
				$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
				$getUserNavigation = $this->loadview('Employer/main/navigation');
				$getUserNavigation->set("nameEmp" , $current_user_data['username']);
				$getUserNavigation->set("profile_img" , $profileImg);
				$getUserNavigation->set("dataFull" , $current_user_data);
				$getUserNavigation->set("unread_msg_count" , $unread_msg_count);
				$getUserNavigation->render();
				$template = $this->loadview('Employer/view_contract/index');
				$template->set("pendingJobs",$jobActivityStatusPending);
				$template->set("CompleteJobs",$jobActivityStatusComplete);
				$template->set("jobType",$jobType);
				$template->set("job",$job_id);
				$template->render();
				$this->loadview('main/footer')->render();
			} else {
				$this->loadview('main/header')->render();
				$this->loadview('Employer/postjob/navigation')->render();
				$this->loadview('main/noaccess')->render();
				$this->loadview('main/footer')->render();
			}
			
			
		} else {
			$this->redirect('');
		}
	} else {
		$this->redirect('/login');
	}
} else {
		$this->redirect('');
}
 ?>