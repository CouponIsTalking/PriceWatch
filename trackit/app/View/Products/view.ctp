<div class="products view">
<h2><?php echo __('Product'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($product['Product']['id']); ?>
			&nbsp;
		</dd>
		<!--dt><?php echo __('Company Id'); ?></dt>
		<dd>
			<?php 
				$company_id = $product['Product']['company_id'];
				$company_name = $company_data[$company_id]['name'];
				echo h(" by " . $company_name); 
			?>
			&nbsp;
		</dd-->
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php 
				$company_id = $product['Product']['company_id'];
				$company_name = $company_data[$company_id]['name'];
				
				echo h($product['Product']['name'] . " by " . $company_name); 
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Desc'); ?></dt>
		<dd>
			<?php echo h($product['Product']['desc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image1'); ?></dt>
		<dd>
			<?php echo h($product['Product']['image1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image2'); ?></dt>
		<dd>
			<?php echo h($product['Product']['image2']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Product'), array('action' => 'edit', $product['Product']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Product'), array('action' => 'delete', $product['Product']['id']), null, __('Are you sure you want to delete # %s?', $product['Product']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('View stuff related to this product'), array('controller' => 'contents', 'action' => 'find_by_product', $product['Product']['id'])); ?></li>
	</ul>
</div>
