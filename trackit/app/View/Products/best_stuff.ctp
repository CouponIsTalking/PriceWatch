<?php
if ($show_company_list)
{
	echo "<ul>";
	$i = 0;
	foreach ($companies as $company_id => $company)
	{
		$i = $i+1;
		//$encoded_url = urlencode($company['Company']['website']);
		$link = SITE_NAME."products/best_stuff/{$company_id}";
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

}
else
{
	foreach ($prods as $index=>$product)
	{
	$product = $product['Product'];
	$product_name = $product['name'];
	$company = $companies[$product['company_id']];
	$product_link = $product['purl'];
	$product_price = $product['cur_price'];
	$product_image = $product['image1'];
	$group_name = " ";
	
	if (empty($product_image) && empty($product_price))
	{
		continue;
	}
	
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
	"	<div class='group_name' style='float:right'>".
	"		{$group_name}".
	"	</div>".
	"	<div style='clear:both'></div>".
	"</div>".
	"";

	echo $div;

	}
}
?>