<div class="contents index">
	<h2><?php echo __('Contents'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo 'Product'; ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('desc'); ?></th>
			<th><?php echo $this->Paginator->sort('link'); ?></th>
			<th><?php echo $this->Paginator->sort('topic1'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($contents as $content): ?>
	<tr>
		<td><?php echo h($content['Content']['id']); ?>&nbsp;</td>
		
		<td><?php 
		
				$product_name = " product id : " . $content['Content']['product_id'];
				$company_name = " company id : " . $content['Content']['company_id'];
				$company_site = "";
				
				if (!empty($product_data[$content['Content']['product_id']]['name']))
				{
					$product_name = $product_data[$content['Content']['product_id']]['name'];
				}
				
				if (!empty ($company_data[$content['Content']['company_id']]['name']))
				{
					$company_name = $company_data[$content['Content']['company_id']]['name'];
					$company_site = $company_data[$content['Content']['company_id']]['website'];
				}
				
				echo "<div>";
				echo $product_name;
				echo "</div>";
				echo " by ";
				echo "<div>";
				echo $company_name;
				echo "</div>";
				echo " ";
				echo "<div>";
				echo $company_site;
				echo "</div>";
				
				//echo h($product['Product']['name'] . " by " . $company_name); 
				//echo h($product['Product']['name']); 
			?>&nbsp;
		</td>
		
		<td><?php echo h($content['Content']['title']); ?>&nbsp;</td>
		<td><?php echo h($content['Content']['desc']); ?>&nbsp;</td>
		<td><?php echo h($content['Content']['link']); ?>&nbsp;</td>
		<td><?php echo h($topic_data[$content['Content']['topic1']]['name']); ?>&nbsp;</td>
		<td><?php echo h($content['Content']['state']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $content['Content']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $content['Content']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $content['Content']['id']), null, __('Are you sure you want to delete # %s?', $content['Content']['id'])); ?>
			<?php echo $this->Html->link(__('Pricing'), array('controller' => 'content_prices', 'action' => 'get_content_pricing', $content['Content']['id'])); ?> 
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Content'), array('action' => 'add')); ?></li>
	</ul>
</div>
