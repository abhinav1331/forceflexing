<?php 
class Menu extends Options
{
	public $table = PREFIX .'options'; 
	public function Insert_Menu($data)
	{
		return $this->Insert_options($data);
	}
	
	public function Get_Menu($MenuID)
	{
		$where_cond = array('id'=>$MenuID,'option_name'=>'menu');
		$menuName = $this->get_all_mul_cond($where_cond,'flex_options');		
		if(!empty($menuName))
		{
			$menuList = $this->get_single_row('option_name', 'menuList_'.$MenuID,'flex_options');		
			return array('menuName'=>$menuName[0]['option_value'],'menuList'=>json_decode($menuList['option_value']),'menuID'=>$menuName[0]['id']);			
		}
		else
		{
			return NULL;
		}
	}
	
	public function getMenuList()
	{
		$where_cond = array('option_name'=>'menu');
		return $this->get_all_mul_cond($where_cond,$this->table);		
	}
	
	public function UpdateMenuName($MenuName,$MenuID)
	{
		$this->update(array('option_value'=>$MenuName),'id',$MenuID,$this->table);		
	}
	
	public function UpdateMenuList($MenuName,$MenuID,$MenuList)
	{
		$this->update(array('option_value'=>$MenuName),'id',$MenuID,$this->table);
		$this->update(array('option_value'=>$MenuList),'option_name','menuList_'.$MenuID,$this->table);
	}
	
	
}