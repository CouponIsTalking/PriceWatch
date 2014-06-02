<?php
App::uses('AppModel', 'Model');
/**
 * VoteType Model
 *
 * @property User $User
 * @property C $C
 */
class VoteType extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $useTable = false;
	
	public function is_valid_prod_vote_type($vote_type=false)
	{
		if(empty($vote_type)){
			return false;
		}
		if ('like' == $vote_type || 'want' == $vote_type || 'own' == $vote_type
			|| 'view' == $vote_type || 'track' == $vote_type)
		{
			return true;
		}
		
		return false;
	}
}