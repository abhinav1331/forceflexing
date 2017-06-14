<?php 
class Users extends Model
{
	
	/* Get All Users */
	public function Get_all_data($table)
	{
		return $this->get_all($table);
	}
	/* Get Single Row Data */
	public function Get_row($wherekey,$whereval,$table)
	{
		return $this->get_single_row($wherekey,$whereval,$table);
	}
	
	public function Check_username($username)
	{
		$result = $this->get_single_row('username', $username,'flex_users');
		if(empty($result))
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	public function Create_username($email)
	{
		$email_chk = $this->Check_email($email);
		if($email_chk == true)
		{
			$username ='';
			$user = explode('@',$email);
			$username .= $user[0];
			/* Generate Random Number and get 1st four Digits		*/
			$username .= $this->generate_number(4);		
			$result = $this->Check_username($username);
			
			if($result == true)
			{ 
				echo json_encode(array('status'=>'SUCCESS','msg'=>$username));
			}	
		}
		else
		{	
			echo json_encode(array('status'=>'ERROR','msg'=>'Email Already Exists !'));
		}
	}
	
	public function Check_email($email)
	{
		$result = $this->get_single_row('email', $email,'flex_users');
		if(empty($result))
		{	return true;	}			
		else
		{	return false;	}		// Email Exist
	}
	
	public function generate_number($no)
	{
		$number = mt_rand(10,500);
		$digit = substr($number, 0, $no);
		return $digit;
	}
	
	public function CheckEmail_ID($email,$ID)
	{
		/* Check Email with ID  */
		$sql = "SELECT * FROM `flex_users` WHERE email = '$email' AND id !='$ID'";
		$result = $this->query($sql);		
	}
		
	public function account_reset()
	{
		
	}
	
	public function Update_row($data,$wherekey,$whereval,$table)
	{
		return $this->update($data,$wherekey,$whereval,$table);
	}
}
	
