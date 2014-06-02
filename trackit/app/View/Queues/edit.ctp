<div class="queues form">
<?php echo $this->Form->create('Queue'); ?>
	<fieldset>
		<legend><?php echo __('Edit Queue'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('ocr_id');
		echo $this->Form->input('processed');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Queue.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Queue.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Queues'), array('action' => 'index')); ?></li>
	</ul>
</div>
