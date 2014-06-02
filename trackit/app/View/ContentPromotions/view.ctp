<div class="ocResponses view">
<h2><?php echo __('Oc Response'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Oc Id'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['oc_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Blogger Id'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['blogger_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Response Type'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['response_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Response Blog Link'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['response_blog_link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Response Data'); ?></dt>
		<dd>
			<?php echo h($ocResponse['OcResponse']['response_data']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Oc Response'), array('action' => 'edit', $ocResponse['OcResponse']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Oc Response'), array('action' => 'delete', $ocResponse['OcResponse']['id']), null, __('Are you sure you want to delete # %s?', $ocResponse['OcResponse']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Oc Responses'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Oc Response'), array('action' => 'add')); ?> </li>
	</ul>
</div>
