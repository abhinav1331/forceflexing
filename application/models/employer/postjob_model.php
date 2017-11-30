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
	public function get_Data_table_groupMy($table,$field,$value)
	{
		return $this->get_DataTable_groupMy($table,$field,$value);
	}
	public function get_job_details($table,$field,$value)
	{
		return $this->get_Job_Detail($table,$field,$value);
	}
		
	public function Get_all_with_cond($wherekey,$wherevalue,$table)
	{
		return $this->get_table_data($table,$wherekey,$wherevalue);
	}
	
	public function Get_all_with_multiple_cond($where_cond,$table,$order="asc")
	{
		return $this->get_all_mul_cond($where_cond,$table,$order);
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
	
	public function get_count_with_multiple_cond($where_cond,$table)
	{
		$where ='';	
		$total = count($where_cond);
		$x = 1;
		foreach($where_cond as $key => $val)
		{
			if($x==$total)
				{	
					$where .=$key."='". $val."'"; 	
				}
			else
				{	
					$where .=$key."='". $val. "' and "; 
				}
			$x++;
		}
		$query='select * from '.$table.' where '.$where.'';
		$results= $this->data_filter($query);
		return count($results);
	}
	
	/*custom query function*/
	public function filter_data($qry)
	{
		return $this->data_filter($qry);
	}
}

