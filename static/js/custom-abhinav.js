/*****************************State Change****************************/
	function onchangeState(event) {
		console.log(event);
		var state=jQuery(event).val();
		var selectd="";
		jQuery.ajax({
			type:"post",
			datatype: "html",
			url : '/contractor/get_cities',
			data :{'stateid':state,'selected_city':selectd},
			success : function(response) {
				jQuery(event).parent().siblings(".col-md-6").find("select").empty().append(response);
			}
		});
	}
/*******************************State Change****************************/



/*****************************************Triggering moddule on success************************************/
	jQuery(document).ready(function(){
		jQuery('.SuccessModel').modal('show'); 
		var lengthFun = jQuery(".SuccessModel").length;
		if(lengthFun == 1) {
			var x= jQuery(".counter").text();
			window.setInterval(function(){
				var textCount = jQuery(".counter").text();
				var finCount = parseInt(textCount) - 1;
				jQuery(".counter").empty().append(finCount);
			}, 1000);
			setTimeout(function(){ window.location.href = '/login'; }, 5000);
		}
	});

/***********************************Triggering moddule on success**********************************/


/************************************Industries Auto Complete****************************************/
jQuery( function() {
	 var availableTags;
	$.getJSON('/employer/listindustries',function(res) 
	{
		 availableTags = res;	
	}); 
   /*  var availableTags = ["Cosmetics","Apparel And Fashion","Sporting Goods","Food And Beverages","Consumer Electronics","Consumer Goods","Furniture","Retail","Wine And Spirits","Luxury Goods And Jewelry","Packaging ","Restaurants","Recreational Facilities And Services","Freight Delivery","Individual And Family Services","Events Services","Security ","Marketing ","Public Relations","Staffing And Recruiting","Professional Training","Logistics And Supply Chain","Outsourcing/Offshoring","Human Resources","Business Supplies And Equipment","Aviation And Aerospace","Automotive","Chemicals","Machinery","Mining And Metals","Oil And Energy","Shipbuilding","Utilities","Textiles","Paper And Forest Products","Electrical And Electronic Manufacturing","Plastics","Engineering","Renewables And Environment","Glass"," Ceramics And Concrete","Biotechnology","Hospital And Health Care","Pharmaceuticals","Veterinary","Medical Device","Health"," Wellness And Fitness","Alternative Medicine","Mental Health Care","Entertainment","Motion Pictures And Film","Broadcast Media","Museums And Institutions","Performing Arts","Publishing","Information Services","Design","Computer Games","Arts And Crafts","Creative services","Legal Services","Law Enforcement","Farming","Ranching","Dairy","Fishery","Construction","Building Materials","Real Estate","Defense And Space","Computer Hardware","Computer Software","Computer Networking","Internet","Semiconductors","Telecommunications","Biotechnology","Pharmaceuticals","Airlines/Aviation","Information Technology And Services","Venture Capital","Nanotechnology","Computer And Network Security","Wireless","Government Administration","International Trade And Development","Banking","Insurance","Financial Services","Accounting","Venture Capital","Capital Markets","Gambling And Casinos","Leisure And Travel","Hospitality","Sports","Recreational Facilities And Services","Religious Institutions","Civic And Social Organization","Consumer Services","Non-Profit Organization ","Fundraising","Program Development","Political Organization" ]; */
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    jQuery( "#industry_knowledge,#indus,.industries")
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	  
  } );
/************************************Industries Auto Complete****************************************/

  function proMoreToggle(event) {
    jQuery(".pro-more-content").show();
    jQuery(event).hide();
  }


  jQuery(function($) {
    jQuery('#send-message-contractor-recommended').validate({
      rules: {
        "sendMessage": {
          required: true
        }
      },
      submitHandler: function(form) { 
        jQuery(form).ajaxSubmit({
          type: "POST",
          data: jQuery(form).serialize(),
          url: 'http://force.stagingdevsite.com/employer/sendMessage', 
          success: function(data) 
          {
            jQuery("textarea[name='sendMessage']").val("");
            jQuery('#contact_contractor').modal('hide'); 
            toastr.success("Message Sent SuccessFully");
          }
        });
      }

    });
  });


  /**************************************************Post Job*******************************/
    jQuery(document).ready(function(){
      jQuery("input[name='jp_other_expenses[]']").each(function(){
        jQuery(this).click(function(){
             if(jQuery(this).is(':checked')) {
                jQuery(this).parent().parent().siblings("td").find("input").removeAttr("disabled");
             } else {
                jQuery(this).parent().parent().siblings("td").find("input").val("");
                jQuery(this).parent().parent().siblings("td").find("input").attr("disabled","disabled");
             }
        });
      });
    });


    function postingAJob() {
      if(jQuery("#post_job").valid()) {
        jQuery("input[name='jp_num_of_actvty']").removeAttr("disabled");
        jQuery(".expences_input_value").each(function(){
          jQuery(this).removeAttr("disabled");
        });
      } 
    }



    jQuery(document).ready(function(){
      jQuery("input[name='jp_actvty_comp']").change(function(){
        var selectedValue = jQuery(this).val();
        if(selectedValue == "no") {
          jQuery("input[name='jp_num_of_actvty']").removeAttr("disabled");
        } else {
          jQuery("input[name='jp_num_of_actvty']").val("");
          jQuery("input[name='jp_num_of_actvty']").attr("disabled","disabled");
        }
      });
    });
  /**************************************************Post Job******************************/



  function editPendingJobs(activityId) {
      jQuery.ajax({
        type:"post",
        url : '/employer/edit_pending_activities',
        data :{"activityId":activityId},
        success : function(response) {
          jQuery(".emp-job-activity-details").empty().append(response);
          var state = jQuery(".state_Activity").val();
          var city = jQuery(".city_Activity").val();
          jQuery("select[name='jp_act_state[]']").trigger('change');
          setTimeout(function(){ jQuery("select[name='jp_act_city[]']").val(city) }, 3000);
        }
      });
  }


  function closeviewActivity(event) {
    jQuery(".emp-job-activity-details").empty();
  }


  function deleteActivity(event) {
    var activityId = jQuery(event).parent().parent().siblings("td:eq(0)").find(".activityId").val();
    alert(activityId);
      jQuery.ajax({
        type:"post",
        url : '/employer/delete_pending_activity',
        data :{"activityId":activityId},
        success : function(response) {
            jQuery(event).parent().parent().parent().remove();
            toastr.success("Activity Successfully Deleted");
        }
      });
  }

  function addNewActivity() {
    jQuery('#myModal').modal('show');
  }
  function getListOfActivity(event) {
    alert(event);
  }


  jQuery(function($) {
    jQuery('#editActivity').validate({
      rules: {
        "jp_activity_name": {
          required: true
        },
        "jp_start_stop_time1": {
          required: true
        },
        "jp_act_start_date": {
          required: true
        },
        "jp_act_start_time": {
          required: true
        },
        "jp_act_end_date": {
          required: true
        },
        "jp_act_end_time": {
          required: true
        },
        "jp_act_state": {
          required: true
        },
        "jp_act_city": {
          required: true
        },
        "jp_act_street": {
          required: true
        },
        "jp_act_zip": {
          required: true
        },
        "jp_act_cont_fname": {
          required: true
        },
        "jp_act_cont_lname": {
          required: true
        },
        "jp_act_cont_phne": {
          required: true
        },
        "jp_act_cont_email": {
          required: true
        },
        "jp_act_notes": {
          required: true
        },
        "jp_act_cont_lname": {
          required: true
        }
      },
      submitHandler: function(form) { 
        jQuery(form).ajaxSubmit({
          type: "POST",
          data: jQuery(form).serialize(),
          url: 'http://force.stagingdevsite.com/employer/editActivityUpdate', 
          success: function(data) 
          {
            console.log(data);
            toastr.success("Updated");
            jQuery(".mrTbody").empty().append(data);
          }
        });
      }

    });
  });







  jQuery(function($) {
    jQuery('#addActivity').validate({
      rules: {
        "jp_activity_name": {
          required: true
        },
        "jp_start_stop_time1": {
          required: true
        },
        "jp_act_start_date": {
          required: true
        },
        "jp_act_start_time": {
          required: true
        },
        "jp_act_end_date": {
          required: true
        },
        "jp_act_end_time": {
          required: true
        },
        "jp_act_state": {
          required: true
        },
        "jp_act_city": {
          required: true
        },
        "jp_act_street": {
          required: true
        },
        "jp_act_zip": {
          required: true
        },
        "jp_act_cont_fname": {
          required: true
        },
        "jp_act_cont_lname": {
          required: true
        },
        "jp_act_cont_phne": {
          required: true
        },
        "jp_act_cont_email": {
          required: true
        },
        "jp_act_notes": {
          required: true
        },
        "jp_act_cont_lname": {
          required: true
        }
      },
      submitHandler: function(form) { 
        jQuery(form).ajaxSubmit({
          type: "POST",
          data: jQuery(form).serialize(),
          url: 'http://force.stagingdevsite.com/employer/addActivityInJob', 
          success: function(data) 
          {
            var job_id = jQuery(".job_id").val();
            jQuery("#jp_activity_name").val("");
            jQuery("#jp_act_start_date").val("");
            jQuery("#jp_act_start_time").val("");
            jQuery("#jp_act_end_date").val("");
            jQuery("#jp_act_end_time").val("");
            jQuery("#stateId").val("");
            jQuery("#cityId").val("");
            jQuery("#jp_act_street").val("");
            jQuery("#jp_act_zip").val("");
            jQuery("#jp_act_cont_fname").val("");
            jQuery("#jp_act_cont_lname").val("");
            jQuery("#jp_act_cont_phne").val("");
            jQuery("#jp_act_cont_email").val("");
            jQuery("#jp_act_notes").val("");
            jQuery(".mrTbody").empty().append(data);
            jQuery('#myModal').modal('hide');
            toastr.success("Message Sent SuccessFully");
          }
        });
      }

    });
  });

/*function deleteActivity(event) {
  var 
}*/


/*function hire_btn() {
  
}*/




jQuery(function($) {
    jQuery('#ExtraExpenditure').validate({
      rules: {
        ExpenceName: {
          required: true
        },
        ExpenceName1: {
          required: true
        },
        ExpenceName2: {
          required: true
        },
        ExpenceName3: {
          required: true
        },
        ExpenceName4: {
          required: true
        }
      },
      submitHandler: function(form) { 
        var finalizeArray = [];
        jQuery("input[name='extra[]']").each(function(){
          jQuery(this).removeAttr("checked");
        });
        jQuery("input[name='jp_other_expenses[]']:checked").each(function(){
          var expenditureName = jQuery(this).val();
          jQuery("."+expenditureName).prop('checked', true);
          var expenditurePrice = jQuery(this).parent().parent().siblings("td").find("input[name='ExpenceName[]']").val();
          jQuery("."+expenditureName).attr("value" , expenditurePrice);
          var arrayObj = {};
          arrayObj['name'] = expenditureName;
          arrayObj['price'] = expenditurePrice;
          finalizeArray.push(arrayObj);
        });
        console.log(finalizeArray);
        jQuery("input[name='extraExpanditureContract']").val(JSON.stringify(finalizeArray));
        jQuery("#MyModelActionOtherExpanditure").modal("hide");
        toastr.success("Data Updated");
        /*jQuery(form).ajaxSubmit({
          type: "POST",
          data: jQuery(form).serialize(),
          url: 'http://force.imarkclients.com/employer/ExtraExpenditure', 
          success: function(data) 
          {
            jQuery(".paymentOptions").empty().append(data);
            
            toastr.success("Data Updated");
            jQuery('#EditJobModel').modal('hide');
          }
        });*/
      }

    });
  });


function JobStatus(event) {
  setTimeout(function(){ 
      if (jQuery(event).prop('checked')==true){
        var status = "1";
      } else {
        var status = "0";
      }
      var ActivityId = jQuery(event).siblings(".thisJobIdActivity").val();
        jQuery.ajax({
          type:"post",
          url : '/employer/ChangeJobStatus',
          data :{"status":status , "ActivityId" : ActivityId},
          success : function(response) {
            if(status == 1) {
                toastr.success("Activity Status Changed to CLOSED");
            } else {
                toastr.success("Activity Status Changed to OPEN");
            }
          }
        });
   }, 100);


}



function viewActivity(event) {
  var ActivityId = jQuery(event).parent().parent().siblings("td:eq(4)").find("label").find(".thisJobIdActivity").val();
  var job_id = jQuery(".job_id").val();
  var user_id = jQuery(".user_id").val();
  jQuery.ajax({
    type:"post",
    url : '/employer/viewActivity',
    data :{"ActivityId" : ActivityId , "job_id" : job_id, "user_id" : user_id},
    success : function(response) {
      console.log(response);
        jQuery(".View_Job").empty().append(response);
        jQuery('#ViewJobModel').modal('show');
        jQuery("select[name='jp_act_state']").change();
        setTimeout(function(){ 
          var CityName = jQuery("input[name='cityName']").val();
          jQuery("select[name='jp_act_city']").val(CityName);
         }, 1500);
    }
  });
}


function editActivity(event) {
  var ActivityId = jQuery(event).parent().parent().siblings("td:eq(4)").find("label").find(".thisJobIdActivity").val();
  var job_id = jQuery(".job_id").val();
  var user_id = jQuery(".user_id").val();
  jQuery.ajax({
    type:"post",
    url : '/employer/editActivity',
    data :{"ActivityId" : ActivityId , "job_id" : job_id, "user_id" : user_id},
    success : function(response) {
      jQuery(".Edit_Job").empty().append(response);
        jQuery('#EditJobModel').modal('show');
        jQuery("select[name='jp_act_state']").change();
        setTimeout(function(){ 
          var CityName = jQuery("input[name='cityName']").val();
          jQuery("select[name='jp_act_city']").val(CityName);
         }, 1500);
    }
  });
}


  jQuery(document).ready(function(){
    jQuery(".jp_act_state").each(function(){
      jQuery(this).change();
      var storeEvent = jQuery(this);
        var CityNameE = jQuery(this).siblings(".cityNameId").val();
      setTimeout(function(){ 
        storeEvent.parent().siblings(".col-md-6").find("select[name='jp_act_city[]']").val(CityNameE);
      }, 1500);
    });
  });

  jQuery(document).ready(function(){
        var finalizeArray = [];
        jQuery("input[name='extra[]']:checked").each(function(){
          var expenditureName = jQuery(this).attr("class");
          var expenditurePrice = jQuery(this).val();
          var arrayObj = {};
          arrayObj['name'] = expenditureName;
          arrayObj['price'] = expenditurePrice;
          finalizeArray.push(arrayObj);
        });
        console.log(finalizeArray);
        jQuery("input[name='extraExpanditureContract']").val(JSON.stringify(finalizeArray));
  });


  function SaveContractorJob(hurdle,event,event2) {

    jQuery.ajax({
        type:"post",
        url : '/employer/SaveContractorJob',
        data :{"event" : event , "job_id" : event2},
        success : function(response) {
          toastr.success("Job Successfully Saved");
          jQuery(hurdle).remove();
        }
    });
  }


  function contractorMasterJob(event) {
    var selection = jQuery(event).val();
    if(selection == "viewEdit") {
      var transferUrl = jQuery("input[name='viewEdit']").val();
      window.location.href = transferUrl;
    } else if(selection == "removeJob") {
      jQuery("#removeJob").modal("show");
    }
  }

  function sayNojob() {
    jQuery("#removeJob").modal("hide");
    jQuery(".myActivityJob").val("");
  }

  function removeJobValue() {
    var myjobId = jQuery("input[name='currentJobId']").val();
    jQuery.ajax({
      type:"post",
      url : '/employer/removeJob',
      data :{"job_id" : myjobId},
      success : function(response) {
        toastr.success("Job Successfully Removed");
        window.location.href = base_url+"employer/openJob";
      }
    });
  }

  jQuery(document).ready(function(){
    jQuery("input[name='one']").change(function(){
      var actionUserReco = jQuery(this).val();
      if(actionUserReco == "invitetojob") {
        var contractorId = jQuery(this).parent().parent().parent().siblings("input[name='contractorNameId']").val();
        var job_id = jQuery("input[name='currentJobId']").val();
        jQuery("#inviteToJob").modal("show");
        jQuery(".inviteSettings").text("Invite initialize");
        jQuery.ajax({
          type:"post",
          url : '/employer/inviteContractorRecommended',
          data :{"employer_id" : job_id , "invitedUsers" : contractorId},
          success : function(response) {
            toastr.success("Job Invite Send");
            jQuery(".inviteSettings").text("Invite Sent");
            setTimeout(function(){
             jQuery("#inviteToJob").modal("hide");
             jQuery(this).attr("disabled" , "disabled");
             jQuery("this").removeAttr("checked");
            }, 1500);
          }
        });
      } else if(actionUserReco == "makeoffer") {
        var jobSlug = jQuery("input[name='jobSLUG']").val();
        var userName = jQuery(this).parent().parent().parent().siblings("input[name='userName']").val();
        window.location.href = base_url+"employer/contract/"+jobSlug+"/"+userName;
      } else if(actionUserReco == "messagecontractor") {
        var contractorNameId = jQuery(this).parent().parent().parent().siblings("input[name='contractorNameId']").val();
        var myId = jQuery(this).parent().parent().parent().siblings("input[name='myId']").val();
        var myjobId = jQuery("input[name='currentJobId']").val();

        jQuery.ajax({
        type:"post",
        url : '/employer/sendMessageMaster',
        data :{"contractorNameId":contractorNameId, "myId" : myId , "myjobId" : myjobId},
        success : function(response) {
          toastr.success("Message Sent Successfully");
          window.location.href = base_url+"inbox/";
        }
      });
      }
    });
  });


  jQuery(document).ready(function(){
    jQuery("input[name='two']").change(function(){
      var actionUserReco = jQuery(this).val();
       if(actionUserReco == "makeOffer") {
        var jobSlug = jQuery("input[name='jobSLUG']").val();
        var userName = jQuery(this).parent().parent().parent().siblings("input[name='userName']").val();
        window.location.href = base_url+"employer/contract/"+jobSlug+"/"+userName;
      } else  if(actionUserReco == "CreateCon") {
        var jobSlug = jQuery("input[name='jobSLUG']").val();
        var userName = jQuery(this).parent().parent().parent().siblings("input[name='userName']").val();
        window.location.href = base_url+"employer/contract/"+jobSlug+"/"+userName;
      }  
      else if(actionUserReco == "Decline") {
        var appliedId = jQuery(this).parent().parent().parent().siblings("input[name='appliedId']").val();
        jQuery.ajax({
          type:"post",
          url : '/employer/removeContractor',
          data :{"appliedId" : appliedId },
          success : function(response) {
            toastr.success("Contractor Declined");
            jQuery(this).parent().parent().parent().parent().parent().parent().remove();
          }
        });
      }
      else if(actionUserReco == "message") {
        var contractorNameId = jQuery(this).parent().parent().parent().siblings("input[name='contractorNameId']").val();
        var myId = jQuery(this).parent().parent().parent().siblings("input[name='myId']").val();
        var myjobId = jQuery("input[name='currentJobId']").val();

        jQuery.ajax({
          type:"post",
          url : '/employer/sendMessageMaster',
          data :{"contractorNameId":contractorNameId, "myId" : myId , "myjobId" : myjobId},
          success : function(response) {
            toastr.success("Message Sent Successfully");
            window.location.href = base_url+"inbox/";
          }
        });
      }
    });
  });

  jQuery(document).ready(function(){
    jQuery("input[name='three']").change(function(){
      var actionUserReco = jQuery(this).val();
      if(actionUserReco == "message") {
        var contractorNameId = jQuery(this).parent().parent().parent().siblings("input[name='contractorNameId']").val();
        var myId = jQuery(this).parent().parent().parent().siblings("input[name='myId']").val();
        var myjobId = jQuery("input[name='currentJobId']").val();

        jQuery.ajax({
          type:"post",
          url : '/employer/sendMessageMaster',
          data :{"contractorNameId":contractorNameId, "myId" : myId , "myjobId" : myjobId},
          success : function(response) {
            toastr.success("Message Sent Successfully");
            window.location.href = base_url+"inbox/";
          }
        });
      }
    });
  });
  jQuery(document).ready(function(){
    jQuery("input[name='four']").change(function(){
      var actionUserReco = jQuery(this).val();
      var inviteID = jQuery(this).parent().parent().parent().siblings("input[name='inviteID']").val();
        if(actionUserReco == "recind") {
          jQuery.ajax({
            type:"post",
            url : '/employer/recindOffer',
            data :{"inviteID" : inviteID },
            success : function(response) {
              toastr.success("Offer Recind");
              jQuery(this).parent().parent().parent().parent().parent().parent().remove();
            }
          });
        }
    });
  });


  function viewCoverLetter(event) {
    var htmll = jQuery(event).parent().parent().siblings(".coverletter").html();
    jQuery(".coverLetter").empty().append(htmll);
    jQuery("#viewCoverLetter").modal("show");
  }


  jQuery(document).ready(function(){
    jQuery("input[name='five']").change(function(){
      var actionHired = jQuery(this).val();
      alert(actionHired);
    });
  });


  jQuery(document).ready(function(){
    jQuery(".datatable-job-report").each(function(){
      jQuery(this).DataTable({searching: false, paging: false});
    });
  });



jQuery(document).on('keyup','#myInput',function(){
	var valThis = jQuery(this).val().toLowerCase();
    jQuery('.SectionAble>a').each(function(){
      var text = jQuery(this).text().toLowerCase();
      (text.indexOf(valThis) == 0) ? jQuery(this).parent().parent().parent().show() : jQuery(this).parent().parent().parent().hide();            
    });
});
  
  /*function myFunction(event) {
    var valThis = jQuery(event).val().toLowerCase();
    jQuery('.SectionAble>a').each(function(){
      var text = jQuery(this).text().toLowerCase();
      (text.indexOf(valThis) == 0) ? jQuery(this).parent().parent().parent().show() : jQuery(this).parent().parent().parent().hide();            
    });
  }
*/

// JQUery Restrict Number in Textbox
  jQuery(document).ready(function(){
    jQuery('input[name="first_name"]').keydown(function(e) {
        var node = jQuery(this);
        node.val(node.val().replace(/[^a-z]/g,'') );
    })
  });
// JQUery Restrict Number in Textbox

///Close this activity

function JobStatus1(event) {
  setTimeout(function(){ 
      if (jQuery(event).prop('checked')==true){
        var status = "1";
      } else {
        var status = "0";
      }
      var ActivityId = jQuery(".thisJobIdActivity").val();
        jQuery.ajax({
          type:"post",
          url : '/employer/ChangeJobStatus',
          data :{"status":status , "ActivityId" : ActivityId},
          success : function(response) {
            if(status == 1) {
                toastr.success("Activity Status Changed to CLOSED");
            } else {
                toastr.success("Activity Status Changed to OPEN");
            }
          }
        });
   }, 100);


}
    jQuery(document).ready(function(){
    new DG.OnOffSwitch({
        el: '#closeJob',
        textOn: 'Closed',
        textOff: 'Open', 
        trackColorOn:'#666',
        trackColorOff:'#00ff00',
        trackBorderColor:'#555',
        textColorOff:'#fff',
         listener:function(name, checked){
            JobStatus1();
        }
    });
  });
///Close this activity




