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
	public function __construct() 
	{

		$this->Validator = $this->loadHelper('validator');
		$this->SendMail=$this->loadHelper('sendmail');
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
		$nav->render();
	}
	
	/*no access view*/
	public function no_access()
	{
		$this->loadview('main/header')->render();
		$this->loadview('Employer/postjob/navigation')->render();
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
				$searchField = $_SERVER['QUERY_STRING'];
				$searchField=urldecode($searchField);
				/*convert to array*/
				if($searchField != "")
				{
					$array=explode ('&',$searchField);
					foreach($array as $a)
					{
						$gf[] = list ($k,$v) = explode ('=',$a);
					}
					$searcharray = array();
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
			
			if(!empty($jobidarray))
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
			/*client history*/
			if (array_key_exists('client_history', $finalarray)) 
			{
				$client_history=$finalarray['client_history'];
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
						<p class="job-details"><?php echo ucfirst($job['job_type']); ?> - Budget: $<?php echo number_format($job['job_price']); ?> - Posted <?php echo $this->time_elapsed_string('@'.$job['job_created'].'');?></p>
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
			echo "No Results Found !!";
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

	
	/* function for time elapsed string*/
	public function settimezone()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$details = json_decode(file_get_contents("http://ip-api.com/json/".$ip.""));
		date_default_timezone_set($details->timezone);
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
				
				if($view == "contractor_profile_settings")
					$template=$this->loadview('contractor/contractor_views/contractor_profile_settings');
				else
					$template=$this->loadview('contractor/contractor_views/contractor_profile');
				
				$template->set('first_name',$this->udata['first_name']);
				$template->set('last_name',$this->udata['last_name']);
				$template->set('username',$this->udata['username']);
				
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
		 
		$result=$this->Model->Update_row($data,'id',$id,$table);
		echo "Your data hs been updated";
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
		echo "Your data hs been updated";
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
		
		$item_per_page = 2;		
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
		$item_per_page = 2;
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
			$toupdate=array('job_visibility' => 'none');
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
			
			<p><b>Job Expense Report:</b> <a href="#"><?php echo ($activity_status_detail[0]['job_report_status'] == 0)?'Create':'View'; ?></a> </p>
			
			<?php 
				$intial=0;
				if($activity_status_detail[0]['status'] == 0 && $activity_status_detail[0]['job_report_status'] == 0)
				{
					$amount_due=number_format((float)$activity_detail['job_price'], 2, '.', '');
					$amount_paid=number_format((float)$intial, 2, '.', '');
					$button='<button type="button" id="view_activity_button" class="btn btn-blue">View Activity </button>
					<button type="button" class="btn btn-blue" data-toggle="modal" data-target="#withdraw_activity">Withdraw From Activity</button>
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
			  <!--<button type="button" class="btn btn-blue">Close</button>
			  <button type="submit" class="btn btn-blue">Create Job Report</button>
			  <button type="button" class="btn btn-blue">Move to Pending</button>-->
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
}