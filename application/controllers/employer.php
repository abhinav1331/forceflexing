<?php 

class Employer extends Controller
{
	public $Validator;
	public $Model;	
	public $EModel;	
	public $JModel;	
	public $Notification;
	public $session;
	public $SendMail;
	public $industries;
	public $Payouts;
	public function __construct() {

		$this->Validator = $this->loadHelper('validator');
		$this->session = $this->loadHelper('session_helper');
		$this->SendMail=$this->loadHelper('sendmail');
		$this->industries = $this->loadHelper('industries');
		$this->options = $this->loadHelper('options');
		$this->Notification = $this->loadHelper('notification');
		
		// Getting Stripe Gateway Credentials Form Admin
		$Keys = $this->options->get_keys();
		$Deckeys = json_decode($Keys['option_value']);
		$this->Payouts = $this->loadHelper('FF_Payouts',$Deckeys);
		
		// Models
		$this->Model = $this->loadModel('Postjob_model','employer');
		$this->EModel = $this->loadModel('employers','employer');
		$this->JModel = $this->loadModel('jobs','employer');
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
			
		}
		
	}
/*	public function job_report() {
		if(isset($_COOKIE['force_username']) && isset($_COOKIE['force_password']) || isset($_SESSION['force_username']) && isset($_SESSION['force_password'])) {
			$this->loadview('main/header')->render();
			$this->loadview('Employer/job_report/navigation')->render();			
			$this->loadview('Employer/job_report/index')->render();	
			$this->loadview('main/footer')->render();	
		} else {
			$this->redirect('');
		}
	}*/

	public function pre($array,$exit=false)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
		if($exit == true)
			exit();
	}
	public function no_access()
	{
		$this->loadview('main/header')->render();
		$data=$this->Model->Get_row('user_id',$this->userid,PREFIX.'contractor_profile');
		$nav=$this->loadview('contractor/main/navigation');			
		$nav->set('first_name',$this->udata['first_name']);
		$nav->set('last_name',$this->udata['last_name']);
		if($data['profile_img'])
		{
			$nav->set('profile_img',$data['profile_img']);
		}
		$nav->render();
	
		$this->loadview('main/noaccess')->render();
		$this->loadview('main/footer')->render();
	}
	
	public function contractorMaster() {
		require_once(APP_DIR.'controllers/employer/contractorMaster/index.php');
		// require_once(APP_DIR.'controllers/employer/openjob/index.php');
	}
	public function viewContract() {
		require_once(APP_DIR.'controllers/employer/contractorMaster/viewContract.php');
		// require_once(APP_DIR.'controllers/employer/openjob/index.php');
	}


	public function recommend() { 
		require_once(APP_DIR.'controllers/employer/contractorMaster/viewRecommend.php');
	}
	public function applied() { 
		require_once(APP_DIR.'controllers/employer/contractorMaster/applied.php');
	}

	public function message() { 
		require_once(APP_DIR.'controllers/employer/contractorMaster/messaged.php');
	}

	public function offered() { 
		require_once(APP_DIR.'controllers/employer/contractorMaster/offered.php');
	}

	public function job_report()
	{ 
		require_once(APP_DIR.'controllers/employer/job_report/index.php');
	}
	public function view_report()
	{
		require_once(APP_DIR.'controllers/employer/job_expense_report.php');
	}

	public function SaveContractorJob() {
		$currentDate = date("y-m-d H:i:s");
		$contractorId = $_POST['event'];
		$job_id = $_POST['job_id'];
		$data4 =  array(
			'contractor_id'=>$contractorId, 
			'job_id'=>$job_id,
			'saved_for'=>"employer",
			'created_date'=>$currentDate,
			'modified_date'=>$currentDate
		);
		$Results3 = $this->Model->Insert_users($data4,PREFIX.'saved_jobs');
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
						$dataNavi = $this->Model->get_Data_table(PREFIX.'company_info','company_id',$current_user_data['id']);
						if (!empty($dataNavi)) {
							$profileImg = $dataNavi[0]['company_image'];
						} else {
							$profileImg = "";
						}
						$c=array('to_id'=>$current_user_data['id'],'is_read'=>0);
						$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
						$getUserNavigation = $this->loadview('Employer/main/navigation');
						$getUserNavigation->set("nameEmp" , $current_user_data['username']);
						$getUserNavigation->set("profile_img" , $profileImg);
						$getUserNavigation->set("dataFull" , $current_user_data);
						$getUserNavigation->set("unread_msg_count" , $unread_msg_count);
						$getUserNavigation->render();

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
			if(isset($_POST['page']))
			{
				$page = $_POST['page'];
			}
			else
			{
				$page = 1 ;
			}
			$results = $this->EModel->searchContractor($_POST['content'],$page);
			echo $results;
		}
		else
		{
			$loc = $this->getLocation();			
			/* Employer Profile Page */		
			$is_login = $this->is_login();	//		Check User is login Or Not
			$this->loadview('main/header')->render();
			if (isset($_COOKIE['force_username'])) {
				$username = $_COOKIE['force_username'];
			} else {
				$username = $_SESSION['force_username'];
			}
			$current_user_data=$this->Model->login_user($username);
			$dataNavi = $this->Model->get_Data_table(PREFIX.'company_info','company_id',$current_user_data['id']);
			if (!empty($dataNavi)) {
				$profileImg = $dataNavi[0]['company_image'];
			} else {
				$profileImg = "";
			}
			$c=array('to_id'=>$current_user_data['id'],'is_read'=>0);
			$unread_msg_count=$this->Model->get_count_with_multiple_cond($c,PREFIX.'message_set');
			$getUserNavigation = $this->loadview('Employer/main/navigation');
			$getUserNavigation->set("nameEmp" , $current_user_data['username']);
			$getUserNavigation->set("profile_img" , $profileImg);
						$getUserNavigation->set("dataFull" , $current_user_data);
						$getUserNavigation->set("unread_msg_count" , $unread_msg_count);
			$getUserNavigation->render();
			
			$item_per_page = 2;
			$page_number = 1;
			$position = (($page_number-1) * $item_per_page);
			$CData = $this->EModel->getContractors($position, $item_per_page);
			$ArrContent = json_decode($CData);
			
			$Count = $this->EModel->getTotalContractor();			
			$pages = ceil($Count/$item_per_page);
			
			$index = $this->loadview('Employer/search/index');
			$index->set('locations',$loc);		
			$index->set('Data',$ArrContent->content);		
			$index->render();			
			
			
			
			
			$additional ='';
			$additional .= '<script src="'.BASE_URL.'static/js/jquery.bootpag.min.js"></script>';
			$additional .='<script type="text/javascript">
			$(document).ready(function() {
				//$("#results").load("'.SITEURL.'employer/getAllContractors");  //initial page number to load
			
			var url = "'.SITEURL.'employer/getAllContractors";
			getPage('.$pages.','.$page_number.',url);
			
			});		
			
			function getPage(page,pagenumber,url)
			{		
				$(".contractor-pagination").bootpag({
				   total: page,
				   page: pagenumber,
				  // maxVisible: 3 
				}).on("page", function(e, num){
					e.preventDefault();
					$("#results").prepend(\'<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>\');
					$("#results").load(url, {\'page\':num});
				});
			}
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
		$CData = $this->EModel->getContractors($position, $item_per_page);
		$ArrContent = json_decode($CData);
		echo $ArrContent->content;
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
		if(isset($_SESSION['force_username'])) {
			$username = $_SESSION['force_username'];
		} else {
			$username = $_COKKIE['force_username'];
		}
		$current_user_data=$this->Model->login_user($username);
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
			//Notification Section 
			$todayDate = date("y-m-d :H:i:s");
			$flex_notification_message =  array(
			 'noti_type'=>"job_invitation", 
			 'noti_message'=>"You got New Invite", 
			 'fromUserID'=>$current_user_data['id'], 
			 'toUserID'=>$value,
			 'forAdmin'=>"1",
			 'forID'=>$employer_id,
			 'is_read'=>"0",
			 'createdOn'=>$todayDate,
			 'modifiedOn'=>$todayDate
			);
			$Results1 = $this->Model->Insert_users($flex_notification_message,PREFIX.'notification_message');
			//Notification Section 
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

	public function sendMessageMaster()
	{
		$current = time();
		$contractorNameId = $_POST['contractorNameId'];
		$myId = $_POST['myId'];
		$myjobId = $_POST['myjobId'];
		$case_1 = $this->Model->check_conversation_id(PREFIX.'conversation_set',$myId,$contractorNameId);
		$case_2 = $this->Model->check_conversation_id(PREFIX.'conversation_set',$contractorNameId,$myId);
		if(empty($case_1) && empty($case_2)) {
			$data4 =  array(
			'conv_to'=>$contractorNameId, 
			'conv_from'=>$myId,
			'job_id'=>$myjobId,
			'created_date'=>$current,
			'modified_date'=>$current
			);
			$conversationCreated = $this->Model->Insert_users($data4,PREFIX.'conversation_set');


			
		} else {
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
		$this->loadview('home/navigation')->render();		
		$this->loadview('Employer/jobs/index')->render();	
		$this->loadview('main/footer')->render();	
	}
	
	public function openJob()
	{
		$header = $this->loadview('main/header');
		$additional = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';
		$header->set('additional',$additional);
		$header->render();
		$this->loadview('Employer/job_report/navigation')->render();
		/* Job Model Count */	
		$count = $this->JModel->get_jobcount();		// getting count of total rows		
		
		$item_per_page = 10;		
		$pages = ceil($count/$item_per_page);
		
		/* Get User Related Jobs */
		$jobList = $this->JModel->get_jobs_ByID();
		
		$view = $this->loadview('Employer/jobs/openjob');		
		$view->set('joblist',$jobList);
		$view->render();			
		/* Footer Additional Scripts */	
			
			/* Security for Ajax Call */
			$code = $this->SecurityAjax($_SESSION['force_username']);
			/* Security End */
		
		$additional ='';
		$additional .= '<script src="'.BASE_URL.'static/js/jquery.bootpag.min.js"></script>';
		$additional .= '<script src="'.BASE_URL.'static/js/toastr.js"></script>';
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
				
				
				/* Deleting the Post Form PopUp */
				$(document).on("click",".delete_id",function()
				{
					var code = "'.$code.'";
					var reference = $(this).attr("rel");
					$.ajax({
						type:"POST",
						url:"'.SITEURL.'employer/JobDelete",
						data:{Call:reference,check:code},
						success:function(res)
						{
							if(res == "success")
							{
								toastr.success(\'Job Deleted.\', \'Successfully\');
								location.reload();
							}
							else
							{
								console.log(res);
							}
							
						}
					});
					
				})
				
				$(document).on("click",".cancel",function()
				{
					$("#myModal").modal("hide");
				});
				
				
				/* View and Edit Post */
				$(document).on("click",".view",function()
				{
					var slug = $(this).attr("value");							
					var url = "'.SITEURL.'employer/editJob/"+slug;
					window.location.href = url;
					// window.open(url);
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
							window.location.href = "'.SITEURL.'postjob";
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
		$check = $this->SecurityAjax($_SESSION['force_username']);
		
		if(base64_decode($_POST['check']) == base64_decode($check) )
			{
				echo $this->JModel->job_delete(base64_decode($_POST['Call']));
			}		
	}
	
	private function SecurityAjax($userID)
	{
		$verify = $userID.'confirm';
		$check	= base64_encode(md5($verify));
		return $check;
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
		$item_per_page = 10;
		$position = (($page_number-1) * $item_per_page);
		$data = $this->JModel->openjob($position,$item_per_page);		
	}
	
	public function company_profile_settings()
	{
		require_once(APP_DIR.'controllers/employer/company_profile_settings.php');
		echo $this->Payouts->VerifyEmployer();
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
	public function removeJob()
	{

		$data = array(
			"jobjob_status" => 4
		);
		$dataToBeUpdatec = $this->Model->update_record($data,"id",$_POST['job_id'],PREFIX.'jobs');
	}

	public function removeContractor()
	{
		$appliedId = $_POST['appliedId'];
		$data = array(
			"status" => 1
		);
		$dataToBeUpdatec = $this->Model->update_record($data,"id",$appliedId,PREFIX.'applied_jobs');
	}
	
	public function recindOffer()
	{
		$data = array(
			"status" => 2
		);
		$dataToBeUpdatec = $this->Model->update_record($data,"id",$_POST['inviteID'],PREFIX.'job_invite');
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
	
	public function randomPassword() 
	{
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	
	public function match_pass()
	{
		 $pass=$_POST['oldpass'];
		 $empid=$_POST['empid'];
		 if(!empty($pass) && !empty($empid))
		 {
			 $data=$this->Model->Get_row('id',$empid,PREFIX.'users');
			 if($data['password'] == md5($pass))
				 echo "1";
			 else
				 echo "0";
		 }
		 exit();
	}
	public function emailexists()
	{
		$email=$_POST['email'];
		$result = $this->Model->Get_row('email', $email,PREFIX.'users');
		if(empty($result))
		{	return 1;	}			
		else
		{	return 0;	}		// Email Exist
	}
	
	//chck security answer
	public function check_answer()
	{
		$answer=$_POST['existing_answer'];
		$company_id=$_POST['company_id'];
		$result=$this->Model->Get_column('security_ans','company_id',$company_id,PREFIX.'company_info');
		if(!empty($result))
		{
			$ans=$result['security_ans'];
			if($answer == $ans)
				echo 1;
			else
				echo 0;
		}
		else
		{
			echo 0;
		}
		exit();
	}
	
	public function send_otp()
	{
		$country=$_POST['country'];
		$phone_number=$_POST['phone_num'];
		$country_code=$this->Model->Get_column('phonecode','sortname',$country,PREFIX.'countries');
		//$phonenum=$country_code['phonecode'].$phone_number;
		$phonenum='91'.$phone_number;
		$random=rand();
		$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
			[
			  'api_key' =>  'b7807115',
			  'api_secret' => '506dc9fa8153c598',
			  'to' => $phonenum,
			  'from' => 'ForceFlexing',
			  'text' => 'Hi, your security code is '.$random.'. Kindly do not share your code with anyone, this is usable only once.'
			]
		);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$res=json_decode($response,true);
		if($res['messages'][0]['status'] == 0)
		{
			$data=array('msgstatus'=>1,"random_number"=>$random);
		}
		else
		{
			$data=array('msgstatus'=>0,"random_number"=>"");
		}
		echo json_encode( $data );
		exit();
	}

	/*Report Module emoployer*/
	public function reports()
	{
		require_once(APP_DIR.'controllers/employer/reports.php');
	}
	
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
			$contracts=$this->Model->Get_all_with_cond('employer_id',$this->userid,PREFIX.'hire_contractor');
			if(!empty($contracts))
			{
				foreach($contracts as $contract)
				{
					$contract_id=$contract['id'];
						
					//get the job name
					$job=$this->Model->Get_row('id',$contract['job_id'],PREFIX.'jobs');
				
					//get the contractor  name
					$contractor=$this->Model->Get_row('id',$contract['contractor_id'],PREFIX.'users');
					
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
							$company=$contractor['first_name']." ".$contractor['last_name'];
							
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
		$contracts=$this->Model->Get_all_with_cond('employer_id',$this->userid,PREFIX.'hire_contractor');
		
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
	
	/* Payments Section */
	
	
	public function StripeCardSave()
	{
		$this->is_login();	
		$username = $_SESSION['force_username'];		
		$current_user_data=$this->Model->login_user($username);
		$current_user_data['id'];
		
		//Checking User Card Exist or NOT
		$Check = $this->Model->get_single_row('user_id', $current_user_data['id'],PREFIX.'bank_details');
		
		if(empty($Check))
		{
			$data = array( 'user_id'=>$current_user_data['id'],'token_detail_value'=>json_encode($_POST['ResponseDetails']) );
			$InsertedID = $this->Model->Insert($data,PREFIX.'bank_details'); // Insert Token Response
			
			$CustomerDetails = $this->Payouts->Create_customer($_POST['TokenID'],$_POST['Email']);		
			
			$Cdata = array('bank_detail_key'=>$CustomerDetails['id'],'bank_detail_value'=>json_encode($CustomerDetails));
			
			$this->Model->update($Cdata,'id',$InsertedID,PREFIX.'bank_details');   // Insert Customer Detail Response
			
			$this->Notification->insertNotification('paymentDetails_inserted',$current_user_data['id'],0,0,0);
			
		}
		else
		{
			
			$data = array( 'token_detail_value'=>json_encode($_POST['ResponseDetails']) );
			
			$UpdatedID = $this->Model->update($data,'id',$Check['id'],PREFIX.'bank_details');	
			
			$CustomerDetails = $this->Payouts->Update_customer($_POST['TokenID'],$Check['bank_detail_key']);
			
			$Cdata = array('bank_detail_value'=>json_encode($CustomerDetails));
			
			$this->Model->update($Cdata,'id',$Check['id'],PREFIX.'bank_details');
			$this->Notification->insertNotification('paymentDetails_updated',$current_user_data['id'],0,0,0);
		}					
	}
	
	/* Payments Section ENDS */
	
	
	
}