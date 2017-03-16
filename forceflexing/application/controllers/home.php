<?php 

class Home extends Controller
{
	public $Model;
	
	public function __construct()
	{
		$this->Model = $this->loadModel('Example_model');		
	}
	
	
	public function index()
	{
		//$Model = $this->loadModel('Example_model');	
		$sql = "SELECT * FROM flex_users";
		$row = $this->Model->getSomething($sql);
		$this->loadview('main/header')->render();	
		$this->loadview('home/navigation')->render();	// Rendering The Header in View/Home/Header.php
		$template = $this->loadview('home/index');			// Loading The index in View/Home/index.php
		$template->set('data','Chhvai');					//Rendering the data on that page [set(Variable Name,Variable Content)]
		$template->set('row',$row);
		$template->render();								//Rendering index template with Data
		$this->loadview('main/footer')->render();			// Rendering The footer in View/Home/footer.php
	}
	
	
	public function login()
	{
		$this->loadview('main/header')->render();	
		$this->loadview('home/navigation')->render();
		$this->loadview('home/login')->render();	
		$this->loadview('main/footer')->render();			
	}
}