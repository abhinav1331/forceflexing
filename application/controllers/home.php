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
			/* get login user details*/
			$this->udata=$this->Model->getSomething('select * from flex_users where username="'.$username.'"');
			$role=$this->udata[0]['role'];
		}
		else
		{
			$role="";
		}

		$nav=$this->loadview('home/navigation');
		$nav->set('user_role',$role);
		$nav->render();

		$template = $this->loadview('home/index');			// Loading The index in View/Home/index.php
		$template->set('data','Chhvai');					//Rendering the data on that page [set(Variable Name,Variable Content)]
		$template->set('row',$row);
		$template->render();								//Rendering index template with Data
		$this->loadview('main/footer')->render();			// Rendering The footer in View/Home/footer.php
	}
	
	
	/*public function login()
	{
		$this->loadview('main/header')->render();	
		$this->loadview('home/navigation')->render();
		$this->loadview('home/login')->render();	
		$this->loadview('main/footer')->render();			
	}*/
	
	public function logout()
	{
		echo "aaaaaaaaaaaaaaaaaaaaaaaaaa";
		/*if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) {
			unset($_COOKIE['force_username']);		
			unset($_SESSION['force_username']);		
			unset($_COOKIE['force_password']);		
			unset($_SESSION['force_password']);
			$this->redirect('');
		} else {
			$this->redirect('');
		}*/
	}
}