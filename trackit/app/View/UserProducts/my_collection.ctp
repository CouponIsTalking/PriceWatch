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

</style>

<script type='text/javascript'>
function _call_draw_pchart($this){
	$prodid=$this.parents('.collection_item').find('._pid').text().trim();
	draw_price_chart(parseInt($prodid));
}
</script>

<?php
echo "<div class='content_news_box' style='width:100%;border:none;'>";
echo "<br/>";


if(empty($filtered_group_name)){
	$filtered_group_name = "All";
}

foreach ($collection_names as $index=>$group_name)
{
	$url = array(
    'controller' => 'user_products',
    'action' => 'my_collection'
	);

	$my_params = array(
		'group_name' => $group_name
	);

	$url = $this->Html->url(array_merge($url, $my_params));
	
	if ($group_name == $filtered_group_name){echo "<div class='selected_collection_name' style='display:inline-block;white-space:nowrap;'>{$group_name}</div>";}
	else{echo "<div class='collection_name' style='display:inline-block;white-space:nowrap;' onclick=\"moveTo('{$url}');\">{$group_name}</div>";}
}
	$url = $this->Html->url(array('controller' => 'user_products', 'action' => 'my_collection'));
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

<?php
/*
foreach ($ups as $index=>$up)
{
$up_id = $up['UserProduct']['id'];
$product_name = $up['UserProduct']['user_product_name'];
$group_name = $up['UserProduct']['group_name'];
$product_id = $up['UserProduct']['product_id'];
$product = $products[$up['UserProduct']['product_id']];
$company = $companies[$product['company_id']];
$product_link = $product['purl'];
$product_price = $product['cur_price'];
$product_image = $product['image1'];
if (empty($product_image))
{
	$product_image = SITE_NAME."/img/default_item_pic.png"; 
}
$company_site_name = $company['website'];
$company_name = $company['name'];

$div = "".
"<div class='collection_item'>
    <span class='_pid' style='display:none;' onclick='_call_draw_pchart($(this));'>{$product_id}</span>
".
"	<item_site_name>".
"		{$company_name}".
"	</item_site_name>".
"	<div style='clear:both'></div>".
"	<div class='item_name' onclick=\"OpenInNewTab('{$product_link}');\">".
//"		<div onclick=\"OpenInNewTab('{$product_link}');\">".
"			{$product_name}".
//"		</div>".
"	</div>".
"	<div style='clear:both'></div>".
"	<item_pic>".
"		<img onclick=\"OpenInNewTab('{$product_link}');\" src=\"{$product_image}\"></img>".
"	</item_pic>".
"	<div style='clear:both'></div>".
"	<price>".
"		{$product_price}".
"	</price>".
"	<div style='clear:both'></div>".
"	<div class='group_name' style='float:right'>".
"		{$group_name}".
"	</div>".
"	<div style='clear:both'></div>".
"   <div style='float:right'>".
"		<div class='group_change_tag_blue'  style='float:right' onclick='update_group_click_in_item_tile($(this));'>remove</div>".
"		<div class='group_change_tag_brown' style='float:right' onclick='update_group_click_in_item_tile($(this));'>move</div>".
"		<div class='group_change_tag_blue'  style='float:right' onclick='update_group_click_in_item_tile($(this));'>copy</div>".
"	</div>
	<div style='clear:both'></div>
	<div class='trend_sign_container' onclick='_call_draw_pchart($(this));'>
	<div class='trend_sign' style='margin:7px 7px 7px 7px;width:16px;height:16px;'></div>
	</div>
".
"	<input class='action_val' style='display:none;' value=''></input>".
"   <div>".
"		".
"		".
"	<form class='remove_item_form' style='display:none'>".
"		<div>".
"			Are you sure you want to remove this item ?".
"		</div>".
"		<br/>".
"		<black_button onclick=\"remove_from_group($(this), '{$up_id}');\">".
"			Yes".
"		</black_button>".
"		<black_button onclick=\"x_overlay_element_click($(this));\">".
"			No".
"		</black_button>".
"	</form>".
"	<form class='update_collection_form' style='display:none'>
		<input type='hidden' class='existing_group_name' style='display:none;' value='{$group_name}'></input>
		<input type='hidden' value='{$up_id}' class='itemid'></input>		
		<div class='action_name'></div>
		<div class='pick_existing_collection_select_box'>
			<select name='group_name' class='group_name_select_box' required='required'>
";

foreach ($collection_names as $index=>$group_name)
{
	$div = $div . "<option name='{$group_name}' val='{$group_name}'>{$group_name}</option>";
}

$div = $div . "			
			</select>
			
			<input type='hidden' value='0' class='is_creating_new_collection'></input>
			<br/><br/>
			<black_button onclick='update_group_name( $(this) );'>Add To Collection</black_button>
		</div>
		
		<black_button onclick='show_add_new_collection_form($(this));'>+</black_button>
		<div class='add_new_collection_name' style='display:none'>
			<input name='new_group_name' class='new_group_name' required='required' maxlength='20'>
			</input>
			<input type='hidden' value='1' class='is_creating_new_collection'></input>
			<br/><br/>
			<black_button onclick='update_group_name( $(this) );'>Add To Collection</black_button>
		</div>
	</form>".
"		".
"   </div>".
"   <div class='overlay_element' style='display:none;'>".
"	</div>".
"	<div class='x_overlay_element' style='display:none;' onclick='x_overlay_element_click($(this));'>".
"		X".
"	</div>".
"	<div class='msg' style='display:none;'>".
"	</div>".
"	<div style='clear:both'></div>".
"</div>".
"";

echo $div;

}

echo "
<div class='graph_container'>
<div id='chart'></div>
<div class='price_chart_close' onclick='clear_price_chart();'>X</div>
</div>
";

*/
?>