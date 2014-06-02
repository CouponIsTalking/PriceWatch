<div class="topics form">
<?php echo $this->Form->create('Topic'); ?>
	<fieldset>
		<legend><?php echo __('Add Topic'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('subtopic1');
		echo $this->Form->input('subtopic2');
		echo $this->Form->input('subtopic3');
		echo $this->Form->input('subtopic4');
		echo $this->Form->input('subtopic5');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Topics'), array('action' => 'index')); ?></li>
	</ul>
</div>
