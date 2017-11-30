<?php 

if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
{
	if (isset($_COOKIE['force_username'])) {
		$username = $_COOKIE['force_username'];
	} else {
		$username = $_SESSION['force_username'];
	}
	$current_user_data=$this->Model->login_user($username);
	
	$role=$current_user_data['role'];
	$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
	if($role_name['role_name'] == 'employer')
	{
		$this->loadview('main/header')->render();
		
		
		/* Navigation */
		/*$data=$this->Model->Get_row('company_id',$current_user_data['id'],PREFIX.'company_info');
		$getUserNavigation = $this->loadview('Employer/main/navigation');
		$getUserNavigation->set("nameEmp" , $data['company_slug']);
		$getUserNavigation->set("profile_img" , $data['company_image']);
		$getUserNavigation->render();*/
		
			$dataNavi = $this->Model->get_Data_table(PREFIX.'company_info','company_id',$current_user_data['id']);
			if (!empty($dataNavi)) {
				$profileImg = $dataNavi[0]['company_image'];
			} else {
				$profileImg = "";
			}
			$myJobsArray = array();
			$jobs = $this->Model->get_Data_table(PREFIX.'jobs','job_author',$current_user_data['id']);
			$hire_contractor = $this->Model->get_Data_table(PREFIX.'hire_contractor','employer_id',$current_user_data['id']);
			$arrayInitialize = array();
			$priceActivity = array();
			$totalPrice = 0;
			foreach ($hire_contractor as $key => $hire_contract) {
				$contractId = $hire_contract['id'];
				$hired_contractor_activity_status = $this->Model->get_Data_table(PREFIX.'hired_contractor_activity_status','contract_id',$contractId);
				foreach ($hired_contractor_activity_status as $key => $hired_contractor_activity) {
					$arrayInitialize[] = array("created_date" => date("y-m" , strtotime($hired_contractor_activity['created_date'])) , "createdDateCheck" => date("y-m-d" , strtotime($hired_contractor_activity['created_date'])));
				}
				foreach ($hired_contractor_activity_status as $key => $hired_contractor_activity) {
					if($hired_contractor_activity['job_report_status'] == 2) {
						$flex_hired_contractor_activity_report = $this->Model->get_Data_table(PREFIX.'hired_contractor_activity_report','activity_status_id',$hired_contractor_activity['id']);
						if(!empty($flex_hired_contractor_activity_report)) {
							$totalPrice = $totalPrice + $flex_hired_contractor_activity_report[0]['total_activity_amount'];
							$priceActivity[] = array("created_date" => date("y-m" , strtotime($hired_contractor_activity['modified_date'])) , "createdDateCheck" => date("y-m-d" , strtotime($hired_contractor_activity['modified_date'])) , "Price" => $flex_hired_contractor_activity_report[0]['total_activity_amount']);	
						}
						
					}
					
				}
			}

			$myarrayCollectsCheck = array();
			$myarrayColleCheck = array();
			foreach($arrayInitialize as $arrayInitialize) {
				if(!in_array($arrayInitialize['created_date'] , $myarrayColleCheck)) {
					$myarrayColleCheck[] = $arrayInitialize['created_date'];
					$myarrayCollectsCheck[] = array("created_date" => $arrayInitialize['created_date'] ,"createdDateCheck" => $arrayInitialize['createdDateCheck'] , "count" => 1);
				} else {
					$key = array_search($arrayInitialize['created_date'], array_column($myarrayCollectsCheck, 'created_date'));
					$oldd = $myarrayCollectsCheck[$key]['count'];
					$countNew = $oldd + 1;
					$myarrayCollectsCheck[$key] = array("created_date" => $arrayInitialize['created_date'],"createdDateCheck" => $arrayInitialize['createdDateCheck'] , "count" => $countNew);
				}
			}

			$countJob = 0;
			foreach ($jobs as $key => $myJob) {
				if($myJob['jobjob_status'] ==3) {
					$countJob = $countJob + 1;
					$myJobsArray[] = array("title" => $myJob['job_title'] , "created_Month" => date("Y-m" , $myJob['job_created']), "created_MonthCheck" => date("Y-m-d" , $myJob['job_created']));
				}
			}

			$myarrayCollects = array();
			$myarrayColle = array();
			foreach($myJobsArray as $created_Mont) {
				if(!in_array($created_Mont['created_Month'] , $myarrayColle)) {
					$myarrayColle[] = $created_Mont['created_Month'];
					$myarrayCollects[] = array("created_Month" => $created_Mont['created_Month'] ,"created_MonthCheck" => $created_Mont['created_MonthCheck'] , "count" => 1);
				} else {
					$key = array_search($created_Mont['created_Month'], array_column($myarrayCollects, 'created_Month'));
					$oldd = $myarrayCollects[$key]['count'];
					$countNew = $oldd + 1;
					$myarrayCollects[$key] = array("created_Month" => $created_Mont['created_Month'],"created_MonthCheck" => $created_Mont['created_MonthCheck'] , "count" => $countNew);
				}
			}
			
			$c=array('to_id'=>$current_user_data['id'],'is_read'=>0);
			$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
			$getUserNavigation = $this->loadview('Employer/main/navigation');
			$getUserNavigation->set("nameEmp" , $current_user_data['username']);
			$getUserNavigation->set("profile_img" , $profileImg);
			$getUserNavigation->set("dataFull" , $current_user_data);
			$getUserNavigation->set("unread_msg_count" , $unread_msg_count);
			$getUserNavigation->render();
			$template = $this->loadview('Employer/view_reports');
			$template->set('jobs',$jobs);
			$template->set('myarrayCollects',$myarrayCollects);
			$template->set('myarrayCollectsCheck',$myarrayCollectsCheck);
			$template->set('priceActivity',$priceActivity);
			$template->set('countJob',$countJob);
			$template->set('totalPrice',$totalPrice);

		
		//$this->get_previous_months(date('F',time()));
		$previous_months=$this->get_previous_months(date('d-m-Y',strtotime ( '-1 month' , time())));
		
		
		//get the transactions and expenses Details
		$completed_activities=array();
		$transactions=array();
		
		//Get the contracts of the currrent employer
		//get the job posted by the current employer
		$jobs_employer=$this->Model->Get_all_with_cond('job_author',$current_user_data['id'],PREFIX.'jobs');
		$jobid="";
		if(!empty($jobs_employer))
		{
			foreach($jobs_employer as $jobs)
				$jobid.=$jobs['id'].",";
		}
		
		$jobid=rtrim($jobid,',');
		
		/*get current employe5r contracts*/
		
		$contracts=$this->Model->filter_data('Select * from '.PREFIX.'hire_contractor where job_id IN('.$jobid.')');
		
		if(!empty($contracts))
		{
			foreach($contracts as $contract)
			{
				//get the job name
				$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
				
				//get the contractor name
				$contra_det=$this->Model->Get_row('id',$contract['contractor_id'],PREFIX.'users');
				
				//get the completed activities
				$conditions=array("contract_id"=>$contract['id'],"status"=>1,"job_report_status"=>2,"dispute_status"=>0);
				
				//check whether the job is hourly or fixed
				$desc=$this->Model->Get_row('id',$contract['applied_job_id'],PREFIX.'applied_jobs');
				if(empty($desc['proposal_type']))
					$proposal_type=ucfirst($job['job_type']);
				else
					$proposal_type=$desc['proposal_type'];
				
				//check the expense details of the contract
				$expenses=json_decode($contract['external_expanditure']);
				$ex="";
				foreach($expenses as $expense)
				{
					$ex.=ucfirst($expense->name).',';
				}
				
				
				$completed=$this->Model->Get_all_with_multiple_cond($conditions,PREFIX.'hired_contractor_activity_status');
				
				if(!empty($completed))
				{
					$com=array();
					$tra=array();
					foreach($completed as $c)
					{
						//get the activity detail
						$activity=$this->Model->Get_row('id',$c['activity_id'],PREFIX.'job_activities');
						
						//get amount from the report status
						$activity_re=$this->Model->Get_row('activity_status_id',$c['id'],PREFIX.'hired_contractor_activity_report');
						
						$total=$activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
						$date=date('m/d/y',strtotime($c['modified_date']));
						$job_title=$job['job_title'];
						$contra_det=$contra_det['first_name']." ".$contra_det['last_name'];
						
						
						$com['date']=$date;
						$tra['date']=$date;
						
						$com['job_name']=$job_title;
						$tra['job_name']=$job_title;
						
						$com['contractor']=$contra_det;
						$tra['contractor']=$contra_det;
						
						$com['amount']=$total;
						$tra['amount']=$total;
						
						$com['activity_id']=$activity['id'];
						$com['activity_name']=$activity['activity_name'];
						$completed_activities[]=$com;
						
						$tra['expense']=rtrim($ex,',');
						$tra['description']=$proposal_type;
						$transactions[]=$tra;
					}
				}
			}
		}
		
		$contracts_c=$this->Model->Get_all_with_multiple_cond(array('employer_id'=>$this->userid,'status'=>'3'),PREFIX.'hire_contractor');
		
		$total_jobs_completed=count($contracts_c);
		$ar=array();
		foreach($contracts as $con)
		{
			$ar[]=$con['id'];
		}
		$count_comple_acti=0;
		if(!empty($ar))
		{
			$con_ids=implode(',',$ar);
			$comple_act=$this->Model->filter_data('select * from '.PREFIX.'hired_contractor_activity_status where status=1 AND job_report_status=2 AND dispute_status=0 AND contract_id IN("'.$con_ids.'")');
			$count_comple_acti=count($comple_act);
		}
		
		$template->set('transactions',$transactions);
		$template->set('expenses',$completed_activities);
		$template->set('prev_months',$previous_months);
		$template->set('completed_jobs',$total_jobs_completed);
		$template->set('comp_acti',$count_comple_acti);
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