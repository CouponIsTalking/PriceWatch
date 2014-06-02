<div class="userCoupons view">
<h2><?php echo __('User Coupon'); ?></h2>
	<dl>
		<dt><?php echo __('Userid Content Coupon Code'); ?></dt>
		<dd>
			<?php echo h($userCoupon['UserCoupon']['userid_content_coupon_code']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($userCoupon['UserCoupon']['id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User Coupon'), array('action' => 'edit', $userCoupon['UserCoupon']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User Coupon'), array('action' => 'delete', $userCoupon['UserCoupon']['id']), null, __('Are you sure you want to delete # %s?', $userCoupon['UserCoupon']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List User Coupons'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Coupon'), array('action' => 'add')); ?> </li>
	</ul>
</div>
