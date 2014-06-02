<script>
function RunOnLoad()
{
	/*$width = $('._company_list').css('width');
	$('._company_list').css('width', $width);
	$('._company_list').css('margin', 'auto');*/
	//reposition_in_center_width('span._company_list');
	reposition_in_center_width('span._company_list');
}
</script>
<?php
echo "<span class='_company_list' style=\"position:relative;display:inline-block;\">";// left:50%\">";
	echo "<span style='display:inline-block;'>";
	$i = 0;
	foreach ($companies as $company_id => $company)
	{
		$i = $i+1;
		//$encoded_url = urlencode($company['Company']['website']);
		$encoded_url = urlencode($company['website']);
		$link = SITE_NAME."open_campaigns/running_campaigns/?c={$company_id}";
		$onclick_evt = "window.location = '{$link}'";//"OpenInNewTab('{$link}');";
		echo "<div class='tag' style='float:left; width:400px; margin:10px;' onclick=\"{$onclick_evt}\">";
			//echo $company['Company']['name'];
			echo $company['name'];
		echo "</div>";
		
		if ($i%3 == 0)
		{
			echo "<div style='clear:both'></div>";
		}
	}
	echo "</span>";
echo "</span>";

?>