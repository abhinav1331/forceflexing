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
		if(isset($_REQUEST['action']))
		{
			$template = $this->loadview('contractor/contractor_views/view_dispute_form');
		}
		else
		{
			$template = $this->loadview('contractor/contractor_views/view_submit_job_report');
		}
		
		$id="";
		if(isset($_REQUEST['id']))
		{
			$id=$_REQUEST['id'];
			
			//check if details exist
			$already_exst=$this->Model->Get_row('activity_status_id',$id,PREFIX.'hired_contractor_activity_report');
		}
		
		/*Job resubmit*/
		if(isset($_POST['resubmit_job_report']))
		{
			//update the status to 0 and redirect
			$this->Model->Update_row(array('job_report_status'=>0),'id',$id,PREFIX.'hired_contractor_activity_status');
			$uri=BASE_URL.'contractor/submit_report/?id='.$id;
			$last_id=$id;
			?>
			<script type="text/javascript">
			window.location.href = '<?php echo $uri ?>';
			</script>
			<?php 
		}
		
		if(isset($_POST['submit_report']) || isset($_POST['save_report']))
		{
			extract($_POST);
			$days_array=array();
			$i=0;
			foreach($activity_date as $activity)
			{
				$array=array();
				$array['activity_date']=$activity;
				$array['arrival_time']=$arrival_time[$i];
				$array['depart_time']=$depart_time[$i];
				$array['call_summary']=$call_summary[$i];
				$array['follow_up_tasks']=$follow_up_tasks[$i];
				$array['other_notes']=$other_notes[$i];
				$days_array[]=$array;
				$i++;
			}
			$day_details=json_encode($days_array);
			
			//get the expense details
			$exp_array=array();
			$expense_total=0;
			foreach($_POST as $key => $value) 
			{
				$expense_array=array();
				if (strpos($key, 'expense_') === 0) 
				{
					$expense_array[$key]=$value;
					$expense_total += $value;
					if(isset($_FILES) && !empty($_FILES))
					{
						if(!empty($_FILES[$key.'_attachment']['tmp_name']))
						{
							$extTmp = $_FILES[$key.'_attachment']['name'];
							$fileName = time().$extTmp;
							$target_dir = ABSPATH."/static/uploads/expenses/";
							$target_file = $target_dir . $fileName;
							move_uploaded_file($_FILES[$key.'_attachment']['tmp_name'], $target_file);
							$fileUrl = BASE_URL."static/uploads/expenses/".$fileName;
							$expense_array[$key.'_attachment']=$fileUrl;
						}
						elseif(!empty($already_exst))
						{
							$expen=json_decode($already_exst['expense_details']);
							foreach($expen as $ex)
							{
								foreach($ex as $k=>$v)
								{
									if($k == $key.'_attachment')
									{
										$expense_array[$key.'_attachment']=$v;
									}
								}
							}
						}
						else
						{
							$expense_array[$key.'_attachment']="";
						}
					}
					$exp_array[]=$expense_array;
				}
			}
			$expense_details=json_encode($exp_array);
			if(isset($_POST['hours_per_contract']))
				$hours_per_contract=$hours_per_contract;
			else
				$hours_per_contract="";
			
			if(isset($_POST['overage']))
				$overage=$overage;
			else
				$overage="";
			
			//insert data
			$dataarray=array
			(
			'activity_status_id'=> $id,
			'day_details'=> $day_details,
			'self_performance_rating'=> $rating_performance,
			'activity_success_rating'=> $rating_activity,
			'location_rating'=> $rating_location,
			'expense_details'=> $expense_details,
			'hours_per_contract'=>$hours_per_contract,
			'overage'=>$overage,
			'total_activity_amount'=> $total_payment_price,
			'total_expense_amount'=> $expense_total
			);
			
			/*if report is submitted*/
			if(isset($_POST['submit_report']))
			{
				//update the status of the report
				$this->Model->Update_row(array('job_report_status'=>1),'id',$id,PREFIX.'hired_contractor_activity_status');
			}
			
			if(!empty($already_exst))
			{
				//update
				$this->Model->Update_row($dataarray,'id',$already_exst['id'],PREFIX.'hired_contractor_activity_report');
				$last_id=$already_exst['id'];
			}
			else
			{
				//insert
				$last_id=$this->Model->Insert_data($dataarray,PREFIX.'hired_contractor_activity_report');
			}
		}
		
		if(isset($_POST['submit_report']) || isset($_POST['save_report']) || isset($_POST['resubmit_job_report']))
		{
			if(isset($_POST['submit_report']) || isset($_POST['save_report']))
				$ty='activity_report_submitted';
			elseif(isset($_POST['resubmit_job_report']))
				$ty='job_report_resubmitted';
			
			/*fetch the author id*/
			$reprt=$this->Model->Get_column('activity_status_id','id',$last_id,PREFIX.'hired_contractor_activity_report');
			$reprtsts=$this->Model->Get_column('contract_id','id',$reprt['activity_status_id'],PREFIX.'hired_contractor_activity_status');
			$contract=$this->Model->Get_column('job_id','id',$reprtsts['contract_id'],PREFIX.'hire_contractor');
			$job=$this->Model->Get_column('job_author','id',$contract['job_id'],PREFIX.'jobs');
			$this->Notification->insertNotification($ty,$this->userid,$job->job_author,0,$last_id);
		}
			
		//check if details exist
		$already_exst=$this->Model->Get_row('activity_status_id',$id,PREFIX.'hired_contractor_activity_report');
		
		if(!empty($id))
		{
			$employee=array();
			
			//get the hired contract id
			$hci=$this->Model->Get_row('id',$id,PREFIX.'hired_contractor_activity_status');
			
			$hired_contract_id=$hci['contract_id'];
			
			//get the contract details
			$hired_activity_details=$this->Model->Get_row('id',$hired_contract_id,PREFIX.'hire_contractor');
			
			//get the employer from job id
			$jd=$this->Model->Get_row('id',$hired_activity_details['job_id'],PREFIX.'jobs');
			$emp_id=$jd['job_author'];
			
			//get the employer details
			$ed=$this->Model->Get_row('id',$emp_id,PREFIX.'users');
			$employee['first_name']=$ed['first_name'];
			$employee['last_name']=$ed['last_name'];
			
			//employee country
			$country=$ed['country'];
			$emp_country=$this->Model->Get_row('sortname',$country,PREFIX.'countries');
			$employee['country']=$emp_country['name'];
			
			//get the image of employer(company)
			$ci=$this->Model->Get_row('company_id',$emp_id,PREFIX.'company_info');
			
			if(!empty($ci['company_image']))
				$employee['image']=SITEURL.'static/images/employer/'.$ci['company_image'];
			else
				$employee['image']=SITEURL.'static/images/avatar-icon.png';
			
			//get the activity details
			$activity_id=$hci['activity_id'];
			$ad=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
			$ad['job_title']=$jd['job_title'];
			
			if(isset($_POST['submit_report']))
			{
				//send mail to employer
				$file=APP_DIR.'email_templates/report_submitted.html';
				$emailBody = file_get_contents($file);
				$search  = array('[[fname]]', '[[lname]]','[[activity_name]]','[[contractor_first_name]]','[[contractor_last_name]]');
				
				//load parametrs
				$replace = array($employee['first_name'], $employee['last_name'],$ad['activity_name'],$this->udata['first_name'],$this->udata['last_name']);
				$emailBodyemp  = str_replace($search, $replace, $emailBody);
				$this->SendMail->setparameters($ed['email'],'Job Report Submitted',$emailBodyemp);
				
				//send notification
			}
			
			if(isset($_POST['confirm_dispute_mediation']))
			{
				//send mail to employer, contractor and admin
				
				//send mail to employer
				$file=APP_DIR.'email_templates/dispute_mediation.html';
				$emailBody = file_get_contents($file);
				$search  = array('[[fname]]', '[[lname]]','[[message]]');
				
				//load parametrs
				$message="Dispute mediation for the activity ".$ad['activity_name']." has been requested by the contractor ".$this->udata['first_name']." ".$this->udata['last_name'];
				$replace_emp = array($employee['first_name'], $employee['last_name'],$message);
				$emailBodyemp  = str_replace($search, $replace_emp, $emailBody);
				$this->SendMail->setparameters($ed['email'],'Dispute Mediation',$emailBodyemp);
				
				//send mail to admin
				$message_admin="Dispute mediation for the activity ".$ad['activity_name']." has been requested by the contractor ".$this->udata['first_name']." ".$this->udata['last_name'];
				$replace_admin = array('Admin','',$message_admin);
				$emailBodyadmin  = str_replace($search, $replace_admin, $emailBody);
				$this->SendMail->setparameters('samriti.chandel@imarkinfotech.com','Dispute Mediation',$emailBodyadmin);
				
				//send mail to Contractor
				$message_cont="Your Request for Dispute mediation for the activity ".$ad['activity_name']." has been submitted successfully";
				$replace_cont = array($this->udata['first_name'],$this->udata['last_name'],$message_cont);
				$emailBodycont  = str_replace($search, $replace_cont, $emailBody);
				$this->SendMail->setparameters($this->udata['email'],'Dispute Mediation',$emailBodycont);
				$url=BASE_URL.'contractor/my_jobs/';
				//redirect to job report page
				?>
				<script>
					window.location.href = '<?php echo $url ?>';
				</script>
				<?php 
			}
			
			//check if project is fixed or hourly
			$applied_job_id=$hired_activity_details['applied_job_id'];
			$pricing_details=array();
			$job_type="";
			$job_price="";
			if(!empty($applied_job_id) && $applied_job_id > 0)
			{
				$aj=$this->Model->Get_row('id',$applied_job_id,PREFIX.'applied_jobs');
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
			$total_activities=count(json_decode($hired_activity_details['activity_id']));
			
			$pricing_details['job_type']=$job_type;
			$pricing_details['job_price']=$job_price;
			$pricing_details['total_activities']=$total_activities;
			$pricing_details['overage']=$hired_activity_details['overage'];
			
			//expenses record
			$pricing_details['expenses']=$hired_activity_details['external_expanditure'];
			
			
			
			$template->set('instance',$this);
			$template->set('activity_status',$hci['job_report_status']);
			$template->set('already_exist',$already_exst);
			$template->set('activity_status_id',$hci['id']);
			$template->set('contract_id',$hired_contract_id);
			$template->set('employer_details',$employee);
			$template->set('pricing_details',$pricing_details);
			$template->set('asd',$hci);
			$template->set('activity_details',$ad);
		}
		
		$template->render();
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
