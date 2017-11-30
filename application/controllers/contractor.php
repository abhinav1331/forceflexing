<?php 
class Contractor extends Controller
{
	public $Validator;
	public $Model;	
	public $userid;
	public $udata;
	public $item_per_page;
	public $rec_count;
	public $SendMail;
	public $TimeZone;
	public $Notification;
	public function __construct() 
	{
		

		$this->Validator = $this->loadHelper('validator');
		$this->SendMail=$this->loadHelper('sendmail');
		$this->TimeZone=$this->loadHelper('timezone');
		$this->Notification=$this->loadHelper('notification');
		
		$this->Model = $this->loadModel('Contractor_model','contractor');
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password']))
		{
			if (isset($_COOKIE['force_username'])) 
			{
				$username = $_COOKIE['force_username'];
			}
			else 
			{
				$username = $_SESSION['force_username'];
			}
			/* get login user details*/
			$this->udata=$this->Model->Get_row('username',$username,PREFIX.'users');
			$this->userid=$this->udata['id'];
			$this->item_per_page = 7;
		}
	}
	
	public function pre($array,$exit=false)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
		if($exit == true)
			exit();
	}
		
	/*function to append navigation*/
	public function navigation($data)
	{
		/* Navigation */
		$nav=$this->loadview('contractor/main/navigation');			
		$nav->set('first_name',$this->udata['first_name']);
		$nav->set('last_name',$this->udata['last_name']);
		if($data['profile_img'])
		{
			$nav->set('profile_img',$data['profile_img']);
		}
		$nav->set('user_visibility',$this->udata['visibility_status']);
		
		/*check new messsages count*/
		$c=array('to_id'=>$this->userid,'is_read'=>0);
		$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
		$nav->set('unread_msg_count',$unread_msg_count);
		
		/*check for new notifications if any*/
		$notifications=$this->Notification->get_notification($this->userid);
		$nav->set('notifications',$notifications);
		$nav->set('instance',$this);
		$nav->render();
		
		
		
	}
	
	/*no access view*/
	public function no_access()
	{
		$this->loadview('main/header')->render();
		$data=$this->Model->Get_row('company_id',$this->userid,PREFIX.'company_info');
		$getUserNavigation = $this->loadview('Employer/main/navigation');
		$getUserNavigation->set("nameEmp" , $data['company_slug']);
		$getUserNavigation->set("profile_img" , $data['company_image']);
		$getUserNavigation->render();
		$this->loadview('main/noaccess')->render();
		$this->loadview('main/footer')->render();
	}
	
				/*****Job Search Module starts *************/
	public function find_job() 
	{
		$instance = $this;
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			$role=$this->udata['role'];
			$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
			if($role_name['role_name'] == 'contractor')
			{
				/**Function Random String**/
				function generateRandomString($length = 10) 
				{
					return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
				}
				/**Function Random String**/

				$this->loadview('main/header')->render();
				//$searchField = $_SERVER['QUERY_STRING'];
				$searchField = $_SERVER['REQUEST_URI'];
				$searchField=urldecode($searchField);
				
				/*convert to array*/
				if(!empty($searchField))
				{
					$array=explode ('&',$searchField);
					$searcharray = array();
					if(count($array) > 1) 
					{
						foreach($array as $a)
						{
							$gf[] = list ($k,$v) = explode ('=',$a);
						}
						foreach($gf as $hg)
						{
							if(!in_array($hg[0],$searcharray))
							{
								$searcharray[$hg[0]][] = $hg[1];						
							}
							else
							{
								$searcharray[$hg[0]][] = $hg[1];		
							}					
						}
					}
					
				}
				
				/*pagination function*/
				$records_per_page=$this->item_per_page;
				$page_number=1;
				$arguments=array('job_visibility'=>'anyone','jobjob_status'=>1);
				$this->rec_count=$this->Model->get_count_with_multiple_cond($arguments,PREFIX.'jobs');
				$total_records=$this->rec_count;
				$total_pages=ceil($total_records / $records_per_page); 
				
				//offset
				$page_position = (($page_number-1) * $records_per_page);
				$results = $this->Model->filter_data("select * from ".PREFIX."jobs where job_visibility='anyone' and jobjob_status=1 ORDER BY id desc LIMIT $page_position, $records_per_page");
				
				/* Navigation */
				$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
				$this->navigation($data);
				
				//Search Module Starts, gathering Elements.
				if (isset($_GET['securityKey'])) {
					$securityKey = $_GET['securityKey'];
				} else {
					$securityKey = generateRandomString();
				}
				
				$template = $this->loadview('contractor/contractor_views/job_search');
				$template->set('securityKey',$securityKey);
				$template->set('userdata',$data);
				$template->set('results',$results);
				$template->set('instance',$instance);
				$template->set('total_records',$this->rec_count);
				if(!empty($searchField))
					$template->set('querystring',$searcharray);
				
				$template->render();

				//Search Module Ends.
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
	}
	
	/*Pagination function */
	public function paginate_function($items_per_page, $current_page, $total_records, $total_pages)
	{
		$pagination = '';
		if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
			$pagination .= '<div class="pagination page_filter">';
			
			$right_links    = $current_page + 3; 
			$previous       = $current_page - 1; //previous link 
			$next           = $current_page + 1; //next link
			$first_link     = true; //boolean var to decide our first link
			
			
			if($current_page > 1) 
				$disabled= "";
			else
				$disabled="disabled";
			
			$previous_link = ($previous==0)?1:$previous;
				$pagination .= '<a href="javascript:void(0);" data-page="'.$previous_link.'" class="'.$disabled.'" title="Previous">Previous</a>'; //previous link
			if($current_page > 1)
			{
				for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
						if($i > 0){
							$pagination .= '<a href="javascript:void(0);" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a>';
						}
					}   
				$first_link = false; //set first link to false
			}
			
			if($first_link)
			{
				//if current active page is first link
				$pagination .= '<a class="first active current">'.$current_page.'</a>';
			}
			elseif($current_page == $total_pages)
			{ 
				//if it's the last active link
				$pagination .= '<a class="last active current">'.$current_page.'</li>';
			}
			else
			{
				//regular current link
				$pagination .= '<a class="active current">'.$current_page.'</li>';
			}
					
			for($i = $current_page+1; $i < $right_links ; $i++)
			{ 
				//create right-hand side links
				if($i<=$total_pages)
				{
					$pagination .= '<a href="javascript:void(0);" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a>';
				}
			}
			
			if($current_page < $total_pages)
				$disable="";
			else
				$disable="disabled";
			
			$next_link = ($next > $total_pages)? $total_pages : $next;
			$pagination .= '<a href="javascript:void(0);"  class="'.$disable.'" data-page="'.$next_link.'" title="Next">Next</a>'; //next link
			$pagination .= '</div>'; 
		}
		return $pagination; //return pagination links
	}
	
	/*Ajax function for filtering data*/
	public function filter_data($search="",$filter="")
	{
		$temp=0;
		if(!empty($filter))
		{
			$searchItem=$search;
			$filterstr=$filter;
		}
		else
		{
			$searchItem=$_POST['searchstring'];
			$filterstr=$_POST['filterstr'];
		}
		
		$wherestr="";
		/*search item*/
		if(isset($searchItem) && $searchItem != "")
		{
			$searchItem=str_replace('+',' ',$searchItem);
			$wherestr.= " && ";
			$wherestr .= '(job_title LIKE "%'.$searchItem.'%" OR job_description LIKE "%'.$searchItem.'%")';
		}
		if($filterstr != "")
		{
			$array=explode ('&',$filterstr);
			foreach($array as $a)
			{
				$gf[] = list ($k,$v) = explode ('=',$a);
			}
			$finalarray = array();
			foreach($gf as $hg)
			{
				if(!in_array($hg[0],$finalarray))
				{
					$finalarray[$hg[0]][] = $hg[1];						
				}
				else
				{
					$finalarray[$hg[0]][] = $hg[1];		
				}					
			}
			
			$this->pre($finalarray);
			/*category filter*/
			if (array_key_exists('category', $finalarray)) 
			{
				$category=$finalarray['category'];
				if(!empty($category))
				{
					$selected_category=$category[0];
					$wherestr.= " && ";
					$wherestr .= '(jobs_speciality = "'.$selected_category.'")';
				}
			}
			
			
			$jobType="";
			/* job type*/
			if (array_key_exists('job_type', $finalarray)) 
			{
				$wherestr.= " && (";
				$jobType=$finalarray['job_type'];
				if(in_array('fixed',$jobType))
				{
					$wherestr.= '(job_type="fixed"';
					if (array_key_exists('fixedRange', $finalarray)) 
					{
						$fixedrange=$finalarray['fixedRange'][0];
						$range=explode(',',$fixedrange);
						$wherestr .= ' && job_price between '.$range[0].' and  '.$range[1].')'; 
					}
					else
					{
						$wherestr .= ')';
					}
				}
				if (count($jobType) > 1)
				{
					$wherestr .= ' ||';
				}
				if(in_array('hourly',$jobType))
				{
					$wherestr.= ' (job_type="hourly"';
					if (array_key_exists('hourlyRange', $finalarray)) 
					{
						$hourlyRange=$finalarray['hourlyRange'][0];
						$range=explode(',',$hourlyRange);
						$wherestr .= ' && job_price between '.$range[0].' and  '.$range[1].')'; 
					}
					else
					{
						$wherestr .= ')';
					}
				}
				$wherestr.= ")";
			}
			
			/*expereience level*/
			if (array_key_exists('experience_level', $finalarray)) 
			{
				$wherestr.= " && ";
				$experience_level=$finalarray['experience_level'];
				$wherestr .= '(job_experiance IN ("' . implode('", "', $experience_level) . '"))'; 
			}
			
			/*travel_distance*/
			$jobidarray=array();
			if (array_key_exists('travel_distance', $finalarray)) 
			{
				$travel_distance=$finalarray['travel_distance'];
				$temp=1;
				/*get the location of contractor*/
				$location_cont = $this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
				
				$latcont=$location_cont['latitude'];
				$longcont=$location_cont['longitude'];
				
				/*if location is there for contractor*/
				if(!empty($latcont) && !empty($longcont))
				{
					/*distance less then 15*/
					if(in_array('0-15',$travel_distance))
					{
						$distance_qry="SELECT `job_id`,(6371 * acos(cos( radians( $latcont ) ) * cos( radians( `latitude` ) ) * cos(radians( `longitude` ) - radians( $longcont )) + sin(radians($latcont)) * sin(radians(`latitude`)))) `distance` FROM `".PREFIX."job_activities` HAVING `distance` <= 15";
						$resul=$this->Model->filter_data($distance_qry);
						if(!empty($resul))
						{
							foreach($resul as $res)
							{
								$jobidarray[]=$res['job_id'];
							}
						}
					}
					
					/*distance between 16 and 30*/
					if(in_array('16-30',$travel_distance))
					{
						$distance_qry="SELECT `job_id`,(6371 * acos(cos( radians( $latcont ) ) * cos( radians( `latitude` ) ) * cos(radians( `longitude` ) - radians( $longcont )) + sin(radians($latcont)) * sin(radians(`latitude`)))) `distance` FROM `".PREFIX."job_activities` HAVING `distance` BETWEEN 16 and 30";
						$resul=$this->Model->filter_data($distance_qry);
						if(!empty($resul))
						{
							foreach($resul as $res)
							{
								$jobidarray[]=$res['job_id'];
							}
						}
					}
					
					/*distance between 31 and 60*/
					if(in_array('31-60',$travel_distance))
					{
						$distance_qry="SELECT `job_id`,(6371 * acos(cos( radians( $latcont ) ) * cos( radians( `latitude` ) ) * cos(radians( `longitude` ) - radians( $longcont )) + sin(radians($latcont)) * sin(radians(`latitude`)))) `distance` FROM `".PREFIX."job_activities` HAVING `distance` BETWEEN 31 and 60";
						$resul=$this->Model->filter_data($distance_qry);
						if(!empty($resul))
						{
							foreach($resul as $res)
							{
								$jobidarray[]=$res['job_id'];
							}
						}
					}
					/*distance between 61 and 180*/
					if(in_array('61-180',$travel_distance))
					{
						$distance_qry="SELECT `job_id`,(6371 * acos(cos( radians( $latcont ) ) * cos( radians( `latitude` ) ) * cos(radians( `longitude` ) - radians( $longcont )) + sin(radians($latcont)) * sin(radians(`latitude`)))) `distance` FROM `".PREFIX."job_activities` HAVING `distance` BETWEEN 61 and 180";
						$resul=$this->Model->filter_data($distance_qry);
						if(!empty($resul))
						{
							foreach($resul as $res)
							{
								$jobidarray[]=$res['job_id'];
							}
						}
					}
					/*distance over 180*/
					if(in_array('180-above',$travel_distance))
					{
						$distance_qry="SELECT `job_id`,(6371 * acos(cos( radians( $latcont ) ) * cos( radians( `latitude` ) ) * cos(radians( `longitude` ) - radians( $longcont )) + sin(radians($latcont)) * sin(radians(`latitude`)))) `distance` FROM `".PREFIX."job_activities` HAVING `distance` >180";
						$resul=$this->Model->filter_data($distance_qry);
						if(!empty($resul))
						{
							foreach($resul as $res)
							{
								$jobidarray[]=$res['job_id'];
							}
						}
					}
				}
			}
			
			/*client history*/
			$client_history=array();
			if (array_key_exists('client_history', $finalarray)) 
			{
				$client_history=$finalarray['client_history'];
				/*case of no hire*/
				if(in_array('none',$client_history))
				{
					$qry="SELECT id from flex_jobs where job_author NOT IN (select employer_id from flex_hire_contractor)";
					$resul=$this->Model->filter_data($qry);
					if(!empty($resul))
					{
						foreach($resul as $res)
						{
							$jobidarray[]=$res['id'];
						}
					}
				}
				
				/*case of 20-30 hires*/
				if(in_array('1-20',$client_history))
				{
					$qry="SELECT id from flex_jobs where job_author IN(SELECT employer_id FROM flex_hire_contractor GROUP BY employer_id HAVING COUNT(*) >= 1 AND COUNT(*) <= 20)";
					$resul=$this->Model->filter_data($qry);
					if(!empty($resul))
					{
						foreach($resul as $res)
						{
							$jobidarray[]=$res['id'];
						}
					}

				}
				if(in_array('20+',$client_history))
				{
					$qry="SELECT id from flex_jobs where job_author IN(SELECT employer_id FROM flex_hire_contractor GROUP BY employer_id HAVING COUNT(*) > 20)";
					$resul=$this->Model->filter_data($qry);
					if(!empty($resul))
					{
						foreach($resul as $res)
						{
							$jobidarray[]=$res['id'];
						}
					}
				}
			}
			
			/*project_duration*/
			if (array_key_exists('project_duration', $finalarray)) 
			{
				$temp=1;
				$project_duration=$finalarray['project_duration'];
				$results = $this->Model->Get_all_data(PREFIX.'job_activities');
				foreach($results as $r)
				{
					$start=new DateTime($r['start_datetime']);
					$end=new DateTime($r['end_datetime']);
					$datetimeobj=date_diff($start,$end); 
					
					/*case of hours*/
					if(in_array('hours',$project_duration))
					{
						if( $datetimeobj->h != 0 && $datetimeobj->y == 0 &&  $datetimeobj->m == 0 && $datetimeobj->d == 0 )
							$jobidarray[]= $r['job_id'];
					}
					
					/*case of days*/
					if(in_array('days',$project_duration))
					{
						if( $datetimeobj->d != 0  && $datetimeobj->d > 7 && $datetimeobj->y == 0 &&  $datetimeobj->m == 0 )
							$jobidarray[]= $r['job_id'];
					}
					
					/*case of weeks */
					if(in_array('weeks',$project_duration))
					{
						/*get difference in seconds*/
						$datefrom = strtotime($r['start_datetime'], 0);
						$dateto = strtotime($r['end_datetime'], 0);
						$difference = $dateto - $datefrom;
						
						/*convert to weeks*/
						$datediff = floor($difference / 604800);
						if($datediff > 1 && $datediff < 4)
							$jobidarray[]= $r['job_id'];
					}
					
					/*case of months*/
					if(in_array('months',$project_duration))
					{
						if( $datetimeobj->m != 0 &&  $datetimeobj->y == 0)
							$jobidarray[]= $r['job_id'];
					}
					
					if(in_array('above-6-months',$project_duration))
					{
						if( $datetimeobj->m > 6 )
							$jobidarray[]= $r['job_id'];
					}
				}
			}
			
			if(empty($jobidarray) && in_array('20+',$client_history))
			{
				$wherestr.= " && ";
				$wherestr .= '(id IN (""))';  
			}
			elseif(!empty($jobidarray))
			{
				$wherestr.= " && ";
				$uniqueproid=array_unique($jobidarray);
				$wherestr .= '(id IN ("' . implode('", "', $uniqueproid) . '"))';  
			}
			
			
			
			/*Training Requirements*/
			if (array_key_exists('training_req', $finalarray)) 
			{
				$training_req=$finalarray['training_req'];
				// foreach($training_req as $pd)
				// {
					// $wherestr .= "`job_title`  LIKE '%".$searchItem."%' OR `job_description` LIKE '%".$searchItem."%'"."&&  ";
				// }
			}
			/*Hours per week*/
			if (array_key_exists('weeklyhours', $finalarray)) 
			{
				$wherestr.= " && ";
				$weeklyhours=$finalarray['weeklyhours'];
				$wherestr .= '(job_hours IN ("' . implode('", "', $weeklyhours) . '"))'; 
			}
			
			/*overnight travel*/
			if (array_key_exists('overnight_travel', $finalarray)) 
			{
				$wherestr.= " && ";
				$overnight_travel=$finalarray['overnight_travel'];
				$wherestr .= '(job_overnight IN ("' . implode('", "', $overnight_travel) . '"))'; 
			}
			
			if (array_key_exists('sorting', $finalarray)) 
			{
				$sortorder=$finalarray['sorting'][0];
				$wherestr .= ' order by id '.$sortorder.'';
			}
			
			if (array_key_exists('page', $finalarray)) 
				$page=$finalarray['page'][0];
			else
				$page=1;
			
				$page_number=$page;
				$records_per_page=$this->item_per_page;
				$count_qry= 'select * from '.PREFIX.'jobs where job_visibility ="anyone" && jobjob_status=1 '.$wherestr.'';
				$res=$this->Model->filter_data($count_qry);
				$this->rec_count=count($res);
				$total_records=$this->rec_count;
				$total_pages=ceil($total_records / $records_per_page); 
				//offset
				$page_position = (($page_number-1) * $records_per_page);
				$wherestr .=' LIMIT '.$page_position.', '. $records_per_page.'';
		}
		
			if($temp == 1 && empty($jobidarray))
			{
				$results=array();
			}
			else
			{
				$qry= 'select * from '.PREFIX.'jobs where job_visibility ="anyone" && jobjob_status=1 '.$wherestr.'';
				echo $qry;
				$results=$this->Model->filter_data($qry);	
			}
		
		
		ob_start();
		?>
		<div class="main-content-data">
		<?php
		if(!empty ($results))
		{
			if($this->rec_count > 1)
				$cnt=$this->rec_count .' Jobs Found';
			else
				$cnt=$this->rec_count .' Job Found';
			echo '<div class="loader" style="display:none;"><img src="'.BASE_URL.'static/images/loading.gif"></div>';
			echo '<input type="hidden" name="count" id="cnt" value="'.$cnt.'">';
			foreach($results as $job)
			{
			?>
				<article class="ff-job">
					<summary>
						<h3>
							<a href="<?php echo SITEURL;?>contractor/job_description/<?php echo $job['job_slug'];?>" class="job_title" data-jobid="<?php echo $job['id'];?>"><?php  echo $job['job_title'];?></a> 
							<?php $industries= $job['job_industry_knowledge'];
								$all_industries=explode(',',$industries);
								foreach($all_industries as $industry)
								{
								?>
									<span class="category-tag"><?php echo $industry; ?></span>
						  <?php } ?>
						</h3>
						<p class="job-details"><?php echo ucfirst($job['job_type']); ?> - Budget: $<?php if(!empty($job['job_price'])) echo number_format($job['job_price']); ?> - Posted <?php echo $this->time_elapsed_string('@'.$job['job_created'].'');?></p>
						<p><?php 
							$job_desc= $job['job_description']; 
							echo $this->truncate($job_desc,'300','....');
							?></p>
					</summary>
					<hr class="vr-divider">
					<div class="job-location"><span><?php $this->get_location_of_job($job['id']); ?></span> <a href="javascript:void(0);" class="favorite-it"><i class="fa fa-heart-o" aria-hidden="true"></i></a></div>
				</article>
				<?php
			}
		}
		else
		{
			echo '<input type="hidden" name="count" id="cnt" value="No Jobs Found">';
			echo '<div class="noResults"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 156.227 82.527">
<path fill="#E9EBF1" d="M62.445,6.966c1.038-0.176,1.541,0.124,1.915,0.771c2.241,3.881,4.531,7.731,6.723,11.64
	c0.43,0.766,0.159,1.755-0.586,2.236c-1.255,0.813-1.712-0.314-2.193-1.156c-1.403-2.448-2.981-4.821-4.125-7.388
	c-1.179-2.646-2.643-2.346-4.671-1.162c-8.748,5.101-17.517,10.167-26.313,15.182c-2.079,1.185-2.266,2.625-1.156,4.538
	c6.911,11.918,13.875,23.807,20.663,35.795c1.451,2.563,2.973,2.077,4.874,0.985c4.896-2.811,9.804-5.602,14.669-8.464
	c1.137-0.669,2.304-2,3.358-0.336c1.184,1.869-0.909,2.124-1.888,2.71c-5.694,3.411-11.434,6.75-17.166,10.102
	c-1.906,1.114-3.423,1.535-4.862-1.018C44.18,58.102,36.554,44.868,28.851,31.682c-1.8-3.081-1.557-4.643,1.896-6.509
	c9.339-5.045,18.377-10.648,27.547-16.008C59.729,8.328,61.23,7.606,62.445,6.966z"></path>
<path fill="#E9EBF1" d="M27.839,63.146c0.156-0.054,0.311-0.131,0.473-0.159c1.434-0.25,3.539-2.631,4.099-0.227
	c0.505,2.172-2.812,2.663-4.443,3.877c-1.835,1.365-2.55-0.083-3.342-1.468C16.72,51.342,8.873,37.479,0.773,23.766
	c-1.344-2.275-0.908-2.941,1.084-4.081C12.804,13.43,23.701,7.088,34.555,0.675c1.451-0.857,2.439-1.134,3.083,0.609
	c0.652,1.773,2.733,3.246,1.689,5.363c-0.569,1.154-1.894,0.63-2.429-0.294c-1.708-2.941-3.679-1.377-5.48-0.351
	C22.77,10.93,14.175,15.952,5.536,20.896c-1.474,0.845-2.272,1.378-1.106,3.368c7.213,12.312,14.237,24.731,21.338,37.107
	C26.236,62.19,26.486,63.331,27.839,63.146z"></path>
<path fill="#1575BB" d="M136.469,55.787c0.851,0.783,1.474,1.31,2.041,1.89c5.095,5.21,10.244,10.365,15.229,15.677
	c1.936,2.063,3.927,4.505,1.023,7.291c-2.763,2.651-5.637,2.439-8.313-0.227c-4.81-4.793-9.472-9.741-14.431-14.372
	c-2.324-2.171-2.166-3.577,0.112-5.503C133.719,59.204,134.965,57.465,136.469,55.787z"></path>
<path fill="#E9EBF1" d="M46.185,66.454c-0.691,2.169-2.893,2.466-4.578,3.384c-2.235,1.217-2.842-0.648-3.666-2.081
	C31.582,56.693,25.185,45.65,18.855,34.569c-5.665-9.917-5.666-9.988,3.95-15.733c7.966-4.759,16.01-9.388,24.052-14.021
	c1.068-0.614,2.397-1.839,3.558-0.435c1.132,1.369,2.349,2.981,2.635,4.644c0.264,1.523-1.605,1.928-2.324,0.766
	c-1.658-2.68-3.396-1.931-5.42-0.752c-8.452,4.916-16.924,9.801-25.375,14.718c-1.546,0.898-2.672,1.971-1.515,3.999
	c7.066,12.381,14.099,24.781,21.145,37.174c1.236,2.177,2.774,1.062,4.263,0.373C45.024,64.745,46.104,64.419,46.185,66.454z"></path>
<path fill="#EAECF1" d="M66.881,33.438c-5.42,5.456-12.637,8.158-19.334,12.72C48.24,43.085,56.288,37.776,66.881,33.438z"></path>
<path fill="#EBEDF2" d="M67.292,39.091c-5.691,4.037-11.738,7.454-18.048,10.415c-0.257-0.439-0.513-0.879-0.77-1.318
	c5.961-3.503,11.922-7.006,17.884-10.509C66.669,38.149,66.98,38.62,67.292,39.091z"></path>
<path fill="#EBECF2" d="M50.82,52.555c4.641-4.256,10.349-6.649,16.087-10.224C65.346,45.851,56.379,51.52,50.82,52.555z"></path>
<path fill="#EAECF1" d="M48.188,38.089c3.916-3.574,8.368-6.366,13.493-7.987C57.841,33.823,53.314,36.394,48.188,38.089z"></path>
	<path fill="#1575BB" d="M106.199,0.786c-18.538-0.087-33.856,15.387-33.82,33.688c0.038,19.229,14.749,34.516,33.268,34.614
		c18.735,0.101,33.773-15.063,33.767-34.047C139.983,16.964,124.584,0.871,106.199,0.786z M105.794,62.392
		c-14.878-0.052-26.702-12.137-26.697-27.29C79.1,19.924,90.876,7.875,105.773,7.807c15.127-0.07,27.017,12.077,26.943,27.523
		C132.646,50.417,120.682,62.442,105.794,62.392z"></path>
<path fill="#1575BB" d="M105.585,39.536c5.47,0.354,9.813,2.692,12.731,7.427c0.567,0.923,1.727,2.117,0.723,3.09
	c-1.268,1.229-2.391-0.188-3.399-0.924c-1.336-0.976-2.534-2.145-3.902-3.063c-5.738-3.854-10.07-3.39-15.086,1.479
	c-1.363,1.325-3.117,4.059-4.646,2.566c-1.811-1.766,0.965-3.718,2.2-5.253C97.079,41.294,101.061,39.82,105.585,39.536z"></path>
<path fill="#1575BB" d="M99.297,33.516c-0.309,2.312-1.713,3.604-3.938,3.489c-1.993-0.103-3.412-1.514-3.437-3.509
	c-0.026-2.244,1.509-3.757,3.699-3.775C97.85,29.701,99.102,31.231,99.297,33.516z"></path>
<path fill="#1575BB" d="M118.383,33.289c-0.077,2.114-1.215,3.55-3.235,3.731c-2.29,0.207-3.827-1.126-3.906-3.482
	c-0.08-2.361,1.439-3.823,3.662-3.808C116.889,29.747,118.137,31.186,118.383,33.289z"></path>
</svg>
<p>Sorry! No results found.</p></div>';
		}
		?>
		</div>
		<div class="paginate">
			<?php echo $this->paginate_function($records_per_page,$page_number,$this->rec_count,$total_pages);?>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output; 
		exit();
	}
	
	
	/* function to calculate the lat long based upon address*/
	public function latlong($city)
	{
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city";
		$json_data = file_get_contents($url);
		$result = json_decode($json_data, TRUE);
		$latitude = $result['results'][0]['geometry']['location']['lat'];
		$longitude = $result['results'][0]['geometry']['location']['lng'];
		return $latitude.','.$longitude;
	}

	/*get current time of employer*/
	public function local_time_of_employer($country,$city)
	{
		//get lat long
		$address=$city.','.$country;
		$address=str_replace(" ","",$address);
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$address";
		
		$json_data = file_get_contents($url);
		$result = json_decode($json_data, TRUE);
		$latitude = $result['results'][0]['geometry']['location']['lat'];
		$longitude = $result['results'][0]['geometry']['location']['lng'];
		
		//get the timezone from current address
		$current_timestamp=time();
		$uri="https://maps.googleapis.com/maps/api/timezone/json?location=".$latitude.",".$longitude."&timestamp=".$current_timestamp."&sensor=false";
		$json = file_get_contents($uri);
		$res = json_decode($json, TRUE);
		
		//set the timezone
		date_default_timezone_set($res['timeZoneId']);
		$date = date('h:i A', time());
		return $date;
	}
	
	/* function for time elapsed string*/
	public function settimezone()
	{
		//echo  $ip = $_SERVER['REMOTE_ADDR'];
		//$details = json_decode(file_get_contents("http://ip-api.com/json/".$ip.""));
		//date_default_timezone_set($details->timezone);
		
		$timezone=$this->TimeZone->get_time_zone();
		date_default_timezone_set($timezone);
	}
	
	public function time_elapsed_string($datetime, $full = false) 
	{
		$line = date('Y-m-d H:i:s');
		$now = new DateTime($line);
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) 
		{
			if ($diff->$k) 
			{
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else 
			{
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
	/*function to get the whether the string is in multidimensioanl array*/
	public function in_array_r($needle, $haystack, $strict = false) 
	{
		foreach ($haystack as $item) 
		{
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict)))
			{
				return true;
			}
		}
		return false;
	}
	
	/* function to get the cities based on id of job*/
	public function get_location_of_job($jobid)
	{
		$city=array();
		$result=$this->Model->Get_all_with_cond('job_id',$jobid,PREFIX.'job_activities');
		foreach($result as $rs)
		{
				$city[]= $rs['city'];
		}
		$cities=implode(',',$city);
		echo $cities;
	}
	/*function which returns the trimmed string*/
	public function truncate($string,$length=100,$append="&hellip;")
	{
		 $string = trim($string);
		 if(strlen($string) > $length) 
		 {
			$string = wordwrap($string, $length);
			$string = explode("\n", $string, 2);
			$string = $string[0] . $append;
		 }
		 return $string;
	}
	
							/*****Job Search Module ends *************/
							
							/*******************Contractor profile Module**********************/

	/* function  for contractor profile and settings*/
	public function contractor_profile_settings()
	{
		$this->load_view('contractor_profile_settings');
	}
	
	/**contractor profile page**/
	public function contractor_profile()
	{
		 /**/
		$url=$_SERVER['REQUEST_URI'];
		$url=explode('/',$url);
		if(isset($url[3]) && !empty($url[3]))
			$this->contractor_view_all($url[3]);
		else
			$this->load_view('contractor_profile');
	}
	
	/*contrator profile view by employer or contractor*/
	public function contractor_view_all($username)
	{
		require_once(APP_DIR.'controllers/contractor/contractor_view_global.php');
	}
	
	public function load_view($view)
	{
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password']))
		{
			/*** if user role is contractor then load page***/
			$role=$this->udata['role'];
			$role_name=$this->Model->Get_column('role_name','roleid',$role,PREFIX.'roles');
			if($role_name['role_name'] == 'contractor')
			{
				$this->loadview('main/header')->render();
				$nav=$this->loadview('contractor/main/navigation');	
				$nav->set('first_name',$this->udata['first_name']);
				$nav->set('last_name',$this->udata['last_name']);
				$nav->set('user_visibility',$this->udata['visibility_status']);
				
				if($view == "contractor_profile_settings")
					$template=$this->loadview('contractor/contractor_views/contractor_profile_settings');
				else
					$template=$this->loadview('contractor/contractor_views/contractor_profile');
				
				$template->set('first_name',$this->udata['first_name']);
				$template->set('last_name',$this->udata['last_name']);
				$template->set('username',$this->udata['username']);
				$template->set('country',$this->udata['country']);
				/*set the speciality*/
				$all_speciality=$this->Model->Get_all_data(PREFIX.'categories');
				$template->set('all_speciality',$all_speciality);
				
				/* set the data if exists already*/
				$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
				if(!empty($data))
				{
					if($data['skills'])
						$template->set('skills',$data['skills']);
					if($data['speciality'])
						$template->set('speciality',$data['speciality']);
					if($data['free_type'])
						$template->set('contractor_type',$data['free_type']);
					if($data['location'])
						$template->set('location',$data['location']);
					if($data['employment_history'])
						$template->set('employment_history',$data['employment_history']);
					if($data['education'])
						$template->set('education',$data['education']);
					if($data['training'])
						$template->set('training',$data['training']);
					if($data['hourly_wages'])
						$template->set('hourly_wages',$data['hourly_wages']);
					if($data['languages'])
						$template->set('languages',$data['languages']);
					if($data['profile_img'])
					{
						$template->set('profile_img',$data['profile_img']);
						$nav->set('profile_img',$data['profile_img']);
					}
					if($data['description'])
						$template->set('description',$data['description']);
					if($data['availability'])
						$template->set('availability',$data['availability']);
					if($data['industries'])
						$template->set('industries',$data['industries']);
					
					if($data['routing_number'])
						$template->set('rn',$data['routing_number']);
					if($data['account_number'])
						$template->set('an',$data['account_number']);
					
				}
				
				 /*get states*/
				 /*get country id*/
				 $country_id=$this->Model->Get_row('sortname',$this->udata['country'],PREFIX.'countries');
				 $country_id=$country_id['id'];
				 $all_results=$this->Model->Get_all_with_cond('country_id',$country_id,PREFIX.'states');
				
				
				$template->set('states',$all_results);
				$nav->render();
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
			/* redirect to login page*/
			$this->redirect('login');
		}
	}
	
	
	
	/*ajax function to save data*/
	public function save_data()
	{
		$user_id=$this->userid;
		$field_name=$_POST['fieldname'];
		$field_val=$_POST['fieldval'];
		
		/*get latitude and longitude of user*/
		if( $field_name == "location")
		{
			/*get country of a user*/	
			$country_code= $this->Model->Get_column('country','id',$this->userid,PREFIX.'users');
			if($country_code['country'] == 'ca')
				$country="canada";
			else
				$country="us";
			$state= $field_val[0];
			$city=  $field_val[1];
			
			/*get lat long of a location*/
			$location=$this->latlong($city.','.$state.','.$country);
			
			$loc_contra=explode(',',$location);
			$latcont=$loc_contra[0];
			$longcont=$loc_contra[1];
		}
		
		/*check if row already exist*/
		$data_exists=$this->Model->Get_row('user_id',$user_id,PREFIX.'contractor_profile');
		if($field_name == "skills" ||  $field_name == "location" || $field_name == "employment_history" || $field_name == "education" || $field_name =="training")
		{
			$field_val=serialize($field_val);
		}
		
		if(!empty($data_exists) || $data_exists != "")
		{
			/* if exists then update*/
			if( $field_name == "location")
				$array=array($field_name=>$field_val,'latitude'=>$latcont,'longitude'=>$longcont);
			else
				$array=array($field_name=>$field_val);
			$this->Model->Update_row($array,'user_id',$user_id,PREFIX.'contractor_profile');
			echo 'updated';
		}
		else
		{
			/*insert*/
			if( $field_name == "location")
				$array=array("user_id"=>$user_id,$field_name=>$field_val,'latitude'=>$latcont,'longitude'=>$longcont);
			else
				$array=array("user_id"=>$user_id,$field_name=>$field_val);
			
			$this->Model->Insert_data($array,PREFIX.'contractor_profile');
			echo "inserted";
		}
		exit;
	}
	
	
	/*ajax function to get the cities*/
	public function get_cities()
	{
		$stateid=$_POST['stateid'];
		$selected_city=$_POST['selected_city'];
		$html='<option value="">Select City</option>';
		$all_results=$this->Model->Get_all_with_cond('state_id',$stateid,PREFIX.'cities','ASC');
		foreach($all_results as $city)
		{
			$selected='';
			if(isset($selected_city) && $selected_city!="")
			{
				if($city['name']==$selected_city){
					$selected="selected"; 
				 }
			}
			 $html.='<option value="'.$city['name'].'" '.$selected.'>'.$city['name'].'</option>';
		}
		echo $html;
		exit();
	}
	
	public function save_image()
	{
		$namefile=$this->udata['username'];
		$image=str_replace(' ','+',$_POST['image']);
		$image=str_replace('data:image/png;base64,','',$image);
		$image=str_replace('data:image/jpeg;base64,','',$image);
		$decode_img=base64_decode($image);
		$uri=ABSPATH.'/static/images/contractor';
		$filenam = glob($uri."/$namefile*.{jpg,gif,png}", GLOB_BRACE);
		foreach ($filenam as $filefound) 
		{
			unlink($filefound);
		}
		$name=$this->udata['username'].'_'.time().'.png';
		
		file_put_contents('static/images/contractor/'.$name,$decode_img);
		/*savbe to db*/
		$data_exists=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
		if(!empty($data_exists) || $data_exists != "")
		{
			/* if exists then update*/
			$array=array('profile_img'=>$name);
			$this->Model->Update_row($array,'user_id',$this->userid,PREFIX.'contractor_profile');
			echo 'updated';
		}
		else
		{
			/*insert*/
			$array=array("user_id"=>$this->userid ,'profile_img'=>$name);
			$this->Model->Insert_data($array,PREFIX.'contractor_profile');
			echo 'inserted';
		}
		exit();
	}
	
	/*Ajax function to save bank details*/
	public function save_bank_details()
	{
		$an=$_POST['an'];
		$rn=$_POST['rn'];
		
		/*decryption method
		$key = pack("H*", "0123456789abcdef0123456789abcdef");
		$iv =  pack("H*", "abcdef9876543210abcdef9876543210");

		$encrypted = base64_decode($_POST["encrypted"]);
		$shown = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
		echo $shown;*/
		//save the details
		
		$data=array('account_number'=>$an,'routing_number'=>$rn);
		$result=$this->Model->Update_row($data,'user_id',$this->userid,PREFIX.'contractor_profile');
		
		echo 'Account Details updated Successfully!!';
		exit();
	}
	
	/*******************Contractor profile Module Ends**********************/
	
	/****************Contractor Job Description Module***************************/
	public function job_description()
	{
		/*job description module*/
		require_once(APP_DIR.'controllers/contractor/job_description.php');
	}
	
	/**ajax to save job*/
	public function save_job()
	{
		$job_id=$_POST['job_id'];
		$contractor_id=$_POST['contr_id'];
		$action=$_POST['action_performed'];
		if($action == 'save_job')
		{
			$data=array('contractor_id'=>$contractor_id,'job_id'=>$job_id,'saved_for'=>'contractor');
			$result=$this->Model->Insert_data($data,PREFIX.'saved_jobs');
			echo $result;
		}
		if($action == 'flex_alert')
		{
			$data=array('alert_type'=>'flex_alert','contractor_id'=>$contractor_id,'job_id'=>$job_id,'alert'=>'1');
			$result=$this->Model->Insert_data($data,PREFIX.'alerts');
			echo $result;
		}
		exit();
	}
	
	public function apply_for_job()
	{
		/*apply for job module*/
		require_once(APP_DIR.'controllers/contractor/apply_job.php');
	}
	
	/*result right after posting the job*/
	public function view_posted_job()
	{
		require_once(APP_DIR.'controllers/contractor/view_posted_job.php');
	}
	
	
	/*ajax function to update the posted job*/
	public function update_posted_job()
	{
		$ap_job_id=$_POST['applied_job_id'];
		$field=$_POST['fieldname'];
		if(is_array($_POST['fieldval']))
			$val=implode(',',$_POST['fieldval']);
		else
			$val=$_POST['fieldval'];
		
		if($field == 'answer')
		{
			$id=$_POST['applied_answer_id'];
			$table=PREFIX.'applied_answers';
		}
		else
		{	
			$id=$_POST['applied_job_id'];
			$table=PREFIX.'applied_jobs';
		}
		 if(isset($_POST['reason']))	
			 $data=array($field=>$val,'reason_withdraw'=>$_POST['reason']);
		else	
			 $data=array($field=>$val);
		
		//save message to conversation table
		if($field == "message")
		{
			/*get the job id*/
			$appl_job_data=$this->Model->Get_row('id',$id,PREFIX.'applied_jobs');
			$job_id=$appl_job_data['job_id'];
			$contractor_id=$appl_job_data['contractor_id'];
			
			$to_id=$this->Model->Get_column('job_author','id',$job_id,PREFIX.'jobs');
			$toid=$to_id['job_author'];
			if(!empty($toid))
			{
				//then check if conversation already exists between the users
				$cond=array('conv_to'=>$toid,'conv_from'=>$contractor_id,'job_id'=>$job_id);
				$cov_data=$this->Model->get_count_with_multiple_cond($cond,PREFIX.'conversation_set');
				$date=strtotime("now");
				if($cov_data == 0)
				{
					//insert to conversation table
					$msg_data=array(
							'conv_to'=>$toid,'conv_from'=>$contractor_id,'job_id'=>$job_id,
							'created_date'=>$date,'modified_date'=>$date
							);
					$conver_id=$this->Model->Insert_data($msg_data,PREFIX.'conversation_set'); 
					if(!empty($conver_id ))
					{
						$msg_insert=array(
							'conv_id'=>$conver_id,
							'to_id'=>$toid,'from_id'=>$contractor_id,
							'message'=>$val,
							'message_time'=>$date
						);
						$conver_id=$this->Model->Insert_data($msg_insert,PREFIX.'message_set'); 
					}
				}
				else
				{
					$conditions=array('conv_to'=>$toid,'conv_from'=>$contractor_id);
					$conv_id=$this->Model->Get_all_with_multiple_cond($conditions,PREFIX.'conversation_set');
					$convid=$conv_id[0]['id'];
					if(!empty($convid ))
					{
						$msg_insert=array(
							'conv_id'=>$convid,
							'to_id'=>$toid,'from_id'=>$contractor_id,
							'message'=>$val,
							'message_time'=>$date
						);
						$conver_id=$this->Model->Insert_data($msg_insert,PREFIX.'message_set'); 
					}
				  }
				}
			
		}
		 /*send notification to employer for the same*/
		$applid_job=$this->Model->Get_column('job_id','id',$ap_job_id,PREFIX.'applied_jobs');
		$job_id=$applid_job['job_id'];
		$employer_id=$this->Model->Get_column('job_author','id',$job_id,PREFIX.'jobs');
		$this->Notification->insertNotification('job_updated',$this->userid,$employer_id['job_author'],0,$ap_job_id);
		
		$result=$this->Model->Update_row($data,'id',$id,$table);

		echo "Your data has been updated";
		exit();
	}
	
	/*update the proposal*/
	public function update_posted_job_proposal()
	{
		$payment_term=$_POST['payment_term'];
		$paytype=$_POST['paytype'];
		$amount=$_POST['amount'];
		$id=$_POST['applied_job_id'];
		
		$data=array('payment_terms'=>$payment_term,'proposal_type'=>$paytype,'proposal_rate'=>$amount);
		$result=$this->Model->Update_row($data,'id',$id,PREFIX.'applied_jobs');
		echo "Your data has been updated";
		exit();
	}
	

/****Job proposals*****/
	public function job_proposals()
	{
		require_once(APP_DIR.'controllers/contractor/job_proposal_contractor.php');
	}
	
	public function Jobs_save()
	{
		/* Jobs Saved by Contractor */
		$this->loadview('main/header')->render();
		$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
		$this->navigation($data);
		$count = $this->Model->get_saved_jobs_count();		// getting count of total rows
		
		$item_per_page = 10;		
		$pages = ceil($count/$item_per_page);	
		
		//$data = $this->Model->get_saved_jobs();
		
		/* print_R($data); */		
		$this->loadview('contractor/contractor_views/saved_jobs')->render();
			$additional ='';
			$additional .= '<script src="'.BASE_URL.'static/js/jquery.bootpag.min.js"></script>';
			$additional .='<script type="text/javascript">
			$(document).ready(function() {		
				$(".contractors-list").load("'.SITEURL.'contractor/get_saved_jobs");  //initial page number to load
				$(".save_pagination").bootpag({
				   total: '.$pages.',
				   page: 1,
				   maxVisible: 5 
				}).on("page", function(e, num){
					e.preventDefault();
					$(".contractors-list").prepend(\'<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>\');
					$(".contractors-list").load("'.SITEURL.'contractor/get_saved_jobs", {\'page\':num});
				});
				
				/* Deleting the Job */
				$(document).on("click",".deleting",function(){
					var Attr = $(this).attr("rel");
					$(this).parent().find(".act").text("Deleting ...");
				});
				
				
			});
			</script>
			';
		$footer = $this->loadview('main/footer');
		$footer->set('additional',$additional);
		$footer->render();
		
	}
	public function get_saved_jobs()
	{
		if(isset($_POST["page"]))
		{
			$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
		}
		else
		{
			$page_number = 1;
		}
		$item_per_page = 10;
		$position = (($page_number-1) * $item_per_page);
		$data = $this->Model->get_saved_jobs($position,$item_per_page);
		
	}
	
	
	/**view contract contractor**/
	public function view_contract()
	{
		require_once(APP_DIR.'controllers/contractor/view_contract_contractor.php');
	}
	
	
	/*Accept/Decline Contract*/
	function contract_status()
	{
		$id=$_POST['contract_id'];
		$status=$_POST['status'];
		
		//update the status
		$data=array('status'=>$status,'modified_date'=>date('Y-m-d H:i:s'));
		$re=$this->Model->Update_row($data,'id',$id,PREFIX.'hire_contractor');
		
		//get the employer/contractor  details
		$contract_details=$this->Model->Get_row('id',$id,PREFIX.'hire_contractor');
		
		// get the employer details
		$job_id=$contract_details['job_id'];
		$job_data=$this->Model->Get_row('id',$job_id,PREFIX.'jobs');
		$employer_details=$this->Model->Get_row('id',$job_data['job_author'],PREFIX.'users');
		
		//get the contractor details
		$contractor_details=$this->Model->Get_row('id',$contract_details['contractor_id'],PREFIX.'users');
		
		
		
		if($status == 1)
		{
			//add it to the contract history if contract is accepted
			$amount=$contract_details['flex_amount'];
			$desc="[[contractor]] accepted [[employer]] offer for a $".$amount." ".$job_data['job_type']."-price project";
			$this->Model->Insert_data(array('contract_id'=>$id,'description'=>$desc),PREFIX.'contract_history');
			
			
			//add the contract activities to the flex_hired_contractor_activity_status table to keep track of each activity
			//get activities associated with contract
			$contract=$this->Model->Get_column('activity_id','id',$id,PREFIX.'hire_contractor');
			$activities=json_decode($contract['activity_id']);
			
			foreach($activities as $activity)
			{
				$data=array('contract_id'=>$id,'activity_id'=>$activity);
				$hcas=$this->Model->Insert_data($data,PREFIX.'hired_contractor_activity_status');
			}
			
			//load the email for the accept proposal
			//load accept  proposal email template and send it to the employer
			$file=APP_DIR.'email_templates/accept_proposal.html';
			$emailBody = file_get_contents($file);
			$search  = array('[[fname]]', '[[lname]]','[[message]]');
			
			//send mail to employer
			$message='Job "'.$job_data['job_title'].'" has been filled by contractor "'. $contractor_details['first_name'].' '.$contractor_details['last_name'] .'".';
			$replace = array($employer_details['first_name'], $employer_details['last_name'],$message);
			$emailBodyemp  = str_replace($search, $replace, $emailBody);
			$this->SendMail->setparameters($employer_details['email'],'Contract Accepted',$emailBodyemp);
			
			//send email to contractor
			$message_to_contractor='Congratulations on getting hired for job "'. $job_data['job_title'].'".';
			$replacestr = array($contractor_details['first_name'], $contractor_details['last_name'],$message_to_contractor);
			$emailBodycontr  = str_replace($search, $replacestr, $emailBody);
			$this->SendMail->setparameters($contractor_details['email'],'Contract Accepted',$emailBodycontr);
			
			//remove the job from public view as contractor is hired for the job
			$toupdate=array('jobjob_status' => '2');
			$this->Model->Update_row($toupdate,'id',$job_id,PREFIX.'jobs');
			
			//if job in active proposals then delete it
			$cond=array('job_id'=>$job_id,'contractor_id'=>$contract_details['contractor_id']);
			$invitations=$this->Model->Get_all_with_multiple_cond($cond,PREFIX.'job_invite');
			if(!empty($invitations))
			{
				//delete invitaion
				$invi_id=$invitations[0]['id'];
				$this->Model->Update_row(array('status'=>1),'id',$invi_id,PREFIX.'job_invite');
			}
		}
		if($status == 2)
		{
			
			//load decline proposal email template and send it to the employer
			$file=APP_DIR.'email_templates/decline_proposal.html';
			$emailBody = file_get_contents($file);
			$search  = array('[[fname]]', '[[lname]]','[[message]]');
			
			//send mail to employer
			$message='"'.$contractor_details['first_name'].' '.$contractor_details['last_name'] .'" has declined your Contract for the job "'. $job_data['job_title'].'".';
			$replace = array($employer_details['first_name'], $employer_details['last_name'],$message);
			$emailBodyemp  = str_replace($search, $replace, $emailBody);
			$this->SendMail->setparameters($employer_details['email'],'Contract Declined',$emailBodyemp);
			
			//send email to contractor
			$message_to_contractor='You have declined the Contract for the job "'. $job_data['job_title'].'".';
			$replacestr = array($contractor_details['first_name'], $contractor_details['last_name'],$message_to_contractor);
			$emailBodycontr  = str_replace($search, $replacestr, $emailBody);
			$this->SendMail->setparameters($contractor_details['email'],'Contract Declined',$emailBodycontr);
		}
		echo "success";
		exit();
	}
	
	/*contractor statastics*/
	public function mystats()
	{
		require_once(APP_DIR.'controllers/contractor/contractor_stats.php');
	}
	
	/*contractor my jobs page*/
	public function my_jobs()
	{
		require_once(APP_DIR.'controllers/contractor/contractor_my_jobs.php');
	}
	
	/*activity detail page for contractor*/
	public function activity_detail()
	{
		require_once(APP_DIR.'controllers/contractor/activity_detail.php');
	}
	
	/*Ajax function to show the activity detail*/
	public function view_activity()
	{
		$activity_id=$_POST['activity_id'];
		$contract_id=$_POST['contract_id'];
		if(!empty($activity_id))
		{
			$activity_detail=$this->Model->Get_row('id',$activity_id,PREFIX.'job_activities');
			$activity_status_detail=$this->Model->Get_all_with_multiple_cond(array('activity_id'=>$activity_id,'contract_id'=>$contract_id),PREFIX.'hired_contractor_activity_status');
			ob_start();
			?>
			<h3>View Activity</h3>
			<p><b>Activity name:</b><?php echo $activity_detail['activity_name']; ?></p>
			<input type="hidden" id="activity_status_id" value="<?php echo $activity_status_detail[0]['id']; ?>">
			<p><b>Select: </b>
				<label class="radio-custom">
					<input type="radio" disabled name="one" <?php if($activity_detail['activity_type'] == "fixed") echo "checked"; ?> value="one1" id="viewPost"> 
					<span class="radio"></span>fixed start and stop time
				</label>
				<label class="radio-custom">
					<input type="radio" <?php if($activity_detail['activity_type'] == "flexible") echo "checked"; ?> disabled name="one" value="one2" id="viewPost"> 
					<span class="radio"></span>flexible start/stop
				</label>
			</p>
			<ul class="activity-list">
				<li>
					<div>
						<svg viewBox="0 8 200 185">
							<use xlink:href="#calendar-icon"></use>
						</svg> Start Day: <?php echo date('m-d-Y',strtotime($activity_detail['start_datetime'])); ?>
					</div>
					<div>
						<svg viewBox="0 10 200 180">
							<use xlink:href="#clock-icon"></use>
						</svg> Start Time: <?php echo date('h:i a',strtotime($activity_detail['start_datetime'])); ?>
					</div>
				</li>
				<li>
					<div>
						<svg viewBox="0 8 200 185">
							<use xlink:href="#calendar-icon"></use>
						</svg> Finish Day: <?php echo date('m-d-Y',strtotime($activity_detail['end_datetime'])); ?>
					</div>
					<div>
						<svg viewBox="0 10 200 180">
							<use xlink:href="#clock-icon"></use>
						</svg> Finish Time: <?php echo date('h:i a',strtotime($activity_detail['end_datetime'])); ?>
					</div>
				</li>
			</ul>
			
			<div class="activity-divider"></div>
			
			<p><b>Address:</b> Street:  <?php echo $activity_detail['street']; ?></p>
			<p><b>City:</b><?php echo $activity_detail['city']; ?> </p>
			<?php 
				//get the state
				$state=$this->Model->Get_row('id',$activity_detail['state'],PREFIX.'states');
			?>
			
			<p><b>State:</b> <?php if(!empty($state)) echo $state['name']; ?></p>
			<p><b>Zip:</b> <?php echo $activity_detail['zip']; ?></p>
			
			<div class="activity-divider"></div>
			
			<p>Contact Name: </p>
			
			<ul class="activity-list">
				<li>First: <?php echo $activity_detail['first_name']; ?></li>
				<li>Last: <?php echo $activity_detail['last_name']; ?></li>
			</ul>
			
			<div class="activity-divider"></div>
			
			<p><b>Contact Information:</b> </p>
			<ul  class="activity-list">
				<li>Phone number: <a href="tel:<?php echo str_replace(' ','',$activity_detail['phone']); ?>"><?php echo $activity_detail['phone']; ?></a></li>
				<li>Email: <a href="mailto:<?php echo $activity_detail['email'] ;?>"><?php echo $activity_detail['email'] ?> </a></li>
			</ul>
			
			<div class="activity-divider"></div>
			
			<p><b>Notes/tasks :</b></p>
			
			<div class="row">
				<div class="col-xs-12">
					<textarea name="" disabled cols="" rows="" class="input" placeholder="Type text"><?php echo $activity_detail['notes']; ?></textarea>
				</div>
			</div>
			
			
			<p><b>Activity Status:</b> <?php echo ($activity_status_detail[0]['status'] == 0)?'Pending':'Completed'; ?></p>
			
			<p><b>Job Expense Report:</b> <a href="<?php echo BASE_URL.'contractor/submit_report/?id='.$activity_status_detail[0]['id']; ?>"><?php echo ($activity_status_detail[0]['job_report_status'] == 0)?'Create':'View'; ?></a> </p>
			
			<?php 
				$intial=0;
				if($activity_status_detail[0]['status'] == 0 && (($activity_status_detail[0]['job_report_status'] == 0) || ($activity_status_detail[0]['job_report_status'] == 1)))
				{
					$amount_due=number_format((float)$activity_detail['job_price'], 2, '.', '');
					$amount_paid=number_format((float)$intial, 2, '.', '');
					//$button='<button type="button" id="view_activity_button" class="btn btn-blue">View Activity </button>';
					$button='<button type="button" class="btn btn-blue" data-toggle="modal" data-target="#withdraw_activity">Withdraw From Activity</button>
					<button type="button" data-status="1" id="activity_status_button" class="btn btn-blue">Move to Completed</button>';
				}
				elseif($activity_status_detail[0]['status'] == 1 && $activity_status_detail[0]['job_report_status'] == 0)
				{
					$amount_due=number_format((float)$activity_detail['job_price'], 2, '.', '');
					$amount_paid=number_format((float)$intial, 2, '.', '');
					$button='<button type="button" id="view_activity_button" class="btn btn-blue">View Activity </button>
					<button type="button" id="complete_job_report" class="btn btn-blue"> Complete Job Report </button>
					<button type="button" data-status="0" id="activity_status_button" class="btn btn-blue">Move Back to Pending</button>';
					
				}
				elseif($activity_status_detail[0]['status'] == 1 && $activity_status_detail[0]['job_report_status'] == 1)
				{
					$amount_due=number_format((float)$activity_detail['job_price'], 2, '.', '');
					$amount_paid=number_format((float)$intial, 2, '.', '');
					$button='<button type="button" id="view_activity_button" class="btn btn-blue">View Activity </button>
					<button type="button" id="view_job_report" class="btn btn-blue"> View Job Report </button>';
				}
				elseif($activity_status_detail[0]['status'] == 1 && $activity_status_detail[0]['job_report_status'] == 2)
				{
					$amount_due=number_format((float)$intial, 2, '.', '');
					$amount_paid=number_format((float)$activity_detail['job_price'], 2, '.', '');
					$button='<button type="button" id="view_activity_button" class="btn btn-blue">View Activity </button>
					<button type="button" id="view_job_report" class="btn btn-blue"> View Job Report </button>';
				}
			?>
			
			
			<p><b>Amount Due:</b><?php if(!empty($amount_due)) echo '$'.$amount_due; ?> </p>
			<p><b>Amount Paid:</b> <?php if(!empty($amount_paid)) echo '$'.$amount_paid; ?>	</p>
		
			<div class="activity-associated-post-btns">
				<?php echo $button; ?>
			  
			</div>
			<?php
			 $returnstring = ob_get_contents();
			 ob_end_clean();
			 echo $returnstring;
			 exit();
		}
	}
	
	/*Ajax function to change the status of the activity*/
	public function hired_activity_status()
	{
		$id=$_POST['id'];
		$activity_status=$_POST['activity_status'];
		if(!empty($id) && ($activity_status== 1 || $activity_status==0))
		{
			$res=$this->Model->Update_row(array('status'=>$activity_status),'id',$id,PREFIX.'hired_contractor_activity_status');
			echo 1;
			exit();
		}	
	}
	
	
	/*Ajax function to withdraw from activity*/
	public function withdraw_activity()
	{
		$id=$_GET['id'];
		if(!empty($id))
		{
			$res=$this->Model->Update_row(array('status'=>2),'id',$id,PREFIX.'hired_contractor_activity_status');
			
			//get the contractor and employer details
			$hired_acti=$this->Model->Get_row('id',$id,PREFIX.'hired_contractor_activity_status');
			
			$contract=$this->Model->Get_row('id',$hired_acti['contract_id'],PREFIX.'hire_contractor');
			$job_id=$contract['job_id'];
			
			$job=$this->Model->Get_column('job_author','id',$job_id,PREFIX.'jobs');
			$cont_id=$contract['contractor_id'];
			$emp_id=$job['job_author'];
			
			//get the final details
			$acti=$this->Model->Get_column('activity_name','id',$hired_acti['activity_id'],PREFIX.'job_activities');
			
			//store it in contract history table
			$history_array=array(
			'contract_id'=>$hired_acti['contract_id'],
			'description'=>'[[contractor]] withdraw activity '.$acti['activity_name'].'');
			$this->Model->Insert_data($history_array,PREFIX.'contract_history');
			
			$contractor=$this->Model->Get_row('id',$cont_id,PREFIX.'users');
			$employer=$this->Model->Get_row('id',$emp_id,PREFIX.'users');
			
			//send mail to employer
			//load template
			$file=APP_DIR.'email_templates/withdraw_activity_employer.html';
			$emailBody = file_get_contents($file);
			
			
			$search  = array('[[fname]]', '[[lname]]','[[activity_name]]','[[contractor]]');
			$replace = array($employer['first_name'], $employer['last_name'],$acti['activity_name'],$contractor['first_name'].' '.$contractor['last_name']);
			$emailBodyemp  = str_replace($search, $replace, $emailBody);
			
			$this->SendMail->setparameters($employer['email'],'Activity Withdrawn',$emailBodyemp);
			
			//send email to contractor
			//load template
			$filecon=APP_DIR.'email_templates/withdraw_activity_contractor.html';
			$emailBodycon = file_get_contents($filecon);
			
			$searchc  = array('[[fname]]', '[[lname]]','[[activity_name]]');
			$replacestrc = array($contractor['first_name'], $contractor['last_name'],$acti['activity_name']);
			$emailBodycontr  = str_replace($searchc, $replacestrc, $emailBodycon);
			
			$this->SendMail->setparameters($contractor['email'],'Activity Withdrawn',$emailBodycontr);
			echo 1;
		}
		exit();
	}
	
	/*Submit job report*/
	public function submit_report()
	{
		require_once(APP_DIR.'controllers/contractor/submit_job_report.php');
	}
	
	/*View Dispute*/
	public function view_dispute()
	{
		require_once(APP_DIR.'controllers/contractor/submit_job_report.php');
	}
	
	
	
	//hour minute drodpwn
	function timeSelect($name,$mode,$selected,$disabled="") 
	{
		if($mode)
		{
			$mode = 24;
			$default_select="Select Hour";
		}
		else 
		{
			$mode = 60;
			$default_select="Select Minutes";
		}
		
		echo '<select name="'.$name.'" '.$disabled.'>';
		echo '<option value="-1">'.$default_select.'</option>';
	 
		for ($i=0;$i<$mode;$i++) 
		{
			if($i <=9) 
				$i = "0".$i;
			
			if($i == $selected) 
				echo '<option selected="selected" value="'.$i.'" >'.$i.'</option>';				
			else
				echo '<option value="'.$i.'" >'.$i.'</option>';
		}
		echo '</select>';
	}
	
	/*company feedback*/
	public function company_feedback()
	{
		require_once(APP_DIR.'controllers/contractor/company_feedback.php');
	}
	
	/*contractot my Job Reports section*/
	public function my_job_reports()
	{
		require_once(APP_DIR.'controllers/contractor/my_job_reports.php');
	}
	
	/*contractor Report Section*/
	public function reports()
	{
		require_once(APP_DIR.'controllers/contractor/reports.php');
	}
	
	/*function to get all previous months from a current month*/
	function get_previous_months($date="",$months_array=array())
	{
		$submonth = date("F", strtotime ( '-1 month' , strtotime ($date))) ;
		
		/*check for year*/
		$current_year=date('Y');
		$previousmonth_year=date("Y", strtotime ( '-1 month' , strtotime ($date))) ;
		
		if($current_year == $previousmonth_year)
		{
			$months_array[]=$submonth ;
			//get the date of previous month
			$dat=date("d-m-Y", strtotime ( '-1 month' , strtotime ($date))) ;
			return $this->get_previous_months($dat,$months_array);
		}
		else
		{
			return $months_array;
		}
	}
	
	/*AJAX FUNCTION FOR THE DATE RANGE FILTER REPORTS SECTION*/
	public function  daterangefilter()
	{
		
		$range=$_GET['date_range'];
		if(!empty($range))
		{
			$type=str_replace("#","",$_GET["type"]);
			ob_start();
			//get the contracts for the contractor
			$contracts=$this->Model->Get_all_with_cond('contractor_id',$this->userid,PREFIX.'hire_contractor');
			if(!empty($contracts))
			{
				foreach($contracts as $contract)
				{
					$contract_id=$contract['id'];
						
					//get the job name
					$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
				
					//get the company name
					$company=$this->Model->Get_row('id',$job['job_author'],PREFIX.'users');
					
					//check whether the job is hourly or fixed
					$desc=$this->Model->Get_row('id',$contract['applied_job_id'],PREFIX.'applied_jobs');
					if(empty($desc['proposal_type']))
						$proposal_type=ucfirst($job['job_type']);
					else
						$proposal_type=$desc['proposal_type'];
					
					//check the expense details of the contract
					$expenses=json_decode($contract['external_expanditure']);
					$ex="";
					foreach($expenses as $expense)
					{
						$ex.=ucfirst($expense->name).',';
					}
					
					/*get the records for this week*/
					if($range == "this-week")
					{
						$query="SELECT * FROM ".PREFIX."hired_contractor_activity_status WHERE yearweek(DATE(`modified_date`), 1) = yearweek(curdate(), 1) AND contract_id=".$contract_id." AND status=1 AND job_report_status=2 AND dispute_status=0";
					}
					elseif($range == "last-week")
					{
						$query="SELECT * FROM ".PREFIX."hired_contractor_activity_status WHERE `modified_date` >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND `modified_date` < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY AND contract_id=".$contract_id." AND status=1 AND job_report_status=2 AND dispute_status=0";
					}
					elseif($range == "this-month")
					{
						$query="SELECT * FROM ".PREFIX."hired_contractor_activity_status WHERE MONTH(`modified_date`) = MONTH(CURRENT_DATE()) AND contract_id=".$contract_id." AND status=1 AND job_report_status=2 AND dispute_status=0";
					}
					elseif($range == "last-month")
					{
						$query="SELECT * FROM ".PREFIX."hired_contractor_activity_status WHERE YEAR(`modified_date`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(`modified_date`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND contract_id=".$contract_id." AND status=1 AND job_report_status=2 AND dispute_status=0";
					}
					elseif(strpos($range,"statement") !== false)
					{
						$month=str_replace('statement-','',$range);
						$date = date_parse($month);
						$month=$date['month'];
						$query="SELECT * FROM ".PREFIX."hired_contractor_activity_status WHERE MONTH(`modified_date`) = ".$month." AND contract_id=".$contract_id." AND status=1 AND job_report_status=2 AND dispute_status=0";
					}
					
					$completed=$this->Model->filter_data($query);
					if(!empty($completed))
					{
						$com=array();
						$tra=array();
						foreach($completed as $c)
						{
							//get the activity detail
							$activity=$this->Model->Get_row('id',$c['activity_id'],PREFIX.'job_activities');
							
							//get amount from the report status
							$activity_re=$this->Model->Get_row('activity_status_id',$c['id'],PREFIX.'hired_contractor_activity_report');
							
							$total=$activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
							$date=date('m/d/y',strtotime($c['modified_date']));
							$job_title=$job['job_title'];
							$company=$company['company_name'];
							
							if($type == "transactions")
							{
							?>
								<tr>
									<td scope="col"><?php echo $date; ?></td>
									<td scope="col"><?php echo rtrim($ex,','); ?></td>
									<td scope="col">Paid (<?php echo $proposal_type; ?>)</td>
									<td scope="col"><?php echo $job_title; ?></td>
									<td scope="col"><?php echo $company; ?></td>
									<td scope="col">$<?php echo $total; ?></td>
									<td scope="col">0000</td>
								</tr>
							<?php
							}
							elseif($type == "expenseReports")
							{
								?>
								<tr>
									<td scope="col"><?php echo $date; ?></td>
									<td scope="col"><?php echo $job_title; ?></td>
									<td scope="col"><?php echo $activity['activity_name']; ?></td>
									<td scope="col"><?php echo $company; ?></td>
									<td scope="col">$<?php echo $total; ?></td>
									<td scope="col"><?php echo $activity['id']; ?></td>
								</tr>
							<?php
							}
						}
					}
				}
			}
		}
		$res = ob_get_contents();
		ob_end_clean();
		echo $res;
		exit();
	}
	
	/*AJAX Function to get the data past weeks/months projected and done*/
	public function activity_sheet()
	{	
		/*get contracts for the current contractor*/
		$contracts=$this->Model->Get_all_with_cond('contractor_id',$this->userid,PREFIX.'hire_contractor');
		
		/*Projected or actual*/
		$timespace=$_POST['timespace']; 
		
		/*monthly or weekly*/
		$timeduration=$_POST['timeduration'];
	
		$header="";
		$rows="";
		if($timespace == "Actual")
		{
			if($timeduration == "Monthly")
			{
				$six=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("-6 months")))));
				$five=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("-5 months")))));
				$four=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("-4 months")))));
				$three=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("-3 months")))));
				$two=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("-2 months")))));
				$one=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("-1 months")))));
				$current=strtolower(strftime("%B",strtotime(date('Y/m/d'))));
				
				$total=0;
				if(!empty($contracts))
				{
					foreach($contracts as $contract)
					{
						$contractid=$contract['id'];
						$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
						
						$qry='SELECT * FROM `flex_hired_contractor_activity_status` WHERE `modified_date` > DATE_SUB(now(), INTERVAL 6 MONTH) AND contract_id ='.$contractid.' AND status=1 AND job_report_status=2 AND dispute_status=0';
						
						$rows="<tr>";
						$monthly_res=$this->Model->filter_data($qry);
						if(!empty($monthly_res))
						{
							$initial="";
							$sixbefore=0;
							$fivebefore=0;
							$fourbefore=0;
							$threebefore=0;
							$twobefore=0;
							$onebefore=0;
							$currentbefore=0;
							$end="";
							
							$job_title=$job['job_title'];
							$rows.="<td>".$job_title."</td>";
							foreach($monthly_res as $m)
							{
								//get amount from the report status
								$activity_re=$this->Model->Get_row('activity_status_id',$m['id'],PREFIX.'hired_contractor_activity_report');
								$total += $activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
								
								$month=strtolower(strftime("%B",strtotime($m['modified_date'])));
								
								if($month == $six)
									$sixbefore =$sixbefore + 1;	
								if($month == $five)
									$fivebefore =$fivebefore + 1;
								if($month == $four)
									$fourbefore =$fourbefore + 1;
								if($month == $three)
									$threebefore =$threebefore + 1;
								if($month == $two)
									$twobefore =$twobefore + 1;
								if($month == $one)
									$onebefore =$onebefore + 1;
								if($month == $current)
									$currentbefore =$currentbefore + 1;
							}
							$rows.="<td>".$sixbefore."</td>";
							$rows.="<td>".$fivebefore."</td>";
							$rows.="<td>".$fourbefore."</td>";
							$rows.="<td>".$threebefore."</td>";
							$rows.="<td>".$twobefore."</td>";
							$rows.="<td>".$onebefore."</td>";
							$rows.="<td>".$currentbefore."</td>";
							$rows.="<td>".$total."</td>";
						}
						$rows.="</tr>";
					}
				}
			}
			elseif($timeduration == "Weekly")
			{
				$six=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-6 days")))));
				$five=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-5 days")))));
				$four=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-4 days")))));
				$three=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-3 days")))));
				$two=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-2 days")))));
				$one=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("-1 days")))));
				$current=strtolower(strftime("%A",strtotime(date('Y/m/d'))));
				
				$total=0;
				if(!empty($contracts))
				{
					foreach($contracts as $contract)
					{
						$contractid=$contract['id'];
						$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
						
						$qry='SELECT * FROM `flex_hired_contractor_activity_status` WHERE `modified_date` > DATE(NOW()) - INTERVAL 7 DAY AND contract_id ='.$contractid.' AND status=1 AND job_report_status=2 AND dispute_status=0';
						
						$rows="<tr>";
						$weekly_results=$this->Model->filter_data($qry);
						if(!empty($weekly_results))
						{
							$initial="";
							$sixbefore=0;
							$fivebefore=0;
							$fourbefore=0;
							$threebefore=0;
							$twobefore=0;
							$onebefore=0;
							$currentbefore=0;
							$end="";
							
							$job_title=$job['job_title'];
							$rows.="<td>".$job_title."</td>";
							foreach($weekly_results as $m)
							{
								//get amount from the report status
								$activity_re=$this->Model->Get_row('activity_status_id',$m['id'],PREFIX.'hired_contractor_activity_report');
								$total += $activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
								
								$day=strtolower(strftime("%A",strtotime($m['modified_date'])));
								
								if($day == $six)
									$sixbefore =$sixbefore + 1;	
								if($day == $five)
									$fivebefore =$fivebefore + 1;
								if($day == $four)
									$fourbefore =$fourbefore + 1;
								if($day == $three)
									$threebefore =$threebefore + 1;
								if($day == $two)
									$twobefore =$twobefore + 1;
								if($day == $one)
									$onebefore =$onebefore + 1;
								if($day == $current)
									$currentbefore =$currentbefore + 1;
							}
							$rows.="<td>".$sixbefore."</td>";
							$rows.="<td>".$fivebefore."</td>";
							$rows.="<td>".$fourbefore."</td>";
							$rows.="<td>".$threebefore."</td>";
							$rows.="<td>".$twobefore."</td>";
							$rows.="<td>".$onebefore."</td>";
							$rows.="<td>".$currentbefore."</td>";
							$rows.="<td>".$total."</td>";
						}
						$rows.="</tr>";
					}
				}
			}
			
			$header='<th scope="col">Weekly Activity Sheet</th>
					<th>'.ucfirst($six).'</th>
					<th>'.ucfirst($five).'</th>
					<th>'.ucfirst($four).'</th>
					<th>'.ucfirst($three).'</th>
					<th>'.ucfirst($two).'</th>
					<th>'.ucfirst($one).'</th>
					<th>'.ucfirst($current).'</th>
					<th scope="col">Amount Owned</th>';
		}
		elseif($timespace == "Projected")
		{
			if($timeduration == "Monthly")
			{
				$monthly=array();
				$current=strtolower(strftime("%B",strtotime(date('Y/m/d'))));
				$one=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("+1 months")))));
				$two=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("+2 months")))));
				$three=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("+3 months")))));
				$four=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("+4 months")))));
				$five=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("+5 months")))));
				$six=strtolower(strftime("%B",strtotime(date('Y/m/d',strtotime("+6 months")))));
				
				$total=0;
				if(!empty($contracts))
				{
					foreach($contracts as $contract)
					{
						$contractid=$contract['id'];
						$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
						
						$qry='SELECT * FROM `flex_hired_contractor_activity_status` WHERE `modified_date` < DATE(NOW()) + INTERVAL 6 MONTH AND contract_id ='.$contractid.' AND status=0 AND (job_report_status=0 OR job_report_status=1)';
						
						$rows="<tr>";
						$monthly_res=$this->Model->filter_data($qry);
						if(!empty($monthly_res))
						{
							$initial="";
							$sixafter=0;
							$fiveafter=0;
							$fourafter=0;
							$threeafter=0;
							$twoafter=0;
							$oneafter=0;
							$currentafter=0;
							$end="";
							
							$job_title=$job['job_title'];
							$rows.="<td>".$job_title."</td>";
							foreach($monthly_res as $m)
							{
								//get amount from the report status
								$activity_re=$this->Model->Get_row('activity_status_id',$m['id'],PREFIX.'hired_contractor_activity_report');
								$total += $activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
								
								$month=strtolower(strftime("%B",strtotime($m['modified_date'])));
								
								if($month == $six)
									$sixafter =$sixafter + 1;	
								if($month == $five)
									$fiveafter =$fiveafter + 1;
								if($month == $four)
									$fourafter =$fourafter + 1;
								if($month == $three)
									$threeafter =$threeafter + 1;
								if($month == $two)
									$twoafter =$twoafter + 1;
								if($month == $one)
									$oneafter =$oneafter + 1;
								if($month == $current)
									$currentafter =$currentafter + 1;
							}
							
							$rows.="<td>".$currentafter."</td>";
							$rows.="<td>".$oneafter."</td>";
							$rows.="<td>".$twoafter."</td>";
							$rows.="<td>".$threeafter."</td>";
							$rows.="<td>".$fourafter."</td>";
							$rows.="<td>".$fiveafter."</td>";
							$rows.="<td>".$sixafter."</td>";
							$rows.="<td>".$total."</td>";
						}
						$rows.="</tr>";
					}
				}
				
			}
			elseif($timeduration == "Weekly")
			{
				$weekly=array();
				$current=strtolower(strftime("%A",strtotime(date('Y/m/d'))));
				$one=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("+1 days")))));
				$two=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("+2 days")))));
				$three=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("+3 days")))));
				$four=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("+4 days")))));
				$five=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("+5 days")))));
				$six=strtolower(strftime("%A",strtotime(date('Y/m/d',strtotime("+6 days")))));
				
				$total=0;
				if(!empty($contracts))
				{
					foreach($contracts as $contract)
					{
						$contractid=$contract['id'];
						$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
						
						$qry='SELECT * FROM `flex_hired_contractor_activity_status` WHERE `modified_date`  BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) AND contract_id ='.$contractid.' AND status=0 AND (job_report_status=0 OR job_report_status=1)';
						
						$rows="<tr>";
						$weekly_results=$this->Model->filter_data($qry);
						if(!empty($weekly_results))
						{
							$initial="";
							$sixafter=0;
							$fiveafter=0;
							$fourafter=0;
							$threeafter=0;
							$twoafter=0;
							$oneafter=0;
							$currentafter=0;
							$end="";
							
							$job_title=$job['job_title'];
							$rows.="<td>".$job_title."</td>";
							foreach($weekly_results as $m)
							{
								//get amount from the report status
								$activity_re=$this->Model->Get_row('activity_status_id',$m['id'],PREFIX.'hired_contractor_activity_report');
								$total += $activity_re['total_activity_amount'] + $activity_re['total_expense_amount'];
								
								$day=strtolower(strftime("%A",strtotime($m['modified_date'])));
								
								if($day == $six)
									$sixafter =$sixafter + 1;	
								if($day == $five)
									$fiveafter =$fiveafter + 1;
								if($day == $four)
									$fourafter =$fourafter + 1;
								if($day == $three)
									$threeafter =$threeafter + 1;
								if($day == $two)
									$twoafter =$twoafter + 1;
								if($day == $one)
									$oneafter =$oneafter + 1;
								if($day == $current)
									$currentafter =$currentafter + 1;
							}
							
							$rows.="<td>".$currentafter."</td>";
							$rows.="<td>".$oneafter."</td>";
							$rows.="<td>".$twoafter."</td>";
							$rows.="<td>".$threeafter."</td>";
							$rows.="<td>".$fourafter."</td>";
							$rows.="<td>".$fiveafter."</td>";
							$rows.="<td>".$sixafter."</td>";
							$rows.="<td>".$total."</td>";
						}
						$rows.="</tr>";
					}
				}
				
			}
				
			$header='<th scope="col">Weekly Activity Sheet</th>
					<th>'.ucfirst($current).'</th>
					<th>'.ucfirst($one).'</th>
					<th>'.ucfirst($two).'</th>
					<th>'.ucfirst($three).'</th>
					<th>'.ucfirst($four).'</th>
					<th>'.ucfirst($five).'</th>
					<th>'.ucfirst($six).'</th>
					<th scope="col">Amount Owned</th>';
		}
		$output['header']=$header;
		$output['rows']=$rows;
		echo json_encode($output);
		exit();
	}
	
	/*Public funciton  all Notifications*/
	public function notifications()
	{
		require_once(APP_DIR.'controllers/contractor/all_notifications.php');
	}
	
	/* function to delete notification*/
	public function deleteNoti()
	{
		$id=$_POST['notifi_id'];
		if(!empty($id))
		{
			$this->Model->delete_data('id',$id,PREFIX.'notification_message');
		}
		exit();
	}
}