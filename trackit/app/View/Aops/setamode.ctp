<?php
if ($is_ajax)
{
	echo json_encode($result);
	return; // important 
}
?>

<script>

function RunOnLoad()
{
	$html = "<div>"
		+ "<label style='width:500px;' class='_find_verifier_label'>enter acode</label>"
		+ "<br/>"
		+ "<label style='width:500px;' class='_code_result'></label>"
		+ "<br/>"
		+ "<input style='width:500px;' class='_code_val' val='enter acode'></input>"
		+ "<br/>"
		+ "<green_button onclick=\"verify_amode($(this));\">Verify</green_button>"
	+ "</div>"
	+"";
	
	close_button_click();
	show_success_message($html);
	fit_to_inner_content('div.success_msg');
	reposition_in_center('div.success_msg');

}

function verify_amode($this)
{
	$('._code_result').html('one moment...');
	
	$code_val = $this.parent().find('._code_val').val().trim();
	if (!$code_val)
	{
		$('._code_result').html("<black_button> Empty value </black_button>");
		return;
	}
	
	//close_button_click();
	
	$url = $SITE_NAME + "aops/setamode";
	
	$.ajax({
			type:"POST",
			url: $url,
			data:{code: $code_val}, 
			success : function(data) {
				//console.log(data);
				$result = IsJsonString(data)
				if ($result)
				{
					if ($result.success)
					{
						$('._code_result').html("<green_button>" + $result.msg +"</green_button>");
					}
					else
					{
						$('._code_result').html("<black_button>" + $result.msg +"</black_button>");
					}
				}
				
			},
			error : function(data) {
			   //alert("false");
			   //show_success_message("FB page Url does not seem right.");
			}
		});
		
}


</script>