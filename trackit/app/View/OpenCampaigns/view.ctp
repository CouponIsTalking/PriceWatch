<div class="openCampaigns view">
<h2><?php echo __('Open Campaign'); ?></h2>
	<dl>
		<dt><?php echo __('Company'); ?></dt>
		<dd>
			<?php 
				$company_id = $openCampaign['OpenCampaign']['company_id'];
				echo h($company_data[$company_id]['name']); 
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Promoting for'); ?></dt>
		<dd>
			<?php 
				$product_id = $openCampaign['OpenCampaign']['product_id'];
				if ($product_id == 0)
				{
					echo "Overall Company";
				}
				else
				{
					echo h($product_data[$product_id]['name']); 
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Promotion Platform'); ?></dt>
		<dd>
			<?php 
				$promo_plat = $openCampaign['OpenCampaign']['type'];
				if ($promo_plat == 'blog')
				{
					echo "Blogs";
				}
				else if ($promo_plat == 'fb_posts')
				{
					echo "Facebook";
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Running ?'); ?></dt>
		<dd>
			<?php 
				$is_active = $openCampaign['OpenCampaign']['active'];
				if ($is_active == 1)
				{
					echo "Yes";
				}
				else if ($is_active == 0)
				{
					echo "No";
				}
			?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Activate'), array('action' => 'activate', $openCampaign['OpenCampaign']['id'])); ?></li>
		<li><?php echo $this->Form->postLink(__('Delete Open Campaign'), array('action' => 'delete', $openCampaign['OpenCampaign']['id']), null, __('Are you sure you want to delete # %s?', $openCampaign['OpenCampaign']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Open Campaigns'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Add an Open Campaign'), array('action' => 'add')); ?> </li>
	</ul>
</div>
