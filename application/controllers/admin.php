<?php 

class Admin extends Backend
{
	public $Users;
	public $Jobs;
	public $Options;
	public $Validate;
	public $Notification;
	public $Testimonials;
	public $Menu;
	public $Pages;
	public $Contracts;
	public $Payouts;
	public $Mailer;
		
	public function __construct()
	{
		ob_start();
		/* Loading Modals */
		$this->Users = $this->loadModel('Users','admin');
		$this->Jobs = $this->loadModel('jobs','admin');
		//$this->Notification = $this->loadModel('notification','admin');
		$this->Pages = $this->loadModel('page','admin');
		$this->Contracts = $this->loadModel('contracts','admin');
		
		/* Loading Helpers */
		$this->Options = $this->loadHelper('options');
		$this->Menu = $this->loadHelper('menu');
		$this->Validate = $this->loadHelper('validator');
		$this->Testimonials = $this->loadHelper('testimonials');
		$this->Notification = $this->loadHelper('notification');
		$this->Mailer = $this->loadHelper('sendmail');
		
		// Getting Stripe Gateway Credentials Form Admin
		$Keys = $this->Options->get_keys();
		$Deckeys = json_decode($Keys['option_value']);
		$this->Payouts = $this->loadHelper('FF_Payouts',$Deckeys);
		ob_end_flush();
	}
	
	
	public function logout()
	{
		session_destroy(); 
		$this->redirect('admin');
	}
	public function index()
	{		
		$this->check_login();	// Session check
		$header = $this->loadview('admin/main/include/header');
		$Noti = $this->getNotification('Header');
		$header->set('notifications',	$Noti);
		$header->render();
		
	
		$this->Users->query("SELECT id, COUNT(*) AS TOTAL, COUNT(IF(role='2',1,null)) as Emply, 
    COUNT(IF(role='3',1,null))as Contractors, (SELECT COUNT(*) FROM ".PREFIX."jobs) as JobsCount FROM ".PREFIX."users");
		$Counts = $this->Users->resultset();
		
		$view = $this->loadview('admin/main/index');
		$view->set('counts',$Counts);
		$view->render();
		
		$this->loadview('admin/main/include/footer')->render();		
	}
	
	public function login()
	{
		$additional_css = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';	
		$additional_js = '<script src="'.BASE_URL.'static/js/toastr.js"></script>';	
		
		if(isset($_POST['ad_login']) && $_POST['ad_pass'] != '' && $_POST['ad_email'] != '')
		{
			$pass = md5( $_POST['ad_pass'] );
			$email = $_POST['ad_email'];
			 
			$login = $this->Users->query("SELECT * FROM ".PREFIX."users WHERE email = '".$email."' AND password = '".$pass."' AND (role = '1' OR role = '5')");
			
			$results = $this->Users->resultset($login);
			
			if(!empty($results)):
				$_SESSION['admin'] = 'admin';
				$_SESSION['log_in'] = '1';	
				$_SESSION['user_id'] = $results[0]['id'];
				$this->redirect('admin/index');
			else:
				
				/* Render header  */
				$header = $this->loadview('admin/login/header');
				$header->set('additional_css',$additional_css);
				$header->render();
				
				$view = $this->loadview('admin/login/index');
				//$view->set('error','Invalid Email-ID or Password');
				$view->render();
				
				
				/* Render Footer */
				$footer = $this->loadview('admin/login/footer');
				$additional_js .='<script> toastr.error("Invalid Email-ID or Password", "Error!"); </script>';
				$footer->set('additional_js',$additional_js);
				$footer->render();
			endif;
			
		}
		else
		{
			
			
			if(isset($_GET['recover']) && !empty($_GET['recover'] ))
			{
			$results = $this->CheckToken($_GET['recover']);
				if( !empty($results))
				{
					$valid = 'Yes';		
				}
				else
				{					
					$valid = 'No';		
				}				
			}
			
			$header = $this->loadview('admin/login/header');
			$header->set('additional_css',$additional_css);
			$header->render();
			$this->loadview('admin/login/index')->render();
			$footer = $this->loadview('admin/login/footer');
			$footer->set('additional_js',$additional_js);
			if(isset($valid))
			{
				$footer->set('valid',$valid);				
			}
			$footer->render();
			
		}
		
	}
	
	public function CheckToken($token)
	{
		$recover = $this->Users->query("SELECT * FROM ".PREFIX."users WHERE token = '".$token."'");
		return $results = $this->Users->resultset($recover);
	}
	
	public function recover()
	{
		if( isset($_POST['action']) && $_POST['action'] == 'recoverPassword' ):
			$Password = $_POST['pass'];
			$token = $_POST['token'];
			$results = $this->CheckToken($token);
			if(!empty($results))
			{
				$Updated = $this->Users->update(array( 'password' => md5($Password), 'token'=>NULL ),'token',$token,PREFIX."users");
				echo json_encode( array( 'status'=>'success', 'message'=>'Password Reset Successfully' ) );
				
			}
			else
			{
				echo json_encode( array( 'status'=>'error', 'message'=>'Invalid Request' ) );
			}
			
			
		else:
			echo json_encode( array( 'status'=>'error', 'message'=>'Invalid Request' ) );
		endif;
		
		
	}
	
	public function forgotPassword()
	{
		if(isset($_POST['action']) && $_POST['action'] == 'resetPassword')
		{
				$email = $_POST['emailForgot'];				
				
				$login = $this->Users->query("SELECT * FROM ".PREFIX."users WHERE email = '".$email."' AND (role = '1' OR role = '5')");
				$results = $this->Users->resultset($login);				
				if( !empty($results) )
				{
					$timestamp = $this->Users->Timestamp();
					$token = base64_encode($email.$timestamp);			
					$this->Users->update(array( 'token' => $token  ),'id',$results[0]['id'],PREFIX."users");
					$Mailed = $this->Mailer->setparameters($to=$results[0]['email'],$subject="Reset Password",$message="Check",$from="sc7618009@gmail.com");					
					if($Mailed):
						echo json_encode( array( 'status'=>'success', 'message'=>'Please Check your Email For a Password Reset Link to reset Password' ) );
					else:
						echo json_encode( array( 'status'=>'error', 'message'=>'Please try Again the Process as Mail was not sent ! ' ) );
					endif;
					
				}
				else
				{
					echo json_encode( array( 'status'=>'error', 'message'=>'Email ID Does\'nt Exists !' ) );					
				}						
		}
		else
		{
			echo json_encode( array( 'status'=>'error', 'message'=>'Request not completed' ) );
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
			/* elseif($_POST['account'] == 'delete')
			{
				exit;
			} */
			/* elseif($_POST['account'] == 'reset')
			{
				$this->generateRandomPassword();
				exit;
			} */
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
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
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
				if($keys["role"] == 3)
				{
					$role = 'Contractor';
				}
				
				if($keys["role"] == 2)
				{
					$role = 'Employer';
				}
				
				$table .='<tr>';
					$table .='<td>'.$keys["first_name"].'</td>';
					$table .='<td>'.$keys["username"].'</td>';
					$table .='<td>'.$keys["email"].'</td>';
					$table .='<td>'.$role.'</td>';
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
						/* 
						$table .='<li><a rel="'.$keys["id"].'" id="reset" class="account" href="javascript:void(0)">Reset Password</a></li>						
						<li><a rel="'.$keys["id"].'" id="delete" class="account" href="javascript:void(0)">Delete Account</a></li>	 */					
					 $table .=' </ul>
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
						else if(attr == "reactivate")
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
						else if(attr == "reset")
						{
							
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
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
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
		/* Render Header */
				$additional_css = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';		
				$header = $this->loadview('admin/main/include/header');
				$header->set('additional_css',$additional_css);
				$Noti = $this->getNotification('Header');
				$header->set('notifications',$Noti);
				$header->render();
		
		if(isset($_GET['PersonID']))
		{
			if(isset($_POST['update_user']))
			{
				$Data = $this->Validate->sanitize($_POST); // You don't have to sanitize, but it's safest to do so.
				
				$this->Validate->validation_rules(array(
					'first_name'   => 'required|alpha',
					'last_name'    => 'required|alpha',
					'company'      => 'required',
					'email'        => 'required|valid_email',
					//'password'     => 'required|max_len,100|min_len,6',				
					'country'      => 'required',
					'role'		   => 'required'
				));	
				
				if(isset($_POST['password']))
				{
					$this->Validate->validation_rules(array(
					'password'     => 'required|max_len,100|min_len,6'					
					));	
				}
				
				
				$validated_data = $this->Validate->run($Data);
				$ValidEmail = $this->Users->Check_email($Data['email']);
				if($validated_data === false && $ValidEmail === false)
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
					 'role'=>$_POST['role'],
					 'connected_with'=>'EMAIL',
					 'created_date'=>date("Y-m-d"),
					 'modified_date'=>date("Y-m-d"),
					 'is_verified'=>'1'
					
					);
					
					if(isset($_POST['password']))
					{
						$data['password'] = md5($_POST['password']);
					}
				
				if(isset($_GET['PersonID']) && $_GET['PersonID'] == $_POST['user_id'] )
				{
					$result = $this->Users->Update_row($data,'id',$_GET['PersonID'],'flex_users');
					$this->pre($result);
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
				else
				{
					$this->redirect('admin/viewUsers');
					exit;
				}
				
			}
				
				
			}
			else
			{
				
				$Details = $this->Users->get_single_row('id',$_GET['PersonID'],'flex_users');
				if(empty($Details))
				{
					$this->redirect('admin/viewUsers');
					exit;
				}
				/* Body View Render */
				
				$options = $this->Options->show_countries($Details['country']);	// GEt Country list to show
				$addUser = $this->loadview('admin/users/edit');				
				$addUser->set('Fileds',$Details);
				$addUser->set('options',$options);
				$addUser->render();				
			}
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
									$("input[name=\'password\']").removeAttr("disabled");						 
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
	
	public function Validate_PageContent($Data)
	{
		
		//$Data = $this->Validate->sanitize($Data); // You don't have to sanitize, but it's safest to do so.

			$this->Validate->validation_rules(array(
				'page_title'	 => 'required',
				'tag_line'   	 => 'required',
				'content'      	 => 'required',
				'status'         => 'required',			
				'banner_image'   => 'required_file|extension,png;jpg'			
			));

			return $validated_data = $this->Validate->run($Data);			
			
	}
	
	
	
	
	
	private function Validate_Notification($Data)
	{
		$Data = $this->Validate->sanitize($Data); // You don't have to sanitize, but it's safest to do so.

			$this->Validate->validation_rules(array(
				'title'   => 'required|alpha_space',
				'message' => 'required',				
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
		/* if(isset($_GET['pop']) && $_GET['pop'] == 'on')
		{
			$user_slug = $this->Jobs->getJob('job_slug',$_GET['viewJob'],'flex_jobs');			
		} */
		
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
		';
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
		
		$Data = $this->Jobs->Get_all_jobs('flex_jobs');
		$view = $this->loadview('admin/job/index');
		$view->set('Details', $Data);
		/* if(isset($_GET['viewJob']) && $_GET['pop'] == 'on')	
		{
			$view->set('title', $user_slug['title']);
			$view->set('content',$user_slug['content']);
			$view->set('profile',$user_slug['profile']);			
			
		}	 */
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
		/* if(isset($_GET['pop']) && $_GET['pop'] == 'on')
		{
			$additional_scripts .= '
			<script>
				$(document).ready(function() {
					$("#myModal").modal("show");;
				});
			</script>';		
		} */
	
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
		
	}

	private function addJob()
	{
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/editor.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/jquery-ui.css" rel="stylesheet">';		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
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
	
	public function viewJob($Jobslug)
	{
		$this->check_login();
		if(empty($Jobslug))
		{	$this->redirect('/admin/allJobs');	}
		
		
		$header = $this->loadview('admin/main/include/header');
		//$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
	
		$user_slug = $this->Jobs->getJob('job_slug',$Jobslug,'flex_jobs');	
		if(empty($user_slug['title']))
		{	$this->redirect('/admin/allJobs'); exit;	}
		$view = $this->loadview('admin/job/view');
			$view->set('title', $user_slug['title']);
			$view->set('content',$user_slug['content']);
			$view->set('profile',$user_slug['profile']);
			$view->set('Activity',$user_slug['Activity']);
			$view->set('Payment',$user_slug['Payment']);
			$view->set('Description',$user_slug['Description']);
			$view->set('Attachment',$user_slug['Attachment']);
		$view->render();
		
			
		$footer = $this->loadview('admin/main/include/footer');
		//$footer->set('additional', $additional_scripts);
		$footer->render();
	}
	
	
	
	public function options()
	{
		
		$this->check_login();
		$additional_css ='<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">';
		$additional_css .= '
		<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';	
		$header = $this->loadview('admin/main/include/header');
		$Noti = $this->getNotification('Header');
		$header->set('additional_css',$additional_css);
		$header->set('notifications',$Noti);
		$header->render();
		
		$segment = explode('/',$_SERVER['REQUEST_URI']);		
		if($segment[3] == 'menu')
		{
			$data  = $this->Menu->getMenuList();
			
			$form = '';
			$form .= '<form method="GET" action="'.BASE_URL.'admin/options/menu/">
			<input type="hidden" name="action" value="edit">
			<select name="menuid"><option>Select Menu </option>';
			foreach($data as $keys)
			{
				if(isset($_GET['menuid']) && $_GET['menuid'] == $keys["id"])
				{
					$form .= '<option selected value="'.$keys["id"].'">'.$keys["option_value"].'</option>';
				}
				else
				{
					$form .= '<option value="'.$keys["id"].'">'.$keys["option_value"].'</option>';
				}
				
			}
			$form .= '</select>';
			$form .= '<input type="submit" value="Select"></form>';
			
			
				if(isset($_GET['action']) && $_GET['action'] == 'edit')
				{
					$MenuData = $this->Menu->Get_Menu($_GET['menuid']);	
					$PageData = $this->Pages->getPageList();	
					
					if($MenuData != NULL)
					{
						$view = $this->loadview('admin/options/menu/index');
						$view->set('menuName',$MenuData['menuName']);
						$view->set('page_list',$PageData);
						$view->set('menuList',$MenuData['menuList']);
						$view->set('menuid',$MenuData['menuID']);
						$view->set('menus',$form);						
						$view->set('action','');
						$view->set('button','<input type="button" name="Createmenu" value="Save Menu" class="btn btn-primary Savemenu">');
						$view->render();
					}
					else
					{
						$this->redirect("admin/options/menu");
						exit;
					}
				}
				else
				{
					$Action_Url = BASE_URL.'admin/CreateMenu';			
					$view = $this->loadview('admin/options/menu/index');
					$view->set('action',$Action_Url);
					$view->set('menus',$form);
					$view->set('button','<input type="submit" name="Createmenu" value="Create Menu">');
					$view->render();
				}
			
		}
		
		if($segment[3] == 'Country')
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
				
				// Getting Countries Listing 
			$Countries = $this->Options->get_countries();			
			$view = $this->loadview('admin/options/country');
			$view->set('Countries',$Countries);
			if(!empty($value))
			{	$view->set('values',$value);	}
			
			$view->render();
		}
		/* elseif(isset($_GET['addOption']) && $_GET['addOption'] == 'addnotification')
		{
			$view = $this->loadview('admin/options/notification');
			//$view->set('',);
			$view->render();
		}  */
		//$this->loadview('admin/options/index')->render();
		
		if($segment[3] == 'payment')
		{
			if(isset($_POST['save']))
			{
				
			
			$Data = $this->Validate->sanitize($_POST);	
			$this->Validate->validation_rules(array(
			'pb_key'   => 'required',
			'sk_key' => 'required',			
			));
			
			$this->Validate->filter_rules(array(
				'pb_key' => 'trim',
				'sk_key' => 'trim'				
			));
			
			if( isset($Data['live_mode']) )
			{
				$this->Validate->validation_rules(array(
				'Lpb_key'   => 'required',
				'Lsk_key' => 'required',			
				));
			
				$this->Validate->filter_rules(array(
					'Lpb_key' => 'trim',
					'Lsk_key' => 'trim'				
				));
			}
						
			$validated_data = $this->Validate->run($Data);
			
			
			if($validated_data === false)
				{		
					$errors = $this->Validate->get_errors_array();				
					$addUser = $this->loadview('admin/options/payment');
					$addUser->set('errors',$errors);
					$addUser->set('Fileds',$Data);					
					$addUser->render();					
				}
			else
				{
					
					$FData = $this->Options->get_keys();					
					$Array = array
						(
							'option_name' =>'flex_keys',
							'option_value' =>json_encode( $Data )
						);
					
					if(empty($FData))
					{
						$Result = $this->Options->Insert_options($Array);
					}
					else
					{
						if( $FData['id'] == $Data['id'] )
						{						
							$this->Options->edit_options($Array,'id',$FData['id']);
							$this->redirect("admin/options/payment");
							exit;						
						}
					} 
					
				}	
			}
			else
			{
				$Keys = $this->Options->get_keys();
				$Deckeys = json_decode($Keys['option_value']);
								
				if(isset($Deckeys->Lpb_key))
				{
					$LivePB = $Deckeys->Lpb_key;					
				}					
				else
				{
					$LivePB = NULL;
				}
				if(isset($Deckeys->Lsk_key))
				{
					$LiveSK = $Deckeys->Lsk_key;					
				}					
				else
				{
					$LiveSK = NULL;
				}
				if(isset($Deckeys->live_mode))
				{
					$LiveMode = $Deckeys->live_mode;					
				}					
				else
				{
					$LiveMode = NULL;
				}
				
				$fields = array( 'id'=>$Keys['id'],'pb_key'=>$Deckeys->pb_key,'sk_key'=> $Deckeys->sk_key,'Lpb_key'=>$LivePB,'Lsk_key'=> $LiveSK,'live_mode'=>$LiveMode);	
				$view = $this->loadview('admin/options/payment');
				$view->set('Fileds',$fields);			
				$view->render();
			}
		}
		
		$additional_scripts = '
					<script src="'.BASE_URL.'static/js/jquery.validate.min.js"></script>
					<script src="'.BASE_URL.'static/js/jquery.nestable.js"></script>
					<script src="'.BASE_URL.'static/js/toastr.js"></script>
					<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
					<script>
					$(document).ready(function(){
						
						
						$(".alert-danger").fadeTo(2000, 500).slideUp(500, function(){
							$(".alert-danger").slideUp(1000);
						});

						$(".dd").nestable();	
						$(\'.AddItem\').click(function(){
							$("#MenuForm").validate({
								rules: {
										 Title: {
											required: true               
										},
										UrlAddress: {
											required: true,
											url: true									
										}
									}									
							});
							if ($("#MenuForm").valid()) // check if form is valid
							{
								var title = $("#title").val();
								var url = $("#url").val();	
								
								$(\'#menus\').append(\'<li data-value="\'+url+\'" data-name="\'+title+\'" class="dd-item"><div class="dd-handle">\'+title+\' <i class="fa fa-window-close dd-nodrag" aria-hidden="true"></i></div></li>\');
								
								$("#MenuForm")[0].reset();													
							}				
						});
						
						$(".Savemenu").click(function()
						{
							var meName = jQuery.trim($("input[name=\'menu_name\']").val());	
							var meNameID = $("input[name=\'menu_name\']").attr("rel");	
							if( meName != "" )
							{
								$.ajax({
										type:"POST",
										url:"'.SITEURL.'admin/UpdateMenu",
										data:{menu:$(\'.dd\').nestable(\'serialize\'),menuname:meName,menuID:meNameID},
										success:function(res)
											{
												toastr.success("Menu Updateds", "Successfully");									
											}
										});
							}
							else
							{
								
								toastr.error("Menu Name Can\'t be Empty ! ", "Successfully");
							}								
						})
						
						
						$(".page_menu").click(function()
						{
							$(".page_li:checked").each(function(){
							
								//alert($(this).attr("value"));
								var title = $(this).attr("value");
								var url = $(this).attr("url");	
													
								$(\'#menus\').append(\'<li data-value="\'+url+\'" data-name="\'+title+\'" class="dd-item"><div class="dd-handle">\'+title+\' <i class="fa fa-window-close dd-nodrag" aria-hidden="true"></i></div></li>\');

								
							})
							$(".page_li").prop("checked", false);
						});

						$(document).on("click",".dd-nodrag",function(){
						$(this).closest("li").remove();

						});
						
					});
					</script>';		
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
		
		
	}
	
	/* 
	*	Page Section 
	*	@Author Chhavinav Sehgal
	*	Admin Section
	*/
	
	public function pages()
	{
		$this->check_login();		// Checking Login Session
		$segement = explode('/',$_SERVER['REQUEST_URI']);
		
		if( isset($segement[3]) && $segement[3] == "addNew" )
		{
			if(isset($_POST['save']))
			{
				$content = array_merge($_POST,$_FILES);
				if($this->Validate_PageContent($content) === false)
				{		
					$errors = $this->Validate->get_errors_array();					
					$this->CreatePage($errors,$content);
					
				}
				else
				{
				$ID = $this->Pages->SavePage($content);
				$this->redirect("admin/pages/editPage/$ID");
				exit; 
				
				}
			}
			else
			{
				$this->CreatePage();
			}
		}
		elseif( isset($segement[3]) && $segement[3] == "editPage" )
		{
			if(!empty($segement[4]))
			{				
				if(isset($_POST['update']))
				{
					$content = array_merge($_POST,$_FILES);
					$this->Pages->UpdatePage($content,$segement[4]);
					$this->redirect("admin/pages/editPage/".$segement[4]);
					exit; 
				}
				else
				{
					$this->EditPage($segement[4]);
				}
			}
			else
			{
				$this->redirect("admin/pages");
				exit;
			}
			
		}
		elseif( isset($segement[3]) && $segement[3] == "Trash" )
		{
			if(isset($_POST['deletPage']) && $_POST['ID'] != "")
			{
				$this->Pages->removePageData($_POST['ID']);
				exit;
			}
			elseif( isset($_POST['restorePage']) && $_POST['ID'] != "" )
			{
				$this->Pages->RestorePage($_POST['ID']);
				exit;
			}
			else
			{
				$this->getTrash();
			}
			
		}		
		else
		{
		
		if(isset($_POST['trashPage']) && $_POST['ID'] != "")
		{
			$this->Pages->trashPage($_POST['ID']);
			exit;
		}		
			
			/* Header Section */
		
		$additional_css = '
		<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';				
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
		
		$heading = '<h1 class="page-header">Pages 
					<a href="'.SITEURL .'admin/pages/addNew"><button type="button" class="btn btn-outline btn-primary">Add Page</button></a>
					<a href="'.SITEURL .'admin/pages/Trash"><button type="button" class="btn btn-outline btn-primary">Trash Page</button></a>					
					</h1>';				
		
		/* Content Section */
		$table = $this->Pages->getAll();
		$view = $this->loadview('admin/pages/index');
		$view->set('heading',$heading);
		$view->set('Details',$table);
		$view->render();
		
		/* Footer Section */
		$additional_scripts = '';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/tinymce.js"></script>
		<script src="'.BASE_URL.'static/js/toastr.js"></script>
		<script>
		$(document).ready(function() 
			{
				$(".Del_page").click(function(){
					var Confirm = confirm(" Are Your Sure ! You Want to Delete This Page");
						if (Confirm == true) 
						{
							var ID = $(this).attr("id");
							$.ajax({
								type:"POST",
								URL:"http://force.imarkclients.com/admin/pages/",
								data:{"ID":ID,"trashPage":"DEL"},
								success:function(res)
								{
									toastr.success("Page Trashed !", "Successfully");
									setTimeout(function(){ location.reload(); }, 3000);
									
								}
							});
						} 
				})
			});
		</script>';		
		$footer = $this->loadview('admin/main/include/footer');	
		$footer->set('additional', $additional_scripts);		
		$footer->render();
		}
		
	}
	
	private function CreatePage($error = NULL, $Data = NULL)
	{
		
		/* Header Section */
		$additional_css = '<link href="'.BASE_URL.'static/admin/css/jquery-ui.css" rel="stylesheet">';		
		$header = $this->loadview('admin/main/include/header');
		//$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();	
		
		/* Content Section */
		$view = $this->loadview('admin/pages/add');
		if($error != '' && $Data != '')
		{
			$Array =array();
			$Array['title'] = $Data['page_title'];
			$Array['tag_line'] = $Data['tag_line'];
			$Array['content'] = $Data['content'];
			
			
			$view->set('errors',$error);
			$view->set('data',$Array);
		}
		
		$view->render();
		
		
		/* Footer Section */
		$additional_scripts = '';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/tinymce.js"></script>
		<script src="'.BASE_URL.'static/js/jquery.validate.min.js"></script>
		<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
		<script>
		$(document).ready(function() 
			{
				tinymce.init({ selector:"textarea",plugins: [
    "advlist autolink lists link image charmap print preview hr anchor pagebreak a11ychecker",
    "searchreplace wordcount visualblocks visualchars code fullscreen tinymcespellchecker advcode",
    "insertdatetime media nonbreaking save table contextmenu directionality",
    "template paste textcolor colorpicker textpattern imagetools codesample toc help emoticons hr"] });
				/* ADDING EDITOR */
				//$(".txtEditor").Editor();
				 //CKEDITOR.replace( "editor1" );
			   //CKEDITOR.replaceAll( "txtEditor" );
			   
		  	
			$("#myform").validate({
				  rules: {
					page_title: "required",
					tag_line: "required",
					content: "required",
					status: "required",
					banner_image: {					 
					  extension: "png|jpg|jpeg"
					}					
				  },
				  messages: 
				  {
					page_title: "Please specify Page Title !",
					tag_line: "Please specify Tag Line !",
					content: "Page Content Can\'t be empty !",
					status: "Page Published is required ! "	,				
					banner_image: "Only PNG JPEG Format Allowed "	,				
				  }
				});
			   
			});
			function readURL(input) {			
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(\'#blah\').attr(\'src\', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }		
		</script>';
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
		
	
	}
	
	private function EditPage($ID)
	{
		
		$Data =  $this->Pages->get_single_row('id', $ID,PREFIX .'pages');
		if(empty($Data))
		{
			$this->redirect("admin/pages");
			exit;
		}			
		
				/* Header Section */
		$additional_css = '<link href="'.BASE_URL.'static/admin/css/jquery-ui.css" rel="stylesheet">';		
		$header = $this->loadview('admin/main/include/header');
		//$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();	
		
		/* Content Section */
		$view = $this->loadview('admin/pages/add');
		$view->set('data',$Data);
		$view->render();
		
		
		/* Footer Section */
		$additional_scripts = '';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/tinymce.js"></script>
		<script>
		$(document).ready(function() 
			{
				tinymce.init({ selector:"textarea", plugins: ["code"] });
				/* ADDING EDITOR */
				//$(".txtEditor").Editor();
				 //CKEDITOR.replace( "editor1" );
			   //CKEDITOR.replaceAll( "txtEditor" );
			});
		function readURL(input) {			
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(\'#blah\').attr(\'src\', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }	
            }	
		</script>';
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
	}
	
	private function getTrash()
	{
		
		/* Header Section */
		$additional_css = '
		<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';				
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
		
		$heading = '<h1 class="page-header">Trash Pages 
						<a href="'.SITEURL .'admin/pages/addNew"><button type="button" class="btn btn-outline btn-primary">Add Page</button></a>
						<a href="'.SITEURL .'admin/pages/"><button type="button" class="btn btn-outline btn-primary">Pages</button></a>
					</h1>';		
		
		/* Content Section */
		$table = $this->Pages->getTrashs();
		$view = $this->loadview('admin/pages/index');
		$view->set('heading',$heading);
		$view->set('Details',$table);
		$view->render();
		
		/* Footer Section */
		$additional_scripts = '';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/tinymce.js"></script>
		<script src="'.BASE_URL.'static/js/toastr.js"></script>
		<script>
		$(document).ready(function() 
			{
				$(".Del_page").click(function(){
					var Confirm = confirm(" Are Your Sure ! You Want to Delete This Page");
						if (Confirm == true) 
						{
							var ID = $(this).attr("id");
							$.ajax({
								type:"POST",
								URL:"http://force.imarkclients.com/admin/pages/",
								data:{"ID":ID,"deletPage":"DEL"},
								success:function(res)
								{
									toastr.success("Have fun storming the castle!", "Miracle Max Says");
									setTimeout(function(){ location.reload(); }, 3000);
								}
							});
						} 
						else 
						{
							console.log("You pressed Cancel!");
						}
				})
				
				$(".Res_page").click(function()
				{					
					var ID = $(this).attr("id");
					$.ajax({
						type:"POST",
						URL:"http://force.imarkclients.com/admin/pages/",
						data:{"ID":ID,"restorePage":"RES"},
						success:function(res)
						{
							toastr.success("Page Restored !", "Successfully");
							setTimeout(function(){ location.reload(); }, 3000);
						}
					});
				})
				
				
				
			});
		</script>';		
		$footer = $this->loadview('admin/main/include/footer');	
		$footer->set('additional', $additional_scripts);		
		$footer->render();
		
	}
	/* 
	*	Menu Section 
	*	@Author Chhavinav Sehgal
	*	Admin Section
	*/
	
	public function CreateMenu()
	{
		$this->check_login();
		if(isset($_POST['Createmenu']))
			{
				$data = array(
					'option_name'=> 'menu',
					'option_value'=> $_POST['menu_name']
					);
				
				$menu_id = $this->Menu->Insert_Menu($data);	
				
				$menulist = array(
					'option_name'=> 'menuList_'.$menu_id,
					'option_value'=> ''
					);
				
				$this->Menu->Insert_Menu($menulist);
				$this->redirect("admin/options/menu/?action=edit&menuid=$menu_id");
				exit;
			}
			
	}
	
	
	public function UpdateMenu()
	{
		if(isset($_POST['menu']) || isset($_POST['menuname']) && isset($_POST['menuID']) )
		{
			if(isset($_POST['menu']))
			{
				$MenuList = json_encode($_POST['menu']);
				$MenuName = $_POST['menuname'];
				$menuid   = $_POST['menuID'];	
				$result = $this->Menu->UpdateMenuList($MenuName,$menuid,$MenuList);				
			}
			elseif(isset($_POST['menuID']))
			{
				$MenuName = $_POST['menuname'];
				$menuid   = $_POST['menuID'];	
				$result = $this->Menu->UpdateMenuName($MenuName,$menuid);				
			}
			
		}
		else
		{
			$this->redirect("admin/options/menu/");
			exit;
		}
		
	}
	
	
	
	
	/* 
	*	Notification Setting Section Start
	*	@Author Chhavinav Sehgal
	*	Admin Section
	*/
	
	private function AddNotification()
	{
		/* Header Section */
		$additional_css = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$header->render();
		
		/* Post Request To Insert */
			if(isset($_POST['add_notification']))
			{
				$Data = $_POST;
				if($this->Validate_Notification($Data) === false)
				{		
					$errors = $this->Validate->get_errors_array();					
					$addNotification = $this->loadview('admin/notification/add');
					$addNotification->set('error',$errors);
					$addNotification->set('Fileds',$Data);					
					$addNotification->render();
					
				}
				else
				{
					$date = new DateTime();					
					$data =  array(
					 'title'=>$_POST['title'], 
					 'message'=>$_POST['message'],					 
					 'CreatedOn'=>$date->getTimestamp(),
					 'ModifiedOn'=>$date->getTimestamp(),					 
					 'visible'=>'0',					 
					);
					
					$result = $this->Notification->InsNotification($data);
					if($result)
					{
						$msg_success = 'Notification Inserted Successfully';
					}
					else
					{
						$msg_error = 'Notification not inserted Please Try Again After Some Time';
					}
					
					
					$addNotification = $this->loadview('admin/notification/add');				
					if(isset($msg_success))
					{
						$addNotification->set('Success',$msg_success);
					}
					elseif(isset($msg_error))
					{
						$addNotification->set('Error',$msg_error);
					}				
					$addNotification->render();
					
					
				}
		/* END POST */
			}
			else
			{
				/* Main Section */
					$addNotification = $this->loadview('admin/notification/add');
					//$addNotification->set('options',$options);
					$addNotification->render();
				
				/* End Main */				
			}
			
		/* Footer Section */
		$additional_scripts = '
		<script src="'.BASE_URL.'static/js/toastr.js"></script>	
		<script src="'.BASE_URL.'static/js/jquery.validate.min.js"></script>
		<script>
		$(document).ready(function(){
			/* Validation  */
			$(".add_noti").click(function(){
					
			$("#myform").validate({
				  rules: {
					title: "required",
					message: "required"					
					},
				  messages: 
				  {
					title: "Please specify Notification Title !",
					message: "Please specify Notification Message !"
					
				  }
				});
			})		 
			
			
		});
		</script>
		';		
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
	}
	
	private function EditNotification()
	{
		if(isset($_GET['ID_Notify']))
		{
				/* Header Section */
			$additional_css = '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';		
			$header = $this->loadview('admin/main/include/header');
			$header->set('additional_css',$additional_css);
			$header->render();
			
			
			if(isset($_POST["edit_notification"]))
			{
			
			/* Post Request To Insert */
				if(isset($_POST['edit_notification']))
				{
					$Data = $_POST;
					if($this->Validate_Notification($Data) === false)
					{		
						$errors = $this->Validate->get_errors_array();					
						$addNotification = $this->loadview('admin/notification/edit');
						$addNotification->set('error',$errors);
						$addNotification->set('Fileds',$Data);					
						$addNotification->render();
						
					}
					else
					{
						$date = new DateTime();					
						$data =  array(
						 'title'=>$_POST['title'], 
						 'message'=>$_POST['message'],					 
						 'CreatedOn'=>$date->getTimestamp(),
						 'ModifiedOn'=>$date->getTimestamp(),					 
						 'visible'=>'0',					 
						);
						
						$result = $this->Notification->UpdateNotification($data,'id',$_GET['ID_Notify']);
						if($result == "success")
						{
							$msg_success = 'Notification Updated Successfully';
						}
						else
						{
							$msg_error = 'Notification not updated Please Try Again After Some Time';
						}
						
						
						$addNotification = $this->loadview('admin/notification/viewNotification');				
						if(isset($msg_success))
						{
							$addNotification->set('Success',$msg_success);
						}
						elseif(isset($msg_error))
						{
							$addNotification->set('Error',$msg_error);
						}				
						$addNotification->render();
						
						
					}
			/* END POST */
				}
			}
				else
				{
					$Details = $this->Notification->get_single_row('id',$_GET['ID_Notify'],'flex_notification_message');
					if(empty($Details))
					{
						$this->redirect('admin/viewNotification');
						exit;
					}
					/* Main Section */
						$addNotification = $this->loadview('admin/notification/edit');
						$addNotification->set('Fileds',$Details);	
						$addNotification->render();
					
					/* End Main */				
				}
				
			/* Footer Section */
			$additional_scripts = '
			<script src="'.BASE_URL.'static/js/toastr.js"></script>	
			<script src="'.BASE_URL.'static/js/jquery.validate.min.js"></script>
			<script>
			$(document).ready(function(){
				/* Validation  */
				$(".add_noti").click(function(){
						
				$("#myform").validate({
					  rules: {
						title: "required",
						message: "required"					
						},
					  messages: 
					  {
						title: "Please specify Notification Title !",
						message: "Please specify Notification Message !"
						
					  }
					});
				})		 
				
				
			});
			</script>
			';		
			$footer = $this->loadview('admin/main/include/footer');
			$footer->set('additional', $additional_scripts);
			$footer->render();
		}
		else
		{
			$this->redirect('admin/viewNotification');
			exit;
		}
	}
	
	private function viewNotification()
	{
		/* Header Section */
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
		';		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$header->render();
		
		/* Main Section */
		$Data = $this->Notification->ShowNotification();
		$table = '';
		$table .= ' <table width="100%" class="table table-striped table-bordered table-hover dataTables">';		
		$table .= '<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Message</th>						<th>Action</th>											
					</tr>
				</thead>';	
		$table .='<tbody>';		
			foreach($Data as $keys)
			{
				$table .='<tr>';
					$table .='<td>'.$keys["id"].'</td>';
					$table .='<td>'.$keys["title"].'</td>';
					$table .='<td>'.$keys["message"].'</td>';					
					$table .='<td>								
					<div class="btn-group">
					  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Action <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">';					
						$table .='<li><a href="'.SITEURL.'admin/editNotification/?ID_Notify='.$keys["id"].'">Edit Notification</a></li>						
						<li><a rel="'.$keys["id"].'" href="javascript:void(0)" class="delete">Delete Notification</a></li>						
					  </ul>
					</div></td>';		
				$table .='</tr>';				
			}
		$table .='</tbody></table>';
		
		$view = $this->loadview('admin/notification/index');
		$view->set('Details', $table);		
		$view->render();
		
		
		
		/* Footer */
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
			$(document).on(\'click\',\'.delete\',function() 
			{
				var id = $(this).attr("rel");
				
			}
		</script>';
		
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
		
	}
	
	private function deleteNotification()
	{
		$this->Notification->DelNotification($data,'id',$_GET['ID_Notify']);		
	}
	
	public function getNotification( $position = NULL )
	{
		if( $position == 'Header')
		{
			$getList = $this->Notification->get_notification(1);
					
			$list = '';
			$list .= $getList[0];
			$list .= '<li class="divider"></li>';
			$list .= $getList[1];
			$list .= '<li class="divider"></li>';
			$list .= $getList[2];
			$list .= '<li class="divider"></li>';
			return $list;
		}
		else
		{
			$additional_css = '
			<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
			<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
			';		
			$header = $this->loadview('admin/main/include/header');
			$header->set('additional_css',$additional_css);
			$Noti = $this->getNotification('Header');
			$header->set('notifications',$Noti);
			$header->render();
			
			
			
			$list = $this->Notification->get_notification(1);
			$table = '';
			$table .= ' <table width="100%" class="table table-striped table-bordered table-hover dataTables">';
			$table .= '<thead>
					<tr>						
						<th>Notifications</th>
					</tr>
				</thead>';	
			$table .='<tbody style="list-style-type: none;">';		
			foreach( $list as $keys ):
				$table .='<tr><td>'.$keys.'</td></tr>';	
			endforeach;
			$table .='</tbody></table>';
				
			
			$view = $this->loadview('admin/notification/index');
			$view->set('Details', $table);		
			$view->render();
			
			
			$additional_scripts = '
			<script src="'.BASE_URL.'static/admin/js/jquery.dataTables.min.js"></script>
			<script src="'.BASE_URL.'static/admin/js/dataTables.bootstrap.min.js"></script>
			<script src="'.BASE_URL.'static/admin/js/dataTables.responsive.js"></script>
			<script>$(document).ready(function() {
				$(".dataTables").DataTable();
				});				
			</script>';
			
			$footer = $this->loadview('admin/main/include/footer');
			$footer->set('additional', $additional_scripts);
			$footer->render();	
		}
		
	}
	
	/* 
	*	Testimonials Section 
	*	@Author Chhavinav Sehgal
	*	Admin Section
	*/
	
	public function testimonials()
	{
		$this->check_login();		// Checking Login Session
		$segement = explode('/',$_SERVER['REQUEST_URI']);
		
		
		/* Header Section */
		$additional_css = '<link href="'.BASE_URL.'static/admin/css/jquery-ui.css" rel="stylesheet">';	
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();	
		
		
		
		if(isset($segement[3]) && $segement[3] == "add")
		{
			if(isset($_POST['save']))
			{
				$Data = $_POST;
				$validated_data = $this->validateTestimonials($Data);
				
				
				if($validated_data === false)
				{		
					$errors = $this->Validate->get_errors_array();					
					$view = $this->loadview('admin/testimonials/add');
					$view->set('error',$errors);
					$Data["segment"] = "add";
					$view->set('data',$Data);					
					$view->render();
					
				}
				else
				{
					$return = $this->Testimonials->Insert_Testimonials($Data);
					$this->redirect("admin/testimonials/edit/$return");					
				}
			}
			else
			{
				/* Content Section */
				$view = $this->loadview('admin/testimonials/add');
				$view->render();	
			
			}
				
		}
		elseif(isset($segement[3]) && $segement[3] == "edit")
		{
			if(isset($segement[4]))
			{
				/* Editing Testimonials */
				if(isset($_POST['update']))
					{
						$Data = $_POST;
						unset($Data['update']);
						$validated_data = $this->validateTestimonials($Data);
						
						if($validated_data === false)
							{		
								$errors = $this->Validate->get_errors_array();					
								$view = $this->loadview('admin/testimonials/edit/'.$segement[4]);
								$view->set('error',$errors);
								$view->set('Data',$Data);					
								$view->render();
								
							}
							else
							{
								$return = $this->Testimonials->Edit_Testimonials($Data,$segement[4]);
								$this->redirect("admin/testimonials/edit/".$segement[4]."/?success=".$return);					
							}
					}
				else
					{
						$Data = $this->Testimonials->getTestimonial($segement[4]);
						$view = $this->loadview('admin/testimonials/add');
						$Data["segment"] = "edit";
						$view->set('data',$Data);
						if(isset($_GET['success']))
						{
							$view->set('Msg',$_GET['success']);												
						}
						$view->render();					
					}
			}
			else
			{
				$this->redirect("admin/testimonials/");	
			}			
		}
		else
		{
			echo "ELSE";
		}
		
		/* Footer Section */
		$additional_scripts = '';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/tinymce.js"></script>
		<script>
		$(document).ready(function() 
			{
				tinymce.init({ selector:"textarea" });
				
			});
		</script>';
		
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();		
		
	}
	
	private function validateTestimonials($Content)
	{
		
		$Data = $this->Validate->sanitize($Content); // You don't have to sanitize, but it's safest to do so.

		$this->Validate->validation_rules(array(
			'clientName'   => 'required|alpha_space',
			'content' => 'required',				
			'rating' => 'required|numeric',				
		));
 
		return $validated_data = $this->Validate->run($Data);
		
		
	}
	
	
	/* 
	*	Contracts Section 
	*	@Author Chhavinav Sehgal
	*	Admin Section
	*/
	
	public function contracts()
	{
		$this->check_login();
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
		';
		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
		
		$Data = $this->Contracts->Get_all_contracts(PREFIX.'hire_contractor');
		$view = $this->loadview('admin/contracts/index');
		$view->set('Details', $Data);
		
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
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();
			
			
	}
	
	public function Viewcontracts($contractSlug)
	{
		$this->check_login();
		
		$additional_css = '
		<link href="'.BASE_URL.'static/admin/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="'.BASE_URL.'static/admin/css/dataTables.responsive.css" rel="stylesheet">
		';		
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
		
		
		echo $ContractID = base64_decode($contractSlug);
		// Get Contract Details 
		$Contract = $this->Contracts->get_table_data(PREFIX.'hire_contractor','id',$ContractID,'ASC');
		if(!empty($Contract[0]))
		{
			$Activ = $this->Contracts->getActivity($Contract[0]['job_id'],json_decode($Contract[0]['activity_id']));			
			$paymentDetails = $this->Contracts->getpaymentDetails($Contract[0]['job_id'],$ContractID);
			
			$Job =  $this->Jobs->get_single_row('id',$Contract[0]['job_id'],PREFIX.'jobs');
			
			$Data = $this->Contracts->Get_all_contracts(PREFIX.'hire_contractor');
			$view = $this->loadview('admin/contracts/view');
			$view->set('jotTitle', $Job['job_title'] );
			$view->set('paymentDetails', $paymentDetails );
			$view->set('Activ', $Activ );
		
			$view->render();
		}
		else
		{
			$this->redirect("admin/contracts");
			exit;			
		}
		$footer = $this->loadview('admin/main/include/footer');
		//$footer->set('additional', $additional_scripts);
		$footer->render();
			
		
	}
	
	
	/* 
	*	Messaging Section 
	*	@Author Chhavinav Sehgal
	*	Admin Section
	*/
	
	public function inbox()
	{
		$this->check_login();
		$this->recentChatList();
		$additional_css = '';
		$additional_css .= '<link href="'.BASE_URL.'static/admin/css/admin.css" rel="stylesheet">';
		$additional_css .= '<link href="'.BASE_URL.'static/admin/css/dropzone.css" rel="stylesheet">';
		$additional_css .= '<link href="'.BASE_URL.'static/admin/css/emoji.css" rel="stylesheet">';
		$additional_css .= '<link href="'.BASE_URL.'static/css/toastr.css" rel="stylesheet">';
		$header = $this->loadview('admin/main/include/header');
		$header->set('additional_css',$additional_css);
		$Noti = $this->getNotification('Header');
		$header->set('notifications',$Noti);
		$header->render();
		
		// Get Contact Listing
		$contactlist = $this->allContactList();
		$recentChatList = $this->recentChatList();
		$view = $this->loadview('admin/inbox/index');	
		$view->set('contactlist',$contactlist);		
		$view->set('recentChatList',$recentChatList);		
		$view->render();
		
		
		$additional_scripts = '';
		$additional_scripts .= '<script> var admin_url = "'.SITEURL.'admin/"; </script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/dropzone.js"></script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/js/toastr.js"></script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/custom-dropzone.js"></script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/config.js"></script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/util.js"></script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/jquery.emojiarea.js"></script>';
		$additional_scripts .= '<script src="'.BASE_URL.'static/admin/js/emoji-picker.js"></script>';
		$additional_scripts .= '<script>
		$(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: "[data-emojiable=true]",
          assetsPath: "'.BASE_URL.'static/admin/images/",
          popupButtonClasses: "fa fa-smile-o"
        });       
        window.emojiPicker.discover();
      });
		
		
		</script>';
		$footer = $this->loadview('admin/main/include/footer');
		$footer->set('additional', $additional_scripts);
		$footer->render();	
	}
	
	public function InsertInbox()
	{
		$this->check_login();
		
		if(isset($_FILES) && !empty($_FILES))
		{
			$ID = $this->saveAttachment($_FILES,$_POST['fromUserId']);
			if( $ID == 0 )
			{
				echo json_encode( array('status'=>'error','message'=>'File Not uploaded !'));
				die();
			}
			else
			{
				$AttachmentID = $ID;				
			}
			
		}
		
		if(isset($_POST) && !empty($_POST['ConversionId']))
		{
			
			if(isset($AttachmentID))
			{
				$Attachment_ID = $AttachmentID;
			}
			else
			{
				$Attachment_ID = 0;
			}
			
			if(isset($_POST['message']) && !empty( $_POST['message'] ))
			{
				$message = $_POST['message'];
			}
			else
			{
				$message = NULL;
			}
			
			$data = array(
				'conv_id'		=>	$_POST['ConversionId'],
				'to_id'			=>	$_POST['toUserId'],
				'from_id'		=>	$_POST['fromUserId'],
				'message'		=>	$message,
				'attachment'	=>	$Attachment_ID,
				'message_time'	=>	$this->Users->Timestamp(),
				);	
			
			
			if( $Attachment_ID != 0 && $message == NULL )
			{
				$Ins = $this->Users->Insert($data,PREFIX.'message_set');
				if( $Ins )
				{
					echo json_encode( array('status'=>'success') );
				}	
			}
			elseif( $Attachment_ID == 0 && $message != NULL )
			{
				$Ins = $this->Users->Insert($data,PREFIX.'message_set');
				if( $Ins )
				{
					echo json_encode( array('status'=>'success') );
				}
			}
			elseif( $Attachment_ID != 0 && $message != NULL )
			{
				$Ins = $this->Users->Insert($data,PREFIX.'message_set');
				if( $Ins )
				{
					echo json_encode( array('status'=>'success') );
				}
			}
			else
			{
				echo json_encode( array('status'=>'error','message'=>'This Action is not Allowed !') );
			}					
		}
	}
	
	public function allContactList()
	{ 
		$this->check_login();
		$result = $this->Users->get_table_data(PREFIX.'users','is_verified',1,'ASC');
		$list = ''; 
		$list .= '<ul>';		
		foreach($result as $keys):
		if( $keys['role'] == 1 ): $role = 'Super Admin'; elseif( $keys['role'] == 2 ): $role = 'Employer'; elseif( $keys['role'] == 3 ): $role = 'Contractor'; elseif( $keys['role'] == 5 ): $role = 'Admin'; endif;
		
			if( $keys['id'] != $_SESSION['user_id'] ):
				$list .='<li class="chatme" data-role="'.$keys["role"].'" data-userID="'.$keys["id"].'">'.$keys["username"].' ( '.$role.' ) </li>';
			endif;
		endforeach;
		$list .= '</ul>';
		return $list;
	}
	
	public function saveAttachment($File,$AuthorID)
	{
		//print_R($File);
		$attachment 	= 	$File;	 
		$filname 		= 	$File['file']['name'];	 
		$target_file	=	time().'__'.$filname;
		$uri			=	ABSPATH.'/static/attachments/'.$target_file;
		
		$url = "static/attachments/".$target_file."";
		
		if (move_uploaded_file($File['file']["tmp_name"], $uri)) 
		{
			$Attachment_url = $url;
			$Attachment_Location = 'Message';
			$Attachment_Author = $AuthorID;		
			
			$data = array(
				'url'					=>$Attachment_url,
				'attachment_location'	=>$Attachment_Location,
				'attachment_author'		=>$Attachment_Author,
				'created_date'			=>$this->Users->Timestamp(),
				'modified_date'			=>$this->Users->Timestamp(),
			);
			return $AttachmentID = $this->Users->Insert($data,PREFIX.'attachments');
		} 
		else 
		{
			return 0;
		}		
	}
	
	public function recentChatList()
	{
		$query = $this->Users->query("SELECT if(to_id=1,from_id,to_id)as userID FROM ".PREFIX."message_set WHERE to_id = ".$_SESSION['user_id']." OR from_id = ".$_SESSION['user_id']." GROUP BY `conv_id` ORDER BY `ID` DESC");
		$results = $this->Users->resultset($query);
		
		$list = ''; 
		foreach($results as $userKeys):	
			$keys = $this->Users->get_single_row('id',$userKeys['userID'],PREFIX.'users');
			$list .= '<ul>';			
				if( $keys['role'] == 1 ): $role = 'Super Admin'; elseif( $keys['role'] == 2 ): $role = 'Employer'; elseif( $keys['role'] == 3 ): $role = 'Contractor'; elseif( $keys['role'] == 5 ): $role = 'Admin'; endif;
				
					if( $keys['id'] != $_SESSION['user_id'] ):
						$list .='<li class="chatme" data-role="'.$keys["role"].'" data-userID="'.$keys["id"].'">'.$keys["username"].' ( '.$role.' ) </li>';
					endif;
				
			$list .= '</ul>';
		endforeach;
		return $list;
		
	}
	
	public function getConversionID()
	{
		$this->check_login();
		$ToUserID = $_POST['ToUserID'];
		$FromUserID = $_POST['FromUserID'];		
		
		if(!empty($ToUserID) && $ToUserID != 0  && $FromUserID != 0 && !empty($FromUserID) ):
		$getConversionID = $this->Users->query("SELECT * FROM ".PREFIX."conversation_set WHERE ( conv_to = '".$ToUserID."' AND conv_from = '".$FromUserID."' ) OR ( conv_to = '".$FromUserID."' AND conv_from = '".$ToUserID."' )");
			
		$IDResult = $this->Users->resultset($getConversionID);
		
		if(!empty($IDResult))
		{
			$result =  json_encode(array('conversionID'=>$IDResult[0]['id'],'status'=>'success'));
		}
		else
		{
			$data = array(
				'conv_to'	=>$ToUserID,
				'conv_from'	=>$FromUserID,
				'created_date'	=>$this->Users->Timestamp(),
				'modified_date'	=>$this->Users->Timestamp(),
			);
			$conversionID = $this->Users->Insert($data,PREFIX.'conversation_set');
			
			$result = json_encode(array('conversionID'=>$conversionID,'status'=>'success'));
		}
		echo $result;
		exit();	
		endif;
		
		
	}
	
	public function getAttachmentdetails($ID)
	{
		$attachments =  $this->Users->get_single_row('id',$ID,PREFIX.'attachments');
		//print_R($attachments);
		if (filter_var($attachments['url'], FILTER_VALIDATE_URL) === FALSE) 
		{
			$ImagUrl = SITEURL.$attachments['url'];			
		}
		else
		{			
			$Img = strstr($attachments['url'], 'static');
			$ImagUrl = SITEURL.$Img;
		}
		
		$imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif");
		$urlExt = pathinfo($ImagUrl, PATHINFO_EXTENSION);
		if (in_array($urlExt, $imgExts)) 
		{
			return '<a href="'.$ImagUrl.'" target="_blank"><img class="attachment_file" src="'.$ImagUrl.'"></a>';
		}
		else
		{
			return '<a href="'.$ImagUrl.'" download><img class="attachment_file" src="'.SITEURL.'/static/images/Attach-icon.png"></a>';
		}	
		
	}
	
	public function getMessages()
	{
		$this->check_login();
		$ConversionId = $_POST['ConversionId'];
		if( !empty($ConversionId) )
		{
			$message = '';
			$where = 'conv_id = "'.$ConversionId.'"';			
			$messages = $this->Users->custom_where($where,PREFIX.'message_set');
			$total = count($messages);
			foreach( $messages as $keys ):
				$Attached = '';
				//$Touser = $this->Users->getProfileData($keys['to_id']);	
				$from_id = $this->Users->getProfileData($keys['from_id']);
				$message_Date = date('d/m/y',$keys["message_time"]);	
				$message_Time = date('H:i A',$keys["message_time"]);	
				
				if( $keys["attachment"] != 0 )
				{
					$Attached = $this->getAttachmentdetails($keys["attachment"]);
				}
				
				
				
				$message .='<div class="message-text">
					<figure class="msg-user-img offline ou">
						<img src="'.$from_id["image"].'">
					</figure>
					<time class="datestamp"> 
						<span class="time">'.$message_Time.'</span>
						<span class="date">'.$message_Date.'</span>
					</time>
					<figcaption>
					  <h4>'.$from_id["username"].'</h4>';
					
					if( !empty( $Attached ) )
					{
						$message .='<p>'.$Attached.'</p>';
					}

					  
				$message .='<p>'.$keys["message"].'</p>
					  <!--<div class="goto-proposal"><a href="#">Go to the proposal</a></div>-->
					</figcaption>
				</div>';			
						
			endforeach;	
			if( empty($message) )
			{	$message = "Start the Chat";	}
			echo json_encode (array( 'count' => $total , 'message' => $message) );
			//echo $message;
		}
		
	}
}