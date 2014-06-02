<?php

echo "
	<script type='text/javascript'>
		//function RunOnLoad() 
		//{
	";
	
if ($was_ajax_request)
{
	echo "
	try
	{
	";
	
		if (0 == $login_successful)
		{
			echo "window.opener.post_tw_login_popup_close(0);";
		}
		else
		{
			echo "window.opener.post_tw_login_popup_close(1);";
		}
	
	echo "
	}
	catch (SecurityError)
	{
	}
	";
	
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
Please close this popup window if it is still open.