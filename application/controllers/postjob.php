<?php 
class Postjob extends Controller
{
	public $Validator;
	public $Model;	
	public $session;	
	public function __construct()
	{
		$this->Validator = $this->loadHelper('validator');
		$this->session = $this->loadHelper('session_helper');
		$this->Model = $this->loadModel('Registration_Model');
	}
	public function index()
	{
		$this->loadview('main/header')->render();
		$this->loadview('Employer/postjob/navigation')->render();
		$this->loadview('Employer/postjob/index')->render();	
		$this->loadview('main/footer')->render();
	}
}
