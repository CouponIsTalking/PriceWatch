<?php
App::uses('AppModel', 'Model');
/**
 * PriceNotification Model
 *
 */
class PriceNotification extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $useTable = 'priceupdate_notifications';
	
	public function add_new($notf_info){
		
		if(empty($notf_info) 
		|| empty($notf_info['uid']) 
		|| empty($notf_info['pid'])
		){
			return false;
		}
		
		$notf_info['id']=$notf_info['uid']."_".$notf_info['pid'];
		$notf_info['sent'] = 0;
		
		$this->create();
		$saved = $this->save(array('PriceNotification'=>$notf_info));
		if (!empty($saved)){
			return true;
		}else{
			return false;
		}
	}
	
	public function find_group_by_userid_and_size($user_id, $max_group_size){
		
		if(empty($user_id)||empty($max_group_size)){return;}
		
		$notifications = $this->find('all', 
			array('recursive'=>-1,
					'limit'=>$max_group_size,
					'conditions'=>array('uid'=>$user_id)
			));
		return $notifications;
	}
	
	public function delete_by_notf_ids($notification_ids){
		
		if(empty($notification_ids)){return;}
		
		$this->deleteAll(array('id'=>$notification_ids));
		
	}
	
	public function delete_by_user_ids($user_ids){
		
		$total_uids = count($user_ids);
		
		if (0 == $total_uids){
			return;
		}else if($total_uids > 1){
			$this->deleteAll(array('uid IN'=>$user_ids));
		}else if(1 == $total_uids){
			$this->deleteAll(array('uid'=>$user_ids[0]));
		}
		
		return;
	}
	
	public function find_first(){
		
		$notification = $this->find('first', array('recursive'=>-1));
		return $notification;
	}
	
}