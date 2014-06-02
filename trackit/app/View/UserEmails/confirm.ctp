<?php
if($result['auto_redir'] && !empty($result['redir_url']))
{
	echo "<script type='text/javascript'>
			window.top.location=\"{$result['redir_url']}\";
		</script>";
}

//echo json_encode($result);
?>