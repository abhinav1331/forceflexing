<?php

class Example_model extends Model {
	
	public function getSomething($sql)
	{
		$this->query($sql);
		return $this->resultset();
	}

}

?>
