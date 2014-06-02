<script type='text/javascript'>

function RunOnLoad()
{
	$text = $(".confirm_email_result_msg").text();
	var $nl = "<?php echo $result['nl'];?>";
	if (""!=$nl)
	{
		show_success_message($text, moveTo, $nl);
	}
	else
	{
		show_success_message($text, moveToHomePage);
	}
	reposition_in_center('div.success_msg');
}

</script>

<?php

echo "
<div class='confirm_email_result_msg' style='display:none;'>
{$result['msg']}
</div>
";
?>