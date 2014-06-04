<?php

if (!empty($company)){
	$element_out = $this->element('common/list_table_fields', 
		array('data' => $company['Company'],
			'edit_link' => SITE_NAME."companies/edit/{$company['Company']['id']}"
		)
	);
	echo $element_out;
}
else{
	echo "<div class='tracker_infos'>";

		echo $this->Form->create('Company'); 
			echo $this->Form->input("company_website", array(
				'label' => "Enter website name to look for tracker."
			));
			echo $this->Form->end(__('Submit'));

	echo "</div>";
}
?>