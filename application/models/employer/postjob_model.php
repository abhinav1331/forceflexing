<?php

class Postjob_model extends Model
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
	public function Delete_all_Records($table , $column , $value)
	{
		return $this->delete_all_record($table , $column , $value);
	}
	
	public function Get_column($column,$wherekey, $whereval,$table)
	{
			return $this->get_single_row_columns($column,$wherekey, $whereval,$table);
	}
	public function Get_column1($column,$wherekey, $whereval,$table)
	{
			return $this->get_single_row_columns1($column,$wherekey, $whereval,$table);
	}
	public function Get_column_Double($column,$wherekey1, $whereval1,$wherekey, $whereval,$table)
	{
			return $this->get_Double_row_columns($column,$wherekey1, $whereval1,$wherekey, $whereval,$table);
	}
	
	public function Update_row($data,$wherekey,$whereval,$table)
	{
		return $this->update($data,$wherekey,$whereval,$table);
	}
	public function get_Data_table($table,$field,$value)
	{
		return $this->get_table_data($table,$field,$value);
	}
		
	public function Get_all_with_cond($wherekey,$wherevalue,$table)
	{
		return $this->get_table_data($table,$wherekey,$wherevalue);
	}
		
		
	public function Get_record_jobe_count($table,$wherekey,$wherevalue)
	{
		return $this->get_record_count1($table,$wherekey,$wherevalue);
	}

	public function check_conversation_id($table,$wherevalue,$wherevalue2)
	{
		return $this->check_CConversation_id($table,$wherevalue,$wherevalue2);
	}
	public function check_contractor_saved_check($table,$wherevalue,$wherevalue2)
	{
		return $this->check_ccontractor_saved_check($table,$wherevalue,$wherevalue2);
	}
	public function update_record($data,$wherekey,$whereval,$table)
	{
		return $this->update($data,$wherekey,$whereval,$table);
	}
}

