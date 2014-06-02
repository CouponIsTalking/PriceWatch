<div class="companies view">
<h2><?php echo h($company['Company']['name']); ?></h2>
<?php echo h($company['Company']['website']); ?>

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
	echo $this->Html->link(__('See how you and '.$company['Company']['name'].' can help each other.'), array('action' => 'get_open_campaigns', $company['Company']['id']));
?>
</div>


<div>

<div class="topic_name">
	<?php echo h($topic_data[$company['Company']['topic1']]['name']); ?>
</div>
<div class="topic_name">
	<?php echo h($topic_data[$company['Company']['topic2']]['name']); ?>
</div>

</div>
	
</div>


<div style="float:left width:200px">
	<ul>
		<li><?php echo $this->Html->link(__('List Companies'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Products by '. $company['Company']['name']), array('controller' => 'products', 'action' => 'view_by_company', $company['Company']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('View stuff related to this company'), array('controller' => 'contents', 'action' => 'view_by_company', $company['Company']['id'])); ?></li>
	</ul>
</div>

<br/>

<div style="clear:both">

<?php

foreach ($product_data as $product_id => $product)
{
	echo "<div style=\"float:left; width:200px; background:'light grey'; color:white;\">";
	echo $this->Html->link($product['name'], array('controller' => 'products', 'action' => 'view', $product_id));
	echo "</div>";

	echo "<div style=\"margin-left:10%; float:left; width:800px; background:grey; color:white;\">";
	$i = 0;
	foreach ($content_data as $index => $content)
	{
		if ($content['Content']['product_id'] != $product_id)
		{
			continue;
		}
		
		if ($i%2) echo "<div style=\"background:green; position:relative; clear:both;\">";
		else echo "<div style=\"background:orange; position:relative; clear:both;\">";
		$i = $i+1;
		
		echo "<div style=\"height:100px;\">";
		
			echo "<div>";
				echo "<a href=\"{$content['Content']['title']}\">";
					echo $content['Content']['title'];
				echo "</a>";
			echo "</div>";

		echo "<div>";
			$pos = 0;
			if (strlen($content['Content']['desc']) > 500)
			{
				$pos = strpos ($content['Content']['desc'], " ", 500);
			}
			if ($pos > 0)
				echo substr($content['Content']['desc'], 0, $pos) . " ... ";
			else 
				echo $content['Content']['desc'];
		echo "</div>";

		echo "</div>";
		
			echo "<div style=\"position:relative; margin-top:10px;\">";
				echo "<div class='share_content'>";
					echo "Share this";
				echo "</div>";
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
</div>