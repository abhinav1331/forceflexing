<?php 
if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
{
	$role=$this->udata['role'];
	$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
	
	$this->loadview('main/header')->render();
	
	/*load the navigation as per the role*/
	if($role_name['role_name'] == 'contractor')
	{
		/* Navigation */
		$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
		$this->navigation($data);
	}
	else
	{
		
	}
	
	/*load the inbox template*/
	$template = $this->loadview('inbox/view_inbox');
	 
	//get the latest conversationm threads for the current 
	$conversation="SELECT a.id AS con_id,a.job_id ,b.from_id,b.to_id,b.message,b.message_time FROM flex_conversation_set AS a, flex_message_set AS b WHERE a.id=b.conv_id AND (a.conv_to=19 or a.conv_from=19) group by a.id order by b.message_time DESC";
	$result=$this->Model->filter_data($conversation);
	
	$con=array();
	$conver_array=array();
	/*conversation thread*/
	if(!empty($result))
	{
		foreach($result as $r)
		{
			$conver_array['conv_id']=$r['con_id'];
			
			/*get the job title from job id*/
			$job_title=$this->Model->Get_column('job_title','id',$r['job_id'],PREFIX.'jobs');
			$conver_array['job_title']=$job_title['job_title'];
			
			$conver_array['message']=$r['message'];
			$conver_array['msg_time']=$r['message_time'];
			
			//get the opponnet name
			if($r['from_id'] == $this->userid)
				$opponent=$r['to_id'];
			elseif($r['to_id'] == $this->userid)
				$opponent=$r['from_id'];
			
			$conver_array['opponent_user_id']=$opponent;
			
			//get the opponent user name
			$opponentdata=$this->Model->Get_row('id',$opponent,PREFIX.'users');
			if(!empty($opponentdata))
				$opponent_name=$opponentdata['first_name']." ".$opponentdata['last_name'];
			else
				$opponent_name="";
			
			//get the last message user 
			if($r['from_id'] == $this->userid)
				$last_message_user="You";
			else
				$last_message_user=$opponent_name;
			
			$conver_array['opponent_user']=$opponent_name;
			$conver_array['last_msg_by']=$last_message_user;
			
			$con[]=$conver_array;
		}
		
		/*get messages of the latest converation*/
		$latest_con_id=$result[0]['con_id'];
		if(!empty($latest_con_id))
		{
			/*get messages*/
			$messages=$this->Model->Get_all_with_cond('conv_id',$latest_con_id,PREFIX.'message_set');
			if(!empty($messages))
			{
				$message_thread=array();
				$mess=array();
				foreach($messages as $msg)
				{
					$sender=$this->Model->Get_row('id',$msg['from_id'],PREFIX.'users');
					$sender_name=$sender['first_name']. " ".$sender['last_name'];
					
					//check sender is employer or contractor
					if($sender['role'] == 2)
					{
						$sen_img=$this->Model->Get_column('company_image','company_id',$sender['id'],PREFIX.'company_info');
						$sender_img=$sen_img['company_image'];
					}
					elseif($sender['role'] == 3)
					{
						$sen_img=$this->Model->Get_column('profile_img','user_id',$sender['id'],PREFIX.'contractor_profile');
						$sender_img=$sen_img['profile_img'];
					}
					$mess['sender_name']=$sender_name;
					$mess['sender_img']=$sender_img;
					$mess['mesg']=$msg['message'];
					$mess['message_time']=$msg['message_time'];
					$mess['role']=$sender['role'];
					$message_thread[]=$mess;
				}
			}
		}
	}
	$template->set('conversation_thread',$con);
	$template->set('message_thread',$message_thread);
	$template->render();
	
	/*load the footer*/
	$this->loadview('main/footer')->render();	
}
else
{
	$this->redirect('login');
}