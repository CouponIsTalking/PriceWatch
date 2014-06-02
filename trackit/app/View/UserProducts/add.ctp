<div class="userProducts form">
<?php echo $this->Form->create('UserProduct'); ?>
	<fieldset>
		<legend><?php echo __('Add User Product'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('product_id');
		echo $this->Form->input('wait_price');
		echo $this->Form->input('user_product_name');
		echo $this->Form->input('group_name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List User Products'), array('action' => 'index')); ?></li>
	</ul>
</div>
