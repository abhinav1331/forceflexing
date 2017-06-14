<?php 

class Admin extends Backend
{
	public $Users;
	public $Jobs;
	public $Options;
	public $Validate;
	
	public function __construct()
	{
		$this->Users = $this->loadModel('Users','admin');
		$this->Jobs = $this->loadModel('jobs','admin');
		$this->Options = $this->loadHelper('options');
		$this->Validate = $this->loadHelper('validator');
	}
	
	public function index()
	{		
		$this->check_login();	// Session check
		$this->loadview('admin/main/include/header')->render();
		$this->loadview('admin/main/index')->render();
		$this->loadview('admin/main/include/footer')->render();		
	}
	
	public function login()
	{
		if(isset($_POST['ad_login']) && $_POST['ad_pass'] != '' && $_POST['ad_email'] != '')
		{
			if($_POST['ad_pass'] == 'admin' && $_POST['ad_email'] == 'admin@gmail.com')
			{
				$_SESSION['admin'] = 'admin';
				$_SESSION['log_in'] = '1';
				$this->redirect('admin/index');
			}
			
		}
		else
		{
			$this->loadview('admin/login/header')->render();
			$this->loadview('admin/login/index')->render();
			$this->loadview('admin/login/footer')->render();
			
		}
		
	}
	
	public function viewUsers()
	{
		$this->check_login();	// Session check
		if(isset($_POST['confirm_id']))
		{
			if($_POST['verify'] == 'reactivate')
			{
				$data = array(
					'is_verified'=>'1',
				);			
				$results = $this->Users->Update_row($data,'id',$_POST['confirm_id'],'flex_users');
				echo $results;
				exit;
			}
			elseif($_POST['verify'] == 'suspend')
			{
				$data = array(
					'is_verified'=>'2',
				);			
				$results = $this->Users->Update_row($data,'id',$_POST['confirm_id'],'flex_users');
				echo $results;
				exit;
			}			
		}
		
		if(isset($_POST['account']))
		{
			if($_POST['account'] == 'suspend')
			{
				$user_details = $this->Users->Get_row('id',$_POST['id'],'flex_users');
				
				$title='<h2 class="text-center"><span class="fa fa-exclamation-triangle"></span> Notice</h2>';				
				$data ="<h4>You are about to suspend ".$user_details['username']."  Account. Are you sure ? </h4>";
				$button = '<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" alt="suspend" rel="'.$_POST["id"].'" class="btn btn-primary confirm">Confirm</button>';
				echo json_encode(array('title'=>$title,'button'=>$button,'content'=>$data));
				exit;
			}
			elseif($_POST['account'] == 'reactivate')
			{
				$user_details = $this->Users->Get_row('id',$_POST['id'],'flex_users');
				
				$title='<h2 class="text-center"><span class="fa fa-exclamation-triangle"></span> Notice</h2>';				
				$data ="<h4>You are about to Re-Activate ".$user_details['username']."  Account. Are you sure ? </h4>";
				$button = '<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" alt="reactivate" rel="'.$_POST["id"].'" class="btn btn-primary confirm">Confirm</button>';
				echo json_encode(array('title'=>$title,'button'=>$button,'content'=>$data));
				exit;
			}
			elseif($_POST['account'] == 'delete')
			{
				exit;
			}
			elseif($_POST['account'] == 'reset')
			{
				$this->generateRandomPassword();
				exit;
			}
			else
			{
				$this->redirect('admin/viewUsers');
				exit;
			}
		}
		
		
		/* Rendering additional Stylesheets  */
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
		';
		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$header->render();			
		
		$Data = $this->Users->Get_all_data('flex_users');
		$table = '';
		$table .= ' <table width="100%" class="table table-striped table-bordered table-hover dataTables">';		
		$table .= '<thead>
					<tr>
						<th>Name</th>
						<th>Username</th>
						<th>Email</th>
						<th>Role</th>
						<th>Register From</th>					
						<th>Profile</th>									
					</tr>
				</thead>';	
		$table .='<tbody>';		
			foreach($Data as $keys)
			{
				$table .='<tr>';
					$table .='<td>'.$keys["first_name"].'</td>';
					$table .='<td>'.$keys["username"].'</td>';
					$table .='<td>'.$keys["email"].'</td>';
					$table .='<td>'.$keys["role"].'</td>';
					$table .='<td>'.$keys["connected_with"].'</td>';		
					$table .='<td>
					<button type="button" value="'.$keys["id"].'" class="btn btn-info ad_view" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> Processing Order">View</button>				
					<div class="btn-group">
					  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Action <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
						<li><a  target="_blank" href="'.BASE_URL.'admin/editUser/?PersonID='.$keys["id"].'">Edit Account</a></li>';
						
						if($keys["is_verified"] == '2')
						{
							$table .='<li><a rel="'.$keys["id"].'" id="reactivate" class="account" href="javascript:void(0)">Re-Activate Account</a></li>';
						}elseif($keys["is_verified"] == '1')
						{
							$table .='<li><a rel="'.$keys["id"].'" id="suspend" class="account" href="javascript:void(0)">Suspend Account</a></li>';
						}
						elseif($keys["is_verified"] == '0')
						{
							$table .='<li><a href="javascript:void(0)">Account Not Activated</a></li>';
						}
						
						$table .='<li><a rel="'.$keys["id"].'" id="reset" class="account" href="javascript:void(0)">Reset Password</a></li>						
						<li><a rel="'.$keys["id"].'" id="delete" class="account" href="javascript:void(0)">Delete Account</a></li>						
					  </ul>
					</div></td>';		
				$table .='</tr>';				
			}
		$table .='</tbody></table>';
		
		$view = $this->loadview('admin/users/index');
		$view->set('Details', $table);		
		$view->render();
		
		/* Rendering Additional Scripts  */
		$additional_scripts = '
		<script src="'.BASE_URL.'static/admin/js/jquery.dataTables.min.js"></script>
		<script src="'.BASE_URL.'static/admin/js/dataTables.bootstrap.min.js"></script>
		<script src="'.BASE_URL.'static/admin/js/dataTables.responsive.js"></script>
		<script>$(document).ready(function() {
			$(".dataTables").DataTable({
			"columns": [	null,null,null,null,null,{ "orderable": false }],	
			responsive: true
			});
			});
			$(document).on(\'click\',\'.ad_view\',function() 
			{
				var $this = $(this);
			    $this.button(\'loading\');
				var id = $this.val();
				console.log(id);
				$.ajax({
					type:"POST",
					url:"'.SITEURL.'admin/get_profile",
					data:{pro_id:id},
					success:function(res){
						console.log(res);
						$this.button(\'reset\');
						$("#myLargeModalLabel").modal(\'show\');
						$(".modal-body").html(res);
						
					}					
				});				
			});
			$(\'.account \').click(function()
			{
				var attr = $(this).attr(\'id\');
				var id = $(this).attr(\'rel\');				
				
				window.header_pop = $(".modal-title").html();
				window.content_pop = $(".modal-body").html();
				window.footer_pop = $(".modal-footer").html();
				
				$.ajax({
					type:"POST",					
					data:{account:attr,id:id},
					success:function(res){
						
						if(attr == "suspend")
						{
							var details = jQuery.parseJSON(res);
							$(".modal-title").html(details.title);
							$(".modal-title").addClass("text-center");
							$(".modal-footer").html(details.button);
							$(".modal-body").html(details.content);
							
							$(".modal-body").addClass("text-center");
							$(".modal-dialog").removeClass("modal-lg");
							$(".modal-dialog").addClass("modal-md");
							
							$(document).on("click",".confirm",function(){
								
								var conf_id  = $(this).attr("rel");
								var conf_text  = $(this).attr("alt");								
								$.ajax({
										type:"POST",										
										data:{verify:conf_text,confirm_id:id},
										success:function(res){											
											$(".modal-title").html(\'<h2 class="text-center"><span style="color:green" class="fa fa-check"></span> Account Updated Successfully !</h2>\');
											$(".modal-body").html("<h4>Page Reloading...</h4>");
											$(".modal-footer").html("");
											setTimeout(function(){ location.reload(); }, 3000);
											
										}					
									});	
							});
							
							
						}
						elseif(attr == "reactivate")
						{
							var details = jQuery.parseJSON(res);
							$(".modal-title").html(details.title);
							$(".modal-title").addClass("text-center");
							$(".modal-footer").html(details.button);
							$(".modal-body").html(details.content);
							
							$(".modal-body").addClass("text-center");
							$(".modal-dialog").removeClass("modal-lg");
							$(".modal-dialog").addClass("modal-md");
							
							$(document).on("click",".confirm",function(){
								
								var conf_id  = $(this).attr("rel");
								var conf_text  = $(this).attr("alt");								
								$.ajax({
										type:"POST",										
										data:{verify:conf_text,confirm_id:id},
										success:function(res){											
											$(".modal-title").html(\'<h2 class="text-center"><span style="color:green" class="fa fa-check"></span> Account Updated Successfully !</h2>\');
											$(".modal-body").html("<h4>Page Reloading...</h4>");
											$(".modal-footer").html("");
											setTimeout(function(){ location.reload(); }, 3000);
											
										}					
									});	
							});
						}
						
						$("#myLargeModalLabel").modal(\'show\');
						
						
						
						$("#myLargeModalLabel").on("hidden.bs.modal", function () 
						{ 
							$(".modal-title").html(window.header_pop);
							$(".modal-body").html(window.content_pop);
							$(".modal-footer").html(window.footer_pop);
							$(".modal-dialog").removeClass("modal-md");
							$(".modal-dialog").addClass("modal-lg");
							
						});
						
						
					}					
				});		
			});
			</script>';
		
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
	}
	
	public function addUser()
	{
		$this->check_login();	// Session check		
		
		/* Check UserEmail Exists */
			if(isset($_POST['check']))
			{	
				if($_POST['check'] == 'email')
				{	
					$CHECK = $this->Users->Check_email($_POST['email']);	
					if($CHECK == true)
						{ echo "true"; }
					else
						{	echo "false"; }	
					exit;
				}
				elseif($_POST['check'] == 'username')
				{
					$CHECK = $this->Users->Check_username($_POST['username']);	
					if($CHECK == true)
						{ echo "true"; }
					else
						{	echo "false"; }	
					exit;
				}
			}
		/* Check END */
		
		/* Render Header */
		$additional_css = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$header->render();
		
		
		/* Posted Data to Insert user */
			if(isset($_POST['password']))
			{
				$Data = $_POST;
				if($this->Validate_User($Data) === false)
				{		
					$errors = $this->Validate->get_errors_array();		
					$options = $this->Options->show_countries();	// GEt Country list to show
					$addUser = $this->loadview('admin/users/add');
					$addUser->set('errors',$errors);
					$addUser->set('Fileds',$Data);
					$addUser->set('options',$options);
					$addUser->render();
					
				}
				else
				{
					$data =  array(
					 'first_name'=>$_POST['first_name'], 
					 'last_name'=>$_POST['last_name'], 
					 'company_name'=>$_POST['company'],
					 'country'=>$_POST['country'],
					 'email'=>$_POST['email'],
					 'password'=>md5($_POST['password']),
					 'role'=>$_POST['role'],
					 'connected_with'=>'EMAIL',
					 'created_date'=>date("Y-m-d"),
					 'modified_date'=>date("Y-m-d"),
					 'is_verified'=>'1',
					 'username'=>$_POST['username']
				);
					$result = $this->Users->Insert($data,'flex_users');
					if($result)
					{
						$msg_success = 'User Inserted Successfully';
					}
					else
					{
						$msg_error = 'User not inserted Please Try Again After Some Time';
					}
					
					$options = $this->Options->show_countries();	// GEt Country list to show
					$addUser = $this->loadview('admin/users/add');				
					if(isset($msg_success))
					{
						$addUser->set('Success',$msg_success);
					}
					elseif(isset($msg_error))
					{
						$addUser->set('Error',$msg_error);
					}				
					$addUser->set('options',$options);
					$addUser->render();
					
				}
			}
			else
			{
				$options = $this->Options->show_countries();	// GEt Country list to show
				$addUser = $this->loadview('admin/users/add');
				$addUser->set('options',$options);
				$addUser->render();
				
			}	
		/* END USER INSERTED */
			
		
		/* Rendering Footer */
		/* Rendering Additional Scripts  */
		$additional_scripts = '
		<script src="'.BASE_URL.'static/js/toastr.js"></script>	
		<script src="'.BASE_URL.'static/js/jquery.validate.min.js"></script>	
		<script>
			$(document).on(\'click\',\'.gnt_usr\',function() 
			{
				var email = $("input[name=\'email\']").val();				
				var $this = $(this);
			    $this.button(\'loading\');
				isValidEmailAddress(email);
				if(email == "")
				{	
					toastr.error("Please Enter Email .", "Error!",{"progressBar": true});					
					$this.button(\'reset\');	
				}else if(isValidEmailAddress(email) != true)
				{
					toastr.error("Please Enter Valid Email ! .", "Error!",{"progressBar": true});					
					$this.button(\'reset\');	
				}
				else
				{
					$.ajax({
						type:"POST",
						url:"'.SITEURL.'admin/get_username",
						data:{email:email},
						success:function(res){
							var result = jQuery.parseJSON(res)
							if(result.status == "ERROR")
							{
								$this.button(\'reset\');	
								toastr.error(result.msg, "Error!",{"progressBar": true});
							}
							else if(result.status == "SUCCESS")
							{
								$this.button(\'reset\');	
								$("input[name=\'username\']").val(result.msg);	
							}							 
						}					
					});
				}
			});
			
			$(document).on(\'click\',\'.gnt_pwd\',function() 
			{
				var $this = $(this);
			    $this.button(\'loading\');
				$.ajax({
						type:"POST",
						url:"'.SITEURL.'admin/generateRandomPassword",						
						success:function(res){
							$this.button(\'reset\');	
							$("input[name=\'password\']").val(res);						 
						}					
					});				
			});
			
			function isValidEmailAddress(emailAddress) {
			var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
			 //alert( pattern.test(emailAddress) );
			return pattern.test(emailAddress);
			};
			
			/* Validation  */
			$(".add_user").click(function(){
					
			$("#myform").validate({
				  rules: {
					first_name: "required",
					company: "required",
					last_name: "required",
					password: "required",
					country: "required",
					role: "required",
					username: 
					{
					  required: true,					  
					  remote: 
						{
							url: "",
							type: "post",
							data:{	check:"username"	}
						}
					},						
					email: 
						{
						  required: true,
						  email: true,
						  remote: 
							{
								url: "",
								type: "post",
								data:{	check:"email"	}
							}
						}
					},
				  messages: 
				  {
					first_name: "Please specify User First Name !",
					company: "Please specify Company Name !",
					last_name: "Please specify User Last Name !",
					password: "Password field can\'t be Empty ! ",
					email: 
						{
						  required: "We need your email address to contact you",
						  email: "Your email address must be in the format of name@domain.com",
						  remote: "Email ID Already Exists !"
						},
					username: {
					  required: "Username is required",
					  remote: "Username Already Exists !"
					}
				  }
				});
			})		 
			
			</script>';
		
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
	}
	
	public function editUser()
	{
		
		if(isset($_GET['PersonID']))
		{
			if(isset($_POST['update_user']))
			{
				$Data = $this->Validate->sanitize($_POST); // You don't have to sanitize, but it's safest to do so.
				$this->Validate->validation_rules(array(
					'first_name'   => 'required|alpha',
					'last_name'    => 'required|alpha',
					'company'      => 'required|alpha',
					'email'        => 'required|valid_email',
					'password'     => 'required|max_len,100|min_len,6',				
					'country'      => 'required',
					'role'		   => 'required'
				));	
				$validated_data = $this->Validate->run($Data);
				if($validated_data === false)
				{		
					$errors = $this->Validate->get_errors_array();		
					$options = $this->Options->show_countries();	// GEt Country list to show
					$addUser = $this->loadview('admin/users/edit');
					$addUser->set('errors',$errors);
					$addUser->set('Fileds',$Data);
					$addUser->set('options',$options);
					$addUser->render();
					
				}
				else
				{
					$data =  array(
					 'first_name'=>$_POST['first_name'], 
					 'last_name'=>$_POST['last_name'], 
					 'company_name'=>$_POST['company'],
					 'country'=>$_POST['country'],
					 'email'=>$_POST['email'],
					 'password'=>md5($_POST['password']),
					 'role'=>$_POST['role'],
					 'connected_with'=>'EMAIL',
					 'created_date'=>date("Y-m-d"),
					 'modified_date'=>date("Y-m-d"),
					 'is_verified'=>'1',
					 'username'=>$_POST['username']
				);
					$result = $this->Users->Insert($data,'flex_users');
					if($result)
					{
						$msg_success = 'User Inserted Successfully';
					}
					else
					{
						$msg_error = 'User not inserted Please Try Again After Some Time';
					}
					
					$options = $this->Options->show_countries();	// GEt Country list to show
					$addUser = $this->loadview('admin/users/add');				
					if(isset($msg_success))
					{
						$addUser->set('Success',$msg_success);
					}
					elseif(isset($msg_error))
					{
						$addUser->set('Error',$msg_error);
					}				
					$addUser->set('options',$options);
					$addUser->render();
					
				}
				
				
			}
		
				
			$Details = $this->Users->get_single_row('id',$_GET['PersonID'],'flex_users');
			if(empty($Details))
			{
				$this->redirect('admin/viewUsers');
				exit;
			}
			/* Render Header */
			$additional_css = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';		
			$header = $this->loadview('admin/main/include/header');
			$header->set('additional_css',$additional_css);
			$header->render();
			
			/* Body View Render */
			
			$options = $this->Options->show_countries($Details['country']);	// GEt Country list to show
			$addUser = $this->loadview('admin/users/edit');
			$addUser->set('Fileds',$Details);
			$addUser->set('options',$options);
			$addUser->render();
			
			$additional_scripts = '
		<script src="'.BASE_URL.'static/js/toastr.js"></script>	
		<script src="'.BASE_URL.'static/js/jquery.validate.min.js"></script>	
		<script>			
			$(document).on(\'click\',\'.gnt_pwd\',function() 
			{
				var con = confirm("Are You Sure ? You want to Reset Password ! User Will Be Notify through Email ");
				if(con == true)
				{
					var $this = $(this);
					$this.button(\'loading\');
					$.ajax({
							type:"POST",
							url:"'.SITEURL.'admin/generateRandomPassword",						
							success:function(res){
								$this.button(\'reset\');	
								$("input[name=\'password\']").val(res);						 
							}					
					});	
				}							
			}); 
			
			function isValidEmailAddress(emailAddress) {
			var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
			 //alert( pattern.test(emailAddress) );
			return pattern.test(emailAddress);
			};
			
			/* Validation  */
			$(".update_user").click(function(){
					
			$("#myform").validate({
				  rules: {
					first_name: "required",
					company: "required",
					last_name: "required",
					password: "required",
					country: "required",
					role: "required"
				  },
				  messages: 
				  {
					first_name: "Please specify User First Name !",
					company: "Please specify Company Name !",
					last_name: "Please specify User Last Name !",
					password: "Password field can\'t be Empty ! "					
				  }
				});
			})		 
			
			</script>';
			
			
			$footer = $this->loadview('admin/main/include/footer');
			$footer->set('additional', $additional_scripts);
			$footer->render();
		}
		else
		{
			$this->redirect('admin/viewUsers');
		}
	}
	
	public function get_username()
	{
		/* Creating username by email , Total count, Date[month and year] */
		if(isset($_POST['email']))
		{	
			return $this->Users->Create_username($_POST['email']);
		}
	}
	
	public function generateRandomPassword() {
		  //Initialize the random password
		  $password = '';

		  //Initialize a random desired length
		  $desired_length = rand(8, 12);

		  for($length = 0; $length < $desired_length; $length++) {
			//Append a random ASCII character (including symbols)
			$password .= chr(rand(32, 126));
		  }

		  echo $password;
	}
	
	public function Validate_User($Data)
	{
		
		$Data = $this->Validate->sanitize($Data); // You don't have to sanitize, but it's safest to do so.

			$this->Validate->validation_rules(array(
				'first_name'   => 'required|alpha',
				'last_name'    => 'required|alpha',
				'company'      => 'required|alpha',
				'email'        => 'required|valid_email',
				'password'     => 'required|max_len,100|min_len,6',
				'username'     => 'required|alpha_numeric',
				'country'      => 'required',
				'role'		   => 'required'
			));

			$this->Validate->filter_rules(array(				
				'password' 	=> 'trim|md5'					
			));

			return $validated_data = $this->Validate->run($Data);			
			
	}
	
	public function check_login()
	{
		if(!isset($_SESSION['admin']))
		{
			$this->redirect('admin/login');
		}		
	}
	
	public function get_profile()
	{
		if(isset($_POST['pro_id']) && $_POST['pro_id'] != '')
		{
			$profile = $this->Users->Get_row('id',$_POST['pro_id'],'flex_users');
			$html = '';
			$html .='<div class="row">
                <div class="col-md-3 col-lg-3 col-sm-3" align="center"> <img alt="User Pic" src="http://babyinfoforyou.com/wp-content/uploads/2014/10/avatar-300x300.png" class="img-circle img-responsive"> </div>
                
                <div class=" col-md-9 col-lg-9 col-sm-9"> 
				<div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>First Name:</td>
                        <td><input readonly type="text" name="role" value="'.$profile["first_name"].'"></td>
                      </tr>
                      <tr>
                        <td>Last Name:</td>
                        <td><input readonly type="text" name="role" value="'.$profile["last_name"].'"></td>
                      </tr>
                      <tr>
                        <td>Company Name</td>
                        <td><input readonly type="text" name="role" value="'.$profile["company_name"].'"></td>
                      </tr>                 
                       <tr>
                        <td>Country</td>
                        <td><input readonly type="text" name="role" value="'.$profile["country"].'"></td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><input readonly type="text" name="role" value="'.$profile["email"].'"></td>
                      </tr>
					  <tr>
                        <td>Registered From</td>
                        <td>'.$profile["connected_with"].'</td>                           
                      </tr>
					  <tr>
                        <td>User Role</td>
                        <td><input readonly type="text" name="role" value="'.$profile["role"].'">
						</td>                           
                      </tr> 
					  <tr>
                        <td>Profile Status</td>
                        <td>'.$profile["is_verified"].'</td>                           
                      </tr>
                     
                    </tbody>
                  </table>
				  </div>
                </div>
              </div>'; 
			  $html .='
			  <div class="row">
				  <div id="user_stats" class="col-md-6 col-lg-6">
				  
				  </div>
			  </div>';
			  
			  echo $html;
					
		}
	}
	
	public function allJobs()
	{
		if(isset($_GET['pop']) && $_GET['pop'] == 'on')
		{
			$user_slug = $this->Jobs->getJob('job_slug',$_GET['viewJob'],'flex_jobs');			
		}
		
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
		';
		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$header->render();
		
		$Data = $this->Jobs->Get_all_jobs('flex_jobs');
		$view = $this->loadview('admin/job/index');
		$view->set('Details', $Data);
		if(isset($_GET['viewJob']) && $_GET['pop'] == 'on')	
		{
			$view->set('title', $user_slug['title']);
			$view->set('content',$user_slug['content']);
			$view->set('profile',$user_slug['profile']);
			
		}	
		$view->render();
	
		$additional_scripts = '';
		$additional_scripts .= '
		<script src="'.BASE_URL.'static/admin/js/jquery.dataTables.min.js"></script>
		<script src="'.BASE_URL.'static/admin/js/dataTables.bootstrap.min.js"></script>
		<script src="'.BASE_URL.'static/admin/js/dataTables.responsive.js"></script>
		<script>$(document).ready(function() {
			$(".dataTables").DataTable({
			"columns": [	null,null,null,null,{ "orderable": false }],	
			responsive: true
			});
			});
		</script>';
		if(isset($_GET['pop']) && $_GET['pop'] == 'on')
		{
			$additional_scripts .= '
			<script>
				$(document).ready(function() {
					$("#myModal").modal("show");;
				});
			</script>';		
		}
	
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
		
	}

	public function addJob()
	{
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/editor.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/jquery-ui.css" rel="stylesheet">';		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$header->render();	
		
		$this->loadview('admin/job/addJob')->render();
		
		$additional_scripts = '';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/editor.js"></script>	
		<script src="'.BASE_URL.'static/admin/js/jquery-ui.min.js"></script>		
		<script>$(document).ready(function() 
			{
				/* ADDING EDITOR */
				$(".txtEditor").Editor();
				
				/* ADDING MORE ACTIVITES */
			
				var max_fields      = 10; //maximum input boxes allowed
				var wrapper         = $("#menu1"); //Fields wrapper
				var add_button      = $(".add_field_button"); //Add button ID
				
				var x = 1; //initlal text box count
				$(add_button).click(function(e){ //on add input button click
					e.preventDefault();
					if(x < max_fields){ //max input box allowed
						x++; //text box increment
						$(wrapper).append(\'<div class=""><table class="table table-responsive"><tbody><tr> <th class="col-md-3" scope="row">Activites Name:</th> <td><div class="row"><div class="col-md-9"> <input name="jp_activity_name[]" id="jp_activity_name" type="text" class="form-control input small half-width"></div></div></td></tr><tr> <th scope="row">Select:</th> <td><label class="radio-custom"> <input type="radio" name="jp_start_stop_time1[]" value="fixed" id="jp_start_stop_time_fix" checked=""></label> <label class="radio-custom"> <input type="radio" name="jp_start_stop_time1[]" value="flexible" id="jp_start_stop_time_flex"></label></td></tr><tr> <th scope="row">Fixed:</th> <td><div class="row"> <div class="col-md-6"><div class="row"> <div class="col-md-6"><input type="text" name="jp_act_start_date[]" id="jp_act_start_date" class="form-control start_\'+ x +\' datePickStart_\'+ x +\'" placeholder="start date" data-grp="\'+ x +\'"> </div><div class="col-md-6"><input type="text" name="jp_act_start_time[]" id="jp_act_start_time" class="form-control input small watch-icon jp_act_start_time ui-timepicker-input" placeholder="start time" autocomplete="off"> </div></div></div><div class="col-md-6"><div class="row"> <div class="col-md-6"><input type="text" name="jp_act_end_date[]" id="jp_act_end_date" class="form-control end_\'+ x +\' datePickEnd_\'+ x +\'" placeholder="finish date" data-grp="\'+ x +\'"> </div><div class="col-md-6"><input type="text" name="jp_act_end_time[]" id="jp_act_end_time" class="form-control input small watch-icon jp_act_start_time ui-timepicker-input" placeholder="finish time" autocomplete="off"> </div></div></div></div></td></tr><tr> <th scope="row">Enter address:</th> <td><div class="row"> <div class="col-md-6"><div class="row"><div class="col-md-6"> <select onchange="onchangeState(this);" data-attribute="activityReunion1" name="jp_act_state[]" id="sel12 stateId" class="form-control input small states"> <option value="">Select State</option> </select> </div><div class="col-md-6"><select onchange="ourChange(this)" name="jp_act_city[]" id="sel13 cityId" class="form-control input small cities"> <option value="">Select City</option></select> </div></div></div><div class="col-md-6"><div class="row"> <div class="col-md-6"> <input type="text" class="form-control input small" name="jp_act_street[]" id="jp_act_street" placeholder="street"><!--<select name="" class="input small"> <option>street</option></select>--> </div><div class="col-md-6"><input type="text" class="form-control input small" name="jp_act_zip[]" id="jp_act_zip" placeholder="zip" onkeyup="isValidPostalCode(this)"><div class="messahe-zip"></div></div></div></div></div></td></tr><tr> <th scope="row">Name contact:</th> <td><div class="row"> <div class="col-md-6"><input name="jp_act_cont_fname[]" id="jp_act_cont_fname" type="text" placeholder="first name" class="form-control input small"> </div><div class="col-md-6"><input name="jp_act_cont_lname[]" id="jp_act_cont_lname" type="text" placeholder="last name" class="form-control input small"> </div></div></td></tr><tr> <th scope="row">Contact:</th> <td><div class="row"> <div class="col-md-6"><input name="jp_act_cont_phne[]" id="jp_act_cont_phne" type="number" placeholder="Phone" class="form-control input small"> </div><div class="col-md-6"><input name="jp_act_cont_email[]" id="jp_act_cont_email" type="email" placeholder="E-mail" class="form-control input small"> </div></div></td></tr><tr> <th scope="row">Notes/tasks:</th> <td><textarea class="form-control" name="jp_act_notes[]" rows="5" id="comment"></textarea></td></tr></tbody></table><a href="#" class="remove_field">Remove</a></div>\'); //add input box
					}
					
					/* DATE PICKER */
					$("#datePickStart_"+ x).datepicker({
						minDate: 0,
						numberOfMonths: 2,
						onSelect: function (selected) {
							var datagrp = $(this).attr(\'data-grp\');
							var end_currdatagrp= ".end"+ x+"[data-grp=\'"+datagrp+"\']";
							var start_currdatagrp= ".start_"+ x+"[data-grp=\'"+datagrp+"\']";
							$(end_currdatagrp).datepicker("option", "minDate", selected);
							console.log(end_currdatagrp);
						}
					});
				
				
					$("#datePickEnd_"+ x).datepicker({
						minDate: 1,
						numberOfMonths: 2,
						onSelect: function (selected) {
							var datagrp = $(this).attr(\'data-grp\');
							var start_currdatagrp= ".start_"+ x+"[data-grp=\'"+datagrp+"\']";
							$(start_currdatagrp).datepicker("option", "maxDate", selected);
						}
					});
					
					
				});
				
				$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
					e.preventDefault(); $(this).parent(\'div\').remove(); x--;
				});
				
				/* DATE PICKER */
				$(".datePickStart").datepicker({
					minDate: 0,
					numberOfMonths: 2,
					onSelect: function (selected) {
						var datagrp = $(this).attr(\'data-grp\');
						var end_currdatagrp= ".end[data-grp=\'"+datagrp+"\']";
						var start_currdatagrp= ".start[data-grp=\'"+datagrp+"\']";
						$(end_currdatagrp).datepicker("option", "minDate", selected);
						
					}
				});
				
				
				$(".datePickEnd").datepicker({
					minDate: 1,
					numberOfMonths: 2,
					onSelect: function (selected) {
						var datagrp = $(this).attr(\'data-grp\');
						var start_currdatagrp= ".start[data-grp=\'"+datagrp+"\']";
						$(start_currdatagrp).datepicker("option", "maxDate", selected);
					}
				});			
			});
		</script>';	
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();	
		
	}
	
	private function InsertJob()
	{
		echo"<pre>";
		print_R($_POST);
		echo"</pre>";
	}
	
	public function options()
	{
		$this->check_login();	// Session check
		$this->loadview('admin/main/include/header')->render();	
		if(isset($_GET['addOption']) && $_GET['addOption'] == 'addCountry')
		{	
			$value = $this->Options->get_single_row('option_name', 'show_country','flex_options');
			if(isset($_POST['submit']) && $_POST['countries'] != '')
			{					
				if(empty($value))
					{
						$countries = json_encode($_POST['countries']);
						$data = array(
						'option_name'=> 'show_country',
						'option_value'=> $countries
						);
						$results = $this->Options->Insert_options($data);
						$value = $this->Options->get_single_row('option_name', 'show_country','flex_options');	
					}
				else
					{
						$countries = json_encode($_POST['countries']);
						$data = array(						
						'option_value'=> $countries
						);
						$results = $this->Options->update($data,'option_name','show_country','flex_options');			
						$value = $this->Options->get_single_row('option_name', 'show_country','flex_options');
					}					
			}
				
				/* Getting Countries Listing */
			$Countries = $this->Options->get_countries();			
			$view = $this->loadview('admin/options/country');
			$view->set('Countries',$Countries);
			if(!empty($value))
			{	$view->set('values',$value);	}
			
			$view->render();
		}
		elseif(isset($_GET['addOption']) && $_GET['addOption'] == 'addnotification')
		{
			$view = $this->loadview('admin/options/notification');
			//$view->set('',);
			$view->render();
		}
		//$this->loadview('admin/options/index')->render();
		$this->loadview('admin/main/include/footer')->render();
	}
}