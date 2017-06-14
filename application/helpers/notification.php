<?php
class Notification extends Model
{
	public $table = 'flex_notification_message';		// Insert Message According to Condition
	public $table_2 = 'flex_notification';
	
	/* Notification Message Section */
	
	public function Insert_message($data)
	{
		/* Insert Message in flex_notification_message */
		return $this->Insert($data,$this->table);
	}
	
	public function Edit_message($data,$wherekey,$whereval)
	{
		return $this->update($data,$wherekey,$whereval,$this->table);
	}
	
	public function get_all_message()
	{
		return $this->get_all($this->table);
	}
	
	public function Delete_message()
	{
		return $this->get_all($this->table);
	}
	
	/* Notification Section */
	
	public function Insert_notification($data)
	{
		/* Insert Notification in flex_notification_message */
		return $this->Insert($data,$this->table_2);
	}
	
}