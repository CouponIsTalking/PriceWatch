<?php
App::uses('AppModel', 'Model');
/**
 * ResetPasswd Model
 *
 */
class ResetPasswd extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = '';

	
	public function deleteByEmail($email)
	{
		if (empty($email))
		{
			return false;
		}
		
		$result = $this->deleteAll(array('email' => $email), false, false);
		
		return $result;
	}
	
	public function findEntryByHash($h)
	{
		if (empty($h)){return null;}
		
		$entry = $this->find('first', array('recursive'=>-1, 'conditions'=>array('hash' => $h)));
		
		if (!empty($entry['ResetPasswd']))
		{
			return $entry;
		}
		else
		{
			return null;
		}
	}
	
	public function addEntry($h, $email, $add_time)
	{
		$re = array('success'=>0, 'msg'=>"");
		
		if (empty($h) || empty($email) || empty($add_time))
		{
			return $re;
		}
		
		$this->create();
		$entry = array('ResetPasswd'=>array('hash'=>$h,'email'=>$email,'add_time'=>$add_time));
		$saved = $this->save($entry);
		if (empty($saved))
		{
			return $re;
		}
		
		$re['success'] = 1;
		return $re;
	}
	
	public function create_new_hash($email, $add_time)
	{
		$re = array('success'=>0, 'msg'=>"");
		
		if (empty($email))
		{
			return $re;
		}
		
		$h = "";
		for($i =0;;$i++)
		{
			$str_to_hash = $add_time . strval($i). $email;
			$h = hash('ripemd160', $str_to_hash);
			
			$h_entry = $this->findEntryByHash($h);
			if (empty($h_entry))
			{
				break;
			}
		}
		
		$re = $this->addEntry($h, $email, $add_time);
		if ($re['success'])
		{
			$re['hash'] = $h;
		}
		else
		{
			$re['hash'] = "";
		}
		
		
		return $re;
		
	}
}
