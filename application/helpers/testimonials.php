<?php 
class Testimonials extends Model
{
	public $table = PREFIX."testimonials";
	
	/* Display Testimonials */
	public function index($format = "Array")
	{
		$return = $this->get_table_data($this->table,'option_name','testimonials');		
		if(!empty($return))
		{
			if($format == "Array")
			{
				return $return[0]["option_value"];	
			}
			elseif($format == "Json")
			{
				return $return;
			}
		}
		else
		{
			return array();
		}
	}
	
	/* Insert testimonials */
	public function Insert_Testimonials($data)
	{
		$date = new DateTime();
		
		$result = array(
		'clientName'=>$data["clientName"],
		'content'=>$data["content"],
		'rating'=>$data["rating"],
		'createdOn'=>$date->getTimestamp(),		
		'modifiedOn'=>$date->getTimestamp()		
		);
		
		$ID = $this->Insert($result,$this->table);
		return $ID;
		
		/* if(empty($value))
		{
			$val[] = $data;
			$records = array(
			"option_name"=>"testimonials",
			"option_value"=>json_encode($val),
			);
			
			print_R($records);
			//$this->Insert($records,$this->table);
			//return $this->lastInsertId();
		}
		else
		{
			array_push($value,$data);
			$this->update(json_encode($value),"option_name","testimonials",$this->table);
			return $this->rowCount();
		} */
		
	}
	
	/* Update Testimonials */
	public function Edit_Testimonials($data,$whereval)
	{
		$this->update($data,'id',$whereval,$this->table);
		return $this->rowCount();
	}
	
	/* Delete Testimonials */
	public function Delete_Testimonials()
	{
		
	}
	
	/* Show Data into table  */
	public function showTable($data)
	{
		
	}
	
	public function getTestimonial($ID)
	{
		$Data = $this->get_single_row('id',$ID,$this->table);
		if(!empty($Data))
			{	
				return $Data;
			}
		else
			{
				return NULL;
			}
		
	}
	
}