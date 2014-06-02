<script src="/ab/js/custom/opencampaign.js" charset="utf-8"></script>

<div class="ocResponses">
	<h2><?php echo __('Your Entries'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('oc_id'); ?></th>
			<th><?php echo 'Promotion Platform'; ?></th>
			<th><?php echo $this->Paginator->sort('response_blog_link'); ?></th>
			<th><?php echo $this->Paginator->sort('response_data'); ?></th>
	</tr>
	<?php foreach ($ocResponses as $ocResponse): ?>
	<tr>
		<td><?php echo h($ocResponse['OcResponse']['created']); ?>&nbsp;</td>
		<td><?php 
				$onclick_activate_event = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
				echo "<a onclick=\"{$onclick_activate_event}\">Campaign</a>"; 
			?>&nbsp;
		</td>
		<td><?php 
				$promo_plat = $ocResponse['OcResponse']['response_type'];
				if ($promo_plat == 'blog')
				{
					echo "Blog";
				}
				else if ($promo_plat == 'fb_post')
				{
					echo "Facebook";
				}
			?>
			&nbsp;
		</td>
		<td><?php echo h($ocResponse['OcResponse']['response_blog_link']); ?>&nbsp;</td>
		<td><?php echo h($ocResponse['OcResponse']['response_data']); ?>&nbsp;</td>
		<!--td class="actions">
			<?php //echo $this->Html->link(__('View'), array('action' => 'view', $ocResponse['OcResponse']['id'])); ?>
			<?php //echo $this->Html->link(__('Edit'), array('action' => 'edit', $ocResponse['OcResponse']['id'])); ?>
			<?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $ocResponse['OcResponse']['id']), null, __('Are you sure you want to delete # %s?', $ocResponse['OcResponse']['id'])); ?>
		</td-->
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
<div class="opencampaign_details_click_response" style="display:none"></div>