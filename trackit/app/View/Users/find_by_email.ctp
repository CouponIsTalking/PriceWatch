<?php

$user = $r['u'];
if (!empty($user)){
	$element_out = $this->element('common/list_table_fields', 
		array('data' => $user,
			'edit_link' => false,
			'links'=>array(
				'Login As This User'=>SITE_NAME . "users/out_and_in_as/".urlencode($r['eu'])
				)
		)
	);
	echo $element_out;
}
else{
	echo "<div class='user_info'>";

		echo $this->Form->create('User'); 
			echo $this->Form->input("email", array(
				'label' => "Enter email to lookup the user."
			));
			echo $this->Form->end(__('Submit'));

	echo "</div>";
}
?>