<style>
.conditions_in_oc_add_form
{
left-margin:20%
}
</style>

<div class="openCampaigns form">
<?php echo $this->Form->create('OpenCampaign'); ?>
	<fieldset>
		<legend><?php echo __('Edit Open Campaign'); ?></legend>
	<?php
		$company_data_select_list = array();
		
		foreach ($company_data as $company_id => $company)
		{
			$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
		}
		
		$product_data_select_list = array();
		
		foreach ($product_data as $product_id => $product)
		{
			if (!empty($company_data[$product['company_id']]['name']))
				$product_data_select_list[$product_id] = $product['name'] . ' by ' . $company_data[$product['company_id']]['name'];
			else
				$product_data_select_list[$product_id] = $product['name'];
		}
		$product_data_select_list[0] = 'Promote our company';
		
		echo $this->Form->input('company_id', array(
            'options' => $company_data_select_list
        ));
		
		echo $this->Form->input('product_id', array(
            'options' => $product_data_select_list,
			'default' => 0
        ));
		
		$oc_type_list = array();
		$oc_type_list['blog'] = 'Blog Post';
		
		echo $this->Form->input('type', array(
            'options' => $oc_type_list
        ));
		
		// echo $this->Form->input('type');
		
		// echo $this->Form->input('active');
		
		$conditions_list = array();
		foreach ($conditions as $id => $name)
		{
			$conditions_list[$id] = $name['name'];
		}
		$conditions_list[0] = '----None----';
		$offer_type['coupon'] = "Coupon worth";
		$offer_type['dollar'] = "Dollar amount worth";
		
		echo "<label>Pick offers to promoters</label>";
		
			echo $this->Form->input('condition1', array(
				'options' => $conditions_list, 'label' => 'For following condition (choose condition) ', 'default' => 0,
				//'style'=>'left-margin:10%;',
				//'css'=>'left-margin:10%;'
			));
			echo $this->Form->input('condition1_param1', array('label' => 'Of (enter a number or date that fulfill above condition)'));
			echo $this->Form->input('condition1_offer_type', array('options' => $offer_type, 'label' => ', you offer promoters'));
			echo $this->Form->input('condition1_offer_worth', array('label' => 'worth (enter total worth of the above offer type)'));
			
		
		echo "<label>Pick another type of offer (if multiple)</label>";
		
			echo $this->Form->input('condition2', array(
				'options' => $conditions_list, 'label' => 'For following condition (choose condition) ', 'default' => 0,
				//'style'=>'left-margin:10%;',
				//'css'=>'left-margin:10%;'
			));
			echo $this->Form->input('condition2_param1', array('label' => 'Of (enter a number or date that fulfill above condition)'));
			echo $this->Form->input('condition2_offer_type', array('options' => $offer_type, 'label' => ', you offer promoters'));
			echo $this->Form->input('condition2_offer_worth', array('label' => 'worth (enter total worth of the above offer type)'));
			
		
		echo $this->Form->input('condition3', array(
            'options' => $conditions_list, 'label' => 'Pick the last date for promoters to fulfill above requirements.', 'default' => 5, 'disabled' => TRUE
        ));
		//echo $this->Form->input('condition3_param1', array('label' => 'Enter MM/DD/YYYY format'));
		echo $this->Form->input('condition3_param1', array(
			'label' => 'Date',
			'type'=>'date',
			'dateFormat' => 'DMY',
			'minYear' => date('Y'),
			'maxYear' => date('Y')+1
		));
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Open Campaigns'), array('action' => 'index')); ?></li>
	</ul>
</div>