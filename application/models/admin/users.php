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
	
	public function getUserRole($userID)
	{
		$getUserRole = $this->query("SELECT role FROM ".PREFIX."users WHERE id = ".$userID." ");			
		return $UserRole = $this->resultset($getUserRole);
	}
	
	public function getProfileData($userID)
	{
		$userRole = $this->getUserRole($userID);
		
		
		if( $userRole[0]['role'] == 2 ):
		// Employer
		$getEmployerprofile = $this->query("SELECT u.username,u.id,e.company_image FROM ".PREFIX."users as u INNER JOIN ".PREFIX."company_info as e ON u.id = e.company_id WHERE u.id= '".$userID."' ");	
			$data = $this->resultset($getEmployerprofile);
			if( empty( $data[0]['profile_img'] )  )
			{
				$EmployerProfileImg = SITEURL.'/static/images/avatar-icon.png';					
			}
			else
			{
				$EmployerProfileImg = SITEURL.'static/images/admin/'.$data[0]['company_image'];	
			}
			
		return array( 'username'=>$data[0]['username'], 'image'=>$EmployerProfileImg );
			
		elseif( $userRole[0]['role'] == 3 ):
		// Contractor
			$getContractorprofile = $this->query("SELECT u.username,u.id,c.profile_img FROM ".PREFIX."users as u INNER JOIN ".PREFIX."contractor_profile as c ON u.id = c.user_id WHERE u.id= ".$userID."");			
				$data = $this->resultset($getContractorprofile);
				if( empty( $data[0]['profile_img'] )  )
				{
					$ContractorProfileImg = SITEURL.'/static/images/avatar-icon.png';					
				}
				else
				{
					$ContractorProfileImg = SITEURL.'static/images/admin/'.$data[0]['profile_img'];	
				}
							
			return array( 'username'=>$data[0]['username'], 'image'=> $ContractorProfileImg);
		
		elseif( $userRole[0]['role'] == 1 || $userRole[0]['role'] == 5 ):
			// Admin  admin_Prof_img
			$getAdminprofile = $this->query("SELECT u.username,u.id,u.admin_Prof_img FROM ".PREFIX."users as u WHERE u.id= ".$userID."");			
				$data = $this->resultset($getAdminprofile);
				
				if( empty( $data[0]['admin_Prof_img'] )  )
				{
					$AdminProfileImg = SITEURL.'/static/images/avatar-icon.png';					
				}
				else
				{
					$AdminProfileImg = SITEURL.'static/images/admin/'.$data[0]['admin_Prof_img'];	
				}
				
			return array( 'username'=>$data[0]['username'], 'image'=> $AdminProfileImg);
		endif;
	}
	
	
}
	
