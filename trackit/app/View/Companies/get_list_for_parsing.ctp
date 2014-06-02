<?php
if (empty($hide))
{
	foreach ($product_data as $product_id => $product)
	{
		echo "<div id=\"_parsing_urls_\" company_id='{$product['company_id']}' company_name='{$company_data[$product['company_id']]['name']}' product_id='{$product_id}' product_name='{$product['name']}'>";
		echo "</div>";
	}

	foreach ($content_data as $content_id => $content)
	{
		echo "<div id=\"_news_urls_\" link=\"{$content['link']}\">";
		echo "</div>";
	}
}
?>