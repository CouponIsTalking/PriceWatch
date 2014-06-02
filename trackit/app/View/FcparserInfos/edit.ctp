<div class="fcparserInfos form">
<?php echo $this->Form->create('FcparserInfo'); ?>
	<fieldset>
		<legend><?php echo __('Edit Fcparser Info'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('company_id', array(
            'type' => 'hidden'
        ));
		echo $this->Form->input('store_name');
		echo $this->Form->input('coupon_page_link');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('FcparserInfo.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('FcparserInfo.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Fcparser Infos'), array('action' => 'index')); ?></li>
	</ul>
</div>
