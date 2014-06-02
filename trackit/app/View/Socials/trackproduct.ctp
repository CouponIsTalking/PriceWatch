<script type="text/javascript">

function RunOnLoad(){
	$("#trackit1234567890A").find("#form1").css('display','block');
}

</script>


<div style='clear:both;'></div>

<?php

$bgtex_image = SITE_NAME.'img/bgtex3.jpg';
echo "<div class='_tracker_frame' style=\"position:relative;padding-bottom:50px;width:100%;height:100%;top:0px;left:0px;background-image:url('{$bgtex_image}');background-repeat:repeat;\">";

echo $this->element('product/trackprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id, 
	'list_supported_stores' => false,
	'show_close_button' => false
	));


echo "
<div style='color:rgba(255, 255, 255,0.69);font-size:24px;font-family:Helvetica sans-serif;padding:15px;margin:auto;margin-top:0px;'>
Supported Stores
</div>
";

if (!empty($companies)){
	echo "<div style='margin:auto;'><ul>";
	$i = 0;
	foreach ($companies as $company_id => $company)
	{
		$i = $i+1;
		//$encoded_url = urlencode($company['Company']['website']);
		$encoded_url = urlencode($company['website']);
		echo "<div class='tag' style='float:left; width:400px; margin:10px;' onclick=\"OpenInNewTab(addhttp('{$company['website']}'));\">";
			//echo $company['Company']['name'];
			echo $company['name'];
		echo "</div>";
		
	}
	echo "</ul></div>";
}
?>

<div style='clear:both;'></div>
</div>