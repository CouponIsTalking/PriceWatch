<div style="float:right; text-align:right;" href="get_open_campaigns">
<?php 
	echo "Like {$company['Company']['name']} ?";
	echo "<br/>";
	echo "Used {$company['Company']['name']}'s products ?";
	echo "<br/>";
	echo "Worked at {$company['Company']['name']} ?";
	echo "<br/>";
	echo $company['Company']['name'] . " is looking for promoters.";
	echo "<br/>";
	echo $this->Html->link(__('See how you and '.$company['Company']['name'].' can help each other.'), array('controller'=>'companies', 'action' => 'get_open_campaigns', $company['Company']['id']));
?>
</div>
<?php

echo "By ";
echo "<div>";
echo $this->Html->link($company['Company']['name'], array('controller' => 'companies', 'action' => 'view', $company['Company']['id']));
echo "</div>";

foreach ($product_data as $product_id => $product)
{
	echo "<div style=\"float:left; width:100px; background:grey; color:white;\">";
	echo $this->Html->link($product['name'], array('controller' => 'products', 'action' => 'view', $product_id));
	echo "</div>";

	echo "<div style=\"margin-left:10%; float:left; width:600px; background:grey; color:white;\">";
	$i = 0;
	foreach ($content_data as $index => $content)
	{
		if ($content['Content']['product_id'] != $product_id)
		{
			continue;
		}

		if ($i%2) echo "<div style=\"background:green; position:relative; clear:both\">";
		else echo "<div style=\"background:orange; position:relative; clear:both\">";
		$i = $i+1;

		echo "<div>";
		echo $content['Content']['title'];
		echo "</div>";

		echo "<div>";
		echo $content['Content']['desc'];
		echo "</div>";

		echo "</div>";

	}
	echo "</div>";
	
	echo "<div style=\"clear:both\"></div>";
	
}

// print company content now
	echo "<div style=\"float:left; width:100px; background:grey; color:white;\">";
	echo $this->Html->link($company['Company']['name'], array('controller' => 'companies', 'action' => 'view', $company['Company']['id']));
	echo "</div>";

	// image column first
	echo "<div style=\"margin-left:10%; float:left; width:600px; background:grey; color:white;\">";
	$i = 0;
	
	foreach ($content_data as $index => $content)
	{
		if ($content['Content']['product_id'] != 0)
		{
			continue;
		}
		
		$type = $content['Content']['type'];
		if ($type != 'image')
		{
			continue;
		}
		
		if ($i%2) echo "<div style=\"text-align:center; margin-bottom:10%; background:green; position:relative; clear:both\">";
		else echo "<div style=\"text-align:center; margin-bottom:10%; background:orange; position:relative; clear:both\">";
		$i = $i+1;

		echo "<div>";
		$image_path = CONTENT_IMG_FOLDER . $content['Content']['title'];
		echo "<img style=\"max-width:100%\" src=\"{$image_path}\"></img>";
		echo "</div>";

		echo "<div>";
		echo $content['Content']['desc'];
		echo "</div>";

		echo "</div>";

	}
	echo "</div>";

	// news and posts column now
	echo "<div style=\"margin-right:10%; float:right; width:400px; background:grey; color:white;\">";
	$i = 0;
	
	foreach ($content_data as $index => $content)
	{
		if ($content['Content']['product_id'] != 0)
		{
			continue;
		}
		
		$type = $content['Content']['type'];
		if ($type != 'news')
		{
			continue;
		}
		
		if ($i%2) echo "<div style=\"margin-bottom:10%; background:green; position:relative; clear:both\">";
		else echo "<div style=\"margin-bottom:10%; background:orange; position:relative; clear:both\">";
		$i = $i+1;

		echo "<div>";
		echo $content['Content']['title'];
		echo "</div>";
		echo "<br/>";
		echo "<br/>";
		echo "<div>";
		echo $content['Content']['desc'];
		echo "</div>";

		echo "</div>";

	}
	echo "</div>";

?>