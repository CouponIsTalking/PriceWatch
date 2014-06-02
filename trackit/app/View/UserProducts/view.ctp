<div class="userProducts view">
<h2><?php echo __('User Product'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($userProduct['UserProduct']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Id'); ?></dt>
		<dd>
			<?php echo h($userProduct['UserProduct']['user_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Id'); ?></dt>
		<dd>
			<?php echo h($userProduct['UserProduct']['product_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Wait Price'); ?></dt>
		<dd>
			<?php echo h($userProduct['UserProduct']['wait_price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Product Name'); ?></dt>
		<dd>
			<?php echo h($userProduct['UserProduct']['user_product_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group Name'); ?></dt>
		<dd>
			<?php echo h($userProduct['UserProduct']['group_name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User Product'), array('action' => 'edit', $userProduct['UserProduct']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User Product'), array('action' => 'delete', $userProduct['UserProduct']['id']), null, __('Are you sure you want to delete # %s?', $userProduct['UserProduct']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List User Products'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Product'), array('action' => 'add')); ?> </li>
	</ul>
</div>
