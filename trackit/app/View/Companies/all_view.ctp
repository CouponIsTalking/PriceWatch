<?php
echo "<ul>";
$i = 0;
foreach ($companies as $company_id => $company)
{
	$i = $i+1;
	//$encoded_url = urlencode($company['Company']['website']);
	$encoded_url = urlencode($company['website']);
	$link = SITE_NAME."companies/show_shop/?shop={$encoded_url}";
	$onclick_evt = "OpenInNewTab('{$link}');";
	echo "<div class='tag' style='float:left; width:400px; margin:10px;' onclick=\"{$onclick_evt}\">";
		//echo $company['Company']['name'];
		echo $company['name'];
	echo "</div>";
	
	if ($i%3 == 0)
	{
		echo "<div style='clear:both'></div>";
	}
}
echo "</ul>";

?>