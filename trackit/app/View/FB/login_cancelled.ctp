<?php

echo "
	<script type='text/javascript'>
		//function RunOnLoad() 
		//{
	";
	
if ($was_ajax_request)
{
	
	if (0 == $login_successful)
	{
		echo "window.opener.post_tw_login_popup_close(0);";
	}
	else
	{
		echo "window.opener.post_tw_login_popup_close(1);";
	}
	
	
}
else
{
echo "
		
";
}

echo "
		self.close();
		//}
	</script>
	";
?>