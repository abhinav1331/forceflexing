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
			// $slug = $pos === false ? $url : substr($url, $pos + 2);
			$urlArray = explode("/",$url);
			$slug = $urlArray[3];
			$username = $urlArray[4];
			$job_id=$this->Model->Get_column('*','job_slug',$slug,PREFIX.'jobs');
			$job_additional_hours = $this->Model->Get_column('*','job_id',$job_id['id'],PREFIX.'job_additional_hours');
			$user_id=$this->Model->Get_column('*','username',$username,PREFIX.'users');

			/*echo "<pre>";
				print_r($user_id);
			echo "</pre>";*/

		

			$job_idd = $job_id['id'];
			$user_idd = $user_id['id'];

			$jobApplication = $this->Model->Get_column_Double('*','contractor_id',$user_idd,'job_id',$job_idd,PREFIX.'applied_jobs');
			/*echo "<pre>";
				print_r($jobApplication);
			echo "</pre>";*/
			$hire_contractor = $this->Model->Get_column_Double('*','contractor_id',$user_idd,'job_id',$job_idd,PREFIX.'hire_contractor');
			
			if(count($hire_contractor) != 0) {
				foreach($hire_contractor as $hire_contracto) {
					if($hire_contracto['status'] == 0) {
						$hired = 1;
					} else {
						$hired = 0;
					}
				}
			} else {
				$hired =0;
			}
            $FlexPrice = $this->Model->Get_column1('*','job_id',$job_idd,PREFIX.'jobs_flex');
            $pastDateArray = array();
            foreach ($FlexPrice as $key => $value) {
	            if (strtotime($value['flex_date']) < time()) {
				    $pastDateArray[] = $value;
				}
            }
            if(empty($pastDateArray)) {
				$total_price = $job_id['job_price'];
            } else {
	            $pastDateArray = end($pastDateArray);
	            $flexAmount = $pastDateArray['flex_amount'];
	            $job_price = $job_id['job_price'];
	            $total_price = $job_price *= (1 + $flexAmount / 100);
            }

			if ($job_id['job_type'] == "hourly") {
				$jobType = "hourly";
			} else {
				$jobType = "fixed";
			}
			$job_id['job_author'];
			if ($job_id['job_author'] == $current_user_data['id'] && $hired == 0 && $job_id['jobjob_status'] != 4) {
				
				$emplyerdetails=$this->Model->Get_column('*','id',$job_id['job_author'],PREFIX.'users');
				
				$getUserRecord = $this->Model->get_Data_table(PREFIX.'job_activities','job_id',$job_id['id']);
				$jobActivityStatusPending = $this->EModel->jobActivityStatus($job_id['id'],0);

				$jobActivityStatusComplete = $this->EModel->jobActivityStatus($job_id['id'],1);

				$all_results = $this->Model->Get_all_with_cond('country_id',231,PREFIX.'states');
				
				function in_array_r($needle, $haystack, $strict = false) {
				    foreach ($haystack as $item) {
				        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				            return true;
				        }
				    }

				    return false;
				}
				function getPerice($Model,$key1 , $item ,$key2, $job_id) {

					$getPerice = $Model->Get_column_Double('*',$key1,$item,$key2,$job_id,PREFIX.'job_expenditure');
					return $getPerice;
				}
				$this->loadview('main/header')->render();
				$this->loadview('Employer/contract/navigation')->render();
				$template = $this->loadview('Employer/contract/index');
				$template->set("jobApplication" , $jobApplication);
				$template->set("Model" , $this->Model);
				$template->set("job_type" , $job_id['job_type']);
				$template->set("total_price" , $total_price);
				$template->set('current_user_data',$current_user_data);
				$template->set('job_id',$job_idd);
				$template->set('job_idd',$job_id);
				$template->set('job_additional_hours',$job_additional_hours);
				$template->set('user_id',$user_idd);
				$template->render();
				$modalTemplate = $this->loadview('Employer/contract/modal');
				$modalTemplate->set('states',$all_results);
				$modalTemplate->set("Model" , $this->Model);
				$modalTemplate->set('job_id',$job_idd);
				$modalTemplate->set('user_id',$user_idd);
				$modalTemplate->render();
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
		$this->redirect('');
	}
} else {
		$this->redirect('');
}
 ?>