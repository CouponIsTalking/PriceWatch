<div class="queues form">
<?php echo $this->Form->create('Queue'); ?>
	<fieldset>
		<legend><?php echo __('Add Queue'); ?></legend>
	<?php
		echo $this->Form->input('ocr_id');
		echo $this->Form->input('processed');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Queues'), array('action' => 'index')); ?></li>
	</ul>
</div>
