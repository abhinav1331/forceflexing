<?php

class Pages_Model extends Model
{
	public $table = 'flex_pages';
	
	public function Getpage($whereval)
	{
		return $this->get_single_row('slug',$whereval,$this->table);
	}
}
	