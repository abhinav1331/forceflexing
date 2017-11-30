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
		$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
		//if employed then load page 
		if($role_name['role_name'] == 'employer') 
		{
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
			if(isset($_GET['action']) && !empty($_GET['action']))
			{
				$template = $this->loadview('Employer/view_job_expense_report_dispute_form');	
			}
			else
			{
				$template = $this->loadview('Employer/view_job_expense_report');	
			}
			
			$id=$_GET['id'];
			
			$activity_sta=$this->Model->Get_row('id',$id,PREFIX.'hired_contractor_activity_status');
			
			$contract_id=$activity_sta['contract_id'];
			$activity_id=$activity_sta['activity_id'];
			
			$contractor=array();
			$contra_id=$this->Model->Get_row('id',$contract_id,PREFIX.'hire_contractor');
			
			/**CONTRACTOR DETAILS**/
			$contr_id=$contra_id['contractor_id'];
			
			$cd=$this->Model->Get_row('id',$contr_id,PREFIX.'users');
			
			/*if dispute made*/
			if(isset($_POST['dispute_form']))
			{
				$reason_dispute=$_POST['reason_dispute'];
				
				//changes dispute reason and save data
				$this->Model->update_record(array('dispute_status'=>1,'dispute_reason'=>$reason_dispute),"id",$id,PREFIX.'hired_contractor_activity_status');
				
				//send notification to contractor (later)
				
				//send mail to contractor
				$email=$cd['email'];
				
				//get the actibity name
				$activity=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
				
				//load  email template 
				$file=APP_DIR.'email_templates/report_dispute.html';
				$emailBody = file_get_contents($file);
				$search  = array('[[fname]]', '[[lname]]','[[job_report]]');
			
				$activity_de='<a href="'.BASE_URL.'/contractor/view_dispute/?id='.$id.'action=view_dispute">'.$activity['activity_name'].'</a>';
				$replace = array($cd['first_name'], $cd['last_name'],$activity_de);
				$emailBodyemp  = str_replace($search, $replace, $emailBody);
				$this->sendmail->setparameters($email,'Dispute',$emailBodyemp);
				echo '<script>window.location.href = "'.BASE_URL.'employer/job_report";</script>';
			}
			
			$contractor['first_name']=$cd['first_name'];
			$contractor['last_name']=$cd['last_name'];
			
			//contractor country
			$country=$cd['country'];
			$country_con=$this->Model->Get_row('sortname',$country,PREFIX.'countries');
			$contractor['country']=$country_con['name'];
			
			//image contractor
			$ci=$this->Model->Get_column('profile_img','user_id',$contr_id,PREFIX.'contractor_profile');
			
			if(!empty($ci['profile_img']))
				$contractor['image']=SITEURL.'static/images/contractor/'.$ci['profile_img'];
			else
				$contractor['image']=SITEURL.'static/images/avatar-icon.png';
			
			/**ACTIVITY DETAILS***/
			$activity_id=$activity_sta['activity_id'];
			$ad=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
				
			/**JOB TITLE**/
			$job_id=$contra_id['job_id'];
			$job_det=$this->Model->Get_column('job_title','id',$job_id,PREFIX.'jobs');
			$ad['job_title']=$job_det['job_title'];
			
			/*GET THE JOB PAYMENT DETAILS*/
			//check if project is fixed or hourly
			$applied_job_id=$contra_id['applied_job_id'];
			$pricing_details=array();
			if(!empty($applied_job_id) && $applied_job_id > 0)
			{
				$aj=$this->Model->Get_row('id',$applied_job_id,PREFIX.'applied_jobs');
				$job_type="";
				$job_price="";
				//check for the payment terms
				if($aj['payment_terms'] == 'accepted')
				{
					//get type from flex jobs
					$job_type=$jd['job_type'];
					$job_price=$hired_activity_details['flex_amount'];
				}
				elseif($aj['payment_terms'] == 'new_terms')
				{
					$job_type=$aj['proposal_type'];
					$job_price=$aj['proposal_rate'];
				}
			}
			
			//total activity count
			$total_activities=count(json_decode($contra_id['activity_id']));
			
			$pricing_details['job_type']=$job_type;
			$pricing_details['job_price']=$job_price;
			$pricing_details['total_activities']=$total_activities;
			$pricing_details['overage']=$contra_id['overage'];
			
			/**REPORT DETAILS***/
			$report_details=$this->Model->Get_row('activity_status_id',$id,PREFIX.'hired_contractor_activity_report');
			
			//set variables
			$template->set('contractor_details',$contractor);
			$template->set('activity_detail',$ad);
			$template->set('rd',$report_details);
			$template->set('pd',$pricing_details);
			$template->set('activity_status_id',$id);
			$template->set('asd',$activity_sta);
			$template->render();
			$this->loadview('main/footer')->render();	
		} 
		else 
		{
			$this->redirect('');
		}
	} 
	else 
	{
		$this->redirect("login");
	}
} else {
	$this->redirect('');