<?php 

class Logout extends Controller
{
	public $Model;
	
	public function __construct()
	{
		$this->Model = $this->loadModel('Example_model');		
	}
	
	
	public function index()
	{
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) {
			unset($_COOKIE['force_username']);	
			unset($_SESSION['force_username']);		
			unset($_COOKIE['force_password']);		
			unset($_SESSION['force_password']);
			$res = setcookie('force_username', '', time() - 3600);
			$res = setcookie('force_password', '', time() - 3600);	
			$this->redirect('');
		} else {
			$this->redirect('');
		}
	}
}