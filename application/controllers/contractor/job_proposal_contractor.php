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
				
				
				$template = $this->loadview('contractor/contractor_views/job_proposals');
				
				//get the submitted and active applications
				$applied_jobs=$this->Model->filter_data('SELECT aj.created_date,aj.id as applied_job_id, aj.message,j.id,j.job_title,j.job_slug from '.PREFIX.'applied_jobs as aj inner join '.PREFIX.'jobs as j on aj.job_id=j.id where aj.contractor_id='.$this->userid.' and aj.status=0 and j.jobjob_status=1');
				$appliedjobs=array();
				$activejobs=array();
				foreach($applied_jobs as $j)
				{
					$cond=array('job_id'=>$j['id'], 'conv_from'=>$this->userid);
					$resf=$this->Model->Get_all_with_multiple_cond($cond,PREFIX.'conversation_set');
					//get the id
					$conv_id=$resf[0]['id'];
					$to=$resf[0]['conv_to'];
					$messages=$this->Model->Get_all_with_cond('conv_id',$conv_id,PREFIX.'message_set');
					//get all messages of the conversation id\
					$temp=0;
					foreach($messages as $msg)
					{
						if($temp == 0)
						{
							if($msg['from_id'] == $to)
							{
								$activejobs[]=$j;
								$temp=1;
							}
							else
							{
								$appliedjobs[]=$j;
							}
						}
					}
				}
				
				$template->set('submitted_jobs',$appliedjobs);
				
				//get the active applications
				$template->set('active_jobs',$activejobs);
				
				//get the employer proposals
				$invitations=$this->Model->filter_data('SELECT fj.*, fji.*, COUNT(fja.id) as job_activities from '.PREFIX.'jobs as fj INNER JOIN '.PREFIX.'job_invite as fji ON fj.id = fji.job_id INNER JOIN '.PREFIX.'job_activities as fja ON fj.id = fja.job_id where fji.contractor_id = '.$this->userid.'');
				$template->set('employer_proposals',$invitations);
				
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