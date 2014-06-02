<div class="ocResponses form">
<?php echo $this->Form->create('OcResponse'); ?>
	<fieldset>
		<legend><?php echo __('Edit Oc Response'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('oc_id');
		echo $this->Form->input('blogger_id');
		echo $this->Form->input('response_type');
		echo $this->Form->input('response_blog_link');
		echo $this->Form->input('response_data');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OcResponse.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('OcResponse.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Oc Responses'), array('action' => 'index')); ?></li>
	</ul>
</div>
