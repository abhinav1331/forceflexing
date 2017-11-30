$(document).ready(function(){
	
	// Forgot Email 
	if($("#recoverEmail").length > 0)
	{
		// Validation 
		$("#recoverEmail").validate({
		   rules: {
			emailForgot: {  required:true,email:true }			  
		   }      
		});		
		
		// Ajax Process
		$('.forgotme').on('click',function(event)
		{
			if (event.preventDefault) 
			{
				event.preventDefault();
			} 
			else 
			{
				event.returnValue = false;
			}
			
			if ($("#recoverEmail").valid()) 
			{  
		  
				var email = $('input[name="emailForgot"]').val();			

				/**
				 * AJAX URL where to send data
				 * (from localize_script)
				 */
				var ajax_url = common_url+"forgotPassword";
				
				
				// Data to send
				data = {
				  action: 'resetPassword',			
				  emailForgot: email					 
				};
		  
				$.post( ajax_url, data, function(response) 
				{			
				  // If we have response
				var obj = JSON.parse(response);
				if(obj.status == 'success' )
					{
						toastr.success(obj.message, "Successfully");
						setTimeout(function(){ window.location = common_url; },5000);
					}
				else
					{
						toastr.error(obj.message, "Error!");	
					}			
				});
			}
			
		})
		

		
	}
	
	// New Password
	if($("#resetPass").length > 0)
	{
		// Validation 
		$("#resetPass").validate({
		   rules: {
			newPass: {  required:true},			  
			confirmPass: {  required:true,  equalTo: "#newPass" }			  
		   }       
		});		
		
		// Ajax Process
		$('.resetMe').on('click',function(event)
		{
			if (event.preventDefault) 
			{
				event.preventDefault();
			} 
			else 
			{
				event.returnValue = false;
			}
			
			if ($("#resetPass").valid()) 
			{  
		  
				var pass = $('input[name="newPass"]').val();			
				var token = $('input[name="token"]').val();			

				/**
				 * AJAX URL where to send data
				 * (from localize_script)
				 */
				var ajax_url = common_url+"recover";
				
				
				// Data to send
				data = {
				  action: 'recoverPassword',			
				  pass: pass,					 
				  token: token					 
				};
		  
				$.post( ajax_url, data, function(response) 
				{			
				  // If we have response
				  console.log(response);
				 var obj = JSON.parse(response);
				 if(obj.status == 'success' )
					{
						toastr.success(obj.message, "Successfully");
						setTimeout(function(){ window.location = common_url; },5000);
					}
				else
					{
						toastr.error(obj.message, "Error!");	
					}
				 
				  
				});
			}
			
		})
	}
	
});