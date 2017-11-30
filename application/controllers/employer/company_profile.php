<?php 
if (isset($_COOKIE['force_username']) || isset($_SESSION['force_username'])) 
{
	if (isset($_COOKIE['force_username'])) 
	{
		$username = $_COOKIE['force_username'];
	} 
	else 
	{
		$username = $_SESSION['force_username'];
	}
	
	//get the slug
	$url=$_SERVER['REQUEST_URI'];
	$url=explode('/',$url);
	
	if(isset($url[3]) && !empty($url[3]))
	{
		$com=$this->Model->Get_row('company_slug',$url[3],PREFIX.'company_info');
		$company_id=$com['company_id'];
		
		//get user data corresponding to the company details
		$userdat=$this->Model->Get_row('id',$company_id,PREFIX.'users');
		$company_data=$this->Model->login_user($userdat['username']);
	}
	
	//if user is logged in
	if(isset($company_data) && !empty($company_data))
	{
		$loginuser=$this->Model->login_user($username);
		$role_name=$this->Model->Get_column('role_name','roleid',$loginuser['role'],PREFIX.'roles');
		
		$this->loadview('main/header')->render();
		//if employer then load page 
		if($role_name['role_name'] == 'employer') 
		{
			$dataNavi = $this->Model->get_Data_table(PREFIX.'company_info','company_id',$company_data['id']);
			if (!empty($dataNavi)) {
				$profileImg = $dataNavi[0]['company_image'];
			} else {
				$profileImg = "";
			}
			$c=array('to_id'=>$current_user_data['id'],'is_read'=>0);
			$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
			$getUserNavigation = $this->loadview('Employer/main/navigation');
			$getUserNavigation->set("nameEmp" , $company_data['username']);
			$getUserNavigation->set("profile_img" , $profileImg);
			$getUserNavigation->set("dataFull" , $current_user_data);
			$getUserNavigation->set("unread_msg_count" , $unread_msg_count);
			$getUserNavigation->render();
		}
		elseif($role_name['role_name'] == 'contractor')
		{
			$data=$this->Model->Get_row('user_id',$loginuser['id'],PREFIX.'contractor_profile');
			$nav=$this->loadview('contractor/main/navigation');			
			$nav->set('first_name',$loginuser['first_name']);
			$nav->set('last_name',$loginuser['last_name']);
			if($data['profile_img'])
			{
				$nav->set('profile_img',$data['profile_img']);
			}
			$nav->render();
		}
		else
		{
			$this->loadview('contractor/signup/navigation');
		}
		
		$template=$this->loadview('Employer/company_profile/index');
		//get the company main data
		$template->set('<?php echo BASE_URL ;?>/static/',$company_data);
		
		//get the company country 
		$country=$this->Model->Get_column('name','sortname',$company_data['country'],PREFIX.'countries');
		$template->set('country',$country['name']);
			
		//get the company details 
		$company_information=$this->Model->Get_row('company_id',$company_id,PREFIX.'company_info');
		$template->set('company_info',$company_information);
		
		//get the company city 
		$city=$this->Model->Get_column('name','id',$company_information['company_city'],PREFIX.'cities');
		$template->set('city',$city['name']);
		
		//get the posted job count of the company
		$posted_jobs=$this->Model->Get_record_jobe_count(PREFIX.'jobs','job_author',$company_id);
		$template->set('posted_jobs',$posted_jobs);
		
		//get the open jobs count
		$where_cond=array('job_author'=>$company_id,'job_visibility'=>'anyone');
		$open_jobs=$this->Model->get_count_with_multiple_cond($where_cond,PREFIX.'jobs');
		$template->set('open_jobs',$open_jobs);
		
		//get the number of active hires by employer
		$all_jobs=$this->Model->Get_all_with_cond('job_author',$company_id,PREFIX.'jobs');
		
		$alljob_array=array();
		foreach($all_jobs as $job)
		{
			$alljob_array[]=$job['id'];
		}
		if(!empty($alljob_array))
		{
			$jobs=implode(',',$alljob_array);
		}
		else
		{
			$jobs="";
		}
		
		$data=$this->Model->filter_data("select * from ".PREFIX."hire_contractor where job_id IN (".$jobs.") and status=1");
		$count_activehired=count($data);
		$template->set('employer_active_hires',$count_activehired);
		
		//get the number of total hires by employer
		$total_hires=$this->Model->filter_data("select * from ".PREFIX."hire_contractor where job_id IN (".$jobs.") and (status=1 or status=3)");
		$counthired=count($total_hires);
		$template->set('employer_hires',$counthired);
		
		//get member joining date 
		$date=$company_data['created_date'];
		$template->set('joining_date',date('M d, Y',strtotime($date)));
		
		$template->render();
		$this->loadview('main/footer')->render();
		
	}
	else
	{
		$this->loadview('main/header')->render();
		$current_user_data=$this->Model->login_user($username);
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
		$this->loadview('Employer/company_not_exist')->render();
		$this->loadview('main/footer')->render();
	}
}
else
{
	$this->redirect('login');
}