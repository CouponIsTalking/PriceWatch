<div class="fcparserInfos view">
<h2><?php echo __('Fcparser Info'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($fcparserInfo['FcparserInfo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Company Id'); ?></dt>
		<dd>
			<?php echo $company['Company']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Store Name'); ?></dt>
		<dd>
			<?php echo h($fcparserInfo['FcparserInfo']['store_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Coupon Page Link'); ?></dt>
		<dd>
			<?php 
				$link = $fcparserInfo['FcparserInfo']['coupon_page_link'];
				echo "<a href=\"{$link}\">{$link}</a>";
			?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Fcparser Info'), array('action' => 'edit', $fcparserInfo['FcparserInfo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Fcparser Info'), array('action' => 'delete', $fcparserInfo['FcparserInfo']['id']), null, __('Are you sure you want to delete # %s?', $fcparserInfo['FcparserInfo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Fcparser Infos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Fcparser Info'), array('action' => 'add')); ?> </li>
	</ul>
</div>
