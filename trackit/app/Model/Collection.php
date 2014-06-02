<?php
App::uses('AppModel', 'Model');
/**
 * Collection Model
 *
 */
class Collection extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'group_name';
	
	
	private function hash_from_uid_and_grpname($uid, $grp_name){
		$uid_grpname = $uid."_".$grp_name;
		$h = crc32($uid_grpname);
		//$h = hash('ripemd160', $uid_grpname);
		return $h;
	}
	
	public function addCollection($uid,$grp_name){
		
		$uid_grpname = $uid."_".$grp_name;
		$hash = $this->hash_from_uid_and_grpname($uid, $grp_name);
		$c = array(
			'user_id'=>$uid,
			'group_name'=>$grp_name,
			'uid_grpname'=>$uid_grpname,
			'hash'=>$hash,
			'views'=>0,
			'share_times'=>0
		);
		$this->create();
		$saved = $this->save(array('Collection'=>$c));
		if (!empty($saved)){
			$c['id'] = $this->id;
			return $c;
		}else{
			return false;
		}
	}
	
	public function findby_uid_and_grpname($uid,$grp_name,$add_if_not = false){
		
		$uid_grpname = $uid."_".$grp_name;
		
		$c = $this->find('first', array(
			'recursive'=>-1,
			'conditions'=>array('uid_grpname'=>$uid_grpname)
		));

		if(!empty($c)){
			$c = $c['Collection'];
		}else if($add_if_not){
			$c = $this->addCollection($uid, $grp_name);
		}
		return $c;
	}
	
	public function findby_id_and_hash($id, $hash){
		
		$c = $this->find('first', array(
			'recursive'=>-1,
			'conditions'=>array('id'=>$id)
		));
		
		if (!empty($c['Collection']['hash']) && ($c['Collection']['hash'] == $hash)
		){
			return $c['Collection'];
		}
		
		return false;
	}
	
}