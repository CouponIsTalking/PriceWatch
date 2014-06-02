function get_blogger_responses(blogger_id)
{

	my_ocrs = $("#my_ocrs")
	
	my_ocrs
		.css('opacity', 0.95)
		.css('z-index', 10)
		.css('top', '20%')
		.css('left', '0%')
		.css('margin-left', '10%')
		.css('margin-right', '10%')		
		.css('position', 'fixed')
		.css('background', 'white')
		.fadeTo(500, 0.95)
		.on("dblclick", function(){
			$(this).hide();
			// //console.log("double click received");
		});
	
	my_ocrs.draggable();
	
	return;
	
	$.ajax({
			type:"POST",
			data:{}, 
			url:$S_N+"bloggers/get_blogger_campaign_responses/"+blogger_id,
			success : function(data) {
			   //alert(data);// will alert "ok"
				update_blogger_responses(data, true)
			},
			error : function(data) {
			   //alert("false");
			   update_blogger_responses(data, false)
			}
		});


}

function update_blogger_responses( response, success)
{

	if (success == false)
	{
		//console.log("error");
	}
	else
	{
		ret = jQuery.parseJSON(response);
		//console.log(ret);
	}

}