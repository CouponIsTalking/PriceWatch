<?php echo $this->Html->script('custom/manage_collection'); ?>

<?php
echo "<div class='content_news_box' style='width:100%;'>";
echo "<br/>";
echo "<purple_button>";

if ($from == 3600)
{
echo "Price changes in last one hour";
}
else if ($from == 24* 3600)
{
echo "Price changes in last one day";
}
else if ($from == 7*24*3600)
{
echo "Price changes in last seven days";
}
else if ($from == 30*24*3600)
{
echo "Price changes in last one month";
}

echo "</purple_button>";

echo "</div>";


?>

<?php

if (empty($products))
{
	echo "No price change found with given criteria.";
}

foreach ($products as $index=>$prod)
{
$product = $prod['Product'];

$company = $companies[$product['company_id']];
$product_name = $product['name'];
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
"<div class='collection_item'>".
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
"   <!--div style='float:right'>".
"		<div class='group_change_tag_blue'  style='float:right' onclick='update_group_click_in_item_tile($(this));'>add</div>".
"	</div>".
"	<input class='action_val' style='display:none;' value=''></input>".
"   <div>".
"		".
"		".
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
"   </div-->".
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
?>