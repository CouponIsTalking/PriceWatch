<?php
	$company_data_select_list = array();
	
	foreach ($company_data as $company_id => $company)
	{
		$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
	}
	
	$tinfo_by_cid = SITE_NAME . "tracker_infos/get_by_cid/";		
	echo $this->Form->input('company_id', array(
		'options' => $company_data_select_list,
		'onchange' => "cid=$(this).val();moveTo('{$tinfo_by_cid}'+cid);",
		'label' => 'See by Company'
	));
?>

<div class="trackerInfos view">
<h2><?php echo __('Tracker Info'); ?></h2>
	<dl>
		
		<?php
			foreach($trackerInfo['TrackerInfo'] as $key=>$value)
			{
				if('company_id' == $key){
					$company_id = $value;
					$company = $company_data[$company_id];
					$value = $company['name'] . " " . $company['website'];
				}
				echo "
				<div style='margin:15px;background:rgba(0,0,0,0.8);'>
				<div style='padding:5px;color:rgba(255,255,255,0.8);min-width:400px;width:auto;height:auto;'>{$key}</div>
				<div style='padding:5px;color:rgba(255,255,255,0.8);width:500px;height:auto;'>{$value}&nbsp;</div>
				</div>
				";
			}
		?>
		
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tracker Info'), array('action' => 'edit', $trackerInfo['TrackerInfo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tracker Info'), array('action' => 'delete', $trackerInfo['TrackerInfo']['id']), null, __('Are you sure you want to delete # %s?', $trackerInfo['TrackerInfo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tracker Infos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tracker Info'), array('action' => 'add')); ?> </li>
	</ul>
</div>
