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
				
				$template = $this->loadview('contractor/contractor_views/view_company_invite');
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