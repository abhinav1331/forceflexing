<?php

class Login_Model extends Model {

	public function Check_loggin_credentials($table,$field,$value)
	{
		return $this->get_table_data($table,$field,$value);
	}
	public function Update_row($data,$wherekey,$whereval,$table)
	{
		return $this->update($data,$wherekey,$whereval,$table);
	}
	public function Get_row($wherekey,$whereval,$table)
	{
		return $this->get_single_row($wherekey,$whereval,$table);
	}

}