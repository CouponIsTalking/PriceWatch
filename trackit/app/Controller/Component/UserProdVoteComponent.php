<?php

/*
controller calling this should have instantiated -
1. UserData component
2. ProdVote class
*/
class UserProdVoteComponent extends Component {
	
	
	public function get_user_votes_on_prod($controller,$product_ids){
		if (empty($product_ids)){
			return array();
		}
		$user_id = $controller->UserData->getUserId();
		$prod_votes = $controller->ProdVote->find_vote($user_id, $product_ids);
		
		$prod_votes = $controller->ProdVote->ReindexOn($prod_votes,'p_id',
				array('cat_name'=>'ProdVote','strip_cat_name'=>true)
				);
		
		return $prod_votes;
	}
	
}

?>