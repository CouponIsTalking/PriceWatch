<?php
App::uses('AppModel', 'Model');
/**
 * Vote Model
 *
 * @property User $User
 * @property C $C
 */
class ProdVote extends AppModel {
	
	public $useTable = 'product_votes';
	
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'own' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'like' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'want' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'p_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	private function make_vote_id ($user_id, $p_id)
	{
		$user_id_prefix=strval($user_id)."u";
		
		$ret_val = "";
		if (!is_array($p_id)){
			return  $user_id_prefix. strval($p_id) ."p";
		}else{
			$ret_val=array();
			$i=0;
			$final_i=count($p_id);
			while($i<$final_i){
				$ret_val[] = $user_id_prefix. strval($p_id[$i]) ."p";
				$i++;
			}
		}
		
		return $ret_val;
	}
	
	
	// Assumes a valid vote_type
	public function set_vote($vote_type, $vote_val, $user_id, $p_id)
	{
		//debug($vote_val); debug($user_id); debug($c_id);
		if ($vote_val < -1 || $vote_val > 1 || empty($user_id) || empty($p_id))
		{
			return false;
		}
				
		$vote_id = $this->make_vote_id($user_id, $p_id);
		//debug($vote_id);
		
		$this->id = $vote_id;
		$vote = array ('ProdVote' => array(
					'id' => $vote_id,
					$vote_type => $vote_val,
					'user_id' => $user_id,
					'p_id' => $p_id,
					'time' => time()
				));
		
		$saved = $this->save($vote);
		//debug($this->validationErrors); 
		if (!empty($saved))
		{
			return true;
		}
		
		return false;
		
	}
	
	public function find_vote($user_id, $p_id)
	{
		if(empty($user_id)){
			if(is_array($p_id)){return array();}
			else{return false;}
		}
		
		$vote_id = $this->make_vote_id($user_id, $p_id);
		
		if (is_array($vote_id)){
			
			if(1==count($vote_id)){
				$vote = $this->find('all', array('recursive'=>-1, 'conditions' => array('id' => $vote_id[0])));
				//if(!empty($vote)){$vote = array(0=>$vote);}
			}else{
				$vote = $this->find('all', array('recursive'=>-1, 'conditions' => array('id IN' =>$vote_id)));
			}
			//$vote = $this->find('all', array('recursive'=>-1, 'conditions' => array('id IN' =>$vote_id)));
		}else{
			$vote = $this->find('first', array('recursive'=>-1, 'conditions' => array('id' => $vote_id)));
		}
		
		if (empty($vote))
		{
			return false;
		}
		
		return $vote;
	}
	
}