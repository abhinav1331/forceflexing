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
	$current_user_data=$this->Model->login_user($username);
	//if user is logged in
	if(isset($current_user_data) && !empty($current_user_data))
	{
		$current_user_data['role'];
		$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
		//if employed then load page 
		if($role_name['role_name'] == 'employer') 
		{
			$this->loadview('main/header')->render();
			$this->loadview('Employer/company_profile/navigation')->render();			
			$template=$this->loadview('Employer/company_profile/index');
			
			$template->set("userdata",$current_user_data);
			$template->set("instance",$this);
			
			$all_industries=$this->industries->get_all_industries();
			$template->set("industries",$all_industries);
			
			$template->render();
			$this->loadview('main/footer')->render();
		}
		else
		{
			$this->redirect('');//no access
		}
	}
}
else
{
	$this->redirect('login');
}
