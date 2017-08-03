<?php 


		/****************************************Distance Fyunction ****************************************/

			function distance($lat1, $lon1, $lat2, $lon2, $unit) {
				$theta = $lon1 - $lon2;
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				$dist = acos($dist);
				$dist = rad2deg($dist);
				$miles = $dist * 60 * 1.1515;
				$unit = strtoupper($unit);
				if ($unit == "K") {
					return ($miles * 1.609344);
				} else if ($unit == "N") {
					return ($miles * 0.8684);
				} else {
					return $miles;
				}
			}
			function userDetails($userId) {
				$getUserRecord = $this->Model->get_Data_table(PREFIX.'users','id',$userId);
				return $getUserRecord[0]['first_name']." ".$getUserRecord[0]['last_name'];
			}

			
		/****************************************Distance Fyunction ****************************************/


	if (isset($_COOKIE['force_username']) || isset($_SESSION['force_username'])) {
		if (isset($_COOKIE['force_username'])) {
			$username = $_COOKIE['force_username'];
		} else {
			$username = $_SESSION['force_username'];
		}
		$current_user_data=$this->Model->login_user($username);

		//if user is logged in
		if(isset($current_user_data) && !empty($current_user_data)) {
			$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
			//if employed then load page 
			if($role_name['role_name'] == 'employer') {

				$url=$_SERVER['REQUEST_URI'];
				$pos = strrpos($url, '/');
				$urlArray = explode("/",$url);
				$slug = $urlArray[3];
				$job_Array = $this->Model->Get_column('*','job_slug',$slug,PREFIX.'jobs');
				$jobId = $job_Array['id'];
				$jobTitle = $job_Array['job_title'];

				$instance = $this;
				$conversation_set = $this->Model->Get_column_Double('*','job_id',$job_Array['id'],'conv_from',$current_user_data['id'],PREFIX.'conversation_set');
				

				if ($job_Array['job_author'] == $current_user_data['id'] &&  $job_Array['jobjob_status'] != 4) {
					$this->loadview('main/header')->render();
					$this->loadview('Employer/main/navigation')->render();			
					$mainTemp = $this->loadview('Employer/contractorMaster/messaged');		
					$mainTemp->set("model" , $this->Model);
					$mainTemp->set("instance" , $instance);
					$mainTemp->set("jobTitle" , $jobTitle);
					$mainTemp->set("job_Array" , $job_Array);
					$mainTemp->set("conversation_set" , $conversation_set);
					$mainTemp->render();
					$modalTemp = $this->loadview('Employer/contractorMaster/modal');
					$modalTemp->render();
					$this->loadview('main/footer')->render();	
				} else {
					$this->loadview('main/header')->render();
					$this->loadview('Employer/postjob/navigation')->render();
					$this->loadview('main/noaccess')->render();
					$this->loadview('main/footer')->render();
				}
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