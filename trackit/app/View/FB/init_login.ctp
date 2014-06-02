<?php

if ($is_ajax)
{
	echo json_encode($result);
}
else
{
	echo "
		<script type=\"text/javascript\">
			function RunOnLoad()
			{
				show_success_message(\"{$result['msg']}\")
			}
		</script>
	";
}

?>