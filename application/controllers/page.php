<?php 

class Page extends Controller
{
	public $Pages;
	public $testimonials;
	public function __construct()
	{
		ob_start();
		/* Loading Modals */
		$this->Pages = $this->loadModel('pages_model');
		
		/* Loading Helpers */		
		//$this->Menu = $this->loadHelper('menu');
		$this->testimonials = $this->loadHelper('testimonials');
		
	}
	
	
	public function index()
	{
		//$data = $this->testimonials->Index();
		//$data = $this->testimonials->Insert_Testimonials(array("1","Peter","5","content"));
		//print_R($data);
		//$this->testimonials->Insert_Testimonials();
		//die();
		
		
		$slug = str_replace("/","",$_SERVER["REQUEST_URI"]);		
		$data = $this->Pages->Getpage($slug);	
		if(!empty($data))
		{
			$this->loadview('main/header')->render();	
			$this->loadview('home/navigation')->render();
			
			$template = $this->loadview('home/pages');			
			$template->set('data',$data);	
			$template->render();	
			
			$this->loadview('main/footer')->render();	
		}	
		else
		{
			$this->error404();
		}	
		
	}
	
	function error404()
	{
		echo '<h1>404 Error</h1>';
		echo '<p>Looks like this page doesn\'t exist</p>';
	}
}