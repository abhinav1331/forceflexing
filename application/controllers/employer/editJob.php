<?php 
function create_url_slug($string){
	$slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

function latlong($city) {
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city";
	$json_data = file_get_contents($url);
	$result = json_decode($json_data, TRUE);
	$latitude = $result['results'][0]['geometry']['location']['lat'];
	$longitude = $result['results'][0]['geometry']['location']['lng'];
	return $latitude.','.$longitude;
}

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
				//if employed then load page 
				if($role_name['role_name'] == 'employer') {

					$url=$_SERVER['REQUEST_URI'];
					$pos = strrpos($url, '/');
					// $slug = $pos === false ? $url : substr($url, $pos + 2);
					$urlArray = explode("/",$url);
					$slug = $urlArray[3];
					$job_id=$this->Model->Get_column('*','job_slug',$slug,PREFIX.'jobs');
					$job_idd = $job_id['id'];
					if($job_idd != "") {
						if(isset($_POST['editPost'])) {
							if(isset($_POST['jp_actvty_comp '])) {
							$statusCount = $_POST['jp_actvty_comp'];
							if ($_POST['jp_num_of_actvty'] == "") {
								$countRecommended = 0;
							} else {
								$countRecommended = $_POST['jp_num_of_actvty'];
							}	
						} else {
							$statusCount = "";
							$countRecommended = 0;
						}
						if($_POST['jp_payRate'] == "fixed") {
							$paymentValue = $_POST['jp_payRate_fixed_val'];
						} else {
							$paymentValue = $_POST['jp_payRate_hourly_val'];
						}
						if($_POST['jp_reqemp'] == "one") {
							$emp_typ = "one";
							$emp_count = "1";
						} else {
							$emp_typ = "multiple";
							$emp_count = $_POST['jp_mul_emp'];
						}
						if(isset($_POST['jp_other_expenses'])) {
						$jp_other_expenses = json_encode($_POST['jp_other_expenses']);
						$jp_expenditure_price = $_POST['jp_other_expenses'];
						} else {
						$jp_other_expenses = json_encode(array());
						$jp_expenditure_price =array();
						}
						
					/*	echo "<pre>";
							print_r($_POST);
						echo "</pre>";*/
						$fileName = time();
						$data1 =  array(
						 'job_author'=>$current_user_data['id'], 
						 'job_title'=>$_POST['jp_title'], 
						 'job_description'=>$_POST['jp_desc'],
						 'job_location'=>$_POST['jp_empDistance'],
						 'job_type'=>$_POST['jp_payRate'],
						 'job_price'=>$paymentValue,
						 'job_attachment'=>$_POST['already-attached'],
						 'job_flex_time'=>$_POST['jp_flex_freq'],
						 'job_additional_hours'=>$paymentValue,
						 'job_travel_cost'=>$_POST['jp_travelCost'],
						 'job_employee_type'=>$_POST['jp_preferences'],
						 'job_completed_on_site'=>$_POST['jp_jobs_completed'],
						 'job_language'=>$_POST['jp_language'],
						 'job_industry_knowledge'=>$_POST['industry_knowledge'],
						 'jobjob_status'=>1,
						 'job_activity'=>$_POST['jp_activities'],
						 'nu_emp'=>$emp_typ,
						 'job_contractior_activity'=>$statusCount,
						 'job_minimum_contractor'=>$countRecommended,
						 'emp_count'=>$emp_count,
						 'job_flex_status'=>$_POST['jp_flexRate'],
						 'jp_other_expenses'=>$jp_other_expenses,
						 'job_modified'=>$fileName,
						 'job_experiance'=>$_POST['experianceEntry'],
						 'job_hours'=>$_POST['hours_per_week'],
						 'hours_billed'=>$_POST['hours_billed'],
						 'jobs_speciality'=>$_POST['job_speciality'],
						 'job_overnight'=>$_POST['over_night_travel']
						);
						// $Results1 = $this->Model->Insert_users($data1,PREFIX.'jobs');
						$Results1 = $this->Model->update_record($data1,"id",$_POST['jobId'],PREFIX.'jobs');
						$index = 0;
						foreach ($_POST['jp_activity_name'] as $key) {
							if (isset($_POST['activity_pricee'][$index])) {
								$activity_pricee = $_POST['activity_pricee'][$index];
							} else {
								$activity_pricee = "";
							}
							
							$indexactivityType = $index+1;
							$dateTime = $_POST['jp_act_start_date'][$index]." ".$_POST['jp_act_start_time'][$index];
							$enddateTime = $_POST['jp_act_end_date'][$index]." ".$_POST['jp_act_end_time'][$index];
							$datarecord = $this->Model->get_Data_table(PREFIX.'states','id',$_POST['jp_act_state'][$index]);
							$state = $datarecord[0]['name'];
							$state_slug = create_url_slug($state);
							$city_slug = create_url_slug($_POST['jp_act_city'][$index]);
							$finalLocation = $state_slug.",".$city_slug;
							$latlng = latlong($finalLocation);
							$latlngArray = explode("," , $latlng);
							$lat = $latlngArray[0];
							$lng = $latlngArray[1];
							$data2 =  array(
							'activity_name'=>$key, 
							'activity_type'=>$_POST['jp_start_stop_time'.$indexactivityType][0],
							'start_datetime'=>$dateTime,
							'end_datetime'=>$enddateTime,
							'state'=>$_POST['jp_act_state'][$index],
							'city'=>$_POST['jp_act_city'][$index],
							'street'=>$_POST['jp_act_street'][$index],
							'zip'=>$_POST['jp_act_zip'][$index],
							'first_name'=>$_POST['jp_act_cont_fname'][$index],
							'last_name'=>$_POST['jp_act_cont_lname'][$index],
							'phone'=>$_POST['jp_act_cont_phne'][$index],
							'email'=>$_POST['jp_act_cont_email'][$index],
							'notes'=>$_POST['jp_act_notes'][$index],
							'latitude'=>$lat,
							'longitude'=>$lng,
							'created_date'=>$fileName,
							'job_price'=>$activity_pricee,
							'modified_date'=>$fileName
							);
							// $Results2 = $this->Model->Insert_users($data2,PREFIX.'job_activities');
							$Results2 = $this->Model->update_record($data2,"job_id",$_POST['jobId'],PREFIX.'job_activities');
							$index++;
						}


						$indexx = 0;
						foreach ($_POST['flex-month-date'] as $key) {
							$data3 =  array(
							'flex_date'=>$_POST['flex-month-date'][$indexx],
							'flex_amount'=>$_POST['flex-month-completion'][$indexx],
							'created_date'=>$fileName,
							'modified_date'=>$fileName
							);
							// $Results3 = $this->Model->Insert_users($data3,PREFIX.'jobs_flex');
							$Results3 = $this->Model->update_record($data3,"job_id",$_POST['jobId'],PREFIX.'jobs_flex');
							$indexx++;
						}



						$data4 =  array(
						'before_time'=>$_POST['jp_allw_time_bfr_acti'],
						'after_time'=>$_POST['jp_allw_time_aftr_acti'],
						'price'=>$_POST['allowwable_overages'],
						'created_date'=>$fileName,
						'modified_date'=>$fileName
						);
						// $Results3 = $this->Model->Insert_users($data4,PREFIX.'job_additional_hours');
						$Results3 = $this->Model->update_record($data4,"job_id",$_POST['jobId'],PREFIX.'job_additional_hours');


						$indexx = 0;
						foreach ($_POST['quiestions'] as $key) {
							$data4 =  array(
							'job_questions'=>$key,
							'created_date'=>$fileName,
							'modified_date'=>$fileName
							);
							// $Results3 = $this->Model->Insert_users($data4,PREFIX.'job_questions');
							$Results3 = $this->Model->update_record($data4,"job_id",$_POST['jobId'],PREFIX.'job_questions');
							$indexx++;
						}

						$ExpenceName = $_POST['ExpenceName'];
						foreach ($jp_expenditure_price as $key => $value) {
							if($value == "food") {
								$i = 0;
							} elseif($value == "parking") {
								$i = 1;
							}  elseif($value == "tolls") {
								$i = 2;
							}  elseif($value == "tips") {
								$i = 3;
							}  elseif($value == "other") {
								$i = 4;
							}
							$data4 =  array(
							'name'=>$value,
							'price'=>$ExpenceName[$i],
							'created_at'=>$fileName,
							'modified_at'=>$fileName
							);
							// $Results3 = $this->Model->Insert_users($data4,PREFIX.'job_expenditure');
							$Results3 = $this->Model->update_record($data4,"job_id",$_POST['jobId'],PREFIX.'job_expenditure');
							
						}
						$this->redirect('/employer/openJob');
						
						} else {
							$jobs=$this->Model->get_Data_table(PREFIX.'jobs','id',$job_idd);
							$attachments=$this->Model->get_Data_table(PREFIX.'attachments','id',$job_idd);
							$jobs_flex=$this->Model->get_Data_table(PREFIX.'jobs_flex','job_id',$job_idd);
							$job_activities=$this->Model->get_Data_table(PREFIX.'job_activities','job_id',$job_idd);
							$job_additional_hours=$this->Model->get_Data_table(PREFIX.'job_additional_hours','job_id',$job_idd);
							$job_questions=$this->Model->get_Data_table(PREFIX.'job_questions','job_id',$job_idd);
							$job_expenditure=$this->Model->get_Data_table(PREFIX.'job_expenditure','job_id',$job_idd);
							$current_user_data=$this->Model->login_user($username);
							$datarecord = $this->Model->get_Data_table(PREFIX.'jobs','job_author',$current_user_data['id']);
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
							$getUserNavigation->set("getUserNavigation" , $getUserNavigation);
							$getUserNavigation->render();
							$template = $this->loadview('Employer/postjob/editJob');
							$template->set("url" , $datarecord);
							$template->set("jobs" , $jobs);
							$template->set("attachments" , $attachments);
							$template->set("jobs_flex" , $jobs_flex);
							$template->set("job_activities" , $job_activities);
							$all_results=$this->Model->Get_all_with_cond('country_id',231,PREFIX.'states');
							$template->set('states',$all_results);
							$template->set("job_additional_hours" , $job_additional_hours);
							$template->set("job_questions" , $job_questions);
							$template->set("otherExpenditure" , $job_expenditure);
							$template->render();
							// $this->loadview('Employer/postjob/index')->render();	
							$templateFooter = $this->loadview('main/footer');
							$templateFooter->set("job_activities" , $job_activities);
							$templateFooter->render();
						}
					} else {
						$this->loadview('main/header')->render();
					$this->loadview('home/navigation')->render();
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