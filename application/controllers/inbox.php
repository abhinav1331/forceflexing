<?php 
class Inbox extends Controller
{
	public $Validator;
	public $Model;	
	public $userid;
	public $udata;
	public $msgs_per_page;
	public $TimeZone;
	public $Notification;
	public function __construct() 
	{

		$this->Validator = $this->loadHelper('validator');
		$this->SendMail=$this->loadHelper('sendmail');
		$this->Options=$this->loadHelper('options');
		$this->TimeZone=$this->loadHelper('timezone');
		$this->Notification=$this->loadHelper('notification');
		$this->Model = $this->loadModel('Contractor_model','contractor');
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password']))
		{
			if (isset($_COOKIE['force_username'])) 
			{
				$username = $_COOKIE['force_username'];
			}
			else 
			{
				$username = $_SESSION['force_username'];
			}
			
			$this->settimezone();
			/* get login user details*/
			$this->udata=$this->Model->Get_row('username',$username,PREFIX.'users');
			$this->userid=$this->udata['id'];
			$this->msgs_per_page=10;
			$this->user_visibility=array();
		}
	}
	
	/*function to append navigation*/
	public function navigation($data)
	{
		/* Navigation */
		/*check user role*/
		if($this->udata['role'] == 2)
		{
			$nav=$this->loadview('Employer/main/navigation');	
			
		}
		elseif($this->udata['role'] == 3)
		{
			$nav=$this->loadview('contractor/main/navigation');	
			$nav->set('first_name',$this->udata['first_name']);
			$nav->set('last_name',$this->udata['last_name']);
			if($data['profile_img'])
			{
				$nav->set('profile_img',$data['profile_img']);
			}
			$nav->set('user_visibility',$this->udata['visibility_status']);
			
			/*check for new notifications if any*/
			$notifications=$this->Notification->get_notification($this->userid);
			$nav->set('notifications',$notifications);
			$nav->set('instance',$this);
			$nav->render();
		
		}
				
		/*check new messsages count*/
		$c=array('to_id'=>$this->userid,'is_read'=>0);
		$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
		$nav->set('unread_msg_count',$unread_msg_count);
		$nav->render();
	}
	
	
	public function index($order="desc",$action="")
	{
		if(isset($_POST['order']))
			$order=$_POST['order'];
		
		if(isset($_POST['action']))
			$action=$_POST['action'];
		
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			$role=$this->udata['role'];
			$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
			
			//get the latest conversation threads for the current user 
			$conversation="SELECT temp.conv_id as con_id, temp.to_id, temp.from_id,temp.message,temp.message_time,b.job_id FROM (SELECT * FROM flex_message_set ORDER BY `message_time` DESC) as temp inner join flex_conversation_set as b on temp.conv_id=b.id and (temp.to_id=".$this->userid." or temp.from_id=".$this->userid.") GROUP BY `conv_id` order by b.id ".$order."";

			
			$result=$this->Model->filter_data($conversation);
			
			$con=array();
			$conver_array=array();
			$main_array=array();
			/*conversation thread*/
			if(!empty($result))
			{
				foreach($result as $r)
				{
					$conver_array['conv_id']=$r['con_id'];
					
					/*get the job title from job id*/
					if($r['job_id'] > 0)
					{
						$job=$this->Model->Get_row('id',$r['job_id'],PREFIX.'jobs');
						$conver_array['job_id']=$job['id'];
						$conver_array['job_title']=$job['job_title'];
					}
					else
					{
						$conver_array['job_id']=0;
						$conver_array['job_title']="";
					}
					
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
				
				/*get messages of the latest conversation*/
				$latest_con_id=$result[0]['con_id'];
				$latest_job_id=$result[0]['job_id'];
				if(!empty($latest_con_id))
				{
					/*get messages*/
					$total_msgs=$this->Model->get_count('conv_id',$latest_con_id,PREFIX.'message_set');
					$offset=$total_msgs-$this->msgs_per_page;
					if($offset < 0)
						$offset=0;
					
					$messages=$this->Model->filter_data("SELECT * FROM ".PREFIX."message_set WHERE `conv_id`=".$latest_con_id." order by id ASC limit ". $this->msgs_per_page." offset ".$offset."");
					
					$off_va=$offset;
					//$messages=$this->Model->Get_all_with_cond('conv_id',$latest_con_id,PREFIX.'message_set','ASC');
					
					if(!empty($messages))
					{
						$authordata=array();
						$message_detail=array();
						
						if($latest_job_id > 0)
						{
							//get job related data
							$jobdata=$this->Model->Get_row('id',$latest_job_id,PREFIX.'jobs');
							$job_title=$jobdata['job_title'];
							$job_desc=$jobdata['job_description'];
							$job_author=$jobdata['job_author'];
							$author=$this->Model->Get_row('id',$job_author,PREFIX.'users');
							$authordata['job_title']=$job_title;
							$authordata['job_desc']=$job_desc;
							$authordata['author_name']=$author['first_name']." ".$author['last_name'];
						}
						$main_array['author_detail']=$authordata;
						
						$message_detail['job_id']=$latest_job_id;
						$message_detail['convers_id']=$latest_con_id;
						$message_detail['sender_id']=$this->userid;
						$message_detail['rec_id']=$messages[0]['to_id'];
						$main_array['message_detail']=$message_detail;
						
						$main_array['offset']=$off_va;
						
						$message_thread=array();
						$mess=array();
						foreach($messages as $msg)
						{
							$sender=$this->Model->Get_row('id',$msg['from_id'],PREFIX.'users');
							$sender_name=$sender['first_name']. " ".$sender['last_name'];
							
							if($msg['from_id'] == $this->userid)
								$us="cu";
							else
								$us="ou";
							
							
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
							else
							{
								$sender_img="";
							}
							
							if($msg['attachment'] != 0)
							{
								$atta_id=$msg['attachment'];
								$u=$this->Model->Get_column('url','id',$atta_id,PREFIX.'attachments');
								$attc_url=$u['url'];
								$name=basename($attc_url);
								$name = str_replace('__','',strstr($name,"__",false));
								
								//show image if it is there
								$supported_image = array('gif','jpg','jpeg','png','gif');
								
								// Use strtolower to overcome case sensitive
								$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION)); 
								if (in_array($ext, $supported_image)) 
								{
									$attchm='<div class="goto-proposal"><a href="'.$attc_url.'" download><img src="'.$attc_url.'" height="50" width="50"></a></div>';
								} 
								else 
								{
									$attchm='<div class="goto-proposal"><a href="'.$attc_url.'" download>'.$name.'</a></div>';
								}
							}
							else
							{
								$attchm="";
							}
							
							$mess['us']=$us;
							$mess['visi']=$sender['visibility_status'];
							$mess['sender_name']=$sender_name;
							$mess['sender_img']=$sender_img;
							$mess['mesg']=$msg['message'];
							$mess['message_time']=$msg['message_time'];
							$mess['attachment']=$attchm;
							$mess['role']=$sender['role'];
							$mess['last_login_time']=$sender['last_login_time'];
							$message_thread[]=$mess;
						}
						$main_array['messages']=$message_thread;
					}
				}
			}
			
			if($action == "ajaxdata")
			{
				$this->load_conversation_ajax_response($con,$main_array);
			}
			
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
				$data=$this->Model->Get_row('company_id',$this->userid,PREFIX.'company_info'); 
				//employer navigation
				$getUserNavigation = $this->loadview('Employer/main/navigation');
				$getUserNavigation->set("nameEmp" , $data['company_slug']);
				$getUserNavigation->set("profile_img" , $data['company_image']);
				$getUserNavigation->render();
			}
			
			/*load the inbox template*/
			$template = $this->loadview('inbox/view_inbox');
			$template->set('conversation_thread',$con);
			$template->set('message_thread',$main_array);
			
			/*set the opponent users and job details for starting a new conversation*/
			if($role == 2)
			{
				//get all contractors list with no conversation with current login user
				//$qry_users="SELECT t1.* FROM flex_users t1 LEFT JOIN flex_conversation_set t2 ON (t2.conv_to = t1.id or t2.conv_from = t1.id) WHERE (t2.conv_to IS NULL or t2.conv_from=NULL) and t1.role=3";
				
				$qry_users="SELECT t1.contractor_id
				FROM flex_applied_jobs t1
				LEFT JOIN flex_conversation_set t2 ON (t2.conv_to = t1.contractor_id or t2.conv_from = t1.contractor_id)
				WHERE (t2.conv_to IS NULL or t2.conv_from=NULL)";
				
				$options=$this->get_job_list($this->userid);
				$opponent_user_list=$this->Model->filter_data($qry_users);
				$opponent_data_list=array();
				if(!empty($opponent_user_list))
				{
					foreach($opponent_user_list as $oppps)
					{
						$arra=array();
						$opdata=$this->Model->Get_row('id',$oppps['contractor_id'],PREFIX.'users');
						$arra['id']=$oppps['contractor_id'];
						$arra['first_name']=$opdata['first_name'];
						$arra['last_name']=$opdata['last_name'];
						$opponent_data_list[]=$arra;
					}
				}
				$template->set('opponent_users',$opponent_data_list);
				$template->set('jobs_of_employer',$options);
			}
			else
			{
				$template->set('opponent_users',"");
				$template->set('jobs_of_employer',"");
			}
			/*elseif($role == 3)
			{
				//get all employers list with no conversation with current user
				$qry_users="SELECT t1.id,t1.first_name,t1.last_name FROM flex_users t1 LEFT JOIN flex_conversation_set t2 ON (t2.conv_to = t1.id or t2.conv_from = t1.id) WHERE (t2.conv_to IS NULL or t2.conv_from=NULL) and t1.role=2";
				$options="";
			}*/
			
			$template->set('instance',$this);
			
			
			$template->render();
			
			/*load the footer*/
			$this->loadview('main/footer')->render();	
		}
		else
		{
			$this->redirect('login');
		}
		
	}

	/*public function to get the job list*/
	public function get_job_list($user_id="")
	{
		/*if(empty($user_id))
		{
			$user_id=$_POST['employer_id'];
		}*/
		$options='<option value="-1">Select Job</option>';
		//get the job posted by an employer
		$empl_jobs=$this->Model->Get_all_with_cond('job_author',$user_id,PREFIX.'jobs');
		if(!empty($empl_jobs))
		{
			foreach($empl_jobs as $jobs)
			{
				$options.='<option value="'.$jobs['id'].'">'.$jobs['job_title'].'</option>';
			}
		}
		return $options;
	}
	
	/*public function to load new conversation */
	public function load_new_conversation() 
	{
		$date=strtotime("now");
		$job_id=$_POST['job_id'];
		$opponent_id=$_POST['opponent_id'];
		$current_user=$this->userid;
		$ms=$_POST['message'];
		$con_insert=array(
		'conv_to'=>$opponent_id,
		'conv_from'=>$current_user,
		'job_id' => $job_id,
		'created_date'=> $date,
		'modified_date' => $date
		);
		$last_insert_id=$this->Model->Insert_data($con_insert,PREFIX.'conversation_set');
		
		$msg_insert=array(
		'conv_id'=>$last_insert_id,
		'to_id'=>$opponent_id,
		'from_id'=>$current_user,
		'message'=>$ms,
		'message_time'=>$date
		);
		echo $lstinsert_id=$this->Model->Insert_data($msg_insert,PREFIX.'message_set');
	}
	
	/*public function is_available*/
	function is_available($sender_lst_login)
	{
		$last_login_time=$sender_lst_login;
		if(strtotime($last_login_time) < strtotime("-10 minutes"))
			return 'available';
		else
			return 'online';
	}
	
	public function load_image($role,$id)
	{
		$user_img="";
		if($role == 2)
		{
			$sen_img=$this->Model->Get_column('company_image','company_id',$id,PREFIX.'company_info');
			if(!empty($sen_img['company_image']))
				$user_img=BASE_URL.'static/images/employer/'.$sen_img['company_image'];
			else
				$user_img=BASE_URL.'static/images/avatar-icon.png';
		}
		elseif($role == 3)
		{
			$sen_img=$this->Model->Get_column('profile_img','user_id',$id,PREFIX.'contractor_profile');
			if(!empty($sen_img['profile_img']))
				$user_img=BASE_URL.'static/images/contractor/'.$sen_img['profile_img'];
			else
				$user_img=BASE_URL.'static/images/avatar-icon.png';
		}
		return $user_img;
	}
	
	/*AJAX Function for loading conversation*/
	public function load_conversation_ajax_response($conversation_thread,$message_thread)
	{
		$rr=array();
		if(!empty($conversation_thread))
		{
			$i=1;
			$conv="";
			foreach($conversation_thread as $con)
			{
				$active=($i==1)?'active':'';
				$conv.='<article class="convo-box '.$active.'">
					<div class="convo-hdr">
						<h4 class="SectionAble"><a id="load_messages" data-attr-con="'.$con['conv_id'].'" data-attr-job="'.$con['job_id'].'" href="javascript:void(0);">'. $con['opponent_user'].'</a></h4>
						<time class="datestamp"> <span class="time">'.date('h:m a',$con['msg_time']).'</span> </time>
					</div>';
					
					if(!empty($con['job_title']))
						$conv.='<p><strong>'.$con['job_title'].'</strong></p>';
					
					$conv.='<p><span class="msr-recipient">'.$con['last_msg_by'].':</span>'.$con['message'].'</p></article>';
				$i++;
			}
			$rr['conversation']=$conv;
		}
		
		/*message details*/
		$rr['msgs']=json_decode($this->load_messages($message_thread['message_detail']['convers_id'],$message_thread['message_detail']['job_id'],'loadmsg','yes'));
		echo json_encode($rr);
		exit();
	}
	
	/*function for loading the messages and saving messages*/
	public function load_messages($convid="",$jobid="",$actn="",$cal_frm_ajax="",$offset="")
	{
		if(!empty($convid))
			$con_id=$convid;
		else
			$con_id=$_POST['conv_id'];
		
		
		if(!empty($jobid))
			$job_id=$jobid;
		else
			$job_id=$_POST['job_id'];
		
		if(!empty($actn))
			$action=$actn;
		else
			$action=$_POST['action'];
		
		/*save messages*/
		if(!empty($action) && $action=="save")
		{
			
			$from=$_POST['sender'];
			$to=$_POST['rec'];
			$mssg=$_POST['msg'];
			
			if(!empty($_POST['attachment']))
				$attch=$_POST['attachment'];
			else
				$attch=0;
			
			$date=strtotime("now");
			$msg_insert=array(
			'conv_id'=>$con_id,
			'to_id'=>$to,
			'from_id'=>$from,
			'message'=>$mssg,
			'attachment' => $attch,
			'message_time'=>$date
			);
			$last_insert_id=$this->Model->Insert_data($msg_insert,PREFIX.'message_set');
			$this->load_messages($con_id,$job_id,'loadmsg');
		}
		
		
		$return=array();
		if(!empty($job_id) && $job_id >0 )
		{
				//get job related data
				$jobdata=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
				$job_title=$jobdata['job_title'];
				$job_desc=$jobdata['job_description'];
				$job_author=$jobdata['job_author'];
				$author=$this->Model->Get_row('id',$job_author,PREFIX.'users');
				
				$return['author_detail']='<h2>
					<a href="#">'.$author['first_name']." ".$author['last_name'].'</a> <small>1:38 am EDT</small>
				</h2>
				<p>'.$job_desc.'</p>
				';
		}
		else
		{
			$return['author_detail']="";
		}
		
		if(!empty($con_id))
		{
			$return['messages']=$this->con_messages($con_id,$job_id);
		}
		
		$return_data=json_encode($return);
		
		if(!empty($cal_frm_ajax) && $cal_frm_ajax=="yes")
		{
			//used while loading conversation
			return $return_data;
		}
		else
		{
			echo $return_data; 
			exit();	
		}
	}

	
	public function con_messages($con_id="",$job_id="",$offset="")
	{
		$mess="";
		
		/*data from ajax on scroltop*/
		if(isset($_POST['conv_id']) && !empty($_POST['conv_id']))
			$con_id=$_POST['conv_id'];
		
		if(isset($_POST['job_id']) && !empty($_POST['job_id']))
			$job_id=$_POST['job_id'];
		
		if(isset($_POST['offset']) && !empty($_POST['offset']))
			$offset=$_POST['offset'];
		
		/*get sender and rec from conv*/
		$cvsn=$this->Model->Get_row('id',$con_id,PREFIX.'conversation_set');
	
		/*get opponent user id**/
		if($cvsn['conv_from'] == $this->userid)
			$opponent_id=$cvsn['conv_to'];
		else
			$opponent_id=$cvsn['conv_from'];
		
		/*current user details*/
		$current_user=$this->Model->Get_row('id',$this->userid,PREFIX.'users');
		$current_user_name=$current_user['first_name']. " ".$current_user['last_name'];
		$current_user_img=$this->load_image($current_user['role'],$this->userid);
		$current_last_login_time=$current_user['last_login_time'];
		$current_user_visi=$current_user['visibility_status'];
		
		/*opponent user details*/
		$opponent_user=$this->Model->Get_row('id',$opponent_id,PREFIX.'users');
		$opponent_user_name=$opponent_user['first_name']. " ".$opponent_user['last_name'];
		$opponent_user_img=$this->load_image($opponent_user['role'],$opponent_id);
		$opponent_last_login_time=$opponent_user['last_login_time'];
		$opponent_user_visi=$opponent_user['visibility_status'];
		
		/*get messages*/
		$total_msgs=$this->Model->get_count('conv_id',$con_id,PREFIX.'message_set');
		
		if(empty($offset))
			$offset=$total_msgs-$this->msgs_per_page;
		else
			$offset=$offset-$this->msgs_per_page;
		
		if($offset < 0 )
			$offset=0;
		
		$qry="SELECT * FROM ".PREFIX."message_set WHERE `conv_id`=".$con_id." order by id ASC limit ". $this->msgs_per_page." offset ".$offset."";
		
		$messages=$this->Model->filter_data($qry);
		if(!empty($messages))
		{
			if($messages[0]['to_id'] == $this->userid)
				$rec=$messages[0]['from_id'];
			else
				$rec=$messages[0]['to_id'];
			
			$mess.='<input type="hidden" id="conv_data" data-attr-con="'.$con_id.'" data-attr-job="'.$job_id.'" data-attr-sender="'.$this->userid.'" data-attr-rec="'.$rec.'" data-attr-offset="'.$offset.'">';
			
			foreach($messages as $msg)
			{
				if($msg['from_id'] == $this->userid)
				{
					$sender_name=$current_user_name;
					$sender_img=$current_user_img;
					$us="cu";
					$last_login_time=$current_last_login_time;
					$user_vis=$current_user_visi;
				}
				else
				{
					$sender_name=$opponent_user_name;
					$sender_img=$opponent_user_img;
					$us="ou";
					$last_login_time=$opponent_last_login_time;
					$user_vis=$opponent_user_visi;
				}
				if($msg['attachment'] != 0)
				{
					$atta_id=$msg['attachment'];
					$u=$this->Model->Get_column('url','id',$atta_id,PREFIX.'attachments');
					$attc_url=$u['url'];
					$name=basename($attc_url);
					$name =  str_replace('__','',strstr($name,"__",false));
					
					//show image if it is there
					$supported_image = array('gif','jpg','jpeg','png','gif');
					
					// Use strtolower to overcome case sensitive
					$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION)); 
					if (in_array($ext, $supported_image)) 
					{
						$attchm='<div class="goto-proposal"><a href="'.$attc_url.'" download><img src="'.$attc_url.'" height="50" width="50"></a></div>';
					} 
					else 
					{
						$attchm='<div class="goto-proposal"><a href="'.$attc_url.'" download>'.$name.'</a></div>';
					}
				}
				else
				{
					$attchm="";
				}
				
				$this->settimezone();
				if(strtotime($last_login_time) < strtotime("-10 minutes"))
					$avail="offline";
				elseif($user_vis == "offline")
					$avail="offline";
				else
					$avail="available";
				
				$mess.='<div class="message-text">
					<figure class="msg-user-img '.$avail.' '.$us.'">
						<img src="'.$sender_img.'">
					</figure>
					<time class="datestamp"> 
						<span class="time">'.date('H:m a',$msg['message_time']).'</span>
						<span class="date">'.date('d/m/Y',$msg['message_time']).'</span>
					</time>
					<figcaption>
					  <h4>'.$sender_name.'</h4>
					  <p>'.$msg['message'].'<br>'.$attchm.'</p>
					  <!--<div class="goto-proposal"><a href="#">Go to the proposal</a></div>-->
					</figcaption>
				</div>';
			}
			return $mess;
		}
	}
		
	/*Ajax function to save attachment*/
	public function save_attachment()
	{
		$attachment=$_POST['attachment'];
		$filname=$_POST['name'];
		if(!empty($attachment))
		{
			$namefile=time().'__'.$filname;
			$attachment=str_replace(' ','+',$attachment);
			$decoded=base64_decode($attachment);
			$uri=ABSPATH.'/static/attachments/';
			file_put_contents($uri.$namefile,$decoded);
			
			$url=BASE_URL."static/attachments/".$namefile."";
			$attdata=array("url"=>$url,"attachment_location"=>"Message","attachment_author"=>$this->userid,"created_date" => time(),'modified_date'=> time());
			
			$attach_id=$this->Model->Insert_data($attdata,PREFIX.'attachments');
			echo $attach_id;
		}
		else
		{
			echo 0;
		}
		exit();
	}
	
	public function settimezone()
	{
		/*$ip = $_SERVER['REMOTE_ADDR'];
		$details = json_decode(file_get_contents("http://ip-api.com/json/".$ip.""));
		date_default_timezone_set($details->timezone);*/
		
		$timezone=$this->TimeZone->get_time_zone();
		date_default_timezone_set($timezone);
		
	}
	
	public function update_user_avail()
	{
		
		$user_visible=$_GET['avail'];
		if($user_visible == "offline")
		{
			$res=$this->Model->Update_row(array('visibility_status'=>'offline'),'id',$this->userid,PREFIX.'users');
		}
		else
		{
			$res=$this->Model->Update_row(array('visibility_status'=>'available'),'id',$this->userid,PREFIX.'users');
		}
		exit();
	}

	/*update is_read*/
	public function update_count()
	{
		$this->Model->Update_row(array('is_read'=>1),'to_id',$this->userid,PREFIX.'message_set');
		exit();
	}
}