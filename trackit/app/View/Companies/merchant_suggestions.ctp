<?php
if (!$show_form)
{
	if (!empty($result))
	{
		echo json_encode($result);
	}
}
else
{
?>
<script type="text/javascript">
function _validate_params($company_name, $website, $info)
{
	if (!($company_name && $company_name.length >= 2))
	{
		s_s_m("Please check the business name.");
	}
	else
	{
		return true;
	}
	
	return false;
}

function _ajax_suggest_company($button_clicked)
{
	$form = $button_clicked.closest("#CompanyMerchantSuggestionsForm");
	$company_name = $form.find("#CompanyName").val();
	$website = $form.find("#CompanyWebsite").val();
	$info = $form.find("#CompanyInfo").val();
	//console.log($info);
	$params_ok = _validate_params($company_name, $website, $info);
	
	
	if ($params_ok){
		s_s_m("Sending suggestion, please wait a sec ...");
		
		$.ajax({type:"POST", data:{name: $company_name,website:$website,info:$info}, 
			url: $S_N+"companies/merchant_suggestions/",
			success : function(data) {data = IsJsonString(data);
				if (data){
					if (data['success']){$html = data['msg'];
						$('div._msg_div').text($html);$('div._msg_div').show();
						$('div.companies').hide();
					}else{$html = data['msg'];}
				}else{
					$html = "There was an errror in processing request. Please let us know your suggestion via email at team@usemenot.com.";
				}
				
				cb_clk();
				cmn_s_m_f_r_f(0, $html, 0, 0);
				
			},error : function(data){cb_clk();
				$html = "There was a network errror in processing request. Please let us know your suggestion via email at team@usemenot.com.";
				cmn_s_m_f_r_f(0, $html, 0, 0);
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
	
	echo "<div class='companies'>";
	echo $this->Form->create('Company'); 
	echo "	
		<fieldset>
			<legend>
			Suggest a place where you love to shop, and we'll try to get their coupons.
			</legend>
		";
			echo $this->Form->input('name', array('label'=>'Business Name', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
			echo $this->Form->input('website', array('label'=>'Business Website', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
			echo $this->Form->input('info', array('type'=>'textarea', 'label'=>'Business Detail', 'maxlength' => 100, 'rows' => 4, 'style'=>'width:500px; height:100px;'));
			
			echo "<green_button onclick='_ajax_suggest_company($(this));'>Send Suggestion</green_button>";
		
	echo "	
		</fieldset>
		";
		
		echo "<div style='display:none'>";
		echo $this->Form->end(array('style' => "display:'none'")); 
		echo "</div>";
	echo "
	</div>
	";
}
?>