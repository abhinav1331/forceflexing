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
		
		//get the contract details
		$template = $this->loadview('contractor/contractor_views/view_contractor_contract');
		$contract_id=1;
		
		if(!empty($contract_id))
		{
			$template->set('contract_id',$contract_id);	
			//get the status of the contract
			$status=$this->Model->Get_row('id',$contract_id,PREFIX.'hire_contractor');
			if($status['status'] == 0)
			{
				//get the job activities
				$job_activities=$status['activity_id'];
				if(!empty($job_activities))
				{
					$all_activities=json_decode($job_activities);
					$activities=implode(',',$all_activities);
					$ac=$this->Model->filter_data('Select * from '.PREFIX.'job_activities where id IN('.$activities.')');
					$template->set('activities',$ac);
				}
				//get the job related data
				$job_id=$status['job_id'];
				$jobdata=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
				$template->set('jobdata',$jobdata);
				
				//get the additional hours of a job
				$additonal_hours=$this->Model->Get_row('id',$job_id,PREFIX.'job_additional_hours');
				$template->set('additional_hours',$additonal_hours);
				
				//expenses for contract
				$expenses=$status['external_expanditure'];
				if(!empty($expenses))
				{
					$exp=json_decode($expenses);
					$ex=implode(',',$exp);
					$e=$this->Model->filter_data('Select * from '.PREFIX.'job_expenditure where id IN('.$ex.')');
					$template->set('expenses',$e);
				}
				
				//get the attachment
				if(!empty($status['attachmentId']))
				{
					$attachment=$this->Model->Get_column('url','id',$status['attachmentId'],PREFIX.'attachments');
					$template->set('attachment_url',$attachment['url']);
				}
				
				//get the attachment
				if(!empty($status['additionalInfo']))
				{
					$template->set('additionalinfo',$status['additionalInfo']);
				}
			}
			else
			{
				$template->set('error','Contract Does not Exist!!');
			}
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