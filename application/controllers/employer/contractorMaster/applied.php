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
				

				$applied_jobs = $this->Model->Get_column_Double('*','job_id',$job_Array['id'],'status',0,PREFIX.'applied_jobs');
				

				if ($job_Array['job_author'] == $current_user_data['id'] &&  $job_Array['jobjob_status'] != 4) {
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
					$mainTemp = $this->loadview('Employer/contractorMaster/applied');		
					$mainTemp->set("model" , $this->Model);
					$mainTemp->set("instance" , $instance);
					$mainTemp->set("jobTitle" , $jobTitle);
					$mainTemp->set("job_Array" , $job_Array);
					$mainTemp->set("applied_jobs" , $applied_jobs);
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
			$this->redirect('');
			}
		} else {
		$this->redirect('login');
	}
	} else {
		$this->redirect('');
	}

 ?>