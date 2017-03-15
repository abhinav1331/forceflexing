<?php
$action=$_REQUEST['action'];

if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
{
   $action = $_REQUEST['action'];
   call_user_func($action);
} 


function ShowForm()
{
	unset($_REQUEST['action']);
?>
<script>
jQuery('#send_email').validate({
		
		rules: { 
			type: { 
				required: true
			},
			fname: {
				required: true
			},
			lname: {
				required: true
			},
			title: {
				required: true
			},
			email: {
				required: true,
				email:true
			}
		},
				
		submitHandler: function(form) {
			jQuery('#loader').show();		
			//return false;	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php',  
				success: function(data) 
				{
					if(data==1)
					{	
						jQuery('#loader').fadeOut('fast'); 		
						jQuery('#contact').find('.loader-box').empty().append('<div class="alert alert-success">Your request has been sent!</div>');   
					}
				} 
			});
		}
		 
	});	 
</script>
<form name="send_email" id="send_email" action="" method="post"> 
<div id="loader" style="display:none;"><img src="images/ripple.svg"></div>
   <input type="hidden" name="action" value="SendEmail">
   <div class="row"> 
	<div class="col-sm-12 col-xs-12">
	  <select name="type">
		<option value="">Select Option</option> 
		<option value="Employer">Employer</option>
		<option value="Sales and marketing Professional">Sales and marketing Professional</option>
		<option value="Business Partner">Business Partner</option>
	  </select>
	</div>
	<div class="col-sm-6 col-xs-12">
	  <input type="text" placeholder="First Name*" name="fname">
	</div>
	<div class="col-sm-6 col-xs-12">
	  <input type="text" placeholder="Last Name*" name="lname">
	</div>
	<div class="col-sm-6 col-xs-12">
	  <input type="text" placeholder="Title*" name="title">
	</div>
	<div class="col-sm-6 col-xs-12">
	  <input type="text" placeholder="Email Address*" name="email">
	</div>
	<div class="col-sm-12 col-xs-12">
	  <input type="text" placeholder="Phone Number" name="phone"> 
	</div>
	<div class="col-sm-12 col-xs-12">
	  <textarea placeholder="Message" name="message"></textarea> 
	</div>
	<div class="col-sm-12 col-xs-12">
	  <input type="submit" value="Submit" name="submit">
	</div> 
  </div>
</form> 
<?php	
}

function SendEmail()
{
	unset($_REQUEST['action']);
	$type=$_REQUEST['type'];
	$fname=$_REQUEST['fname'];
	$lname=$_REQUEST['lname'];
	$title=$_REQUEST['title'];
	$email=$_REQUEST['email'];
	$phone=$_REQUEST['phone'];
	$message=$_REQUEST['message'];
	
	
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';
	$headers[] = 'From: ForceFlexing <noreply@forceflexing.com>';
	
	$subject="Force Flexing - ".$fname.' '.$lname;  
	$body=' 
	<p>Hello Admin</p>
	<p>We have new request from ForceFlexing landing page</p>
	<p>Type : '.$type.'</p>
	<p>Name : '.$fname.' '.$lname.'</p>
	<p>Title : '.$title.'</p>
	<p>Email : '.$email.'</p>
	<p>Phone : '.$phone.'</p>
	<p>Message : '.$message.'</p> 
	<p>Thank you</p> 
	';
	
	
	mail('ramit@imarkinfotech.com', $subject, $body, implode("\r\n", $headers));
	echo "1";
}

?>