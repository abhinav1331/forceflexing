    function ajaxCall() {
        this.send = function(data, url, method, success, type) {
          type = type||'json';
          var successRes = function(data) {
              success(data);
          }

          var errorRes = function(e) {
              console.log(e);
              alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
          }
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: successRes,
                error: errorRes,
                dataType: type,
                timeout: 60000
            });

          }

        }

function locationInfo() {
    var rootUrl = "http://iamrohit.in/lab/php_ajax_country_state_city_dropdown/apiv1.php";
    var call = new ajaxCall();
    this.getCities = function(id, event) {
        var get_id = jQuery(event).attr("data-attribute");
        $("#"+get_id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(1)").find(".cities option:gt(0)").remove();
        var url = rootUrl+'?type=getCities&stateId=' + id;
        var method = "post";
        var data = {};
         $("#"+get_id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(1)").find('.cities').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
             $("#"+get_id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(1)").find('.cities').find("option:eq(0)").html("Select City");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('.cities').append(option);
                });
                 $("#"+get_id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(1)").find(".cities").prop("disabled",false);
            }
            else{
                 alert(data.msg);
            }
        });
    };

    this.getStates = function(id) {
        $(".states option:gt(0)").remove(); 
        $(".cities option:gt(0)").remove(); 
        var url = rootUrl+'?type=getStates&countryId=' + id;
        var method = "post";
        var data = {};
        $('.states').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.states').find("option:eq(0)").html("Select State");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('.states').append(option);
                });
                $(".states").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getCountries = function() {
        var url = rootUrl+'?type=getCountries';
        var method = "post";
        var data = {};
        $('.countries').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.countries').find("option:eq(0)").html("Select Country");
            console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('.countries').append(option);
                });
                $(".countries").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

$(function() {
var loc = new locationInfo();
loc.getCountries();
 $(".countries").on("change", function(ev) {
        var countryId = $(this).val()
        if(countryId != ''){
        loc.getStates(countryId);
        }
        else{
            $(".states option:gt(0)").remove();
        }
    });
/*     $(".states").on("change", function(ev) {
          
        });*/
});

function onchangeState(event) {
    var loc = new locationInfo();
    loc.getCountries();
      var stateId = $(event).val()
            if(stateId != ''){
            loc.getCities(stateId, event);
            }
            else{
                $(".cities option:gt(0)").remove();
            }
}

function getReadySession(id,state,city) {
    setTimeout(function(){ jQuery("#"+id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(0)>select:eq(1)").val(state); jQuery("#"+id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(0)>select:eq(1)").trigger( "change" ); }, 2000);
    setTimeout(function(){ jQuery("#"+id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(1)>select").val(city); jQuery("#"+id+">table>tbody>tr:eq(3)>td>.row>.col-md-6:eq(0)>.row>.col-md-6:eq(1)>select").trigger( "change" ); }, 3000);
}

