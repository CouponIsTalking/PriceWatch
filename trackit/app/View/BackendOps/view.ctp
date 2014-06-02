<div class="backendOps view">
<h2><?php echo __('Backend Op'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($backendOp['BackendOp']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($backendOp['BackendOp']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($backendOp['BackendOp']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($backendOp['BackendOp']['status']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Backend Op'), array('action' => 'edit', $backendOp['BackendOp']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Backend Op'), array('action' => 'delete', $backendOp['BackendOp']['id']), null, __('Are you sure you want to delete # %s?', $backendOp['BackendOp']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Backend Ops'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Backend Op'), array('action' => 'add')); ?> </li>
	</ul>
</div>
