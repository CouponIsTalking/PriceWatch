function viewcollection1234567890showform($this)
{
	
	$button = $this;
	$a = $($.find("#viewcollection_part1_form"));
	//$iframe_div = $('#viewcollect1234567890iframe');
	if ($a.css('display') == 'none')
	{	
		$top = $button.position().top + $button.height();
		$left = Math.max($button.position().left - 500, 10);
		
		$a.css('left', $left);
		$a.css('top', $top);
		$a.css('position', 'absolute');
		$a.css('width', '550px');
		$a.css('opacity', 0.0);
		$a.css('display', 'block');
		$a.css('z-index', 100);
		$a.fadeTo('slow', 1.0);
		//$iframe = $a.find('iframe');
		//$iframe.attr('src', $trackit_iframe_url);
	}
	else
	{
		$a.find('.collection_list').css('display', 'none');
		$a.find('.email_input_for_collection').css('opacity', 1);
		$a.find('.email_input_for_collection').css('display', 'block');
		$a.find("#msg").text("We're asking for email, so that we can look up your collection by your email.");
		$a.fadeTo('slow', 0, function(){$(this).css('display', 'none');});
	}
	
}

/*
function viewcollect1234567890_part1($this)
{
	$form = $this.parent('form');
	$temp_div = $form.find('.temp');
	$options = $temp_div.children('black_button');
	for (i=0;i<$options.length;i++)
	{
		
	}
	
}
*/


function viewcollect1234567890_part1($this)
{
	$main_div = $this.parents('#viewcollection_part1_form');
	$user_email = $main_div.find('#view_collect_input_email').val();
	
	$main_div.find("#msg").text("Please Wait ... ");
	
	OpenInNewTab($S_N+ "user_products/my_collection/"+encodeURIComponent($user_email));
	return;
	
	$.ajax({
		type:"POST",
		data:{user_email: $user_email},
		url: $S_N+ "user_products/get_collection_names",
		success : function(data) {
			result = $.parseJSON(data)
			if (result.success)
			{
				//console.log("found collections");
				$collection_list = $main_div.find('.collection_list');
				
				$temp_div = $collection_list.find('.temp')
				if ($temp_div.length)
				{
					$temp_div.remove();
				}
				$temp_div = $("<div class='temp'></div>");
				
				$collection_list.find('form').append($temp_div);
				for (i=0; i< result.collections.length;i++)
				{
					$temp_div.append("<black_button onclick=\"v = $(this).val(); if(v==1){$(this).val(0); $(this).css('color', 'white');} else{$(this).val(1); $(this).css('color', 'grey');}\" value='0'>"+ result.collections[i] +"</black_button>");
					$temp_div.append("<br/><br/>");
				}
				$temp_div.append("<black_button onclick=\"v = $(this).val(); if(v==1){$(this).val(0); $(this).css('color', 'white');} else{$(this).val(1); $(this).css('color', 'grey');}\" value='0'>"+ "Show All" +"</black_button>");
				$temp_div.append("<br/><br/>");
				
				$main_div.find("#msg").text("Select Collections that you want to check.");
				$main_div.find('.email_input_for_collection').fadeTo('slow', 0, 
									function(){ $(this).css('display', 'none'); }
									);
				$collection_list.css('opacity', 0);//, function(){$(this).css('display', 'block', function(){$(this).fadeTo('slow', 1);})});
				$collection_list.css('display', 'block');
				$collection_list.fadeTo('slow', 1);
			}
			else
			{
				//console.log("didnt find any collections");
				OpenInNewTab($S_N+"user_products/my_collections");
				$main_div.find("#msg").text(result.msg);
			}
			
		},
		error : function() {
			// console.log("call failed");
			//ToggleTrackitform($form);
			OpenInNewTab($S_N+"user_products/my_collections");
			$main_div.find("#msg").text(result.msg);
		}
	});
}
