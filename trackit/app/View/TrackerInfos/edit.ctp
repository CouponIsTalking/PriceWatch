<div class="trackerInfos form">
<?php echo $this->Form->create('TrackerInfo'); ?>
	<fieldset>
		<legend><?php echo __('Edit Tracker Info'); ?></legend>
	<?php
		//echo $this->Form->input('company_id');
		echo $this->Form->input('id', array(
            'type' => 'hidden'
        ));
		echo $this->Form->input('company_id', array(
            'type' => 'hidden'
        ));
		echo "<div style='font-weight:bold;font-size:20px;'>{$company_website}</div>";
		echo $this->Form->input('titlexpath');
		echo $this->Form->input('pricerootxpath');
		echo $this->Form->input('pricexpath');
		echo $this->Form->input('oldpricexpath');
		echo $this->Form->input('saleprxpath_onsale');
		echo $this->Form->input('regprxpath_onsale');
		echo $this->Form->input('wasprxpath_onsale');
		echo $this->Form->input('priceofferxpath');
		echo $this->Form->input('pricerootxpath_regex');
		echo $this->Form->input('saleprxpath_onsale_regex');
		echo $this->Form->input('regprxpath_onsale_regex');
		echo $this->Form->input('wasprxpath_onsale_regex');
		echo $this->Form->input('priceofferxpath_regex');
		echo $this->Form->input('title_price_xpath');
		echo $this->Form->input('pimg_xpath');
		echo $this->Form->input('details_xpath');
		echo $this->Form->input('title_price_css');
		echo $this->Form->input('pimg_xpath1');
		echo $this->Form->input('pimg_xpath2');
		echo $this->Form->input('pimg_xpath3');
		echo $this->Form->input('pimg_xpath4');
		echo $this->Form->input('pimg_xpath5');
		echo $this->Form->input('urllib2_pimg_xpath1');
		echo $this->Form->input('urllib2_pimg_xpath2');
		echo $this->Form->input('urllib2_pimg_xpath3');
		echo $this->Form->input('urllib2_pimg_xpath4');
		echo $this->Form->input('urllib2_pimg_xpath5');
		echo $this->Form->input('image_and_title_parent_xpath');
		echo $this->Form->input('image_and_details_container_xpath');
		echo $this->Form->input('product_id_for_regex');
		echo $this->Form->input('titlexpath_regex');
		echo $this->Form->input('pricexpath_regex');
		echo $this->Form->input('oldpricexpath_regex');
		echo $this->Form->input('title_price_xpath_regex');
		echo $this->Form->input('pimg_xpath1_regex');
		echo $this->Form->input('pimg_xpath2_regex');
		echo $this->Form->input('pimg_xpath3_regex');
		echo $this->Form->input('pimg_xpath4_regex');
		echo $this->Form->input('pimg_xpath5_regex');
		echo $this->Form->input('urllib2_pimg_xpath1_regex');
		echo $this->Form->input('urllib2_pimg_xpath2_regex');
		echo $this->Form->input('urllib2_pimg_xpath3_regex');
		echo $this->Form->input('urllib2_pimg_xpath4_regex');
		echo $this->Form->input('urllib2_pimg_xpath5_regex');
		echo $this->Form->input('image_and_title_parent_xpath_regex');
		echo $this->Form->input('image_and_details_container_xpath_regex');
		echo $this->Form->input('is_image_in_og_image_meta_tag');
		echo $this->Form->input('pinterest_position');
		echo $this->Form->input('price_cur_code');
		echo $this->Form->input('old_price_cur_code');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Tracker Infos'), array('action' => 'index')); ?></li>
	</ul>
</div>
