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
	
	public function slugify($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) 
	  {
		return 'n-a';
	  }
		return $text;
	}

}