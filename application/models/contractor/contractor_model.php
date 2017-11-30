<?php
class Contractor_model extends Model
{
	
	public function Insert_data($data,$table)
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
	
	public function Get_all_with_cond($wherekey,$wherevalue,$table,$order="desc")
	{
		return $this->get_table_data($table,$wherekey,$wherevalue,$order);
	}
	
	public function Get_all_with_multiple_cond($where_cond,$table,$order="asc")
	{
		return $this->get_all_mul_cond($where_cond,$table,$order);
	}
	
	
	public function Get_column($column,$wherekey, $whereval,$table)
	{
		return $this->get_single_row_columns($column,$wherekey, $whereval,$table);
	}
	
	public function Update_row($data,$wherekey,$whereval,$table)
	{
		return $this->update($data,$wherekey,$whereval,$table);
	}
	public function get_Data_table($table,$field,$value)
	{
		return $this->get_table_data($table,$field,$value);
	}
	public function get_job_filter($table, $searchItem , $userId , $jobType , $fixedRange , $hourlyRange , $experianceLevel , $travelDistance , $projectDuration)
	{
		return $this->get_filterjob($table, $searchItem , $userId , $jobType , $fixedRange , $hourlyRange , $experianceLevel , $travelDistance , $projectDuration);
	}
	public function filter_data($qry)
	{
		return $this->data_filter($qry);
	}
	
	public function get_count($wherekey,$whereval,$table)
	{
		return $this->get_record_count($table,$wherekey,$whereval);
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
	
	public function get_saved_jobs($position,$item_per_page)
	{
		$userdata = $this->Get_row('username',$_SESSION['force_username'],'flex_users');
		
		$getCountry = $this->query("SELECT flex_countries.name FROM flex_users INNER JOIN flex_countries on flex_countries.sortname = flex_users.country WHERE flex_users.id ='$userdata[id]'");
		$userCountry = $this->resultset($getCountry);
		
		$query ="SELECT * FROM flex_saved_jobs WHERE contractor_id ='$userdata[id]' ORDER BY id ASC LIMIT $position, $item_per_page";
		$savejobs = $this->query($query);
		$result = $this->resultset($savejobs);	
		
		$job = '';	
		foreach($result as $keys):	
				
				$Activites = $this->get_activities($keys['job_id']);		
					
				$job_details = $this->Get_row('id',$keys['job_id'],'flex_jobs');				
				$date = gmdate('d-m-Y',$job_details['job_created']);
				
				$job .='<article class="contractor-box">
              <div class="contractor-action">
                <div class="dropdown">
                  <button class="btn btn-blue dropdown-toggle act" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                  <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                    <li><a target="_blank" href="'.SITEURL.'contractor/apply_for_job/'.$job_details["job_slug"].'">Apply Job</a></li>
                    <li><a  rel="'.$keys["id"].'" class="deleting" href="javascript:void(0)">Delete Job</a></li>
                  </ul>
                </div>
              </div>
              <div class="contractor-pro-details">
                <div class="contract-hdr">
                  <h3><a target="_blank" href="'.SITEURL.'contractor/job_description/'.$job_details["job_slug"].'">'.$job_details["job_title"].'</a></h3>
                  <p><strong>Time Posted:</strong> '.$date.'</p>
                  <p><strong>Pay:</strong> $'.$job_details["job_price"].'</p>
                  <p class="tasks-list"><strong>Tasks and Location:</strong>'.$Activites.'</p>
                  <p class="training-required"><strong>Training Required:</strong> </p>
                </div>
                <div class="descriptionArea">
                  <p><strong>Description:</strong></p>
                  <p>'.$job_details["job_description"].'</p>
                </div>
                <div class="aboutClient">
                  <p><strong>Client: </strong> <span class="isVerified yes">verified</span> <span class="clientRating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span> <span class="divider">|</span> <span class="clientLocation"><i class="location-pin"></i> '.$userCountry[0]["name"].'</span></p>
                </div>
                
                <a target="_blank" href="'.SITEURL.'contractor/job_description/'.$job_details["job_slug"].'" class="moreDetailsBtn">More</a>
              </div>
            </article>';				
		endforeach;
		
		echo $job;
	}
	
	public function get_activities($job_id)
	{
		/* Get Activities */
		$Act_query = $this->query("SELECT flex_job_activities.activity_name,flex_states.name FROM flex_job_activities INNER JOIN flex_states on flex_states.id = flex_job_activities.state WHERE flex_job_activities.job_id ='$job_id'");
		$Activites = $this->resultset($Act_query);
		$act ='';
		foreach($Activites as $keys):
			$act.='<span class="task-serial">'.$keys["activity_name"].' | <i class="location-pin"></i> '.$keys["name"].'</span> ';
		endforeach;
		return $act;
	}
	
	public function get_saved_jobs_count()
	{
		$userdata = $this->Get_row('username',$_SESSION['force_username'],'flex_users');
		$savejobs = $this->Get_all_with_cond('contractor_id',$userdata['id'],'flex_saved_jobs','DESC');
		return count($savejobs);
	}
	
	public function delete_data($wherekey,$whereval,$table)
	{
		return $this->delete_all_record($table,$wherekey,$whereval);
	}
}