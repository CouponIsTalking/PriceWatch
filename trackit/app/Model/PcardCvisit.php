<?php
App::uses('AppModel', 'Model');
/**
 * PcardCvisit Model
 *
 */
class PcardCvisit extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'pcard_cvisits';
	
	public function set_note($cust_card_id, $visit_num, $note){
		
		$r = array('s'=>false,'m'=>'');
		
		if (empty($cust_card_id) || empty($visit_num)){
			$r['m'] = 'Bad param';
			return $r;
		}
		
		$pcid_vnum = "{$cust_card_id}_{$visit_num}";
		
		$ts = $this->find('first', array(
				'recursive' => -1,
				'conditions'=>array(
					'pcid_vnum' => $pcid_vnum
				)
			));
		
		if (!empty($ts['PcardCvisit'])){
			$this->id = $ts['PcardCvisit']['id'];
			if ($ts['PcardCvisit']['note'] == $note){
				$saved = true;
			}
			else{
				$ts['PcardCvisit']['note'] = $note;
				$saved = $this->save($ts, true, array('note'));
			}
		}
		else{
			$this->create();
			$ts = array('PcardCvisit' => array());
			$ts['PcardCvisit']['pcid'] = $cust_card_id;
			$ts['PcardCvisit']['vnum'] = $visit_num;
			$ts['PcardCvisit']['pcid_vnum'] = $pcid_vnum;
			$ts['PcardCvisit']['note'] = $note;
			$saved = $this->save($ts);
		}
		
		if (!empty($saved)){
			$r['s'] = true;
		}else{
			$r['m'] = $this->validationErrors;
		}
		
		return $r;
	}
	
	public function mark_visited($cust_card_id, $visit_num){
		$r = $this->set_visited($cust_card_id, $visit_num, 1);
		return $r;
	}
	
	public function mark_unvisited($cust_card_id, $visit_num){
		$r = $this->set_visited($cust_card_id, $visit_num, 0);
		return $r;
	}
	
	public function set_visited($cust_card_id, $visit_num, $visit_val){
		
		$r = array('s'=>false,'m'=>'');
		
		if (empty($cust_card_id) || empty($visit_num)){
			$r['m'] = 'Bad param';
			return $r;
		}
		
		$pcid_vnum = "{$cust_card_id}_{$visit_num}";
		
		$ts = $this->find('first', array(
				'recursive' => -1,
				'conditions'=>array(
					'pcid_vnum' => $pcid_vnum
				)
			));
		
		if (!empty($ts['PcardCvisit'])){
			$this->id = $ts['PcardCvisit']['id'];
			$ts['PcardCvisit']['visited'] = $visit_val;
			$saved = $this->save($ts, true, array('visited'));
		}
		else{
			$this->create();
			$ts = array('PcardCvisit' => array());
			$ts['PcardCvisit']['pcid'] = $cust_card_id;
			$ts['PcardCvisit']['vnum'] = $visit_num;
			$ts['PcardCvisit']['pcid_vnum'] = $pcid_vnum;
			$ts['PcardCvisit']['visited'] = $visit_val;
			$saved = $this->save($ts);
		}
		
		if (!empty($saved)){
			$r['s'] = true;
		}else{
			$r['m'] = $this->validationErrors;
		}
		
		return $r;
	}
	
	public function find_visits_by_custcardid($cust_card_id){
		if (empty($cust_card_id)){
			return false;
		}
		
		$visits = $this->find('all',array(
			'recursive' => -1,
			'conditions'=>array('pcid' => $cust_card_id)
		));
		
		return $visits;
	}
}
