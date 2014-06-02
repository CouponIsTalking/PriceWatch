<script type='text/javascript'>

function RunOnLoad()
{
	$text = $(".reset_password_msg").text();
	if (typeof $text != 'undefined' && "" != $text.trim() )
	{
		//show_success_message($text, show_user_login_form, 0);
		<?php if ($nopopup){ 
		echo "
			cmn_s_m_f_r_f(0, $text, 0, 0);
			";
		}
		?>
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
	echo "<div style='padding:15px;color:#e32;font-size:160%;font-weight:bold;'>Update your password</div>";
	echo $this->Form->input('pass1', array('type'=> 'password', 'default' => "", 'label'=>'   Enter New Password', 'maxlength' => 30, 'style'=>'width:400px; height:10px;'));
	echo $this->Form->input('pass2', array('type'=> 'password', 'default' => "", 'label'=>'Re-enter New Password', 'maxlength' => 30, 'style'=>'width:400px; height:10px;'));
	echo $this->Form->end('Update Password', array('style'=>'width:200px;'));
}
else
{
	
}	
?>