<?php
App::uses('AppModel', 'Model');
/**
 * Pcard Model
 *
 */
class Pcard extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'pcards';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	
	public function add_card($title, $desc, $total_visits, $company_id, $create_date){
		$r = array('s'=>false,'m'=>'');
		
		if (empty($title) || empty($total_visits) || empty($company_id)){
			$r['m'] = 'Bad param';
			return $r;
		}
		
		$this->create();
		$ts = array('Pcard' => 
				array('title' => $title,
					  'desc' => $desc,
					  'company_id' => $company_id,
					  'create_date' => $create_date,
					  'total_visits' => $total_visits
				));
		
		$saved = $this->save($ts);
		if (!empty($saved)){
			$r['s'] = true;
			$r['id'] = $this->id;
		}else{
			$r['m'] = $this->validationErrors;
		}
		
		return $r;
	}
	
	public function find_by_cardid($card_id){
		if (empty($card_id)){
			return false;
		}
		
		$card = $this->find('first',array(
			'recursive' => -1,
			'conditions'=>array('id' => $card_id)
		));
		
		return $card;
	}
	
	public function find_multiple_cards_by_id($card_ids){
		
		$cards = $this->find('all',array(
			'recursive' => -1,
			'conditions'=>array('id' => $card_ids)
		));
		
		return $cards;
	}
	
	public function increase_visits($card_id, $new_visits){
		
		$r = array('s'=>false,'m'=>'');
		
		if (empty($card_id) || empty($new_visits)){
			$r['m'] = 'Bad params';
			return $r;
		}		
		
		$card = $this->find('first', array(
				'recursive'=>-1,
				'conditions'=>array(
					'id' => $card_id
				)
			));
			
		if (empty($card['Pcard'])){
			$r['m'] = 'Punch card not found';
		}else if ($card['Pcard']['total_visits'] == $new_visits){
			$r['m'] = "Punch card already has {$new_visits} visits";
		}else if ($card['Pcard']['total_visits'] > $new_visits){
			$r['m'] = "Punch card already has {$card['Pcard']['total_visits']} visits. You can only increase punch card visits.";
		}else{
			$card['total_visits']=$new_visits;
			$saved = $this->save($card, true, array('total_visits'));
			if (!empty($saved)){
				$r['s'] = true;
			}else{
				$r['m'] = $this->validationErrors;
			}
		}
		
		return $r;
	}
	

}
