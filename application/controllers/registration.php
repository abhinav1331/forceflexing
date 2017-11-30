<?php 

class Registration extends Controller
{
	public $Validator;
	public $Model;	
	public $Linkedin;	
	public $session;	
	public $SendMail;
	public $countries;
	public function __construct()
	{
		$loader =  array(
			'api_key' => '81l4b3asq8ir1j', 
			'api_secret' => 'DGdLucwPsfVF9CBc', 
			'callback_url' => 'http://force.stagingdevsite.com/registration/register/'
		);

		$this->Validator = $this->loadHelper('validator');
		$this->session = $this->loadHelper('session_helper');
		$this->Model = $this->loadModel('Registration_Model');
		$this->LModel = $this->loadModel('Login_Model');
		$this->LinkedIn = $this->loadHelper('linkedin', $loader);
		$this->SendMail=$this->loadHelper('sendmail');
		$this->countries = $this->loadHelper('options');
	}
	
	public function contractor()
	{
		if(isset($_SESSION['registered_user']) ) 
		{
			if($_SESSION['registered_user'] == "true")
			{
				$user_role = $_SESSION['linkedin'];
				unset($_SESSION['name']);
				$this->load_view('Thanks for your registration!!',$user_role,'success');
			}
		} else {
			
			$postdata=array();
			$code=array();
			$key=array();
			// $url = $this->LinkedIn->getLoginUrl(
				// array(
					// LinkedIn::SCOPE_BASIC_PROFILE, 
					// LinkedIn::SCOPE_EMAIL_ADDRESS, 
					// LinkedIn::SCOPE_COMPANY_ADMIN, 
					// LinkedIn::SCOPE_WRITE_SHARE
				// )
			// );
			
			$type='contractor';
			if(isset($_POST['registration']))
			{	
				$postdata=$_POST;
			}
			if(isset($_GET['code']))
			{
				$code=$_REQUEST['code'];
			}
			if(isset($_GET['key']))
			{
				$key=array("key" => $_GET['key'],"key_email" => $_GET['email']);
			}
			$this->register($type,$postdata,$code,$key);
		}
	}
	
	public function validate_cont()
	{
		/* Validation For the Data Posted For Registration of Contractor[Freelancer] */
		
		$_POST = $this->Validator->sanitize($_POST);			//Filtering the Input
		
		/*	Validation Rules FOr data Posted  */
		$this->Validator->validation_rules(array(
				'first_name'    => 'required|alpha_numeric|max_len,100',
				'password'    => 'required|max_len,100|min_len,6',
				'email'       => 'required|valid_email', 				
			));
			
		$validated_data = $this->Validator->run($_POST);
		if($validated_data === false) 
		{
			return $this->Validator->get_readable_errors(TRUE,'','alert alert-danger alert-dismissable');
		}
		else 
		{
			return 'Pass'; // validation successful
		}
	}
	
	public function employer()
	{
		if(isset($_SESSION['registered_user'])) {
			$user_role = $_SESSION['linkedin'];
			
			$this->load_view('Thanks for your registration!!',$user_role,'success');
			unset($_SESSION['name']);
		} else {
			$type='employer';
			$postdata=array();
			$code=array();
			$key=array();
			if(isset($_POST['registration']))
			{	
				$postdata=$_POST;
			}
			if(isset($_GET['code']))
			{
				$code=$_REQUEST['code'];
			}
			if(isset($_GET['key']))
			{
				$key=array("key" => $_GET['key'],"key_email" => $_GET['email']);
			}
		$this->register($type,$postdata,$code,$key);
		}
	}
	/*public function linkedin() 
	{
		if ($_SESSION['linkedin'] == "employer") {
			if(isset($_GET['code'])) {
			$token = $this->LinkedIn->getAccessToken($_REQUEST['code']);
			$token_expires = $this->LinkedIn->getAccessTokenExpiration();
			$info = $this->LinkedIn->get('/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name,positions)');
			echo "<pre>";
			print_r($info);
			echo "</pre>";
			die();
			$_SESSION['myData'] = $info;
			$_SESSION['myData']['positions']['values']['0']['company']['name'];
			 $data =  array(
				 'first_name'=>$_SESSION['myData']['firstName'], 
				 'last_name'=>$_SESSION['myData']['lastName'], 
				 'company_name'=>$_SESSION['myData']['positions']['values']['0']['company']['name'],
				 'country'=>$_SESSION['myData']['location']['country']['code'],
				 'email'=>$_SESSION['myData']['emailAddress'],
				 'password'=>$_SESSION['myData']['id'],
				 'role'=>'1',
				 'connected_with'=>'LINKEDIN',
				 'created_date'=>date("Y-m-d"),
				 'modified_date'=>date("Y-m-d"),
				 'is_verified'=>'1',
				 'username'=>$_SESSION['myData']['id']
			);
				$Results = $this->Model->Insert_users($data,'flex_users');	 			//Calling Model Function to Insert Value in Table
				$this->redirect('registration/success');

		}
		} elseif($_SESSION['linkedin'] == "contractor") {
			if(isset($_GET['code'])) {
			$token = $this->LinkedIn->getAccessToken($_REQUEST['code']);
			$token_expires = $this->LinkedIn->getAccessTokenExpiration();
			$info = $this->LinkedIn->get('/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name,positions)');
			$_SESSION['myData'] = $info;
			$_SESSION['myData']['positions']['values']['0']['company']['name'];
			 $data =  array(
				 'first_name'=>$_SESSION['myData']['firstName'], 
				 'last_name'=>$_SESSION['myData']['lastName'], 
				 'company_name'=>$_SESSION['myData']['positions']['values']['0']['company']['name'],
				 'country'=>$_SESSION['myData']['location']['country']['code'],
				 'email'=>$_SESSION['myData']['emailAddress'],
				 'password'=>$_SESSION['myData']['id'],
				 'role'=>'0',
				 'connected_with'=>'LINKEDIN',
				 'created_date'=>date("Y-m-d"),
				 'modified_date'=>date("Y-m-d"),
				 'is_verified'=>'1',
				 'username'=>$_SESSION['myData']['id']
			);
				$Results = $this->Model->Insert_users($data,'flex_users');	 			//Calling Model Function to Insert Value in Table
				$this->redirect('registration/success');

		}
		}
	}*/
	public function success()
	{
			$email=$this->session->get('current_user');
			//get the user role and name
			$role_id=$this->Model->Get_column('role','email',$email,PREFIX.'users');
			$role=$this->Model->Get_column('role_name','roleid',$role_id['role'],PREFIX.'roles');
			$role_name=strtolower($role['role_name']);
			if($role_name =="contractor")
			{
				$this->redirect('contractor/find_job');
			}
			if($role_name == "employer")
			{
				$this->redirect('postjob/');
			}
	}
	
	public function register($type,$postdata='',$code='',$key='') {
		//fetch the role id
		if(isset($_REQUEST['code']) && !empty($_REQUEST['code'])) {
			if($_SESSION['linkedin'] == "Login") {
				$token = $this->LinkedIn->getAccessToken($_REQUEST['code']);
				$token_expires = $this->LinkedIn->getAccessTokenExpiration();
				$info = $this->LinkedIn->get('/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name,positions)');
				$_SESSION['myData'] = $info;
				$_SESSION['myData']['positions']['values']['0']['company']['name'];
				$datarecord22 = $this->LModel->Check_loggin_credentials(PREFIX.'users','email',$_SESSION['myData']['emailAddress']);
				if(count($datarecord22) == 0) {
					$message = "User Not Registered";
					$this->loadview('main/header')->render();
					$this->loadview('Employer/signup/navigation')->render();			
					$template = $this->loadview('home/login-new');
					$template->set('error',$message);
					$template->set('instance',$this);
					$template->render();
					$this->loadview('main/footer')->render();
				} else {
						setcookie('force_username', $datarecord22[0]['username'], time()+60*60*24*90);
						setcookie('force_password', $datarecord22[0]['password'], time()+60*60*24*90);
						$_SESSION['force_username'] = $datarecord22[0]['username'];
						$_SESSION['force_password'] = $datarecord22[0]['password'];
						$role = $datarecord22[0]['role'];
							if($role == 3) {
								$this->redirect('contractor/find_job');
							} elseif($role == 2) {
								$this->redirect('employer/job_report');
							}
				}
			} else {
				if($_SESSION['linkedin'] == "contractor") { $role= 3; } else { $role = 2; }
				$token = $this->LinkedIn->getAccessToken($_REQUEST['code']);
				$token_expires = $this->LinkedIn->getAccessTokenExpiration();
				$info = $this->LinkedIn->get('/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name,positions)');
				$_SESSION['myData'] = $info;
				$_SESSION['myData']['positions']['values']['0']['company']['name'];
				 $data =  array(
					 'first_name'=>$_SESSION['myData']['firstName'], 
					 'last_name'=>$_SESSION['myData']['lastName'], 
					 'company_name'=>$_SESSION['myData']['positions']['values']['0']['company']['name'],
					 'country'=>$_SESSION['myData']['location']['country']['code'],
					 'email'=>$_SESSION['myData']['emailAddress'],
					 'password'=>$_SESSION['myData']['id'],
					 'role'=>$role,
					 'connected_with'=>'LINKEDIN',
					 'created_date'=>date("Y-m-d"),
					 'modified_date'=>date("Y-m-d"),
					 'is_verified'=>'1',
					 'username'=>$_SESSION['myData']['id']
				);
					$user_role = $_SESSION['linkedin'];
					$_SESSION['registered_user'] = "True";
					$datarecord = $this->LModel->Check_loggin_credentials(PREFIX.'users','email',$_SESSION['myData']['emailAddress']);
					if(count($datarecord) == 0) {
						$Results = $this->Model->Insert_users($data,'flex_users');	 			//Calling Model Function to Insert Value in Table
						$this->redirect('registration/'.$user_role);
					//$this->load_view('Thanks for your registration!!',$user_role,'success');
					} else {
						$datarecord11 = $this->LModel->Check_loggin_credentials(PREFIX.'users','email',$_SESSION['myData']['emailAddress']);
						$role = $datarecord11[0]['role'];
						setcookie('force_username', $datarecord11[0]['username'], time()+60*60*24*90);
						setcookie('force_password', $datarecord11[0]['password'], time()+60*60*24*90);
						$_SESSION['force_username'] = $datarecord11[0]['username'];
						$_SESSION['force_password'] = $datarecord11[0]['password'];

						if($role == 3) {
							$this->redirect('contractor/find_job');
						} elseif($role == 2) {
							$this->redirect('employer/job_report');
						}
					}
			}
				

		}
		if(isset($postdata) && !empty($postdata))
		{
			$user_role=$postdata['user_role'];
			$roleid=$this->fetch_role_id($user_role);
			$email_exists="";
			$uname_exists="";
			$Validate_Check = $this->validate_cont($postdata);	// Posting data For Validation Check
			//fetch all rows
			$user_count = $this->Model->Get_count_records(PREFIX.'users','username',$postdata['user_name']);			
			$email_count = $this->Model->Get_count_records(PREFIX.'users','email' , $postdata['email']);
			if($email_count != 0)
			{
				$email_exists="yes";
				$error="Email Already Exists!!";
			}
			if($user_count != 0)
			{
				$uname_exists="yes";
				$error="Username Already Exists!!";
			}
			if($Validate_Check == 'Pass')
			{
				//check email or username already exists
				if($email_exists=="" && $uname_exists=="")
				{
					$date= date('Y-m-d h:i:s');
					$password=md5($postdata['password']);
					$data =  array(
					 'first_name'=>$postdata['first_name'], 
					 'last_name'=>$postdata['last_name'], 
					 'company_name'=>$postdata['company_name'],
					 'country'=>$postdata['country'],
					 'email'=>$postdata['email'],
					 'password'=>$password,
					 'role'=>$roleid,
					 'connected_with'=>'EMAIL',
					 'created_date'=>$date,
					 'modified_date'=>$date,
					 'is_verified'=>'1',
					 'username'=>$postdata['user_name'],
					 'token'=>md5($postdata['email'].rand(1000,9999))
					);
					$Results = $this->Model->Insert_users($data,PREFIX.'users');	 			//Calling Model Function to Insert Value in Table
					//if data successfully inserted
					if(isset($Results) && $Results != '')
					{
						//get user details for last inserted user
						$row = $this->Model->Get_row('id',$Results,PREFIX.'users');
						
						//load registration email template
						$file=APP_DIR.'email_templates/registration_success.html';
						$emailBody = file_get_contents($file);
						$search  = array('[[fname]]', '[[lname]]','[[username]]','[[country]]','[[email]]','[[pagelink]]');
						$replace = array($row['first_name'],$row['last_name'],$row['username'],$row['country'],$row['email'], SITEURL.'registration/'.$user_role.'/?email='.$row['email'].'&key='.$row['token']);
						$emailBody  = str_replace($search, $replace, $emailBody);
						
						//send mail
						$this->SendMail->setparameters($row['email'],'Registration',$emailBody);
						$this->load_view('Thanks for your registration!! Kindly Confirm your Email by clicking on the link we sent you.',$user_role,'success');
					}				
				}
				else
				{
					//username or email already exist
					$this->load_view($error,$user_role);
				}
			}
			else
			{
				//validation failure
				$this->load_view($Validate_Check,$user_role);
			}
		}
		elseif(isset($key) && !empty($key)) 
		{
			//confirm email and send the success message
			$keys=$key['key'];
			$key_email=$key['key_email'];
			
			$userdata=$this->Model->Get_row('email',$key_email,PREFIX.'users');
			//if token matches
			if($userdata['token'] == $keys)
			{
				/*set cookie and session*/
				setcookie('force_username', $userdata['username'], time()+60*60*24*90);
				setcookie('force_password', $userdata['password'], time()+60*60*24*90);
				$_SESSION['force_username'] = $userdata['username'];
				$_SESSION['force_password'] = $userdata['password'];
				
				//update data and redirect
				$new_token=md5($key_email.rand(1000,9999));
				//update token with the new token
				$data =  array('is_verified' => 1,'token'=>$new_token);
				$this->Model->Update_row($data,'email',$key_email,PREFIX.'users');
				$this->session->set('current_user',$key_email);
				$this->redirect('registration/success');
			}
			else
			{
				$role=$userdata['role'];
				$user_role=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
				$error="Sorry!! Your key has expired!!";
				$this->load_view($error,$user_role['role_name']);
			}
		}
		else
		{
			$url = $this->LinkedIn->getLoginUrl(
				array(
					LinkedIn::SCOPE_BASIC_PROFILE, 
					LinkedIn::SCOPE_EMAIL_ADDRESS, 
					LinkedIn::SCOPE_COMPANY_ADMIN, 
					LinkedIn::SCOPE_WRITE_SHARE
				)
			);
			if($type=="employer")
			{
				$this->loadview('main/header')->render();
				$this->loadview('Employer/signup/navigation')->render();			
				$template = $this->loadview('Employer/signup/index');
				$template->set("url" , $url);
				$template->set("instance",$this);
				$template->render();	
				$this->loadview('main/footer')->render(); 
			}
			else
			{
				$this->loadview('main/header')->render();	
				$this->loadview('contractor/signup/navigation')->render();			
				$template = $this->loadview('contractor/signup/index');	
				$template->set("url",$url);
				$template->set("instance",$this);
				/*get the categories*/
				$categories=$this->Model->Get_all_data(PREFIX.'categories');
				$template->set("categories",$categories);
				$template->render();
				$this->loadview('main/footer')->render();
			}
			
		}
	}
	
	
	public function load_view($message,$viewof,$success="")
	{
		$this->loadview('main/header')->render();
		if($viewof =="contractor")
		{
			$this->loadview('contractor/signup/navigation')->render();
			$template = $this->loadview('contractor/signup/index');	
			if($success != "")
			{
				$template->set('success',$message);
			}
			else
			{
				$categories=$this->Model->Get_all_data(PREFIX.'categories');
				$template->set("categories",$categories);
				$template->set('errors',$message);
			}
			$template->set('instance',$this);
			$template->render();
		}
		if($viewof =="employer")
		{
			$this->loadview('Employer/signup/navigation')->render();
			$template = $this->loadview('Employer/signup/index');	
			if($success != "")
				$template->set('success',$message);
			else
				$template->set('errors',$message);
			$template->set('instance',$this);
			$template->render();
		}
		$this->loadview('main/footer')->render();
	}
	
	public function fetch_role_id($role)
	{
		$role_name=strtolower($role);
		$row = $this->Model->Get_row('role_name',$role_name,PREFIX.'roles');
		return $row['roleid'];
	}

}