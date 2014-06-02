<script type="text/javascript">
function RunOnLoad()
{
	node = $("#error_msg");
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
</script>
<?php

?>