<?php
if ($is_ajax)
{
	echo json_encode($result);
	return;
}
?>

<script type='text/javascript'>
function updateMeans(e)
{
	$(e).siblings().attr('checked', false);
	$(e).attr('checked', true);
}

function _validate_params($company_name, $email, $password1, $password2, $website, $phone)
{
	if (!($company_name && $company_name.length >= 2))
	{
		show_success_message("Please check the company name that you entered.");
	}
	else if (!($email && $email != "" && validateEmail($email)))
	{
		show_success_message("Please check your email again.");
	}
	else if (!($password1 && $password1.length >= 6))
	{
		show_success_message("Please choose password that is at least 6 characters.");
	}
	else if (!($password1 && $password2 && ($password1 == $password2)))
	{
		show_success_message("Passwords that you entered, do not match. Please re-enter your passwords again.");
	}
	else if (!($website && $website.length >= 2))
	{
		show_success_message("Please enter your company's website.");
	}
	
	else
	{
		return true;
	}
	
	return false;
}

function _ajax_add_company($button_clicked)
{
	$form = $button_clicked.closest("#CompanyAddForm");
	$company_name = $form.find("#CompanyName").val();
	$email = $form.find("#CompanyEmail").val();
	$password1 = $form.find("#CompanyPassword1").val();
	$password2 = $form.find("#CompanyPassword2").val();
	$website = $form.find("#CompanyWebsite").val();
	$phone = $form.find("#CompanyPhone").val();
	
	$params_ok = _validate_params($company_name, $email, $password1, $password2, $website, $phone);
	//alert($company_name +"+"+ $email+"+"+  $password1+"+"+  $password2+"+"+  $website+"+"+  $phone);
	
	if ($params_ok)
	{
		show_success_message("Processing ... please wait ...");
		
		$.ajax({
			type:"POST",
			data:{ 
				company_name: $company_name,
				email:$email, 
				password1:$password1, 
				password2:$password2, 
				website:$website,
				phone: $phone
				}, 
			url: $S_N+"companies/add/",
			success : function(data) {
			   //alert(data);// will alert "ok"
				cb_clk();
				if (IsJsonString(data))
				{
					data = $.parseJSON(data);
					if (data['success'])
					{
						show_success_message(data['msg']);
					}
					else
					{
						show_success_message(data['msg']);
					}
				}
				else
				{
					show_success_message("There was an errror in processing request. Please drop us an email or phone if this error persists.");
				}
			},
			error : function(data) {
			   //alert("false");
			   cb_clk();
			   show_success_message("There was an errror in processing request. Please drop us an email or phone if this error persists.");			   
			}
		});
	}
}

</script>


<div class="companies">
<?php echo $this->Form->create('Company'); ?>
	<fieldset>
		<legend><?php echo __('Add Your Business'); ?></legend>
	<?php
		echo $this->Form->input('name', array('label'=>'Business Name', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
		echo $this->Form->input('website', array('label'=>'Business Website', 'maxlength' => 50, 'style'=>'width:600px; height:10px;'));
		echo $this->Form->input('phone', array('label'=>'Business Phone', 'maxlength' => 13, 'style'=>'width:200px; height:10px;'));
		
		echo $this->Form->input('email', array('label'=>'Email for login', 'maxlength' => 38, 'style'=>'width:600px; height:10px;'));
		echo $this->Form->input('password1', array('label'=>'Password', 'type' => 'password', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
		echo $this->Form->input('password2', array('label'=>'Re-type Password', 'type' => 'password', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
		
		echo "<green_button onclick='_ajax_add_company($(this));'>Add Your Business</green_button>";
		
		echo "<span style='margin-top:5px;font-size:10px;'>By registering or logging in, you agree to the <a style='font-style:underline;cursor:pointer;' target='blank' href='".SITE_NAME."pure/terms_and_conditions.html'>terms of use.</a></span><br/>.";
		
	?>
	
	</fieldset>
<?php 
	echo "<div style='display:none'>";
	echo $this->Form->end(array('style' => "display:'none'")); 
	echo "</div>";
?>
</div>