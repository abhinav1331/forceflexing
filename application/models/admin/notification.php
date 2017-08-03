<?php 
class Notification extends Model
{
	public $table = 'flex_notification_message';
	
	/* Insert Notification  */
	public function InsNotification($data)
	{
		/* Insert Message in flex_notification_message */
		return $this->Insert($data,$this->table);
	}
	
	/* Edit Notification */
	public function UpdateNotification($data,$wherekey,$whereval)
	{
		try 
		{
			$this->update($data,$wherekey,$whereval,$this->table);
			return "success";
		}
		catch(Exception $e) {
			
			return "error";
		}
		
	}
	
	/* Delete Notification */
	public function DelNotification($wherekey,$whereval)
	{
		try 
		{
			$data = array('visible'=>'1');
			$this->update($data,$wherekey,$whereval,$this->table);
			return "success";
		}
		catch(Exception $e) {
			
			return "error";
		}
	}
	
	/* List All Notification */
	public function ShowNotification()
	{
		$Act_query = $this->query("SELECT * FROM ".$this->table." WHERE visible = '0'");
		return $this->resultset($Act_query);		
	}
}