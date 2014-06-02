<script type="text/javascript">
function RunOnLoad()
{
	$msg_type = $('#msg_type').text();
	$msg = $('#msg').text()
	if ($msg && $msg_type)
	{
		$valid_type = false;
		if ($msg_type == 'error_msg')
		{
			node = $("#error_msg");
			$valid_type = true;
		}
		else if ($msg_type == 'success_msg')
		{
			node = $("#success_msg");
			$valid_type = true;
		}
		else if ($msg_type == 'info_msg')
		{
			node = $("#info_msg");
			$valid_type = true;
		}
		if ($valid_type)
		{
			node.css("z-index", 10);
			node.text("It looks like the page does not exist.");
			
			$fade = $('#fade');
			$fade.show();
			$fade.on('click', function()
						{ 
							node.empty();
							node.hide();
							$fade.hide();
						}
					);
			$fade.css("z-index", 9);
		}
	}
}
</script>
<?php
if(!empty($msg_type) && !empty($msg))
{
	echo "<div id='msg_type'>{$msg_type}</div>";
	echo "<div id='msg'>{$msg}</div>";
}
else
{
	echo "<div id='msg_type'></div>";
	echo "<div id='msg'></div>";
}
?>