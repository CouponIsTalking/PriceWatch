<div class="backendOps form">
<?php echo $this->Form->create('BackendOp'); ?>
	<fieldset>
		<legend><?php echo __('Add Backend Op'); ?></legend>
	<?php
		echo $this->Form->input('type');
		echo $this->Form->input('url');
		echo $this->Form->input('status');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Backend Ops'), array('action' => 'index')); ?></li>
	</ul>
</div>
