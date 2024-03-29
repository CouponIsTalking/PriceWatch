<div class="topics index">
	<h2><?php echo __('Topics'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('subtopic1'); ?></th>
			<th><?php echo $this->Paginator->sort('subtopic2'); ?></th>
			<th><?php echo $this->Paginator->sort('subtopic3'); ?></th>
			<th><?php echo $this->Paginator->sort('subtopic4'); ?></th>
			<th><?php echo $this->Paginator->sort('subtopic5'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($topics as $topic): ?>
	<tr>
		<td><?php echo h($topic['Topic']['id']); ?>&nbsp;</td>
		<td><?php echo h($topic['Topic']['name']); ?>&nbsp;</td>
		<td><?php echo h($topic['Topic']['subtopic1']); ?>&nbsp;</td>
		<td><?php echo h($topic['Topic']['subtopic2']); ?>&nbsp;</td>
		<td><?php echo h($topic['Topic']['subtopic3']); ?>&nbsp;</td>
		<td><?php echo h($topic['Topic']['subtopic4']); ?>&nbsp;</td>
		<td><?php echo h($topic['Topic']['subtopic5']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $topic['Topic']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $topic['Topic']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $topic['Topic']['id']), null, __('Are you sure you want to delete # %s?', $topic['Topic']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Topic'), array('action' => 'add')); ?></li>
	</ul>
</div>
