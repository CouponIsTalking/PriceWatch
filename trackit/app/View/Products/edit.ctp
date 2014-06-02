<div class="products form">
<?php echo $this->Form->create('Product'); ?>
	<fieldset>
		<legend><?php echo __('Edit Product'); ?></legend>
	<?php
		$company_data_select_list = array();
		
		foreach ($company_data as $company_id => $company)
		{
			$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
		}
		
		echo $this->Form->input('company_id', array(
            'options' => $company_data_select_list
        ));

		echo $this->Form->input('id');
		//echo $this->Form->input('company_id');
		echo $this->Form->input('name');
		echo $this->Form->input('desc');
		echo $this->Form->input('image1');
		echo $this->Form->input('image2');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Save')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Product.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Product.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Products'), array('action' => 'index')); ?></li>
	</ul>
</div>
