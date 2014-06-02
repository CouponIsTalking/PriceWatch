<?php echo $this->Html->script('custom/manage_collection'); ?>

<?php
$company = $raw_comp_info['Company'];
$company_site_name = $company['website'];
$company_name = $company['name'];

echo "<div class='content_news_box' style='width:100%;'>
	<br/>
	<div class='tag'>{$company_name}</div>
</div>";

foreach ($raw_products as $index=>$product)
{
$product_name = $product['Product']['name'];
$product_link = $product['Product']['purl'];
$product_price = $product['Product']['cur_price'];
$product_image = $product['Product']['image1'];
if (empty($product_image))
{
	$product_image = SITE_NAME."/img/default_item_pic.png"; 
}

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