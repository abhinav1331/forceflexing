<?php

class Backend {
	
	public function loadModel($name,$folder='')
	{
		if(isset($folder) && $folder!='')
		{
			require(APP_DIR .'models/'.$folder.'/'. strtolower($name) .'.php');
			$model = new $name;
			return $model;
		}
		else
		{
			require(APP_DIR .'models/'. strtolower($name) .'.php');
			$model = new $name;
			return $model;
		}
	}
	
	public function loadView($name)
	{
		$view = new View($name);
		return $view;
	}
	
	public function loadPlugin($name)
	{
		require(APP_DIR .'plugins/'. strtolower($name) .'.php');
	}
	
	public function loadHelper($name,$array = null) {
		
		require(APP_DIR .'helpers/'. strtolower($name) .'.php');
		if($array == null)
		{$helper = new $name;}
		else
		{$helper = new $name($array);}

		return $helper;
	}
	
	public function redirect($loc)
	{
		global $config;
		header('Location: '. $config['base_url'] . $loc.'/');		
	}
	
	public function pre($Array)
	{
		echo"<pre>";
		print_R($Array);
		echo"</pre>";
	}
    
}

?>