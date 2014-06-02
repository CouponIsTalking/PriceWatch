<?php
$tracker_info = $tracker_info['TrackerInfo'];
//echo "<div id='_tracker_info_' title_xpath=\"{$tracker_info['titlexpath']}\" old_price_xpath=\"{$tracker_info['oldpricexpath']}\" price_xpath=\"{$tracker_info['pricexpath']}\" title_and_price_xpath=\"{$tracker_info['title_price_xpath']}\" image_and_details_container_xpath=\"{$tracker_info['image_and_details_container_xpath']}\" image_and_title_parent_xpath=\"{$tracker_info['image_and_title_parent_xpath']}\" pimg_xpath1=\"{$tracker_info['pimg_xpath1']}\" pimg_xpath2=\"{$tracker_info['pimg_xpath2']}\" pimg_xpath3=\"{$tracker_info['pimg_xpath3']}\" pimg_xpath4=\"{$tracker_info['pimg_xpath4']}\" pimg_xpath5=\"{$tracker_info['pimg_xpath5']}\" pimg_xpath=\"{$tracker_info['pimg_xpath']}\" details_xpath=\"{$tracker_info['details_xpath']}\" titlexpath_regex=\"{$tracker_info['titlexpath_regex']}\" oldpricexpath_regex=\"{$tracker_info['oldpricexpath_regex']}\" pricexpath_regex=\"{$tracker_info['pricexpath_regex']}\" title_price_xpath_regex=\"{$tracker_info['title_price_xpath_regex']}\" image_and_details_container_xpath_regex=\"{$tracker_info['image_and_details_container_xpath_regex']}\" image_and_title_parent_xpath_regex=\"{$tracker_info['image_and_title_parent_xpath_regex']}\" pimg_xpath1_regex=\"{$tracker_info['pimg_xpath1_regex']}\" pimg_xpath2_regex=\"{$tracker_info['pimg_xpath2_regex']}\" pimg_xpath3_regex=\"{$tracker_info['pimg_xpath3_regex']}\" pimg_xpath4_regex=\"{$tracker_info['pimg_xpath4_regex']}\" pimg_xpath5_regex=\"{$tracker_info['pimg_xpath5_regex']}\" pimg_xpath_regex=\"{$tracker_info['pimg_xpath_regex']}\" details_xpath_regex=\"{$tracker_info['details_xpath_regex']}\" />";

echo "<div id='_tracker_info_' ";
foreach ($tracker_info as $key => $value)
{
	if ($key == 'title_price_css')
	{
		continue;
	}
	echo " {$key}=\"{$value}\"";
}
echo " />";

echo "<div id='csses'>";
echo json_encode("to be updated");
//echo $tracker_info['title_price_css'];
echo "</div>";

?>