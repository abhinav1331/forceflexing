<?php 
if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			$role=$this->udata['role'];
			$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
			if($role_name['role_name'] == 'contractor')
			{
				$this->loadview('main/header')->render();
				
				/* Navigation */
				$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
				$this->navigation($data);
				
				/*get the id from the slug*/
				$url=$_SERVER['REQUEST_URI'];
				$pos = strrpos($url, '/');
				$slug = $pos === false ? $url : substr($url, $pos + 1);
				
				/*get the jobid*/
				$job_id=$this->Model->Get_column('id','job_slug',$slug,PREFIX.'jobs');
				
				$jobid=$job_id['id'];
				$template = $this->loadview('contractor/contractor_views/job_description');
				if(isset($jobid) && $jobid != "")
				{
					$job_data=$this->Model->Get_row('id',$jobid,PREFIX.'jobs');
					$template->set('job_data',$job_data);
					
					/*get client related information*/
					$emp_id=$job_data['job_author'];
					
					/*COMPANY INFORMATION*/
					/*get the company name*/
					$emp_details=$this->Model->Get_row('id',$emp_id,PREFIX.'users');
					$template->set('company_name',$emp_details['company_name']);
					
					/*get the country of employer*/
					$country_name=$this->Model->Get_column('name','sortname',$emp_details['country'],PREFIX.'countries');
					if(!empty($country_name))
						$template->set('emp_country',$country_name['name']);
					
					/*get the company information*/
					$company_info=$this->Model->Get_row('company_id',$emp_id,PREFIX.'company_info');
					if(!empty($company_info))
					{
						//get the city
						$cityname=$this->Model->Get_column('name','id',$company_info['company_city'],PREFIX.'cities');
						if(!empty($country_name))
							$template->set('emp_city',$cityname['name']);
						
					}
					
					/*get member since info */
					$template->set("member_since",$emp_details['created_date']);
					
					/*get the count of posted jobs*/
					$posted_jobs=$this->Model->get_count_with_multiple_cond(array('job_author'=>$emp_id,'job_visibility'=>'none'),PREFIX.'jobs');
					if(!empty($open_jobs))
						$template->set('posted_jobs',$posted_jobs);
					
					/*get the count of open jobs*/
					$open_jobs=$this->Model->get_count_with_multiple_cond(array('job_author'=>$emp_id,'job_visibility'=>'anyone'),PREFIX.'jobs');
					if(!empty($open_jobs))
						$template->set('open_jobs_count',$open_jobs);
					
					/*get the time of the country*/
					//$details = json_decode(file_get_contents("http://ip-api.com/json/".$ip.""));
					
					/*COMPANY INFORMATION ENDS*/
					
					/*get job activities*/
					$job_activities=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_activities');
					$template->set('job_activities',$job_activities);
					
					/*get similar jobs*/
					/*contractor data*/
					$contr_lat=$data['latitude'];
					$contr_long=$data['longitude'];
					$contr_indus=$data['industries'];
					$contr_pay=$data['hourly_wages'];
					$job_ids=array();
					/*get jobs with similar geographical location as contractor location */
					if((isset($contr_lat)  && $contr_lat !="") && (isset($contr_long)  && $contr_long !=""))
					{
						$wherecondact=array('latitude'=>$contr_lat,'longitude'=>$contr_long);
						$job_acti=$this->Model->Get_all_with_multiple_cond($wherecondact,PREFIX.'job_activities');
						
						if(!empty($job_acti))
						{
							foreach($job_acti as $job)
							{
								$job_ids[]=$job['job_id'];
							}
						}
					}
					/*get jobs with similar industries*/
					if(isset($contr_indus) && $contr_indus != "")
					{
						$wherestr="";
						$all_indu=explode(',',$contr_indus);
						$i=1;
						foreach($all_indu as $inus)
						{
							if($i>1)
								$wherestr .=' or ';
							$wherestr .='`job_industry_knowledge` like "%'.$inus.'%"';
							$i++;
						}
						$query_indus='select * from '.PREFIX.'jobs where '.$wherestr.'';
						$results=$this->Model->filter_data($query_indus);
						if(!empty($results))
						{
							foreach($results as $job)
							{
								$job_ids[]=$job['id'];
							}
						}
					}
					
					/*get jobs with similar pay*/
					if(isset($contr_pay) && $contr_pay != "")
					{
						$similar_pay_res=$this->Model->Get_all_with_cond('job_price',$contr_pay,PREFIX.'jobs');
						if(!empty($similar_pay_res))
						{
							foreach($similar_pay_res as $job)
							{
								$job_ids[]=$job['id'];
							}
						}
					}
					$all_id='';
					$where='';
					if(!empty($job_ids))
					{
						$all_id=array_unique($job_ids);
						$all_id=implode(',',$all_id);
						$where=' and id in('.$all_id.')';
					}
					$similar_jobs_query='select * from '.PREFIX.'jobs where job_visibility="anyone"'.$where.'';
					$similar_jobs_data=$this->Model->filter_data($similar_jobs_query);
					
					$template->set('similar_job',$similar_jobs_data);
					$template->set('instance',$this);
					
					/*check if job is saved or not*/
					$wherecond=array('contractor_id'=>$this->userid,'job_id'=>$job_id['id'],'saved_for'=>'contractor');
					$job_saved_res=$this->Model->Get_all_with_multiple_cond($wherecond,PREFIX.'saved_jobs');
					if(!empty($job_saved_res))
						$template->set('job_saved','yes');
					else
						$template->set('job_saved','no');
					
					/*check for appied jobs*/
					$where_cond_appljobs=array('job_id'=>$job_id['id'],'status'=> 0 );
					$applied_jobs=$this->Model->get_count_with_multiple_cond($where_cond_appljobs,PREFIX.'applied_jobs');
					$template->set('applied_jobs',$applied_jobs);
					
					/*check if alert is saved or not*/
					$wherecondalert=array('contractor_id'=>$this->userid,'job_id'=>$job_id['id'],'alert_type'=>'flex_alert');
					$flex_alert_res=$this->Model->Get_all_with_multiple_cond($wherecondalert,PREFIX.'alerts');
					if(!empty($flex_alert_res))
						$template->set('alert','yes');
					else
						$template->set('alert','no');
					
					/*check whether the user has already applied for the job*/
					$wherecon=array('contractor_id'=>$this->userid,'job_id'=>$job_id['id']);
					$already_applied=$this->Model->Get_all_with_multiple_cond($wherecon,PREFIX.'applied_jobs');
					if(!empty($already_applied))
					{
						$template->set('already_applied','yes');
						$template->set('applied_job_id',$already_applied[0]['id']);
						$template->set('applied_job_status',$already_applied[0]['status']);
					}
					else
					{
						$template->set('already_applied','no');
					}
					
					/*get jobs flex rates*/
					$flex_rates=$this->Model->filter_data('SELECT flex_date FROM flex_jobs_flex WHERE `flex_date` >= CURDATE() and `job_id`=1 order by flex_date asc limit 1');
					if(!empty($flex_rates))
						$template->set('flexed_data',$flex_rates);
					
				}
				$template->render();
				$this->loadview('main/footer')->render();	
			}
			else
			{
				$this->no_access();
			}
		}
		else
		{
			$this->redirect('login');
		}
?>