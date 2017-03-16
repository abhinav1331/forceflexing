<?php

class Registration_Model extends Model
{
	
	public function Insert_users($data,$table)
	{
		return $this->Insert($data,$table);
	}
	
	public function Get_row($wherekey,$whereval,$table)
	{
		return $this->get_single_row($wherekey,$whereval,$table);
	}
	
	public function Get_all_data($table)
	{
		return $this->get_all($table);
	}
	
	public function Get_count_records($table,$field,$value)
	{
		return $this->get_record_count($table,$field,$value);
	}
	
	public function Get_column($column,$wherekey, $whereval,$table)
	{
			return $this->get_single_row_columns($column,$wherekey, $whereval,$table);
	}
	
	public function Update_row($data,$wherekey,$whereval,$table)
	{
		return $this->update($data,$wherekey,$whereval,$table);
	}
}

