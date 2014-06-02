<div class="ocConditions form">
<?php echo $this->Form->create('OcCondition'); ?>
	<fieldset>
		<legend><?php echo __('Add Oc Condition'); ?></legend>
	<?php
		echo $this->Form->input('oc_id');
		echo $this->Form->input('condition_id');
		echo $this->Form->input('prerequisite_condition1_id');
		echo $this->Form->input('prerequisite_condition2_id');
		echo $this->Form->input('param1');
		echo $this->Form->input('param2');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Oc Conditions'), array('action' => 'index')); ?></li>
	</ul>
</div>
