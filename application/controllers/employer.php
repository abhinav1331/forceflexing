<?php 

class Employer extends Controller
{
	public $Validator;
	public $Model;	
	public $EModel;	
	public $JModel;	
	public $session;
	public $industries;
	public function __construct() {

		$this->Validator = $this->loadHelper('validator');
		$this->session = $this->loadHelper('session_helper');
		$this->industries = $this->loadHelper('industries');
		$this->options = $this->loadHelper('options');
		$this->Model = $this->loadModel('Postjob_model','employer');
		$this->EModel = $this->loadModel('employers','employer');
		$this->JModel = $this->loadModel('jobs','employer');
		
		
	}
	public function job_report() {
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) {
			$this->loadview('main/header')->render();
			$this->loadview('Employer/job_report/navigation')->render();			
			$this->loadview('Employer/job_report/index')->render();	
			$this->loadview('main/footer')->render();	
		} else {
			$this->redirect('');
		}
	}

	public function recomended_contractor() {
		$instance = $this;



		/****************************************Distance Fyunction ****************************************/

			function distance($lat1, $lon1, $lat2, $lon2, $unit) {
				$theta = $lon1 - $lon2;
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				$dist = acos($dist);
				$dist = rad2deg($dist);
				$miles = $dist * 60 * 1.1515;
				$unit = strtoupper($unit);
				if ($unit == "K") {
					return ($miles * 1.609344);
				} else if ($unit == "N") {
					return ($miles * 0.8684);
				} else {
					return $miles;
				}
			}

		/****************************************Distance Fyunction ****************************************/
		if (isset($_COOKIE['force_username']) || isset($_SESSION['force_username'])) {
			if (isset($_COOKIE['force_username'])) {
				$username = $_COOKIE['force_username'];
			} else {
				$username = $_SESSION['force_username'];
			}
			$current_user_data=$this->Model->login_user($username);
		
			//if user is logged in
			if(isset($current_user_data) && !empty($current_user_data))
			{
				$current_user_data['role'];
				
				$role_name=$this->Model->Get_column('role_name','roleid',$current_user_data['role'],PREFIX.'roles');
				//if employed then load page 
				if($role_name['role_name'] == 'employer')
				{
					if(isset($_SESSION['invite_jobs'])) { 
						$userrecommendedData = array();
						$jobactivityRecord = $this->Model->get_Data_table(PREFIX.'job_activities','job_id',$_SESSION['invite_jobs'][0]['id']);
						$contractorRecord = $this->Model->Get_all_data(PREFIX.'contractor_profile');
						/*echo "<pre>";
						print_r($_SESSION['invite_jobs']);
						echo "</pre>";*/
						foreach($jobactivityRecord as $jobactivityRecords) {
							$job_location = $_SESSION['invite_jobs'][0]['job_location'];
							$job_location = $_SESSION['invite_jobs'][0]['job_location'];
							$jobs_speciality = $_SESSION['invite_jobs'][0]['jobs_speciality'];
							$job_type = $_SESSION['invite_jobs'][0]['job_type'];
							$job_price = $_SESSION['invite_jobs'][0]['job_price'];
							$jobactivityRecords['latitude'];
							$jobactivityRecords['longitude'];
							foreach($contractorRecord as $contractorRecords) {
								$contlatitude = $contractorRecords['latitude'];
								$contlongitude = $contractorRecords['longitude'];
								$speciality = $contractorRecords['speciality'];
								$hourly_wages = $contractorRecords['hourly_wages'];
								/*echo "<pre>";
								print_r($contractorRecords);
								echo "</pre>";*/
								$milesCount = distance(floatval($contlatitude) , floatval($contlongitude) , floatval($jobactivityRecords['latitude']) , floatval($jobactivityRecords['longitude']) , "M");
								if($milesCount <= $job_location) {
									$userrecommendedData[] = $contractorRecords;
								}
								if($jobs_speciality == $speciality) {
									$userrecommendedData[] = $contractorRecords;
								}
								if($job_type == "hourly") {
									if($job_price <= $hourly_wages) {
									$userrecommendedData[] = $contractorRecords;
									}
								}
							}
						}
						// $userrecommendedData = array_unique($userrecommendedData);
						$tryArray = array();
						$finalArray = array();
						foreach ($userrecommendedData as $key => $value) {
							
							if (!in_array($value['id'], $tryArray)) {
								$finalArray[] = $value;
								$tryArray[] = $value['id'];
							}
						}

						/*echo "<pre>";
						print_r($finalArray);
						echo "</pre>";*/

						$this->loadview('main/header')->render();
						$this->loadview('Employer/postjob/navigation')->render();

						$template = $this->loadview('Employer/postjob/recomended-contractor');
						$template->set("employerId",$_SESSION['invite_jobs']);
						$template->set("recommendedData",$finalArray);
						$template->set("instance",$instance);
						$template->render();

						$modalTemplate = $this->loadview('Employer/postjob/modal');
						$modalTemplate->set("recommendMessageContact","modal");
						$modalTemplate->set("userId",$current_user_data['id']);
						$modalTemplate->render();
						$this->loadview('main/footer')->render();
					} else {
						$this->loadview('main/header')->render();
						$this->loadview('Employer/postjob/navigation')->render();
						$this->loadview('main/noaccess')->render();
						$this->loadview('main/footer')->render();
					}
				} else {
					$this->redirect();
				}
			} else {
				$this->redirect();
			}
		} else {
			$this->redirect();
		}


	}

		function userDetails($userId) {
			$getUserRecord = $this->Model->get_Data_table(PREFIX.'users','id',$userId);
			return $getUserRecord[0]['first_name']." ".$getUserRecord[0]['last_name'];
		}
	
	public function searchContractor()
	{
		if(isset($_POST['search']) && $_POST['search'] == 'ContractorSearch')
		{
			$results = $this->EModel->searchContractor($_POST['content']);
			print_r($results);
		}
		else
		{
			$loc = $this->getLocation();
			/* Employer Profile Page */		
			$is_login = $this->is_login();	//		Check User is login Or Not
			$this->loadview('main/header')->render();
			$this->loadview('Employer/postjob/navigation')->render();
			
			
			
			$index = $this->loadview('Employer/search/index');
			$index->set('locations',$loc);			
			$index->render();
			
			$additional ='';
			$additional .= '<script src="'.BASE_URL.'static/js/jquery.bootpag.min.js"></script>';
			$additional .='<script type="text/javascript">
			$(document).ready(function() {
				$("#results").load("http://force.imarkclients.com/employer/getAllContractors");  //initial page number to load
				$(".contractor-pagination").bootpag({
				   total: 5,
				   page: 1,
				   maxVisible: 3 
				}).on("page", function(e, num){
					e.preventDefault();
					$("#results").prepend(\'<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>\');
					$("#results").load("http://force.imarkclients.com/employer/getAllContractors", {\'page\':num});
				}); 

			}); 
			</script>';
			$footer = $this->loadview('main/footer');
			$footer->set('additional',$additional);
			$footer->render();
		}
		
	}
	
	public function getAllContractors()
	{
		if(isset($_POST["page"])){
			$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
		}else{
			$page_number = 1;
		}
		$item_per_page = 2;
		//get current starting point of records
		$position = (($page_number-1) * $item_per_page);
		$this->EModel->getContractors($position, $item_per_page);	
	}
	
	private function is_login()
	{
		/* Check Session and Cookies */
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) 
		{
			return true;
		}
		else
		{
			$this->redirect('login');
		}
	}
	
	public function getContractorDetails()
	{
		$event = $_POST['event'];
		$jobId = $_POST['job_id'];
		$this->basic_details($event,$jobId);
		$this->more_details($event,$jobId);
	}
	public function basic_details($event,$jobId)
	{
		$getUserRecord = $this->Model->get_Data_table(PREFIX.'contractor_profile','user_id',$event);

		$case_1 = $this->Model->check_contractor_saved_check(PREFIX.'saved_jobs',$event,$jobId);
		/*echo "<pre>";
		print_r($case_1);
		echo "</pre>";*/

		if($getUserRecord[0]['profile_img'] == "") {
			$url = "http://placehold.it/240x240&amp;text=No image found";
		} else {
			$url = BASE_URL."static/images/contractor/".$getUserRecord[0]['profile_img'];
		}
		if($getUserRecord[0]['skills'] != "") {
			$skillsArray = unserialize($getUserRecord[0]['skills']);
			$skills = implode(" , ", $skillsArray);
		} else {
			$skills = "";
		}
		if($getUserRecord[0]['industries'] != "") {
			$industriesArray = explode(",", $getUserRecord[0]['industries']);

		} else {
			$industriesArray = "";
		}
		if($getUserRecord[0]['location'] != "") {
			$locationArray = unserialize($getUserRecord[0]['location']);
			$state= $locationArray[0];
			$getcountryCode = $this->Model->get_Data_table(PREFIX.'states','name',$state);
			$getcountryName = $this->Model->get_Data_table(PREFIX.'countries','id',$getcountryCode[0]['country_id']);
			$getNameCountry = $getcountryName[0]['sortname'];
		} 

		?>
			<div class="profile-details saved">
			    <div class="add-avatar">
			      <div class="avatar-set"><img src="<?php echo $url; ?>" alt=""></div>
			    </div>
			    <div class="add-personal-details">
			      <h2 class="pro-title"><?php echo $this->userDetails($getUserRecord[0]['user_id']); ?> <span class="pro-price-range">$<?php echo $getUserRecord[0]['hourly_wages']; ?> /hr</span></h2>
			      <p class="pro-skills"><?php echo $skills; ?></p>
			      <?php 
			      	if($getUserRecord[0]['location'] != "") {
			      		?>
			      			<p class="pro-location"><?php echo $state; ?>, <?php echo $getNameCountry; ?></p>
			      		<?php
			      	}
			       ?>
					<?php 
						if(!empty($industriesArray)) {
							?>
								<p class="pro-industries">
									<?php 
										if(count($industriesArray) < 4 && count($industriesArray) != 0) {
											$newArray = array_slice($industriesArray,0,count($industriesArray));
										} else {
											$newArray = array_slice($industriesArray,0,3);
										}
									 ?>
									<?php 
									foreach ($newArray as $key => $industries) {
									?>
										<span class="industry-tag"><?php echo $industries; ?></span>
									<?php
									}
									?>
								</p>
						<div>
							<?php 
								if(count($industriesArray) > 4) {
									$newArray11 = array_slice($industriesArray,4,count($industriesArray));
							 ?>
								<div class="pro-more-content" style="display:none;">
								<?php 
								foreach ($newArray11 as $key => $industries) {
								?>
								<span class="industry-tag"><?php echo $industries; ?></span>
								<?php
								}
								?>
								</div>
								<a onclick="proMoreToggle(this);" id="proMoreToggle" href="javascript:void(0);" class="pro-more-toggle"><span class="sr-only">View More</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a> </div>
							<?php } ?>
							<?php
						}
					 ?>
			     
			    </div>
			</div>
		<?php
	}
	public function more_details($event,$jobId)
	{
		$getUserRecord = $this->Model->get_Data_table(PREFIX.'contractor_profile','user_id',$event);

		$case_1 = $this->Model->check_contractor_saved_check(PREFIX.'saved_jobs',$event,$jobId);
		$employment_history = unserialize($getUserRecord[0]['employment_history']);
		$education = unserialize($getUserRecord[0]['education']);
		?>

          <div class="more-details"
            <article class="ff-description">
              <h3>Description</h3>
              <p><?php echo $getUserRecord[0]['description']; ?></p>
               
            </article>
            <div class="work-history-feedback">
              <div class="hdr-work-feedback clearfix">
                <h2>Work history and feedback</h2>
                <select class="input medium inline">
                  <option>Newest first</option>
                  <option>Oldest first</option>
                </select>
              </div>
              <div class="in-progress-jobs">
                <h3 id="inProgressJobs">15 jobs in progress <i class="fa fa-angle-down"></i></h3>
                <div class="jobs-list-inProgress" style="display:none;">
                  <div class="feedbackedJob">
                    <div class="fbJobTitles">
                      <h4>Website from scratch for mobile app</h4>
                      <time>Mar 2016  -  jun 2016</time>
                      <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                    </div>
                    <div class="fbJobEarningType">
                      <h4>$450.00 earned</h4>
                      <p>Fixed job</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="feedbackedJobListing">
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
                <div class="feedbackedJob">
                  <div class="fbJobTitles">
                    <h4>Website from scratch for mobile app</h4>
                    <time>Mar 2016  -  jun 2016</time>
                    <div class="fbRatings"> <img src="<?php  echo BASE_URL;?>static/images/5-star-rating.png"/> <span>5.00</span> </div>
                  </div>
                  <div class="fbJobEarningType">
                    <h4>$450.00 earned</h4>
                    <p>Fixed job</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="availability-dates"> <img src="<?php  echo BASE_URL;?>static/images/calendar.png" alt="calendar">
              <p class="no-availability">Contractor not available from: Oct 5 to Oct 20</p>
            </div>
             
            <article class="employment-history">
              <h2>Employment History</h2>
              <?php 
              if(!empty($employment_history)) {
              	foreach ($employment_history as $key => $value) {
	              	?>
		              <div class="emp-hitory-bar">
		                <h3><span class="designation"><?php echo $value[0]; ?></span> | <span class="companyName"><?php echo $value[1]; ?></span></h3>
		                <p class="timePeriod"><?php echo $value[2]; ?> - <?php echo $value[3]; ?></p>
		              </div>
	              	<?php
              	}
              }
               ?>
            </article>
            <article class="educational-history">
              <h2>Education</h2>
               <?php 
              if(!empty($education)) {
              	foreach ($education as $key => $value) {
	              	?>
					<div class="edu-history-bar">
						<h3><span class="courseType"><?php echo $value[0]; ?></span></h3>
						<p class="timePeriod"><?php echo $value[0]; ?>  - <?php echo $value[0]; ?></p>
					</div>
	              	<?php
              	}
              }
               ?>
              
            </article>
            <div class="pro-button-group clearfix">
            <a href="javascript:void(0)" onclick="openMessageBox();" class="btn btn-blue">Contact</a>
            <a href="#" class="btn btn-gray">Hire now</a>
            <?php 
            	if (empty($case_1)) {
            		?>
        				<a href="javascript:void(0)" class="btn btn-gray btn-save" onclick="saveJobContractorRecommended();">Save</a>
            		<?php
            	} else {
            		?>
        				<a href="javascript:void(0)" class="btn btn-gray btn-save" onclick="alreadySavedContractor();">Saved</a>
            		<?php
            	}
             ?>
            </div>
          </div>
		<?php
	}


	public function getContractorSidebar()
	{
		$event = $_POST['event'];
		$this->SidebarWork($event);
	}

	public function SidebarWork($event)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$timezone = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $details->country);
		date_default_timezone_set($timezone[0]);
		$currentTime = date('Y-m-d H:i:s');
		$getUserRecord = $this->Model->get_Data_table(PREFIX.'contractor_profile','user_id',$event);
		$lastLogin = $getUserRecord[0]['last_login_time'];
		$date_a = new DateTime($currentTime);
		$date_b = new DateTime($lastLogin);

		$interval = date_diff($date_a,$date_b);

		$numberHours = $interval->format('%h');
		$numberdays = $interval->format('%d');

		?>
		<div class="work-history">
            <h4>Work history</h4>
            <div>
              <p class="pro-hours"><?php echo $getUserRecord[0]['hours_worked']; ?> hours worked</p>
              <p class="pro-jobs"><?php echo $getUserRecord[0]['jobs_done']; ?> jobs</p>
            </div>
          </div>
          <div class="profile-link">
            <h4>Profile link</h4>
            <div>
              <input type="text" value="https://in.search.Lorem.com" class="input" readonly>
            </div>
          </div>
          <div class="last-online">
            <h4>Last online</h4>
            <div>
            	<?php 
            		if($numberHours <= 24) {
            			?>
              			<p><?php echo $numberHours; ?> hours ago</p>
            			<?php
            		} else {
            			?>
              			<p><?php echo $numberdays; ?> days ago</p>
            			<?php
            		}
            	 ?>
            </div>
          </div>
		<?php
	}
	public function inviteContractorRecommended()
	{
		$time = time();
		$employer_id = $_POST['employer_id'];
		$invitedUsers = $_POST['invitedUsers'];
		$inviteUser = explode("," , $invitedUsers);
		foreach ($inviteUser as $key => $value) {
			$getUserRecord = $this->Model->get_Data_table(PREFIX.'users','id',$value);
			$data4 =  array(
				'alert_type'=>"job_recomendation", 
				'contractor_id'=>$value, 
				'job_id'=>$employer_id, 
				'alert'=>1, 
				'created_date'=>$time,
				'modified_date'=>$time
			);
			$Results3 = $this->Model->Insert_users($data4,PREFIX.'alerts');

			$data4 =  array(
				'job_id'=>$employer_id, 
				'contractor_id'=>$value, 
				'created_date'=>$time,
				'modified_date'=>$time
			);
			$Results3 = $this->Model->Insert_users($data4,PREFIX.'job_invite');
			// mail($getUserRecord[0]['email'],"Job Invite Recommended, ForceFlexing","Job Invite Aya");
			mail("abhinav@imarkinfotech.com","Job Invite Recommended, ForceFlexing","Job Invite Aya");

		}
	}





	public function listindustries()
	{
		return $this->industries->get_json_industries();
	}
	public function saveContractorJobRecommended()
	{
		$recommended_user_id = $_POST['recommended_user_id'];
		$employer_id = $_POST['employer_id'];
		$data4 =  array(
			'contractor_id'=>$recommended_user_id, 
			'job_id'=>$employer_id,
			'saved_for'=>"contractor"
		);
		$Results3 = $this->Model->Insert_users($data4,PREFIX.'saved_jobs');
	}



	public function sendMessage()
	{
		$current = time();
		$sendMessage = $_POST['sendMessage'];
		$recommended_user_id = $_POST['recommended-user-id'];
		$employer_id = $_POST['employer_id'];
		$case_1 = $this->Model->check_conversation_id(PREFIX.'conversation_set',$employer_id,$recommended_user_id);
		$case_2 = $this->Model->check_conversation_id(PREFIX.'conversation_set',$recommended_user_id,$employer_id);
		if(empty($case_1) && empty($case_2)) {
			$data4 =  array(
			'conv_to'=>$recommended_user_id, 
			'conv_from'=>$employer_id,
			'created_date'=>$current,
			'modified_date'=>$current
			);
			$conversationCreated = $this->Model->Insert_users($data4,PREFIX.'conversation_set');


			$data5 =  array(
			'conv_id'=>$conversationCreated, 
			'to_id'=>$recommended_user_id, 
			'from_id'=>$employer_id, 
			'message'=>$sendMessage,
			'message_time'=>$current
			);
			$conversationCreated111 = $this->Model->Insert_users($data5,PREFIX.'message_set');
			echo "Success!!!!!";
		} else {
			if(!empty($case_1)) {
			$data5 =  array(
			'conv_id'=>$case_1[0]['id'], 
			'to_id'=>$recommended_user_id, 
			'from_id'=>$employer_id, 
			'message'=>$sendMessage,
			'message_time'=>$current
			);
			$conversationCreated111 = $this->Model->Insert_users($data5,PREFIX.'message_set');
			} else {
				$data5 =  array(
				'conv_id'=>$case_2[0]['id'], 
				'to_id'=>$recommended_user_id, 
				'from_id'=>$employer_id, 
				'message'=>$sendMessage,
				'message_time'=>$current
				);
				$conversationCreated111 = $this->Model->Insert_users($data5,PREFIX.'message_set');

			}
		}
	}
	
	public function activity()
	{
		require_once(APP_DIR.'controllers/employer/employer_activity.php');
	}
	public function edit_pending_activities()
	{
		require_once(APP_DIR.'controllers/employer/edit_pending_activities.php');
	}
	public function delete_pending_activity()
	{
		require_once(APP_DIR.'controllers/employer/delete_pending_activity.php');
	}
	public function contract()
	{
		require_once(APP_DIR.'controllers/employer/contract/index.php');
	}
	public function ExtraExpenditure()
	{
		require_once(APP_DIR.'controllers/employer/contract/ExtraExpenditure.php');
	}
	
	public function ChangeJobStatus()
	{
		require_once(APP_DIR.'controllers/employer/contract/ChangeJobStatus.php');
	}
	public function addActivityInJob()
	{
		function latlong($city)
		{
			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city";
			$json_data = file_get_contents($url);
			$result = json_decode($json_data, TRUE);
			$latitude = $result['results'][0]['geometry']['location']['lat'];
			$longitude = $result['results'][0]['geometry']['location']['lng'];
			return $latitude.','.$longitude;
		}
		
		function create_url_slug($string){
			$slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
			return $slug;
		}
		require_once(APP_DIR.'controllers/employer/contract/addActivityInJob.php');
	}
		
	public function getLocation()
	{
		$results = $this->EModel->getLocations();	
		$options = '';
		$arr = array();
		$options .='<option value="">Select Location</option>';		
		foreach($results as $keys)
		{
			
			if(($keys['country_id'] == 38 || $keys['country_id'] == 231) && !in_array($keys['country_id'],$arr) )
			{
				if($keys['country_id'] == 38 && !in_array($keys['country_id'],$arr))
					{	$options .='<option class="" value="">Canada</option>';	$arr[]=$keys['country_id']; }
				if($keys['country_id'] == 231 && !in_array($keys['country_id'],$arr))
					{ $options .='<option class="" value="" >USA</option>'; $arr[]=$keys['country_id']; }	
				
				$options .='<option value="'.$keys['name'].'">'.$keys['name'].'</option>';				
				
			}
			else
			{
				$options .='<option value="'.$keys['name'].'">'.$keys['name'].'</option>';				
			}			
		}
		return $options;
	}
	
		
	public function viewActivity()
	{
		require_once(APP_DIR.'controllers/employer/contract/viewActivity.php');
	}
	
	public function jobs()
	{
		$this->loadview('main/header')->render();
		$this->loadview('Employer/job_report/navigation')->render();			
		$this->loadview('Employer/jobs/index')->render();	
		$this->loadview('main/footer')->render();	
	}
	
	public function openJob()
	{
		$this->loadview('main/header')->render();
		$this->loadview('Employer/job_report/navigation')->render();
		/* Job Model Count */	
		$count = $this->JModel->get_jobcount('job_author','flex_jobs');		// getting count of total rows		
		
		$item_per_page = 2;		
		$pages = ceil($count/$item_per_page);
		
		/* Get User Related Jobs */
		$jobList = $this->JModel->get_jobs_ByID();
		
		$view = $this->loadview('Employer/jobs/openjob');		
		$view->set('joblist',$jobList);
		$view->render();			
		/* Footer Additional Scripts */		
		$additional ='';
		$additional .= '<script src="'.BASE_URL.'static/js/jquery.bootpag.min.js"></script>';
		$additional .='<script type="text/javascript">
			$(document).ready(function() {		
				$(".openjobsList").load("'.SITEURL.'employer/get_jobs");  //initial page number to load
				$(".nextjobs").bootpag({
				   total: '.$pages.',
				   page: 1,
				   maxVisible: 5 
				}).on("page", function(e, num){
					e.preventDefault();
					$(".openjobsList").prepend(\'<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>\');
					$(".openjobsList").load("'.SITEURL.'employer/get_jobs", {\'page\':num});
				});
				
				/* Duplicate Post */
				$(document).on("click",".action",function()
				{
					var show = $(this).attr("value");
					if(show == "duplicatePost")
					{
						$("."+show).show();
						$("#myModal").modal("show");						
					}
					else if(show == "removePost")
					{
						var ptID = $(this).attr("id");
						$("."+show).show();
						$(".delete_id").attr("rel",ptID);
						$("#myModal").modal("show");
					}
					
				});
				
				
				/* Deleting the Post */
				$(document).on("click",".delete_id",function()
				{
					
				})
				
				
				/* View and Edit Post */
				$(document).on("click",".view",function()
				{
					var slug = $(this).attr("value");							
					var url = "'.SITEURL.'employer/editJob/"+slug;
					window.open(url, "_blank");
				});
				
				/* Popup For Duplicate Post */
				$(".job_in").click(function(){
					var onchangeValue = $(".job_id").val();
					$.ajax({
						url:"'.SITEURL.'postjob/onchangeValue",
						type:"POST",
						data:{onchangeValue:onchangeValue},
						success:function(resp)
						{
							window.location = "'.SITEURL.'postjob";
						}
					});
					
				});			
			});
			</script>';
		
		$footer = $this->loadview('main/footer');
		$footer->set('additional',$additional);
		$footer->render();	
	}
	
	public function JobDelete()
	{
		$this->is_login();
		$check = md5('confirm');
		if(base64_decode($_POST['check']) == $check )
			{
				$this->JModel->job_delete($_POST['id']);
			}
		
	}
	
	public function get_jobs()
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
		$data = $this->JModel->openjob($position,$item_per_page);		
	}
	
	public function company_profile()
	{
		require_once(APP_DIR.'controllers/employer/company_profile.php');
	}
	
	public function editActivity()
	{
		require_once(APP_DIR.'controllers/employer/contract/editActivity.php');
	}
	public function editActivityUpdate()
	{
		require_once(APP_DIR.'controllers/employer/contract/editActivityUpdate.php');
	}
	
	public function editJob()
	{
		require_once(APP_DIR.'controllers/employer/editJob.php');
	}
	
	/*ajax function to get the states based on country*/
	public function get_states($country_sortname="",$stateid="")
	{
		if(isset($_POST['sortname']) && !empty($_POST['sortname']))
			$country_sortname=$_POST['sortname'];
		if(!empty($country_sortname))
		{
			$country_id= $this->Model->Get_column('id','sortname',$country_sortname,PREFIX.'countries');
			$states = $this->Model->Get_all_with_cond('country_id',$country_id['id'],PREFIX.'states');
			if(!empty($states))
			{
				ob_start();
				?>
				<option value="">Select State</option>
				<?php 
				foreach($states as $state)
				{
					$selected=($state['id'] == $stateid)?'selected':'';
					?>
					<option value="<?=$state['id'];?>" <?=$selected;?>><?=$state['name'];?></option>
					<?php 
				}
				$options= ob_get_contents();
				ob_end_clean();
				return $options; 
			}
		}
	}
	
	//function to get the cities
	public function get_cities($state_id="",$city_id="")
	{
		if(isset($_POST['stateid']) && !empty($_POST['stateid']))
			$state_id=$_POST['stateid'];
		
		if(!empty($state_id))
		{
			$cities=$this->Model->Get_all_with_cond('state_id',$state_id,PREFIX.'cities');
			ob_start();
			?>
			<option value="">Select City</option>
			<?php 
			foreach($cities as $city)
			{
				$selected=($city['id'] == $city_id)?'selected':'';
				?>
					<option value="<?=$city['id'];?>" <?=$selected;?>><?=$city['name'];?></option>
				<?php 
			}
			$options= ob_get_contents();
			ob_end_clean();
			return $options; 
		}
	}
	
}