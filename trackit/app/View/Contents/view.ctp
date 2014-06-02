<div class="contents view">
<h2><?php echo __('Content'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($content['Content']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		
		<dd><?php 
		
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
		</dd>
		
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($content['Content']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Desc'); ?></dt>
		<dd>
			<?php echo h($content['Content']['desc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($content['Content']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Topic1'); ?></dt>
		<dd>
			<?php echo h($topic_data[$content['Content']['topic1']]['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($content['Content']['state']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Content'), array('action' => 'edit', $content['Content']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Content'), array('action' => 'delete', $content['Content']['id']), null, __('Are you sure you want to delete # %s?', $content['Content']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Content'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Pricing'), array('controller' => 'content_prices', 'action' => 'get_content_pricing', $content['Content']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('View other stuff related to this product'), array('controller' => 'contents', 'action' => 'find_by_product', $content['Content']['product_id'])); ?> </li>
		
	</ul>
</div>
