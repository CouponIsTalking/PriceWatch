<div class="fcparserInfos form">
<?php echo $this->Form->create('FcparserInfo'); ?>
	<fieldset>
		<legend><?php echo __('Add Fcparser Info'); ?></legend>
	<?php
		$company_data_select_list = array();
		
		foreach ($company_data as $company_id => $company)
		{
			$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
		}
		
		echo $this->Form->input('company_id', array(
            'options' => $company_data_select_list
        ));
		echo $this->Form->input('store_name', array(
            'options' => array('coupons.com'=>'Coupons.com', 'retailmenot.com'=>'RetailMeNot.com', 'dealshark.com' => 'DealShark.com')
        ));
		//echo $this->Form->input('store_name');
		echo $this->Form->input('coupon_page_link');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Fcparser Infos'), array('action' => 'index')); ?></li>
	</ul>
</div>
