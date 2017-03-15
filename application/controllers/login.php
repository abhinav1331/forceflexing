<?php 

class Login extends Controller
{
		
	public function index()
	{
		$this->loadview('home/header')->render();			
		$this->loadview('home/login')->render();	
		$this->loadview('home/footer')->render();			
	}
}