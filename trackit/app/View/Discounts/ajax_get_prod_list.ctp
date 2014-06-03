<?php

if ($ret['s']){

if (empty($products)){$ret['new']=0;}
else{
	$ret['new']=1;

	$ret['prodlist'] = $this->element('product/prod_list_php', 
	  array('products' => $products, 
		'prod_votes' => $prod_votes,
		'loggedin_user_id'=>$preset_var_logged_in_user_id, 
		'edit_options' => $edit_options,
		'track_options' => $track_options,
		'like_want_own_options'=>$like_want_own_options
	  ));
	}
}

echo json_encode($ret);

?>