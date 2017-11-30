<?php 
	if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			$role=$this->udata['role'];
			$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
			if($role_name['role_name'] == 'contractor')
			{
				/*if job is applied*/
				if(isset($_POST['apply_job']))
				{
					/*Save applied job*/
					$contractor_id=$_POST['contractor_id'];
					$job_id=$_POST['job_id'];
					$selected_activities=$_POST['selected_activity'];
					$activities=implode(',',$selected_activities);
					if(isset($_POST['company_rate']))
						$company_rate=$_POST['company_rate'];
					else
						$company_rate="";
					
					$payment_terms=$_POST['payment_terms'];
					if($payment_terms == "new_terms")
					{
						$payrate=$_POST['payRate'];
						$proposed_amount=$_POST['proposed_amount'];
					}
					else
					{
						$payrate='';
						$proposed_amount='';
					}
					$training_acceptance=$_POST['acceptTerms'];
					if($training_acceptance == "on")
						$training_acceptance=1;
					
					$cover_letter=$_POST['cover_letter'];
					$message=$_POST['message'];
					$data=array(
								'contractor_id'=>$contractor_id,'job_id'=>$job_id,'activity_id'=>$activities,'company_proposal_rate'=>$company_rate,
								'payment_terms'=>$payment_terms,'proposal_type'=>$payrate,'proposal_rate'=>$proposed_amount,
								'training_acceptance'=>$training_acceptance,'cover_letter'=>$cover_letter,'message'=>$message,
								'created_date'=>date('Y-m-d h:m:s')
								);
					$applied_job_id=$this->Model->Insert_data($data,PREFIX.'applied_jobs'); 
					
					/*save message */
					/*get the creator of the job*/
					if(!empty($message))
					{
						$to_id=$this->Model->Get_column('job_author','id',$job_id,PREFIX.'jobs');
						$toid=$to_id['job_author'];
						if(!empty($toid))
						{
							//then check if conversation already exists between the users
							$cond=array('conv_to'=>$toid,'conv_from'=>$contractor_id,'job_id'=>$job_id);
							$cov_data=$this->Model->get_count_with_multiple_cond($cond,PREFIX.'conversation_set');
							$date=strtotime("now");
							if($cov_data == 0)
							{
								//insert to conversation table
								$msg_data=array(
										'conv_to'=>$toid,'conv_from'=>$contractor_id,'job_id'=>$job_id,
										'created_date'=>$date,'modified_date'=>$date
										);
								$conver_id=$this->Model->Insert_data($msg_data,PREFIX.'conversation_set'); 
								if(!empty($conver_id ))
								{
									$msg_insert=array(
										'conv_id'=>$conver_id,
										'to_id'=>$toid,'from_id'=>$contractor_id,
										'message'=>$message,
										'message_time'=>$date
									);
									$conver_id=$this->Model->Insert_data($msg_insert,PREFIX.'message_set'); 
								}
							}
							else
							{
								$conditions=array('conv_to'=>$toid,'conv_from'=>$contractor_id);
								$conv_id=$this->Model->Get_all_with_multiple_cond($conditions,PREFIX.'conversation_set');
								$convid=$conv_id[0]['id'];
								if(!empty($convid ))
								{
									$msg_insert=array(
										'conv_id'=>$convid,
										'to_id'=>$toid,'from_id'=>$contractor_id,
										'message'=>$message,
										'message_time'=>$date
									);
									$conver_id=$this->Model->Insert_data($msg_insert,PREFIX.'message_set'); 
								}
							}
						}
					}
					
					
					/*save answers*/
					if(isset($_POST['questions']) && !empty($_POST['questions']))
					{
						$all_questions=$_POST['questions'];
						foreach($all_questions as $question => $answer)
						{
							$answer_data=array('applied_job_id' => $applied_job_id,'question_id' => $question, 'answer' => $answer['answer'], 'created_date' => date('Y-m-d h:m:s'));
							 $this->Model->Insert_data($answer_data,PREFIX.'applied_answers'); 
						}
					}
					
					/*remove this job from saved jobs if it was there*/
					/*check if there and then delete*/
					$conditions=array('contractor_id'=>$contractor_id, 'job_id'=> $job_id, 'saved_for'=>'contractor');
					$if_in_saved=$this->Model->Get_all_with_multiple_cond($conditions,PREFIX.'saved_jobs');
					if(!empty($if_in_saved))
					{
						foreach($if_in_saved as $sj)
						{
							$saved_job_id=$sj['id'];
							/*delete*/
							$this->Model->delete_data('id',$saved_job_id,PREFIX.'saved_jobs');
						}
					}
					
					/*save it to the notifications*/
					/* $nw=strtotime("now") ;
					$notification_array=array('alert_type'=> 'applied_job','contractor_id'=>$this->userid,'job_id'=>$job_id,'alert'=>0,'created_date'=>$nw,'modified_date'=>$nw);
					$this->Model->Insert_data($notification_array,PREFIX.'alerts'); 
					 */
					
					/*get the employer from  job id*/
					$employer_id=$this->Model->Get_column('job_author','id',$job_id,PREFIX.'jobs');
					$this->Notification->insertNotification('job_applied',$this->userid,$employer_id['job_author'],0,$applied_job_id);
					
					$this->redirect('contractor/view_posted_job/?applied_job='.$applied_job_id);
					exit();
				}
				else
				{ 
					$this->loadview('main/header')->render();
					$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
					$this->navigation($data);
					$template = $this->loadview('contractor/contractor_views/apply_for_job');
				
					/*get the job related info*/
					/*get the slug*/
					$url=$_SERVER['REQUEST_URI'];
					$pos = strrpos($url, '/');
					$slug = $pos === false ? $url : substr($url, $pos + 1);
					
					/*get the jobid*/
					$job_id=$this->Model->Get_column('id','job_slug',$slug,PREFIX.'jobs');
					$jobid=$job_id['id'];
					
					if(isset($jobid) && $jobid != "")
					{
						$job_data=$this->Model->Get_row('id',$jobid,PREFIX.'jobs');
						$template->set('job_data',$job_data);
						
						/*get job activities*/
						$job_activities=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_activities');
						$template->set('job_activities',$job_activities);
						
						/*job overages*/
						$job_overages=$this->Model->Get_row('job_id',$jobid,PREFIX.'job_additional_hours');
						$template->set('job_overages',$job_overages);
						
						/*questions related to job*/
						$job_questions=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_questions');
						$template->set('job_questions',$job_questions);
						
						/*check if alert is saved or not*/
						$wherecondalert=array('contractor_id'=>$this->userid,'job_id'=>$jobid,'alert_type'=>'flex_alert');
						$flex_alert_res=$this->Model->Get_all_with_multiple_cond($wherecondalert,PREFIX.'alerts');
						
						if(!empty($flex_alert_res))
							$template->set('alert','yes');
						else
							$template->set('alert','no');
						
						/*set allowable expenses*/
						$job_expenses=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_expenditure');
						$template->set('job_expenses',$job_expenses);
						
						/*set instance*/
						$template->set('instance',$this);
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