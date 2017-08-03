<?php 
	if (isset($_COOKIE['force_username']) || isset($_SESSION['force_username'])) {
		if (isset($_COOKIE['force_username'])) {
			$username = $_COOKIE['force_username'];
		} else {
			$username = $_SESSION['force_username'];
		}
		$current_user_data=$this->Model->login_user($username);

		//if user is logged in
		if(isset($current_user_data) && !empty($current_user_data)) {
			$current_user_data['role'];

			$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
			$currentUserId = $current_user_data['id'];
			$anyone = $this->Model->Get_column_Double('*','job_visibility',"anyone",'job_author',$currentUserId,PREFIX.'jobs');
			$invite_only = $this->Model->Get_column_Double('*','job_visibility',"invite_only",'job_author',$currentUserId,PREFIX.'jobs');
			$finalInviteJobs = array_merge($anyone,$invite_only);
			$price = array();
			foreach ($finalInviteJobs as $key => $row)
			{
			    $price[$key] = $row['id'];
			}
			array_multisort($price, SORT_DESC, $finalInviteJobs);
			$finalInviteJobs = array_slice( $finalInviteJobs, 1, 3 );

			//if employed then load page 
			if($role_name['role_name'] == 'employer') {
				$this->loadview('main/header')->render();
				$this->loadview('Employer/main/navigation')->render();			
				$mainTemp = $this->loadview('Employer/openjob/index');	
				$mainTemp->set("model" , $this->Model);
				$mainTemp->set("openJobData" , $finalInviteJobs);
				$mainTemp->render();
				$this->loadview('main/footer')->render();	
			} else {
			$this->redirect();
			}
		} else {
		$this->redirect();
	}
	} else {
		$this->redirect();
	}

 ?>