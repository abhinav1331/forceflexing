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
			$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
			//if employed then load page 
			if($role_name['role_name'] == 'employer') {
				if(isset($_GET['contract'])) {
					$contractId = $_GET['contract'];
					$hire_contractor = $this->Model->Get_column('*','id',$contractId,PREFIX.'hire_contractor');
					if(!empty($hire_contractor)) {
						$jobs = $this->Model->Get_column('*','id',$hire_contractor['job_id'],PREFIX.'jobs');
						if($current_user_data['id'] == $jobs['job_author']) {
							if(isset($hire_contractor) && !empty($hire_contractor)) {
								if($hire_contractor['status'] == 0) {

									$template = $this->loadview('contractor/contractor_views/view_contractor_contract');
									$template->set('contract_id',$contract_id);
									$job_activities=$hire_contractor['activity_id'];
									if(!empty($job_activities)) {
										$all_activities=json_decode($job_activities);
										$activities=implode(',',$all_activities);
										$ac=$this->Model->filter_data('Select * from '.PREFIX.'job_activities where id IN('.$activities.')');
										$template->set('activities',$ac);
									}
									$job_id=$hire_contractor['job_id'];
									$jobdata=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
									$template->set('jobdata',$jobdata);
									
									//get the additional hours of a job
									$additonal_hours=$this->Model->Get_row('id',$job_id,PREFIX.'job_additional_hours');
									$template->set('additional_hours',$additonal_hours);
									
									//expenses for contract
									$expenses=$hire_contractor['external_expanditure'];
									if(!empty($expenses))
									{
										$exp=json_decode($expenses);
										/* $ex=implode(',',$exp);
										$e=$this->Model->filter_data('Select * from '.PREFIX.'job_expenditure where id IN('.$ex.')'); */
										$template->set('expenses',$exp);
									}
									
									//get the attachment
									if(!empty($hire_contractor['attachmentId']))
									{
										$attachment=$this->Model->Get_column('url','id',$hire_contractor['attachmentId'],PREFIX.'attachments');
										$template->set('attachment_url',$attachment['url']);
									}
									
									//get the attachment
									if(!empty($hire_contractor['additionalInfo']))
									{
										$template->set('additionalinfo',$hire_contractor['additionalInfo']);
									}
								}  elseif($hire_contractor['status'] == 1 || $hire_contractor['status'] == 3) {
					
									$template = $this->loadview('contractor/contractor_views/view_contract');
									$job_id=$hire_contractor['job_id'];
									$job_details=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
									$job_title=$job_details['job_title'];
									
									/*EMPLOYER  DETAILS*/
									$employer=array();
									$employer['job_title']=$job_title;
									
									$job_employer=$job_details['job_author'];
									
									//get the employer details
									$employer_details=$this->Model->Get_row('id',$job_employer,PREFIX.'users');
									$employer_company_info=$this->Model->Get_row('company_id',$job_employer,PREFIX.'company_info');
									
									//employer image
									$employer_image=$employer_company_info['company_image'];
									if(!empty($employer_image))
										$emp_image_uri=BASE_URL.'static/images/employer/'.$employer_image;
									else
										$emp_image_uri=BASE_URL.'static/images/avatar-icon.png';
									$employer['employer_image']=$emp_image_uri;
									
									//employer name
									//$job['emp_name']=$employer_details['company_name'];
									$employer['emp_name']=$employer_details['first_name']." ".$employer_details['last_name'];
									
									//country
									$country=$employer_details['country'];
									$emp_country=$this->Model->Get_row('sortname',$country,PREFIX.'countries');
									$employer['country']=$emp_country['name'];
									
									/*EMPLOYER DETAILS END*/
									
									/*ACTIVITIES RELATED INFO*/
									$activities=$this->Model->Get_all_with_cond('contract_id',$contractId,PREFIX.'hired_contractor_activity_status','asc');
									
									//completed activities
									$completed_acti=array();
									$pending_acti=array();
									
									foreach($activities as $activity)
									{
										$activity_id=$activity['activity_id'];
										if($activity['status'] != 2)
										{
											$acti=array();
											//activity details
											$activity_detail=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
											if(!empty($activity_detail))
											{
												$acti['activity_name']=$activity_detail['activity_name'];
												$acti['start_time']=$activity_detail['start_datetime'];
												
												/* $activity_city=$this->Model->Get_column('name','id',$activity_detail['city'],PREFIX.'cities');
												$acti['city']=$activity_city['name']; */
												
												$acti['city']=$activity_detail['city'];
												
												
												$acti['contact_name']=$activity_detail['first_name']." ".$activity_detail['last_name'];
												
												/*Job Report Status and price status*/
												$intial=0;
												if($activity['status'] == 0 && $activity['job_report_status'] == 0)
												{
													$acti['job_report_status']="N/A";
													$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
													$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
												}
												elseif($activity['status'] == 1 && $activity['job_report_status'] == 0)
												{
													$acti['job_report_status']="Not Received";
													$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
													$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
												}
												elseif($activity['status'] == 1 && $activity['job_report_status'] == 1)
												{
													$acti['job_report_status']="Not Paid";
													$acti['amount_due']=number_format((float)$activity_detail['job_price'], 2, '.', '');
													$acti['amount_paid']=number_format((float)$intial, 2, '.', '');
												}
												elseif($activity['status'] == 1 && $activity['job_report_status'] == 2)
												{
													$acti['job_report_status']="Paid";
													$acti['amount_due']=number_format((float)$intial, 2, '.', '');
													$acti['amount_paid']=number_format((float)$activity_detail['job_price'], 2, '.', '');
												}
												
												if($activity['status'] == 0)
													$pending_acti[]=$acti;
												elseif($activity['status'] == 1)
													$completed_acti[]=$acti;
											}
										}
									}
									/*END OF ACTIVITIES*/
									
									/*PAYMENT TERMS*/
									$template->set('job_data',$job_details);
									$template->set('rate_of_pay',$hire_contractor['flex_amount']);
									
									/*job overages*/
									$job_overages=$this->Model->Get_row('job_id',$job_id,PREFIX.'job_additional_hours');
									$template->set('job_overages',$job_overages);
									
									/*set allowable expenses*/
									$job_expenses=$this->Model->Get_all_with_cond('job_id',$job_id,PREFIX.'job_expenditure');
									$template->set('job_expenses',$job_expenses);
									
									/*PAYMENT TERMS END*/
									
									/*CONTRACT HISTORY*/
									$history=$this->Model->Get_all_with_cond('contract_id',$contractId,PREFIX.'contract_history');
									
									$all_history=array();
									if(!empty($history))
									{
										foreach($history as $h)
										{
											$histry=array();
											$histry['date']=$h['date'];
											if (strpos($h['description'], '[[employer]]') !== false )
												$desc=str_replace('[[employer]]',$employer_details['first_name']." ".$employer_details['last_name'],$h['description']);
												
											if(strpos($desc, '[[contractor]]') !== false )
												$desc=str_replace('[[contractor]]','you',$desc);
												
											$histry['description']=$desc;
											$all_history[]=$histry;
										}
									}
									
									
									/*CONTRACT HISTORY ENDS*/
									$template->set('employer',$employer);	
									$template->set('completed_acti',$completed_acti);
									$template->set('pending_acti',$pending_acti);
									
									$this->loadview('main/header')->render();
									$this->loadview('Employer/main/navigation')->render();	
									$template->set('all_history',$all_history);
									$template->render();
									$this->loadview('main/footer')->render();	
								}
								else
								{
									$template = $this->loadview('contractor/contractor_views/view_contractor_contract');
									$template->set('error','Contract Does not Exist!!');
								}
							}
						} else {
							$this->loadview('main/header')->render();
							$this->loadview('Employer/postjob/navigation')->render();
							$this->loadview('main/noaccess')->render();
							$this->loadview('main/footer')->render();
						}
					} else {
						$this->loadview('main/header')->render();
						$this->loadview('Employer/postjob/navigation')->render();
						$this->loadview('main/noaccess')->render();
						$this->loadview('main/footer')->render();
					}
				} else {
					$this->redirect('');
				}
			 }else {
			$this->redirect('');
			}
		} else {
		$this->redirect('');
	}
	} else {
		$this->redirect('');
	}
 ?>