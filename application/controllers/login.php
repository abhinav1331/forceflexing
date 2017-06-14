<?php 
class Login extends Controller
{
	public $Validator;
	public $Model;	
	public $Linkedin;	
	public $SendMail;
	public function __construct() {
		$loader =  array(
			'api_key' => '81l4b3asq8ir1j', 
			'api_secret' => 'DGdLucwPsfVF9CBc', 
			'callback_url' => 'http://force.imarkclients.com/registration/register/'
		);
		$this->Validator = $this->loadHelper('validator');
		$this->Model = $this->loadModel('Login_Model');
		$this->LinkedIn = $this->loadHelper('linkedin', $loader);
		$this->SendMail=$this->loadHelper('sendmail');
	}
		
	public function index()
	{
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) {
			if(isset($_COOKIE['force_username'])) {
				$userfin = $_COOKIE['force_username'];
			} else {
				$userfin = $_SESSION['force_username'];
			}
			$datarecord = $this->Model->Check_loggin_credentials(PREFIX.'users','username',$userfin);
			$role = $datarecord[0]['role'];
			if($role == 3) {
				$this->redirect('contractor/find_job');
			} elseif($role == 2) {
				$this->redirect('employer/job_report');
			}

		} else {
			if(isset($_POST['submit'])) {
				$postdata = $_POST;
				echo $testng = $this->login($postdata);

			} else {
				$url = $this->LinkedIn->getLoginUrl(
					array(
						LinkedIn::SCOPE_BASIC_PROFILE, 
						LinkedIn::SCOPE_EMAIL_ADDRESS, 
						LinkedIn::SCOPE_COMPANY_ADMIN, 
						LinkedIn::SCOPE_WRITE_SHARE
					)
				);
				$this->loadview('main/header')->render();
				$this->loadview('Employer/signup/navigation')->render();			
				// $this->loadview('home/login-new')->render();
				$template = $this->loadview('home/login-new');
				$template->set("url" , $url);
				$template->render();		
				$this->loadview('main/footer')->render();				
			}
		}
		
	}

	public function login($postdata='') {
		if(isset($postdata) && !empty($postdata))
		{
			$Validate_Check = $this->validate_cont($postdata);
			if($Validate_Check == 'Pass') {
				$username = $postdata['username'];
				$password = $postdata['password'];
				$datarecord = $this->Model->Check_loggin_credentials(PREFIX.'users','username',$username);
				if(count($datarecord) != 0) {
					/*echo "<pre>";
					print_r($postdata);
					echo "</pre>";
					echo "<pre>";
					print_r($datarecord);
					echo "</pre>";*/
					$password=md5($password);
					$passdata = $datarecord[0]['password'];
					if($passdata == $password) {
						if($datarecord[0]['is_verified'] == 1) {
							if(isset($postdata['remember'])) {
								setcookie('force_username', $username, time()+60*60*24*90);
								setcookie('force_password', $password, time()+60*60*24*90);
								$_SESSION['force_username'] = $username;
								$_SESSION['force_password'] = $password;
								$role = $datarecord[0]['role'];
									$this->update_last_login_time();
							 if($role == 3) {
								$this->redirect('contractor/find_job');
							} elseif($role == 2) {
								$this->redirect('employer/job_report');
							}
							} else {
								$_SESSION['force_username'] = $username;
								$_SESSION['force_password'] = $password;
								$role = $datarecord[0]['role'];
								$this->update_last_login_time();
								if($role == 3) {
									$this->redirect('contractor/find_job');
								} elseif($role == 2) {
									$this->redirect('employer/job_report');
								}
							}

						} else {
							$message = "User Not Verified!! Please verify your Email.";
							$this->loadview('main/header')->render();
							$this->loadview('Employer/signup/navigation')->render();			
							$template = $this->loadview('home/login-new');
							$template->set('error',$message);
							$template->render();
							$this->loadview('main/footer')->render();	
						}
					} else {
						$message = "Incorrect Password!!";
						$this->loadview('main/header')->render();
						$this->loadview('Employer/signup/navigation')->render();			
						$template = $this->loadview('home/login-new');
						$template->set('error',$message);
						$template->render();
						$this->loadview('main/footer')->render();	
					}

				} else {
					$message = "Invalid Username";
					$this->loadview('main/header')->render();
					$this->loadview('Employer/signup/navigation')->render();			
					$template = $this->loadview('home/login-new');
					$template->set('error',$message);
					$template->render();
					$this->loadview('main/footer')->render();	
				}
			} else {
				$message = "Missing Parameters!!";
				$this->loadview('main/header')->render();
				$this->loadview('Employer/signup/navigation')->render();			
				$template = $this->loadview('home/login-new');
				$template->set('error',$message);
				$template->render();
				$this->loadview('main/footer')->render();	
			}
			
		}
	}

	public function validate_cont()
	{
		/* Validation For the Data Posted For Registration of Contractor[Freelancer] */
		
		$_POST = $this->Validator->sanitize($_POST);			//Filtering the Input
		
		/*	Validation Rules FOr data Posted  */
		$this->Validator->validation_rules(array(
				'username'    => 'required',
				'password'    => 'required|max_len,100|min_len,6',				
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
	
	/*ajax function to verify user email*/
	public function verify_email()
	{
		$email=$_POST['email'];
		/*get user from email*/
		$userdata = $this->Model->Check_loggin_credentials(PREFIX.'users','email',$email);
		if(!empty($userdata))
		{
			/*update token and send email*/
			$token=md5($email.rand(1000,9999));
			$data =  array('token'=>$token);
			$this->Model->Update_row($data,'email',$email,PREFIX.'users');
			
			//send mail(load confirm password email template)
			$file=APP_DIR.'email_templates/confirm_password.html';
			$emailBody = file_get_contents($file);
			$search  = array('[[fname]]', '[[lname]]','[[pagelink]]');
			$replace = array($userdata[0]['first_name'],$userdata[0]['last_name'],SITEURL.'change_password/index/?email='.$email.'&key='.$token);
			$emailBody  = str_replace($search, $replace, $emailBody);
			
			//send mail
			$this->SendMail->setparameters($email,'Confirm Password',$emailBody);
			
			echo '1';
		}
		else
		{
			echo '0';
		}
		exit();
	}
	
	public function update_last_login_time()
	{
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			if(isset($_COOKIE['force_username'])) 
				$loginuser = $_COOKIE['force_username'];
			 else 
				$loginuser = $_SESSION['force_username'];
			
			/*save current user login time as per user timezone*/
			$ip = $_SERVER['REMOTE_ADDR'];
			$details = json_decode(file_get_contents("http://ip-api.com/json/".$ip.""));
			date_default_timezone_set($details->timezone);
			
			$data=array('last_login_time'=> date("Y-m-d H:i:s"));
			$this->Model->Update_row($data,'username',$loginuser,PREFIX.'users');
		}
	}
}


