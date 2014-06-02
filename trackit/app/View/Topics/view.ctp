<div class="topics view">
<h2><?php echo __('Topic'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtopic1'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['subtopic1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtopic2'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['subtopic2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtopic3'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['subtopic3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtopic4'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['subtopic4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtopic5'); ?></dt>
		<dd>
			<?php echo h($topic['Topic']['subtopic5']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Topic'), array('action' => 'edit', $topic['Topic']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Topic'), array('action' => 'delete', $topic['Topic']['id']), null, __('Are you sure you want to delete # %s?', $topic['Topic']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Topics'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Topic'), array('action' => 'add')); ?> </li>
	</ul>
</div>
