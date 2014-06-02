<div class="userProducts index">
	<h2><?php echo __('User Products'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('wait_price'); ?></th>
			<th><?php echo $this->Paginator->sort('user_product_name'); ?></th>
			<th><?php echo $this->Paginator->sort('group_name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($userProducts as $userProduct): ?>
	<tr>
		<td><?php echo h($userProduct['UserProduct']['id']); ?>&nbsp;</td>
		<td><?php echo h($userProduct['UserProduct']['user_id']); ?>&nbsp;</td>
		<td><?php echo h($userProduct['UserProduct']['product_id']); ?>&nbsp;</td>
		<td><?php echo h($userProduct['UserProduct']['wait_price']); ?>&nbsp;</td>
		<td><?php echo h($userProduct['UserProduct']['user_product_name']); ?>&nbsp;</td>
		<td><?php echo h($userProduct['UserProduct']['group_name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $userProduct['UserProduct']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $userProduct['UserProduct']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $userProduct['UserProduct']['id']), null, __('Are you sure you want to delete # %s?', $userProduct['UserProduct']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New User Product'), array('action' => 'add')); ?></li>
	</ul>
</div>
