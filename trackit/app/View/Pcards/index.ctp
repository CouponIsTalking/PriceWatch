<?php

if (empty($cards)){
	$create_card_link = SITE_NAME . "pcards/create";
	echo "
	<span style='rgba(0,0,0,0.8);font-size:25px; font-style:Helvetica sans-serif;padding:15px;'>
		No punchcards found. <span style='cursor:pointer;border-bottom:2px solid rgba(0,0,0,0.8);' onclick=\"moveTo('{$create_card_link}')\">Create your punchcard.</span>
	</span>";
}
else{
?>
<div class="pcards">
	<h2><?php echo __('Your Punch Cards'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Title'); ?></th>
			<th><?php echo $this->Paginator->sort('Description'); ?></th>
			<th><?php echo $this->Paginator->sort('Max visits'); ?></th>
			<th><?php echo $this->Paginator->sort('Date created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cards as $card): ?>
	<tr>
		<td><?php
			$view_link = SITE_NAME . "pcards/view/{$card['Pcard']['id']}";
			echo "<span onclick=\"moveTo('{$view_link}')\" style='border-bottom:2px solid rgba(0,0,0,0.8);cursor:pointer;'>{$card['Pcard']['title']}</span>"; 
			?>&nbsp;</td>
		<td style='max-width:400px;'><?php echo h($card['Pcard']['desc']); ?>&nbsp;</td>
		<td><?php echo h($card['Pcard']['total_visits']); ?>&nbsp;</td>
		<td><?php echo h($card['Pcard']['create_date']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $card['Pcard']['id'])); ?>
			<?php //echo $this->Html->link(__('Expand'), array('action' => '')); ?>			
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
<?php
}
?>