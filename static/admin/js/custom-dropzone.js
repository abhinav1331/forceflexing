var TotalChat = 0 ;
var ChangChat = '';
$(document).ready(function(){
	
	setInterval(function()
	{ 
	var ConversionId = $('input[name="ConversionId"]').attr('value');
	if( ConversionId != '' )
	{	
		if( ChangChat != ConversionId )
		{
			ChangChat = ConversionId;
			$('.loader').show();
		}		
		getAjaxMsg(ConversionId);
	}
	
	}, 3000);
	
	// Filter Contact List
	$('#filter_list').change(function(){
		$('.chatme').show();
		var roleID = $(this).val();
		if( roleID != '' )
		{
			$('.chatme').not("[data-role*='"+ roleID +"']").hide();			
		}
		else
		{
			$('.chatme').show();
		}
	});
	
	$('.chatme').on('click',function()
	{
		// Adding active Class
		$('.chatme').removeClass('active');
		$(this).addClass('active');
		
		// Getting Conversion ID		
		var ToUserID = $(this).attr('data-userid');
		var FromUserID = $('input[name="fromUserId"]').attr('value');
		var ChatingWith = $(this).html();
		
		
		$('.name_user').html(ChatingWith);
		$('.chat-ajax').html("");
		
		$.ajax({
				  url: admin_url+'getConversionID',
				  type: "POST",
				  data: {'ToUserID':ToUserID,'FromUserID':FromUserID},
				  success:function(res)
				  {
					var obj = jQuery.parseJSON(res);  
					if( obj.status = 'success' )
					{
						$('input[name="ConversionId"]').attr('value',obj.conversionID);
						$('input[name="toUserId"]').attr('value',ToUserID);
					}					
				  }				 
		});
	});
	

	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("#id_dropzone", { 
    url: admin_url+'InsertInbox',
    autoProcessQueue:false,
	maxFilesize:5,
	addRemoveLinks: true,
	parallelUploads: 1,
	acceptedFiles: 'application/pdf,image/jpeg,image/png,image/gif,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	clickable: ".buttonText,#id_dropzone",
	queuecomplete:function()
	{
		$('.dz-remove').hide();		
	},
	init: function ()
	{
        this.on("sending", function (file, xhr, formData, e) {			           
			formData.append("message", $('#message').val());
			formData.append("fromUserId", $('input[name="fromUserId"]').val()); 
			formData.append("toUserId", $('input[name="toUserId"]').val()); 
			formData.append("ConversionId", $('input[name="ConversionId"]').val());	
		});		
		this.on("success", function(file, response) {
                var obj = JSON.parse(response);
				if( obj.status == 'error' )
					{
						console.log(obj.message);
						toastr.error(obj.message, "Error!");
						myDropzone.removeAllFiles(true);
					}
				else
					{
						myDropzone.removeAllFiles(true);
					}					
                
            });		
     }
  });
     
	
	// Progress Bar
	/*  myDropzone.on("totaluploadprogress", function(progress) 
	{
		progress = progress.toFixed(2);	 
		$('.progress').show();
		$('#percent').html(progress);
		$('.progress-bar-success').attr('aria-valuenow',progress);
		$('.progress-bar-success').css('width',progress+"%");
		if( progress == 100)
		{
			$('#percent').html("Completed");
			setTimeout(function(){ $('.progress').hide();  $('.alert-success').show();  }, 3000);		
			//setTimeout(function(){ location.reload(); }, 4000);
			
		} 
	});  */

  $('.send_me').on('click',function(e)
  {
    e.preventDefault();
	var mess 			= $('#message').val();
	var fromUserId 		= $('input[name="fromUserId"]').val(); 
	var toUserId 		= $('input[name="toUserId"]').val(); 
	var ConversionId	= $('input[name="ConversionId"]').val();	
	
	if( ConversionId != '' )
	{
		
	
		if (myDropzone.getQueuedFiles().length > 0)
			{                        
				myDropzone.processQueue(); 				
			}
			else
			{
				$.ajax({
				  url: admin_url+'InsertInbox',
				  type: "POST",
				  data: {'message':mess,'fromUserId':fromUserId,'toUserId':toUserId,'ConversionId':ConversionId},
				  success:function(res)
				  {
					  var obj = JSON.parse(res);
					if( obj.status == 'error' )
					{
						toastr.error(obj.message, "Error!");						
					}
				else
					{						
						$('#message').val('');
						$('.emoji-wysiwyg-editor').text('');
						$('.alert-success').show();  						
					}	
					//setTimeout(function(){ location.reload(); }, 4000);
				  }				 
				});
			}
	}
	else
	{
		toastr.error("Please Select the User to Start Chat", "Error!");
	}
					
	
	//setTimeout(function(){ $('.alert-danger').hide(); }, 5000);
  });   


	/* $('.cancel').on('click',function(){
		
		 myDropzone.removeAllFiles(true);
	}) */
	
	
	
	
})


function getAjaxMsg(ConversionId)
{
	$.ajax({
		url: admin_url+'getMessages',
		type: "POST",
		data: {'ConversionId':ConversionId},
		success:function(res)
		  {
			var obj =  JSON.parse(res); 
						
			if( TotalChat != obj.count )
			{
				$('.chat-ajax').html(obj.message);
				if( TotalChat == 0)
				{
					setTimeout(function(){	var $t = $('.chat-ajax');
					$t.animate({"scrollTop": $('.chat-ajax')[0].scrollHeight}, "slow");	},1000);
				}
				else
				{
					var $t = $('.chat-ajax');
					$t.animate({"scrollTop": $('.chat-ajax')[0].scrollHeight}, "slow");
				}					
				
				//console.log($('.chat-ajax')[0].scrollHeight);
				TotalChat = obj.count;
				$('.loader').hide();				
			}
			
			if( obj.message == 'Start the Chat' )
			{
				//$('.chat-ajax').html(obj.message);
				$('.loader').hide();	
			}
			
			  	
		  }				 
		});
}
