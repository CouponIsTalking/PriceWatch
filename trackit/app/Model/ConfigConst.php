<?php
App::uses('AppModel', 'Model');
/**
 * ConfigConst Model
 *
 */
class ConfigConst extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'config_consts';
	
	public function get_entry_by_key($key_name){
		
		$c = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array('name' => $key_name)
		));
		
		return $c;
	}
	
	public function get_val_by_key($key_name){
		$c = $this->get_entry_by_key($key_name);
		
		if (!empty($c['ConfigConst'])){
			$format = $c['ConfigConst']['valid_val_format'];
			if ('char'==$format){
				return $c['ConfigConst']['char_val'];
			}else if ('int'==$format){
				return $c['ConfigConst']['int_val'];
			}
		}
		
		return "";
		
	}
	
	public function update_val_by_key($key_name, $val){
		
		$c = $this->get_entry_by_key($key_name);
		
		if (empty($c['ConfigConst'])){
			return false;
		}
		
		$field_to_save = array();
		$format = $c['ConfigConst']['valid_val_format'];
		if ($format=='char'){
			$field_to_save = 'char_val';			
		}else if ($format=='int'){
			$field_to_save = 'int_val';
		}else{
			return false;
		}
		
		if ($val == $c['ConfigConst'][$field_to_save]){
			return true;
		}
		
		$c['ConfigConst'][$field_to_save] = $val;
		$this->id = $c['ConfigConst']['id'];
		$saved = $this->save($c, true, array($field_to_save));
		if (!empty($saved)){
			return true;
		}
		
		return false;
		
	}
}