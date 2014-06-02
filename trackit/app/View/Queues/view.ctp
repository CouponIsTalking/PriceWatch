<div class="queues view">
<h2><?php echo __('Queue'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($queue['Queue']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ocr Id'); ?></dt>
		<dd>
			<?php echo h($queue['Queue']['ocr_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($queue['Queue']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Processed'); ?></dt>
		<dd>
			<?php echo h($queue['Queue']['processed']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Queue'), array('action' => 'edit', $queue['Queue']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Queue'), array('action' => 'delete', $queue['Queue']['id']), null, __('Are you sure you want to delete # %s?', $queue['Queue']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Queues'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Queue'), array('action' => 'add')); ?> </li>
	</ul>
</div>
