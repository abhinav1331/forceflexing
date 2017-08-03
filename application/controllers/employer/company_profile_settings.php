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
		$company_id=$current_user_data['id'];
		$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
		
		//company slug
		$company_slug=$this->options->slugify($current_user_data['company_name']);
		
		//check if company slug already exists
		 $count=$this->Model->get_count_with_multiple_cond(array('company_slug'=>$company_slug),PREFIX.'company_info');
		
		if($count > 0)
		{
			$company_slug = 0 + $count;
		}
		
		//if employer then load page 
		if($role_name['role_name'] == 'employer') 
		{
			/*$this->loadview('main/header')->render();
			$this->loadview('Employer/company_profile/navigation')->render();			
			$template=$this->loadview('Employer/company_profile/index');
			$template->set("instance",$this);
			$template->set("comp_id",$company_id);*/
			
			/*check whether the company information already exists*/
			$company_details=$this->Model->Get_row("company_id",$company_id,PREFIX."company_info");
			if(!empty($company_details))
				$company_info_exists="exist";
			else
				$company_info_exists="not-exist";
			
			/*check the company notification settings*/
			$company_notifications=$this->Model->Get_row("company_id",$company_id,PREFIX."company_notifi_sett");
			if(!empty($company_notifications))
				$company_notifi_exists="exist";
			else
				$company_notifi_exists="not-exist";
			
			
			/*company team details*/
			$company_team_details=$this->Model->Get_row("company_id",$company_id,PREFIX."company_team_details");
			if(!empty($company_team_details))
				$company_team_details_exists="exist";
			else
				$company_team_details_exists="not-exist";
			
			$success="";
			
			//save company details
			if(isset($_POST['save_company_details']))
			{
				extract($_POST);
				if($company_info_exists == "exist" && !empty($company_details['security_ques']) && !empty($company_details['security_ans']))
				{
					$question=(isset($employer_new_question_edit) && !empty($employer_new_question_edit))? $employer_new_question_edit : $company_details['security_ques'];
					$answer=(isset($employer_new_answer_edit) && !empty($employer_new_answer_edit))? $employer_new_answer_edit : $company_details['security_ans'];
				}
				else
				{
					$question= (isset($employer_new_question))? $employer_new_question :'';
					$answer=(isset($employer_new_answer))? $employer_new_answer :'';
				}
				
				//update the data in user's table
				$userdata=array('company_name'=>$company_name,'country'=>$company_country,'email'=>$company_email,'first_name'=>$company_first_name,'last_name'=>$company_last_name);
				$this->Model->Update_row($userdata,'id',$company_id,PREFIX.'users');
				
				//update the password
				if(isset($employer_confirm_password) && !empty($employer_confirm_password))
				{
					$this->Model->Update_row(array('password' => md5($employer_confirm_password)),'id',$company_id,PREFIX.'users');
				}
				
				$company_address_2=(isset($company_address_2) && !empty($company_address_2))? $company_address_2 : '';
				$business_desc=(isset($business_desc) && !empty($business_desc))? $business_desc : '';
				$company_website=(isset($company_website) && !empty($company_website))? $company_website : '';
				$company_link=(isset($company_link) && !empty($company_link))? $company_link : '';
				$company_landline=(isset($company_landline) && !empty($company_landline))? $company_landline : '';
				$company_linkedin_link=(isset($company_linkedin_link) && !empty($company_linkedin_link))? $company_linkedin_link : '';
				$company_facebook_link=(isset($company_facebook_link) && !empty($company_facebook_link))? $company_facebook_link : '';
				$vat=(isset($vat) && !empty($vat))? $vat : '';
				$company_industries= substr($company_industries,0,-1);
				
				//if image already exists
				
				if($company_info_exists == "exist" && !empty($company_details['company_image']))
				{
					$name=$company_details['company_image'];
				}
				
				//save employer image
				if(isset($employer_avatar) && !empty($employer_avatar))
				{
					$namefile=$username;
					$employer_avatar=base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $employer_avatar));
					
					$uri=ABSPATH.'/static/images/employer';
					$filenam = glob($uri."/$namefile*.{jpg,gif,png}", GLOB_BRACE);
					foreach ($filenam as $filefound) 
					{
						unlink($filefound);
					}
					$name=$username.'_'.time().'.png';
					file_put_contents('static/images/employer/'.$name,$employer_avatar);
				}
				
				$company_info_data=array(
				'company_id' =>$company_id,
				'company_image'=>$name,
				'company_state' => $company_state,
				'company_city' => $company_city,
				'company_zip' => $company_zip,
				'company_add_1' =>  $company_address_1,
				'company_add_2' => $company_address_2,
				'company_busi_desc' => $business_desc,
				'company_website' =>$company_website,
				'company_about_us' =>$company_link,
				'company_mobile_phone' =>$company_mobile_phone,
				'company_landline' =>$company_landline,
				'company_linkedin' => $company_linkedin_link,
				'company_fb' => $company_facebook_link,
				'contact_vat' =>$vat,
				'company_indus' => $company_industries,
				'security_ques' => $question,
				'security_ans' =>$answer
				);
				
				
				if($company_info_exists == "exist")
				{
					//update the company_info
					$this->Model->Update_row($company_info_data,'company_id',$company_id,PREFIX.'company_info');
				}
				else
				{
					$company_info_data['company_slug']=$company_slug;
					//insert the company information
					$this->Model->Insert_users($company_info_data,PREFIX.'company_info');
				}
				
				
				if(isset($employer_remember))
				{
					setcookie("answer",$answer,time()+60*60*24*90); //future date of 10 years from now
				}
				else
				{
					if(isset($_COOKIE["answer"]))
						setcookie("answer", "", time() - 3600);
				}
				
				if(isset($team_email) && !empty($team_email))
				{
					if($company_team_details_exists == "exist")
					{
						//update the team member in users table
						$team_mem_id=$company_team_details['team_member_id'];
						$team_member_data=array('first_name'=>$team_first_name , 'last_name'=>$team_last_name,'email'=>$team_email);
						$this->Model->Update_row($team_member_data,'id',$team_mem_id,PREFIX.'users');
					}
					else
					{
						//insert it to the user's table
						$rand_pass=$this->randomPassword();
						$username=$team_first_name.rand(10,100);
						
						$team_member_data=array('first_name'=>$team_first_name , 'last_name'=>$team_last_name,
						'company_name'=>$current_user_data['company_name'],'country'=>$current_user_data['country'],'email'=>$team_email,'password' => md5($rand_pass),
						'role'=>2, 'connected_with' =>'' , 'created_date'=>date('Y-m-d h:m:s'),'modified_date' =>date('Y-m-d h:m:s'), 'is_verified'=>1,
						'username'=>$username,'token'=>'');
						$team_mem_id=$this->Model->Insert_users($team_member_data,PREFIX.'users');
					}
					
					
					//insert into the team details table
					$team_member_info=array(
					'company_id'=>$company_id,
					'team_member_id'=>$team_mem_id,
					'team_mem_title' =>$team_title, 
					'team_mem_mobile' =>$team_mobile,
					'team_mem_landline' =>$team_landline,
					'admin_permission' =>$tmem_admin_permission,
					'hiring_permission' =>$tmem_hiring_permission,
					'training_permission' =>$tmem_trainig_permission,
					'activities_permission' =>$tmem_activities_permission,
					'feedback_permission' =>$tmem_feedback_permission,
					'message_permission' =>$tmem_message_permission,
					);
					
					if($company_team_details_exists == "exist")
					{
						$team_member_info=$this->Model->Update_row($team_member_info,'company_id',$company_id,PREFIX.'company_team_details');
						$this->sendmail->setparameters($team_email,'Information Uodated',"Your information has been updae by your company");
					}
					else
					{
						$team_member_info=$this->Model->Insert_users($team_member_info,PREFIX.'company_team_details');
						$this->sendmail->setparameters($team_email,'Team Member Added','Username:'.$username.'<br>Password:'.$rand_pass);
					}
				}
				
				
				//insert compnay notification settings
				$company_desktop_notifi=(isset($company_desktop_notifi) && !empty($company_desktop_notifi))? $company_desktop_notifi : 'off';
				$company_mobile_notifi=(isset($company_mobile_notifi) && !empty($company_mobile_notifi))? $company_mobile_notifi : 'off';
				$company_email_notifi=(isset($company_email_notifi) && !empty($company_email_notifi))? $company_email_notifi : 'off';
				$jobIsPosted=(isset($jobIsPosted) && !empty($jobIsPosted))? $jobIsPosted : 'off';
				$offerIsAccepted=(isset($offerIsAccepted) && !empty($offerIsAccepted))? $offerIsAccepted : 'off';
				$offerIsModified=(isset($offerIsModified) && !empty($offerIsModified))? $offerIsModified : 'off';
				$jobExpire=(isset($jobExpire) && !empty($jobExpire))? $jobExpire : 'off';
				$contractBegins=(isset($contractBegins) && !empty($contractBegins))? $contractBegins : 'off';
				$contractModified=(isset($contractModified) && !empty($contractModified))? $contractModified : 'off';
				$activityStarting=(isset($activityStarting) && !empty($activityStarting))? $activityStarting : 'off';
				$activityCompleted=(isset($activityCompleted) && !empty($activityCompleted))? $activityCompleted : 'off';
				$expenseReport=(isset($expenseReport) && !empty($expenseReport))? $expenseReport : 'off';
				$trainingCompleted=(isset($trainingCompleted) && !empty($trainingCompleted))? $trainingCompleted : 'off';
				$trainingNotCompleted=(isset($trainingNotCompleted) && !empty($trainingNotCompleted))? $trainingNotCompleted : 'off';
				$contractEnd=(isset($contractEnd) && !empty($contractEnd))? $contractEnd : 'off';
				
				
				$company_profile_settings=array(
				'company_id' => $company_id,
				'desktop' => $company_desktop_notifi,
				'mobile' => $company_mobile_notifi,
				'email' => $company_email_notifi,
				'job_posted' => $jobIsPosted,
				'offer_accepted' => $offerIsAccepted,
				'offer_changes_del' => $offerIsModified,
				'job_post_expire' => $jobExpire,
				'contract_begin' => $contractBegins,
				'contract_modified' => $contractModified,
				'activity_start' => $activityStarting,
				'activity_complete' => $activityCompleted,
				'job_report_submitted' => $expenseReport,
				'training_completed' => $trainingCompleted,
				'training_not_completed' => $trainingNotCompleted,
				'contract_ended' => $contractEnd
				);
				
				if($company_notifi_exists == "exist")
					$company_settings=$this->Model->Update_row($company_profile_settings,'company_id',$company_id,PREFIX.'company_notifi_sett');
				else
					$company_settings=$this->Model->Insert_users($company_profile_settings,PREFIX.'company_notifi_sett');
			
					if(!empty($company_details))
						$success='Data successfully Updated!!';
					else
						$success='Data successfully Inserted!!';
				
			}
			
			$this->loadview('main/header')->render();
			$this->loadview('Employer/company_profile_settings/navigation')->render();	
			$template=$this->loadview('Employer/company_profile_settings/index');
			$template->set("success",$success);
			$template->set("instance",$this);
			$template->set("comp_id",$company_id);
			
			//update cookie details
			if(isset($_COOKIE['answer']) && !empty($_COOKIE['answer']))
				$template->set("ans",$_COOKIE['answer']);
			else
				$template->set("ans","");
			
			
			/*update compnay details */
			$company_details=$this->Model->Get_row("company_id",$company_id,PREFIX."company_info");
			if(!empty($company_details))
				$template->set('company_details',$company_details);
			
			
			$company_team_details=$this->Model->Get_row("company_id",$company_id,PREFIX."company_team_details");
			if(!empty($company_team_details))
			{
				$team_mem_id=$company_team_details['team_member_id'];
				$meminfo=$this->Model->Get_row("id",$team_mem_id,PREFIX."users");
				$template->set('team_mem_info',$meminfo);
				$template->set('company_team_dtls',$company_team_details);
			}
			
			$company_notifications=$this->Model->Get_row("company_id",$company_id,PREFIX."company_notifi_sett");
			if(!empty($company_notifications))
				$template->set('company_notifi',$company_notifications);
			
			$template->set("userdata",$current_user_data);
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
