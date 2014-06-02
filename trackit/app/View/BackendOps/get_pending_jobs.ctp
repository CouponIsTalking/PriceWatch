<?php

echo "<div id='_backend_ops_'>";

foreach ($jobs as $index => $job)
{
	$job = $job['BackendOp'];
	echo "<div class='_single_backend_op_' ";
	foreach ($job as $key => $value)
	{
		echo " {$key}=\"{$value}\"";
	}
	echo "></div>";	
}

echo "</div>";

?>