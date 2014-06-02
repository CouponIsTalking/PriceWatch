<div class="ocConditions view">
<h2><?php echo __('Oc Condition'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Oc Id'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['oc_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Condition Id'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['condition_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prerequisite Condition1 Id'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['prerequisite_condition1_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prerequisite Condition2 Id'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['prerequisite_condition2_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param1'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['param1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param2'); ?></dt>
		<dd>
			<?php echo h($ocCondition['OcCondition']['param2']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Oc Condition'), array('action' => 'edit', $ocCondition['OcCondition']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Oc Condition'), array('action' => 'delete', $ocCondition['OcCondition']['id']), null, __('Are you sure you want to delete # %s?', $ocCondition['OcCondition']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Oc Conditions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Oc Condition'), array('action' => 'add')); ?> </li>
	</ul>
</div>
