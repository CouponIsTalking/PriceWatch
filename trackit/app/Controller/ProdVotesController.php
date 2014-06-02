<?php
App::uses('AppController', 'Controller');
/**
 * PollVotes Controller
 *
 * @property PollVote $PollVote
 * @property PaginatorComponent $Paginator
 */
class ProdVotesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	public $uses = array ('Product', 'ProdVote', 'VoteType');

	/*
		$vote_type = one of the values 'like','want','own','track','view'
		$new_vote = one of the values 'yes','no','dontknow'
		$p_id = <product_id>
	*/
	public function vote()
	{
		$this->layout = 'ajax';
		
		$result = array('s' => 0, 'm' => '');
		$this->set('result', $result);
		
		$is_ajax = $this->RequestHandler->isAjax();
		if(!$is_ajax){
			return;
		}
		
		$data = $this->request->data;
		//debug($data);
		$vote_type = $data['vote_type'];
		$new_vote = $data['new_vote'];
		$p_id = $data['p_id'];
		$user_id = $this->UserData->getUserId();		
		
		// check if vote type is valid
		$valid_vote_type = $this->VoteType->is_valid_prod_vote_type($vote_type);
		
		if (empty($user_id)){
			$result['ul'] = false;
		}
		else if(empty($new_vote) || (!$valid_vote_type) || empty($p_id)){
			$result['m'] = 'Bad Request.';
		}
		else if (('yes' != $new_vote) && ('no' != $new_vote) && ('dontknow' != $new_vote)){
			$result['m'] = 'Bad Vote Value.';
		}
		else {
			//$cd = $this->Contender->getRawContenderInfo($cid);
			$product = $this->Product->findRawProductInfoById($p_id);
			
			if (empty($product['Product']))
			{
				$result['m'] = 'Bad Request';
			}
			else
			{
				
				$vote_change = true;
				
				$prodvote = $this->ProdVote->find_vote($user_id, $p_id);
				if (!empty($prodvote['ProdVote']))
				{
					$cur_val = $prodvote['ProdVote'][$vote_type];
					$dir = 'none';
					
					if ( (($cur_val == 1) && ('yes' == $new_vote))
						||(($cur_val == -1) && ('no' == $new_vote))
						|| (($cur_val == 0) && ('dontknow' == $new_vote))
						)
					{
						$result['m'] = 'Records updated.';
						$result['s'] = true;
						$vote_change = false;
					}
					else if ($cur_val == 1 && $new_vote != 'yes')
					{
						$dir='down';
						$vote_change = true;
					}
					else if ($cur_val != 1 && $new_vote == 'yes')
					{
						$dir='up';
						$vote_change = true;
					}
				}else{
					if('yes' == $new_vote){
						$dir='up';
					}else if ('yes' == $new_vote){
						$dir='down';
					}					
				}
				
				if ($vote_change)
				{
					if ('yes' == $new_vote){
						$new_vote_val = 1;
					}else if ('no' == $new_vote){
						$new_vote_val = -1;
					}else if ('dontknow' == $new_vote){
						$new_vote_val = 0;
					}
					$updated = $this->ProdVote->set_vote($vote_type, $new_vote_val, $user_id, $p_id);
					if (empty($updated))
					{
						$result['m'] = 'There was a problem in updating records.';
					}
					else
					{
						$updated = $this->Product->update_lwo_vote($p_id, $vote_type, $dir);
						if ($updated){
							$result['s'] = true;
							$result['m'] = 'Records updated.';
						}else{
							$result['m'] = 'There was a problem while updating records.';
						}
					}
				}
				
			}
		}
		
		$this->set('result', $result);
	}
	
}