<div class="contents form">
<?php echo $this->Form->create('Content'); ?>
	<fieldset>
		<legend><?php echo __('Edit Content'); ?></legend>
	<?php
		echo $this->Form->input('id');
		$company_data_select_list = array();
		
		foreach ($company_data as $company_id => $company)
		{
			$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
		}
		
		$product_data_select_list = array();
		
		foreach ($product_data as $product_id => $product)
		{
			if (!empty($company_data[$product['company_id']]['name']))
				$product_data_select_list[$product_id] = $product['name'] . ' by ' . $company_data[$product['company_id']]['name'];
			else
				$product_data_select_list[$product_id] = $product['name'];
		}
		
		$topic_data_select_list = array();
		
		foreach ($topic_data as $topic_id => $topic)
		{
			$topic_data_select_list[$topic_id] = $topic['name'];
		}
		
		echo $this->Form->input('company_id', array(
            'options' => $company_data_select_list
        ));
		
		echo $this->Form->input('product_id', array(
            'options' => $product_data_select_list
        ));
		//echo $this->Form->input('company_id');
		//echo $this->Form->input('product_id');
		echo $this->Form->input('title');
		echo $this->Form->input('desc');
		echo $this->Form->input('link');
		
		echo $this->Form->input('topic1', array(
            'options' => $topic_data_select_list
        ));
		//echo $this->Form->input('topic1');
		
		echo $this->Form->input('state');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Content.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Content.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Contents'), array('action' => 'index')); ?></li>
	</ul>
</div>
