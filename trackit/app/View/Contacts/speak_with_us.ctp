<?php
	if ($is_ajax)
	{
		echo json_encode($result);
		return;
	}
?>

<style>
* div._contact_form
{
	max-width:60%;
}
</style>

<script type='text/javascript'>
function verify_form_and_send_req($form)
{
	$from_name = $form.find('#ContactName').val();
	$from_email = $form.find('#ContactEmail').val();
	$reason = $form.find('#ContactReason').val();
	$subject = $form.find('#ContactSubject').val();
	$message = $form.find('#ContactMessage').val();
	
	if (!$from_name)
	{
		show_success_message('Please enter your name.');
	}
	else if (!$from_email)
	{
		show_success_message('Please enter your email, so we can reach you back if needed.');
	}
	else if (!(validateEmail($from_email)))
	{
		show_success_message('Please enter a valid email, so we can reach you back if needed.');
	}
	else if (!$reason)
	{
		show_success_message('Please choose a reason that is closest to your message.');
	}
	else if (!$subject)
	{
		show_success_message('Please add a subject to your message.');
	}
	else if (!$message)
	{
		show_success_message('Please write your message.');
	}
	else
	{
		show_success_message("Please wait for a second, while we process your message.");
		
		$url = $S_N+"contacts/speak_with_us"
		$.ajax({
				type:"POST",
				data: {'name' : $from_name,'email' : $from_email,'reason' : $reason,'subject' : $subject,'message' : $message},
				url: $url,
				success : function(data) {
					//console.log(data);
					cb_clk();
					data = IsJsonString(data);
					if (data)
					{
						show_success_message(data.msg);
						$('div._msg_div').text(data.msg);
						$('div._msg_div').show();
						$('div._contact_form').hide();
					}
				},
				error : function(data) {
				   //alert("false");
				   //show_success_message("FB page Url does not seem right.");
				}
			});
	}
}
</script>

<?php 
	
	echo "
		<div class='_msg_div' style='display:none;background:#E0E0E0;border:2px; margin:5px; padding:5px;color:black;line-height:20px;font-size:14px;'>
		</div>
	";
	
	if(!empty($successfully_mail_sent)) {
		if($successfully_mail_sent == 1) {
			// show thank you msg
			
			echo "<div id='contact_req_thankyou_message' style='display:none'>";
			echo "<h1>";
			echo $result['msg'];
			echo "</h1>";
			echo "</div>";
		}
	}
	else {

		echo "<div class='_contact_form'>";
		
		echo $this->Form->create('Contact', array('onsubmit' => "verify_form_and_send_req($(this).closest('form'));"));
		//echo "<legend> 'Contact us'</legend>";
		//echo $this->Form->inputs();
		
		
		echo $this->Form->input('name', array('label' => __('Your Name', true), 'tabindex' => 1)) . PHP_EOL;
		
		if (!empty($user_email))
		{
			echo $this->Form->input('email', array('default' => $user_email, 'label' => __('Email', true), 'tabindex' => 1)) . PHP_EOL;
		}
		else
		{
			echo $this->Form->input('email', array('label' => __('Email', true), 'tabindex' => 1)) . PHP_EOL;
		}
		
		echo $this->Form->input('subject', array('label' => __('Subject', true), 'tabindex' => 1)) . PHP_EOL;
		
		$reasons = array(
			'A suggestion or idea' => 'A suggestion or idea',
			'Add your business' => 'Add your business',
			'Problem with Coupon' => 'Problem with Coupon',
			'Problem with the site' => 'Problem with the site',
			'Other' => 'Other'			
		);
		
		echo $this->Form->input('reason', array(
				'options' => $reasons,
				'default' => 0,
				'label' => 'Promote'
			)) . PHP_EOL;
			
		echo $this->Form->input('message', array('type'=>'textarea', 'label' => 'Message (max 500 characters).', 'maxlength' => '500'));
		
		//echo $captchaTool->show();		
		?>
		<div onclick="verify_form_and_send_req($(this).closest('form'));"><green_button>Submit</green_button></div>
	
		<div class="submit" style='display:none;'>
			<input type="submit" value="submit"></input>
		</div>
		
		<?php
		echo $this->Form->end();
		
		echo "</div>";
		
		echo "<div id='contact_req_wait_message' style='display:none'>";
		echo "<h1>";
		echo "Please wait while we send your inquiry.";
		echo "</h1>";
		echo "</div>";
		
		echo "<div id='contact_req_thankyou_message' style='display:none'>";
		echo "<h1>";
		echo "Thank you for contacting us. We would get back to you as soon as we can.";
		echo "</h1>";
		echo "</div>";
				
	}


?>