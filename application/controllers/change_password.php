<?php 
class change_password extends Controller
{
	public $Validator;
	public $Model;	

	public function __construct() 
	{
		$this->Validator = $this->loadHelper('validator');
		$this->Model = $this->loadModel('Login_Model');
	}
		
	public function index()
	{
		/*check if user is already login then show navigation accordingly*/
		
		$this->loadview('main/header')->render();
		
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			if(isset($_COOKIE['force_username'])) 
				$userfin = $_COOKIE['force_username'];
			else 
				$userfin = $_SESSION['force_username'];
			
			$datarecord = $this->Model->Check_loggin_credentials(PREFIX.'users','username',$userfin);
			$role = $datarecord[0]['role'];
			
			if($role == 3) 
			{
				$nav=$this->loadview('contractor/main/navigation');			
				$nav->set('first_name',$datarecord[0]['first_name']);
				$nav->set('last_name',$datarecord[0]['last_name']);
				$data=$this->Model->Get_row('user_id',$datarecord[0]['id'],PREFIX.'contractor_profile');
				if($data['profile_img'])
				{
					$nav->set('profile_img',$data['profile_img']);
				}
				$nav->render();
			}
			elseif($role == 2) 
			{
				$this->loadview('Employer/postjob/navigation')->render();	
			}
		}
		else
		{
			$this->loadview('Employer/signup/navigation')->render();
		}
		
		$template = $this->loadview('home/confirm_pass');
		if(!empty($_POST))
		{
			$this->update_password($_REQUEST,$template);	
		}
		
		$template->render();
		$this->loadview('main/footer')->render();	
	}
	
	public function update_password($data=null,$template)
	{
		$password=md5($data['confirm_password']);
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			if(isset($_COOKIE['force_username'])) 
				$userfin = $_COOKIE['force_username'];
			else 
				$userfin = $_SESSION['force_username'];
			
			$datarecord = $this->Model->Check_loggin_credentials(PREFIX.'users','username',$userfin);
			$id = $datarecord[0]['id'];
			
			/*update record from id*/
			$newdata =  array('password'=>$password);
			$this->Model->Update_row($newdata,'id',$id,PREFIX.'users');
			$template->set('success','Password Updated Successfully');
		}
		else
		{
			$email=$data['email'];
			$key=$data['key'];
			
			/*confirm token value*/
			$userdata=$this->Model->Get_row('email',$email,PREFIX.'users');
		
			//if token matches
			if($userdata['token'] == $key)
			{
				//update data
				$new_token=md5($email.rand(1000,9999));
				$newdata =  array('token'=>$new_token,'password'=>$password);
				$this->Model->Update_row($newdata,'email',$email,PREFIX.'users');
				$template->set('success','Password Updated Successfully');
			}
			else
			{
				$template->set('error','Your key has expired!!');
			}
		}
	}
}
?>