<!-- app/View/Users/add.ctp -->
<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Add User'); ?></legend>
        <?php echo $this->Form->input('username', array('label'=>'email'));
        echo $this->Form->input('password');
		echo $this->Form->input('password2', array('type'=>'password', 'label'=>'Re-enter Password'));
        echo $this->Form->input('role', array(
            'options' => array('company' => 'I need someone to advertise', 'blogger' => 'I advertise')
        ));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>