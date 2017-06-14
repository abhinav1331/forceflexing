<?php
class Industries extends Model
{
	public $table = 'flex_industries';
	
	public function Insert_industries($data)
	{
		return $this->Insert($data,$this->table);
	}
	
	public function edit_industries($data,$wherekey,$whereval)
	{
		return $this->update($data,$wherekey,$whereval,$this->table);
	}
	
	public function delete_industries()
	{
		
	}
	
	public function get_all_industries()
	{
		return $this->get_all($this->table);
	}

	public function get_json_industries()
	{
		$industries = $this->get_all($this->table);
		$json_array = array();
			foreach($industries as $keys)
			{
				$json_array[] = $keys['industries_name'];
			}
			
		return  json_encode($json_array);	
	}
	
}