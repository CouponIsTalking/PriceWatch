<?php
		$topic_data_select_list = array();
		
		foreach ($topic_data as $topic_id => $topic)
		{
			$topic_data_select_list[$topic_id] = $topic['name'];
		}
?>

<div class="companies form">
<?php echo $this->Form->create('Company'); ?>
	<fieldset>
		<legend><?php echo __('Edit Profile'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('website');
		//echo $this->Form->input('email');
		echo $this->Form->input('phone');
		
		if ($is_admin){
			echo $this->Form->input('topic1', array(
				'options' => $topic_data_select_list
			));
			
			echo $this->Form->input('topic2', array(
				'options' => $topic_data_select_list
			));
		}
		
		//echo $this->Form->input('topic1');
		//echo $this->Form->input('topic2');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Update info')); ?>
</div>

<div class="actions">
<?php
	$change_pwd_link = SITE_NAME . "reset_passwds/safe_update";
	echo "<span onmouseout=\"$(this).css('border-bottom', '2px solid rgba(0,0,0,0.8)');\" onmouseover=\"$(this).css('border-bottom', '3px solid rgba(0,0,0,0.6)');\" onclick=\"moveTo('{$change_pwd_link}');\" style='font-size:16px;padding:15px;color:rgba(0,0,0,0.8);border-bottom:2px solid rgba(0,0,0,0.8);cursor:pointer;'>Update password</span>";
	
	if ($is_admin){
	echo "<h3> Actions</h3>
	<ul>
		<li>"; 
		echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Company.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Company.id'))); 
	echo "</li>
		<li>";
	echo $this->Html->link(__('List Companies'), array('action' => 'index')); 
	echo "</li>
	</ul>";
	} 
?>	
</div>
