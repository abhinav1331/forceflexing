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
		$template = $this->loadview('contractor/contractor_views/view_reports');
		
		//$this->get_previous_months(date('F',time()));
		$previous_months=$this->get_previous_months(date('d-m-Y',strtotime ( '-1 month' , time())));
		
		//get the transactions and expenses Details
		$weekly_time_sheet=array();
		$completed_activities=array();
		$transactions=array();
		
		//Get the contracts of the currrent user_error
		$contracts=$this->Model->Get_all_with_cond('contractor_id',$this->userid,PREFIX.'hire_contractor');
		foreach ($contracts as $key => $contract) 
		{
			$myJobId = $contract['job_id'];
			$arrayInitialize = array();
			$priceActivity = array();
			$arrayInitialize1 = array();
			$jobs=$this->Model->Get_all_with_cond('id',$myJobId,PREFIX.'jobs');
			foreach ($jobs as $key => $jobsMy) {
				if($jobsMy['jobjob_status'] == 3){
					 $arrayInitialize[] = array("created_date" => date("y-m" , $jobsMy['job_modified']) , "createdDateCheck" => date("y-m-d" , $jobsMy['job_modified']));
				}
				
			}

			$myContractId = $contract['id'];
			$hired_contractor_activity_status=$this->Model->Get_all_with_cond('contract_id',$myContractId,PREFIX.'hired_contractor_activity_status');
			foreach ($hired_contractor_activity_status as $key => $hired_contractor_activity) {
				if($hired_contractor_activity['status'] == 2 && $hired_contractor_activity['job_report_status'] == 2 && $hired_contractor_activity['dispute_status'] == 0){
					 $arrayInitialize1[] = array("created_date" => date("y-m" , strtotime($hired_contractor_activity['modified_date'])) , "createdDateCheck" => date("y-m-d" , strtotime($hired_contractor_activity['modified_date'])));
				}
				
			}
			foreach ($hired_contractor_activity_status as $key => $hired_contractor_activity) 
			{
				if($hired_contractor_activity['job_report_status'] == 2) 
				{
					$flex_hired_contractor_activity_report = $this->Model->get_Data_table(PREFIX.'hired_contractor_activity_report','activity_status_id',$hired_contractor_activity['id']);
					if(!empty($flex_hired_contractor_activity_report)) 
					{
						//$totalPrice = $totalPrice + $flex_hired_contractor_activity_report[0]['total_activity_amount'];
						$priceActivity[] = array("created_date" => date("y-m" , strtotime($hired_contractor_activity['modified_date'])) , "createdDateCheck" => date("y-m-d" , strtotime($hired_contractor_activity['modified_date'])) , "Price" => $flex_hired_contractor_activity_report[0]['total_activity_amount']);	
					}
					
				}
			}
		}

		//Jobs Completed
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
		//Jobs Activity Completed
		$myarrayCollectsCheck1 = array();
			$myarrayColleCheck1 = array();
			foreach($arrayInitialize1 as $arrayInitialize1w) {
				if(!in_array($arrayInitialize1w['created_date'] , $myarrayColleCheck1)) {
					$myarrayColleCheck1[] = $arrayInitialize1w['created_date'];
					$myarrayCollectsCheck1[] = array("created_date" => $arrayInitialize1w['created_date'] ,"createdDateCheck" => $arrayInitialize1w['createdDateCheck'] , "count" => 1);
				} else {
					$key = array_search($arrayInitialize1w['created_date'], array_column($myarrayCollectsCheck1, 'created_date'));
					$oldd = $myarrayCollectsCheck1[$key]['count'];
					$countNew = $oldd + 1;
					$myarrayCollectsCheck1[$key] = array("created_date" => $arrayInitialize1w['created_date'],"createdDateCheck" => $arrayInitialize1w['createdDateCheck'] , "count" => $countNew);
				}
			}
		
		if(!empty($contracts))
		{
			foreach($contracts as $contract)
			{
				//get the job name
				$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
				
				//get the company name
				$companyy=$this->Model->Get_row('id',$job['job_author'],PREFIX.'users');
				$company=$companyy['company_name'];
				
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
						
						$com['date']=$date;
						$tra['date']=$date;
						
						$com['job_name']=$job_title;
						$tra['job_name']=$job_title;
						
						$com['company']=$company;
						$tra['company']=$company;
						
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
			
				/*Get the weekly time sheet*/
				$qry="SELECT * FROM `flex_hired_contractor_activity_status` WHERE `modified_date` > DATE(NOW()) - INTERVAL 7 DAY AND contract_id=".$contract['id']." AND status=1 AND job_report_status=2 AND dispute_status=0";
				$weekly_results=$this->Model->filter_data($qry);
				if(!empty($weekly_results))
				{
					$weekly=array();
					
					$sixdaybefore=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-6 days")))));
					$fivedaybefore=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-5 days")))));
					$fourdaybefore=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-4 days")))));
					$threedaybefore=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-3 days")))));
					$twodaybefore=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-2 days")))));
					$onedaybefore=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-1 days")))));
					$current=strtolower(strftime("%A",strtotime(date('Y/m/d'))));
					
					$weekly["initial"]="";
					$weekly[$sixdaybefore]=0;
					$weekly[$fivedaybefore]=0;
					$weekly[$fourdaybefore]=0;
					$weekly[$threedaybefore]=0;
					$weekly[$twodaybefore]=0;
					$weekly[$onedaybefore]=0;
					$weekly[$current]=0;
					$weekly["end"]="";
					
					$total=0;
					foreach($weekly_results as $wr)
					{
						//get the activity detail
						$activity=$this->Model->Get_row('id',$wr['activity_id'],PREFIX.'job_activities');
						
						//get amount from the report status
						$activity_re=$this->Model->Get_row('activity_status_id',$wr['id'],PREFIX.'hired_contractor_activity_report');
						
						$total += $activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
						$day=strtolower(strftime("%A",strtotime($wr['modified_date'])));
						$job_title=$job['job_title'];
						
						$weekly["initial"]= $job_title;
						
						if($day == $sixdaybefore)
							$weekly[$sixdaybefore] = $weekly[$sixdaybefore] + 1;
						
						if($day == $fivedaybefore)
							$weekly[$fivedaybefore] = $weekly[$fivedaybefore] + 1;
						
						if($day == $fourdaybefore)
							$weekly[$fourdaybefore] = $weekly[$fourdaybefore] + 1;
						
						if($day == $threedaybefore)
							$weekly[$threedaybefore] = $weekly[$threedaybefore] + 1;
						
						if($day == $twodaybefore)
							$weekly[$twodaybefore] = $weekly[$twodaybefore] + 1; 
						
						if($day == $onedaybefore)
							$weekly[$onedaybefore] = $weekly[$onedaybefore] + 1;
						
						if($day == $current)
							$weekly[$current] = $weekly[$current] + 1;
						
						$weekly["end"]=$total;
						//if($day == )
					}
					$weekly_time_sheet[]=$weekly;
				}
			}
			
		}
		
		
		$contracts_c=$this->Model->Get_all_with_multiple_cond(array('contractor_id'=>$this->userid,'status'=>'3'),PREFIX.'hire_contractor');
		
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
		
		$template->set('weekly_time_sheet',$weekly_time_sheet);
		$template->set('transactions',$transactions);
		$template->set('expenses',$completed_activities);
		$template->set('prev_months',$previous_months);
		$template->set('completed_jobs',$total_jobs_completed);
		$template->set('comp_acti',$count_comple_acti);
		$template->set('myarrayCollectsCheck',$myarrayCollectsCheck);
		$template->set('myarrayCollectsCheck1',$myarrayCollectsCheck1);
		$template->set('priceActivity',$priceActivity);
		
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