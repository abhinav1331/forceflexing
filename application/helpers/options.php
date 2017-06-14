<?php
class Options extends Model
{
	public $table = 'flex_options';
	
	public function Insert_options($data)
	{
		return $this->Insert($data,$this->table);
	}
	
	public function edit_options($data,$wherekey,$whereval)
	{
		return $this->update($data,$wherekey,$whereval,$this->table);
	}
	
	public function delete_industries()
	{
		
	}
	
	public function get_all_options()
	{
		return $this->get_all($this->table);
	}
	
	public function get_countries()
	{
		return $this->get_all('flex_countries');		
	}
	
	public function show_countries($selected = null)
	{
		$result = $this->get_single_row('option_name', 'show_country','flex_options');
		$show_cty = json_decode($result['option_value']);
		$list = $this->get_countries();
		$options = '';
		$options .='<option value="">Select Country</option>';
		foreach($list as $keys):
			if(in_array($keys['id'],$show_cty)):
				if($selected == $keys['sortname']):
					$options .='<option selected value="'.$keys['sortname'].'">'.$keys['name'].'</option>';				
				else:
					$options .='<option value="'.$keys['sortname'].'">'.$keys['name'].'</option>';				
				endif;
			endif;
		endforeach;
		return $options;
	}

	public function insert_notification()
	{
		
	}
	

}