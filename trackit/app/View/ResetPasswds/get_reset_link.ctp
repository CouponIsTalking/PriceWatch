<?php

if ($ispost)
{
	echo json_encode($result);
}
else
{
	
	echo $this->Form->create();
	echo $this->Form->input('email', array('type'=> 'text', 'default' => "", 'label'=>'Enter your email', 'maxlength' => 30, 'style'=>'width:400px; height:10px;'));
	//echo "<input type='submit' class='purple_button'>Change Password</input>";
	echo $this->Form->end('Change Password', array('style'=>'width:auto;'));
	
}
?>