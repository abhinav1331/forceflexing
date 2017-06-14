<?php 
if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
{
	/*get user data from username*/
	$user=$this->Model->Get_row('username',$username,PREFIX.'users');
	if(!empty($user))
	{
		$this->loadview('main/header')->render();
		if($user['role'] == 3)
			$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
		else
			$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile'); //employer navigation
		$this->navigation($data);
		
		$template = $this->loadview('contractor/contractor_views/global_contractor_profile');
		$template->set('instance',$this);
		$template->set('user',$user);
		
		/*get the user profile data*/
		$user_data=$this->Model->Get_row('user_id',$user['id'],PREFIX.'contractor_profile');
		$template->set('userdata',$user_data);
		
		/*get the user activities record*/
		
		
		$template->render();
		
		$this->loadview('main/footer')->render();
	}
	
}
else
{
	$this->redirect('login');
}
?>