<?php
echo $this->Html->css('pchart');
echo $this->Html->script('custom/manage_collection');
echo $this->Html->script('pdisp/pchart');
echo $this->Html->script('underscore/underscore-min');
echo $this->Html->script('charts/d3.v3');

?>
<style type='text/css'>
* .collection_name {margin: 10px;padding: 5px;background: rgb(7, 7, 7);color: white;
font-family: Helvetica sans-serif;font-size: 18px;cursor:pointer;
}
* .collection_name:hover{ 
color: rgb(7,7,7); background:rgb(220,220,220);
-webkit-transition: background-color 300ms linear;
-moz-transition: background-color 300ms linear;
-o-transition: background-color 300ms linear;
-ms-transition: background-color 300ms linear;
transition: background-color 300ms linear;
}

*.selected_collection_name{margin: 10px;padding: 5px;background:rgb(170,70,70);color: white;
font-family: Helvetica sans-serif;font-size: 18px;cursor:pointer;	
}

*.no_collection_msg{margin: 10px;background:rgb(170,70,70);color: white;
font-family: Helvetica sans-serif;cursor:pointer;
font-size:30px;padding:20px;text-align:center;padding-left:100px;padding-right:100px;
}
* .no_collection_msg:hover{ 
color: rgb(7,7,7); background:rgb(220,220,220);
-webkit-transition: background-color 300ms linear;
-moz-transition: background-color 300ms linear;
-o-transition: background-color 300ms linear;
-ms-transition: background-color 300ms linear;
transition: background-color 300ms linear;
}

* .share_tag{
	letter-spacing:2px;
	border-bottom:1px dotted #333;
	transition-duration:0.5s;
	-webkit-transition-duration:0.5s;
	cursor:pointer;
	font-size:16px;
}
* .share_tag:hover{
	border-bottom:1px solid #333;
}


</style>

<script type='text/javascript'>
function _call_draw_pchart($this){
	$prodid=$this.parents('.collection_item').find('._pid').text().trim();
	draw_price_chart(parseInt($prodid));
}

function _share_link_click($this){
var st=$this.parent().find('._share_link');
if('none'==st.css('display')){
st.css('opacity','0').css('display', 'block').fadeTo(500, 0.95);
}else{st.css('display', 'none');}

}
</script>

<?php

if (empty($collection_names)){
	$tracker_page = SITE_NAME . "socials/trackproduct";
	echo "<div class='no_collection_msg' style='' onclick=\"moveTo('{$tracker_page}');\">You haven't built any collections yet. Start by tracking some products and adding them in your collection first :).</div>";
	return;
}

echo "<div class='content_news_box' style='width:100%;border:none;'>";
echo "<br/>";
?>

<div class="paging">
<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total')
	));
	echo "&nbsp;&nbsp;&nbsp;";
	echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
?>
</div>

<?php
if(empty($filtered_group_name)){
	$filtered_group_name = "All";
}

$share_link = SITE_NAME."collections/shared/{$collection_share_tag}";
echo "<div style='margin:10px;'><span class='share_tag'>
<span onclick=\"_share_link_click($(this));\">SHARE</span>
<div style='max-width:800px;'>
<div style='margin:15px;margin-bottom:0px;'><span class='_share_link' style='padding: 3px 5px;font-size:14px;display:none;border:1px dotted #333;' onclick=\"var e=arguments[0]; e.stopPropagate();\">Link: {$share_link}</span></div>
<div style='margin:15px;margin-bottom:0px;'><span class='_share_link' style='padding: 3px 5px;font-size:14px;display:none;border:1px dotted #333;' onclick=\"var e=arguments[0]; e.stopPropagate();\">Share Collection On Facebook <div class='fb-share-button' data-href=\"{$share_link}\" data-type='button'></div></span></div>
<div style='margin:15px;margin-bottom:0px;'><span class='_share_link' style='padding: 3px 5px;font-size:14px;display:none;border:1px dotted #333;' onclick=\"var e=arguments[0]; e.stopPropagate();\">Share Collection on Twitter  <a href=\"https://twitter.com/share\" class='twitter-share-button' data-url=\"{$share_link}\" data-dnt='true' data-count='none' data-text='Shopping Collection' data-via='".GENERIC_APPNAME."'>Tweet</a></span></div>
</div>
</span></div>
";

foreach ($collection_names as $index=>$group_name)
{
	$url = array(
    'controller' => 'collections',
    'action' => 'my'
	);

	$my_params = array(
		'group_name' => $group_name
	);

	$url = $this->Html->url(array_merge($url, $my_params));
	
	if ($group_name == $filtered_group_name){echo "<div class='selected_collection_name' style='display:inline-block;white-space:nowrap;'>{$group_name}</div>";}
	else{echo "<div class='collection_name' style='display:inline-block;white-space:nowrap;' onclick=\"moveTo('{$url}');\">{$group_name}</div>";}
}
	$url = $this->Html->url(array('controller' => 'collections', 'action' => 'my'));
	if ('All' == $filtered_group_name){echo "<div class='selected_collection_name' style='display:inline-block;white-space:nowrap;'>All</div>";}
	else{echo "<div class='collection_name' style='display:inline-block;white-space:nowrap;' onclick=\"moveTo('{$url}');\">All</div>";}
	
echo "<br/>";
echo "<br/>";

echo "</div>";


?>

<?php

$show_track_option = true;
if (!empty($self_collection) && $self_collection){
	$show_track_option = false;
}

$element_out = $this->element('product/prod_list', 
	array('products' => $products, 
		'prod_votes' => $prod_votes,
		'loggedin_user_id'=>$preset_var_logged_in_user_id, 
		'edit_options' => true,
		'track_options' => $show_track_option,
		'like_want_own_options'=>true
	));
	
echo "<div style='max-width:100%;float:left;'>";
echo $element_out;
echo "</div>";

//$bgtex3_image = SITE_NAME.'img/bgtex3.jpg';
if ($show_track_option){
echo "<div class='_tracker_frame' style=\"display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:rgba(0,0,0,0.6);\">";
echo $this->element('product/trackprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id, 
	'list_supported_stores' => false,
	'show_close_button' => true
	));
echo "</div>";
}


echo "<div class='_prodvote_frame' onclick=\"$(this).find('._prodvote_popup_x').click();\" style=\"display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:transparent\">";
echo $this->element('product/voteprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id,
	'show_close_button' => true
	));
echo "</div>";

?>