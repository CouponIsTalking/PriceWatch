<div class="userCoupons form">
<?php echo $this->Form->create('UserCoupon'); ?>
	<fieldset>
		<legend><?php echo __('Edit User Coupon'); ?></legend>
	<?php
		echo $this->Form->input('userid_content_coupon_code');
		echo $this->Form->input('id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('UserCoupon.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('UserCoupon.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List User Coupons'), array('action' => 'index')); ?></li>
	</ul>
</div>
