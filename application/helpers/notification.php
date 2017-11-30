<?php
class Notification extends Model
{
	/* Real Notification */
	
	public function insertNotification($type,$FromUserID,$ToUserID,$ForAdmin,$ForID)
	{
		if( $type == 'job_posted' )
		{
			$message = 'New Job Posted';
		}
		elseif( $type == 'contract_created' )
		{
			$message = 'New Contract Created';
		}
		elseif( $type == 'contract_completed' )
		{
			$message = 'Contract Completed';
		}
		elseif( $type == 'paymentDetails_updated' )
		{
			$message = 'Payment Details Updated';
		}
		elseif( $type == 'paymentDetails_inserted' )
		{
			$message = 'Payment Details Inserted';
		}
		elseif($type == 'job_applied')
		{
			$message= 'New Job Applied'; 
		}
		elseif($type == 'job_updated')
		{
			$message= 'Applied Job has been Updated';
		}
		elseif($type == 'activity_report_submitted')
		{
			$message= 'Activity Report Submitted';
		}
		
		
		$data = array
		(
			'noti_type'		=>$type,
			'noti_message'	=>$message,
			'fromUserID'	=>$FromUserID,
			'toUserID'		=>$ToUserID,
			'forAdmin'		=>$ForAdmin,
			'forID'			=>$ForID,
			'createdOn'		=>date('Y-m-d H:i:s'),
			'modifiedOn'	=>date('Y-m-d H:i:s'),
		);
		
		return $InsertedID = $this->Insert($data,PREFIX.'notification_message');
		
	}
	
	
	public function get_notification($userID)
	{
		$getUserDeatils = $this->getUserDetails($userID);
		
		if( $getUserDeatils['userRole'] == 1 || $getUserDeatils['userRole'] == 5 )
		{
			$result = $this->getDetailedNotification( 'YES' , $userID );
		}
		else
		{
			$result = $this->getNewNotifications($userID );
		}
		return $result;
	}
	
	public function get_all_notifications($userID)
	{
		ob_start();
		$getUserDeatils = $this->getUserDetails($userID);
		$Details = $this->get_table_data(PREFIX.'notification_message','toUserID',$userID);
		if(!empty($Details))
		{
			foreach($Details as $noti)
			{
				$message="";
				$link="javascript:void(0)";
				//contractor notification
				if($noti['noti_type'] == "job_invitation")
				{
					$fromUserID = $this->getUserDetails($noti['fromUserID']);
					$link=SITEURL."contractor/job_proposals/";
					$message="You've a new job invitation from ".ucfirst($fromUserID['username'])."";
				}
				elseif($noti['noti_type'] == "applied_job_rejected")
				{
					/*job id*/
					$job_id=$noti['forID'];
					$jobdetails=$this->getJobDetails($job_id);
					
					$link=SITEURL."contractor/job_description/".$jobdetails['job_slug']."";
					$message='Job "'.$jobdetails['job_title'].'" applied by you has been rejected by the employer.';
					
				}
				elseif($noti['noti_type'] == "contract_created")
				{
					$contract_id=$noti['forID'];
					$contract_details=$this->getContractDetails($contract_id);
					$job_details=$this->getJobDetails($contract_details['job_id']);
					
					$link=SITEURL."contractor/view_contract/?contract_id=".$contract_id."";
					$message='Contract for the job "'.$job_details['job_title'].'" has been created by the employer';
					
				}
				elseif($noti['noti_type'] == "dispute_by_employer")
				{
					$hired_contractor_activity_status_id=$noti['forID'];
					$ac_st_de=$this->getContractActivityStatusDetails($hired_contractor_activity_status_id);
					$acvty_details=$this->getActivityDetails($ac_st_de['activity_id']);
					$job_details=$this->getJobDetails($acvty_details['job_id']);
					
					$link=SITEURL."contractor/submit_report/?id=".$hired_contractor_activity_status_id."&action=dispute";
					$message= 'Dispute has been created by the employer on your activity "'.$acvty_details['activity_name'].'" for the job "'.$job_details['job_title'].'"';
					
				}
				elseif($noti['noti_type'] == "activity_completed")
				{
					$activity_status_id=$noti['forID'];
					
					$ac_st_de=$this->getContractActivityStatusDetails($activity_status_id);
					$acvty_details=$this->getActivityDetails($ac_st_de['activity_id']);
					$job_details=$this->getJobDetails($acvty_details['job_id']);
					
					$link=SITEURL."contractor/my_job_reports/";
					$message= 'payment for the Activity "'.$acvty_details['activity_name'].'" of the job "'.$job_details['job_title'].'" has been completed';
				}
				elseif($noti['noti_type'] == "payment_completed")
				{
					$contract_id=$noti['forID'];
					$contract_details=$this->getContractDetails($contract_id);
					$job_details=$this->getJobDetails($contract_details['job_id']);
					$link=SITEURL."contractor/my_jobs/";
					$message= 'Payment for the job "'.$job_details['job_title'].'" has been made';
				}
				elseif($noti['noti_type'] == "contract_completed")
				{
					$contract_id=$noti['forID'];
					$contract_details=$this->getContractDetails($contract_id);
					$job_details=$this->getJobDetails($contract_details['job_id']);
					$link=SITEURL."contractor/my_jobs/";
					$message= 'Contract for the Job "'.$job_details['job_title'].'" has been completed.';
				}
				elseif($noti['noti_type'] == "job_applied")  //Employer Notifications
				{
					
				}
				elseif($noti['noti_type'] == "job_updated")
				{
					
				}
				elseif($noti['noti_type'] == "activity_report_submitted")
				{
					
				}
				elseif($noti['noti_type'] == "dispute_by_contractor") 
				{
					
				}
				elseif($noti['noti_type'] == "job_report_resubmitted")
				{
					
				}
				elseif($noti['noti_type'] == "payment_requested")
				{
					
				}
				
				if(!empty($message))
				{
				?>
					<article class="notifs"> <a class="remove-notif" id="<?php echo $noti['id']; ?>" href="javascript:void(0);"></a>
						<p><a href="<?php echo $link; ?>"><?php echo $message; ?></a></p>
						<time><?php echo date('M d', strtotime($noti['createdOn'])); ?></time>
					</article>
				<?php 
				}
			}
		}
		else
		{?>
			<article class="notifs">
				<p>You don't have any Notification Yet</p>
			</article>
		<?php 
		}
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	public function getContractActivityStatusDetails($id)
	{
		return $this->get_single_row('id', $id,PREFIX.'hired_contractor_activity_status');		
	}
	
	public function getActivityDetails($activity_id)
	{
		return $this->get_single_row('id',$activity_id,PREFIX.'job_activities');
	}
	
	public function getUserDetails($userID)
	{
		$username = $this->get_single_row('id', $userID,PREFIX.'users');
		return array('userID'=>$username['id'],'userRole'=>$username['role'],'username'=>$username['username']);
	}
	
	
	public function getJobDetails( $jobID )
	{
		return $this->get_single_row('id', $jobID,PREFIX.'jobs');		
	}
	
	
	public function getContractDetails( $ContractID )
	{
		return $this->get_single_row('id', $ContractID,PREFIX.'hire_contractor');
	}
	
	public function getDetailedNotification( $IsAdmin = Null, $userID )
	{
		if( $IsAdmin == 'YES' )
		{
			$Details = $this->get_table_data(PREFIX.'notification_message','forAdmin',0);
			$text = array();
			foreach( $Details as $keys ):
			$fromUserID = $this->getUserDetails($keys['fromUserID']);
			$Date = date('Y-m-d',strtotime($keys['createdOn']));
			
			
			if( $keys['noti_type'] == 'job_posted' )
				{
					$forID = $this->getJobDetails( $keys['forID'] );
					
					$text[]= '<li>
						<a href="'.SITEURL.'admin/viewJob/'.$forID['job_slug'].'" target="_blank">
							<div>
								<strong>'.$keys['noti_message'].'</strong>
								<span class="pull-right text-muted">
									<em>'.$Date.'</em>
								</span>
							</div>
							<div>'.ucfirst($fromUserID['username']).' posted New Job [ '.ucfirst($forID['job_title']).' ]  </div>
						</a>
					</li>';					
					
				}
			elseif( $keys['noti_type'] == 'contract_created' )
				{
					$ContractDetails = $this->getContractDetails( $keys['forID'] );
					$Contractor = $this->getUserDetails( $ContractDetails['contractor_id'] );
					$Employer = $this->getUserDetails( $ContractDetails['employer_id'] );
					
					$text[]= '<li>
						<a href="'.SITEURL.'admin/Viewcontracts/'.base64_encode($ContractDetails['id']).'" target="_blank">
							<div>
								<strong>'.$keys['noti_message'].'</strong>
								<span class="pull-right text-muted">
									<em>'.$Date.'</em>
								</span>
							</div>
							<div> New Contract is created between '.ucfirst($Employer["username"]).' ( Employer ) and '.ucfirst($Contractor["username"]).' ( Contractor ) </div>
						</a>
					</li>';
					
				}
			elseif( $keys['noti_type'] == 'contract_completed' )
				{
					$ContractDetails = $this->getContractDetails( $keys['forID'] );
					$text[]= '<li>
						<a href="'.SITEURL.'admin/Viewcontracts/'.base64_encode($ContractDetails['id']).'" target="_blank">
							<div>
								<strong>'.$keys['noti_message'].'</strong>
								<span class="pull-right text-muted">
									<em>'.$Date.'</em>
								</span>
							</div>
							<div>Contract Completed</div>
						</a>
					</li>';									
				}
			elseif( $keys['noti_type'] == 'paymentDetails_updated' )
				{
					
					$text[]= '<li>
						<a href="javascript:void(0)" target="_blank">
							<div>
								<strong>'.$keys['noti_message'].'</strong>
								<span class="pull-right text-muted">
									<em>'.$Date.'</em>
								</span>
							</div>
							<div> '.ucfirst($fromUserID['username']).' Updated his Card Details.</div>
						</a>
					</li>';									
				}
			elseif( $keys['noti_type'] == 'paymentDetails_inserted' )
				{
					
					$text[]= '<li>
						<a href="'.SITEURL.'admin/Viewcontracts/'.base64_encode($ContractDetails['id']).'" target="_blank">
							<div>
								<strong>'.$keys['noti_message'].'</strong>
								<span class="pull-right text-muted">
									<em>'.$Date.'</em>
								</span>
							</div>
							<div> '.ucfirst($fromUserID['username']).' Successfully! Inserted Card Details.</div>
						</a>
					</li>';									
				}
			endforeach;
			return $text;
		}
		else
		{
			$text .= "<li><a href='".SITEURL.'admin/viewJob/'.$forID['job_slug']."' target='_blank'><span class='noti_title'>".$keys['noti_message']."</span>".$fromUserID['username']." posted New Job [ ".$forID['job_title']."] </a></li>";
			
		}
	}
	
	public function getNewNotifications($userID)
	{
		$Details=$this->get_all_mul_cond(array('toUserID'=>$userID,'is_read'=>0),PREFIX.'notification_message','DESC');
		return $Details;
	}
}