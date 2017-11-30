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
		
		$template = $this->loadview('contractor/contractor_views/view_company_feedback');
		//get the contract id
		if(isset($_GET['contract_id']))
			$contract_id=$_GET['contract_id'];
		else
			$contract_id="";
		
		$data=array();
		if(!empty($contract_id))
		{
			//from user
			$frommuser=$this->udata['id'];
			
			//get the employer id from contract id
			$contract_details=$this->Model->Get_row('id',$contract_id,PREFIX.'hire_contractor');
			$job_id=$contract_details['job_id'];
			
			//job details
			$jd=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
			
			$data['job_title']=$jd['job_title'];
			$data['contract_id']=$contract_id;
			
			//author details
			$employer_id=$jd['job_author'];
			
			if(isset($_POST['submit_feedback']))
			{
				extract($_POST);
				$feedback=array();
				$feedback['orga_prepa']=$orga_prepa;
				$feedback['training']=$training;
				$feedback['communication']=$communication;
				$feedback['ajd']=$ajd;
				$feedback['ajc']=$ajc;
				
				$array=array("contract_id"=>$contract_id,'feedback_from'=>$frommuser,'feedback_to'=>$employer_id,'reason_contract_ended'=>$reason_contract_ended,'recommendation_score'=>$recommendation_score,'feedback'=>json_encode($feedback),'experience'=>$experience,'total_average_score'=>$average_score);
				echo $id=$this->Model->Insert_data($array,PREFIX.'user_feedback');
			}
		}
		$template->set('data',$data);
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