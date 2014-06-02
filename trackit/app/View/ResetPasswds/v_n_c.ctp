<script type='text/javascript'>

function RunOnLoad()
{
	$text = $(".reset_password_msg").text();
	if (typeof $text != 'undefined' && "" != $text.trim() )
	{
		//show_success_message($text, show_user_login_form, 0);
		show_success_message($text, 0, 0);
		reposition_in_center('div.success_msg');	
	}
	
}

</script>

<?php

if ( !empty($result['msg']))
{
	echo "
	<div class='reset_password_msg' style='display:none;'>
	{$result['msg']}
	</div>
	";
}
	
if ($show_form)
{
	echo $this->Form->create();
	echo $this->Form->input('email', array('type'=> 'text', 'default' => "", 'label'=>'Enter your email', 'maxlength' => 30, 'style'=>'width:400px; height:10px;'));
	echo $this->Form->input('pass1', array('type'=> 'password', 'default' => "", 'label'=>'   Enter Password', 'maxlength' => 30, 'style'=>'width:400px; height:10px;'));
	echo $this->Form->input('pass2', array('type'=> 'password', 'default' => "", 'label'=>'Re-enter Password', 'maxlength' => 30, 'style'=>'width:400px; height:10px;'));
	echo $this->Form->end('Change Password', array('style'=>'width:200px;'));
}
else
{
	
}	
?>