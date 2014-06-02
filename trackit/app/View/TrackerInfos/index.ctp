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

<div class="trackerInfos index">
	<h2><?php echo __('Tracker Infos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('company_id'); ?></th>
			<th><?php echo $this->Paginator->sort('titlexpath'); ?></th>
			<th><?php echo $this->Paginator->sort('pricexpath'); ?></th>
			<th><?php echo $this->Paginator->sort('title_price_xpath'); ?></th>
			<th><?php echo $this->Paginator->sort('pimg_xpath'); ?></th>
			<th><?php echo $this->Paginator->sort('title_price_css'); ?></th>
			<th><?php echo $this->Paginator->sort('details_xpath'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($trackerInfos as $trackerInfo): ?>
	<tr>
		<td><?php echo h($trackerInfo['TrackerInfo']['id']); ?>&nbsp;</td>
		<td><?php 
			$company_id = $trackerInfo['TrackerInfo']['company_id'];
			$company_website = $company_data[$company_id]['website'];
			echo $company_website;
		?>&nbsp;</td>
		<td><?php echo h($trackerInfo['TrackerInfo']['titlexpath']); ?>&nbsp;</td>
		<td><?php echo h($trackerInfo['TrackerInfo']['pricexpath']); ?>&nbsp;</td>
		<td><?php echo h($trackerInfo['TrackerInfo']['title_price_xpath']); ?>&nbsp;</td>
		<td><?php echo h($trackerInfo['TrackerInfo']['pimg_xpath']); ?>&nbsp;</td>
		<td><?php echo h($trackerInfo['TrackerInfo']['title_price_css']); ?>&nbsp;</td>
		<td><?php echo h($trackerInfo['TrackerInfo']['details_xpath']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $trackerInfo['TrackerInfo']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $trackerInfo['TrackerInfo']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $trackerInfo['TrackerInfo']['id']), null, __('Are you sure you want to delete # %s?', $trackerInfo['TrackerInfo']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Tracker Info'), array('action' => 'add')); ?></li>		
	</ul>
</div>
