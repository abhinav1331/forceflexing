$(document).ready(function(){

		/* SearchContractor */
	$('form#searchContract').on('submit', function(event){
		  event.preventDefault();
		  var str = $( "form#searchContract" ).serializeArray();	
		  var query_str = '';	

		 
		 $.each(str, function(i,data){
                if($.trim(data['value'])){
                    query_str += (query_str == '') ? '?' + data['name'] + '=' + data['value'] : '&' + data['name'] + '=' + data['value'];
                }
            });
			window.history.pushState("", "Title", searchurl+'/'+query_str);
			//return false;
		 $.ajax({
			 type:'POST',
			 url:searchurl,
			 data:{content:str,search:'ContractorSearch'},
			 success:function(res)
			 {
				 var obj = JSON.parse(res);
				 			
				 var page = Math.ceil(obj.count/2);
				 
				 $('#results').html(obj.content);
				 getPage.die();
				 getPage(page,obj.page_number,searchurl);
			 }
		 });
		 
	})
	
	
	if (jQuery("#hourlyRateRangeSlider").length > 0) 
	{
			var hourlyRateRangeSlider = document.getElementById('hourlyRateRangeSlider'),
	 		hourlyRateRangeValue = document.getElementById('hourlyRateRangeValue'),
			maxValue = 100;
		
 
		noUiSlider.create(hourlyRateRangeSlider, {
			start: 0,
			animate: false,
			range: {
				min: 0,
				max: maxValue
			}
		});
			/** Update the value on drag **/
			hourlyRateRangeSlider.noUiSlider.on('update', function( values, handle ){
			hourlyRateRangeValue.innerHTML = values[handle];
			
			 
			var trackWidthValue = values[handle],
				trackWidth = (trackWidthValue/maxValue) * 100;
			$('input[name="job_success"]').val(parseFloat(trackWidth));	
			document.getElementById('trackFilled').style.width = trackWidth + "%";
		});
	}
	
	//Range Slider initialize
	if (jQuery("#ff-range-slider1").length > 0) 
	{
		var Slider = document.getElementById('ff-range-slider1'),
			marginMin = document.getElementById('ff-slider-value-min'),
			marginMax = document.getElementById('ff-slider-value-max');		
			
		/** Pay Rate Hourly **/
		noUiSlider.create(Slider, {
			start: [ 0, 300 ],
			margin: 1,
			connect: true,
			range: {
				'min': 0,
				'max': 300
			}
		});
		
			
		Slider.noUiSlider.on('update', function ( values, handle ) {
			
			if ( handle ) {
				marginMax.innerHTML = values[handle];
			} else {
				marginMin.innerHTML = values[handle];
			}
			var val_min = parseFloat($('#ff-slider-value-min').html());
			var val_max = parseFloat($('#ff-slider-value-max').html());
			
			$('#ff-slider-value-min').attr('value',val_min);
			$('#ff-slider-value-max').attr('value',val_max);
			$('input[name="hourly_wages"]').val(val_min+','+val_max);					
		});	
	}
		
})


    
 		
		

