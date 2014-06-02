<div class="userCoupons index">
	<h2><?php echo __('User Coupons'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('userid_content_coupon_code'); ?></th>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($userCoupons as $userCoupon): ?>
	<tr>
		<td><?php echo h($userCoupon['UserCoupon']['userid_content_coupon_code']); ?>&nbsp;</td>
		<td><?php echo h($userCoupon['UserCoupon']['id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $userCoupon['UserCoupon']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $userCoupon['UserCoupon']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $userCoupon['UserCoupon']['id']), null, __('Are you sure you want to delete # %s?', $userCoupon['UserCoupon']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New User Coupon'), array('action' => 'add')); ?></li>
	</ul>
</div>
