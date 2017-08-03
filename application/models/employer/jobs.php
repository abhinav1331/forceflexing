<?php 
class Jobs extends Model
{
	
	public function openjob($position,$item_per_page)
	{
		
		/* Get User ID From Username from Session */
		$userid = $this->get_single_row_columns('id','username', $_SESSION['force_username'],'flex_users');		
		/* Get Jobs Posted By User */
		$query ="SELECT * FROM flex_jobs WHERE job_author ='$userid[id]' AND jobjob_status = '1' ORDER BY id DESC LIMIT $position, $item_per_page";
		$openjobs = $this->query($query);
		$result = $this->resultset($openjobs);
		
		foreach($result as $keys)
		{
			$jobID = $keys['id'];
			$jobTitle = $keys['job_title'];
			$jobslug = $keys['job_slug'];
			$posted_date = date('d-m-Y',$keys['job_created']);
			$res = json_decode($this->get_applicants($keys['id']));	// Get Applicant & Message number
			
			$hired = $this->get_hired($keys['id']);
			echo '<article>
                            <div class="open-job-info">
                                <h3><a href="#">'.$jobTitle.'</a></h3>
                                <ul class="job-posted-info">
                                    <li>Posted: '.$posted_date.'</li>                                   
                                </ul>
                                <ul class="view-jobs-btn-group">
                                    <li><a href="#">View Activity Detail</a></li>
                                    <li><a href="#">View suggested contractors</a></li>
                                </ul>
                            </div>
                            <div class="open-jobs-task">
                                <ul>';
								if($res->new != 0)
								{
									echo '<li><big>'.$res->total.' <span>('.$res->new.' new)</span></big>Applicants</li>';
								}
								else
								{
									echo '<li><big>'.$res->total.'</big>Applicants</li>';
								}
                                   
                                    echo '<li><big>'.$res->message.'</big>Messaged</li>
                                    <li><big>--</big>Offers</li>
                                    <li><big>'.$hired.'</big>Hired</li>
                                </ul>
                            </div>
                            <div class="open-jobs-action">
                                <div class="dropdown">
                                    <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                                    <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                                        <li>
                                            <label class="radio-custom">
                                                <input class="view" type="radio" name="one" value="'.$jobslug.'" id="viewPost"> <span class="radio"></span>View Post</label>
                                        </li>
                                        <li>
                                            <label class="radio-custom">
                                                <input class="view" type="radio" name="one" value="'.$jobslug.'" id="editPost"> <span class="radio"></span>Edit Post</label>
                                        </li>
                                        <li>
                                            <label class="radio-custom">
                                                <input class="action" type="radio" name="one" value="removePost" id="'.base64_encode($jobID).'"> <span class="radio"></span>Remove Post</label>
                                        </li>
                                        <li>
                                            <label class="radio-custom">
                                                <input class="action" type="radio" name="one" value="duplicatePost"> <span class="radio"></span>Duplicate Post</label>
                                        </li>
                                        <li>
                                            <label class="radio-custom">
                                                <input class="action" type="radio" name="one" value="one1" id="makePrivate"> <span class="radio"></span>Make Private</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </article>';
			
			
			
			
			
		}
	}
	
	public function get_jobcount()
	{
		$userdata = $this->get_single_row('username',$_SESSION['force_username'],'flex_users');
		
		$query ="SELECT * FROM flex_jobs WHERE job_author ='$userdata[id]' AND jobjob_status = '1'";
		$openjobs = $this->query($query);
		$result = $this->resultset($openjobs);			
		return count($result);
	}
	

	public function get_applicants($jobid)
	{
		//$applicant = $this->get_table_data('flex_applied_jobs','job_id',$jobid);
				
		$query ="SELECT *,COUNT(NULLIF(TRIM(message), '')) as MSG FROM flex_applied_jobs WHERE job_id = $jobid GROUP BY contractor_id";
		$openjobs = $this->query($query);
		$result = $this->resultset($openjobs);
		
		
		$current = strtotime(date('Y-m-d H:i:s'));
		
		$date = strtotime("-2 day", $current);
		$prev =  date('Y-m-d',$date);
		$total = 0;
		$new = 0;
		$message = 0;
		
		foreach($result as $keys):			
			if( $keys['created_date'] > $prev )
			{
				$total = $total + 1;
				$new = $new + 1;
			}
			else
			{
				$total = $total + 1;
			}
			
			$message = $keys['MSG'];
			
			
		endforeach;
		
		return json_encode(array('total'=>$total,'new'=>$new,'message'=>$message));
		
	}
	
	public function get_hired($jobid)
	{
		$query ="SELECT count(*) as Hired FROM flex_hire_contractor WHERE job_id = $jobid AND status = '1'";
		$openjobs = $this->query($query);
		$result = $this->resultset($openjobs);
		return $result[0]['Hired'];
		
	}

	public function get_jobs_ByID()
	{
		$userid = $this->get_single_row_columns('id','username', $_SESSION['force_username'],'flex_users');		
		return $this->get_table_data(PREFIX.'jobs','job_author',$userid['id']);
	}

	public function job_delete($jid)
	{
		try 
		{
			$data = array('jobjob_status'=> 4 );
			$this->update($data,'id',$jid,'flex_jobs');
			echo "success";
		}
		catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
		}
		
		
	}
}