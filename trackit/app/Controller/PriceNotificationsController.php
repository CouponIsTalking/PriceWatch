<?php
App::uses('AppController', 'Controller');
/**
 * PriceNotifications Controller
 *
 * @property PriceNotification $PriceNotification
 * @property PaginatorComponent $Paginator
 */
class PriceNotificationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'ProdAPI', 'UserData', 'EmailAccess', 'RequestHandler'
		);
	
	public $uses = array ('Product','UserProduct','PriceNotification');
	
	public function test_gen_notifications(){
		
		$this->only_admin_can_see();
		
		$this->request->data = array(
			'plink'=> 'http=>//www.express.com/catalog/product_detail.jsp?productId=7862823&categoryId=cat740010', 
			'old_price'=> '59.9', 
			'pid'=> '368', 
			'new_price'=> '59.9', 
			'users_tracking'=> '28', 
			'pcode'=> $this->ProdAPI->get_python_ver_code(), 
			'pimage'=> '');
			
		$re = $this->gen_notifications();
		$this->set('re',$re);

	}
	
	public function test_send_next_notification(){
		
		$this->only_admin_can_see();
		
		$this->request->data = array('testing'=> true, 'pcode'=> $this->ProdAPI->get_python_ver_code());
		
		$re = $this->send_next_notification();
		$this->set('re', $re);
	}
	
	public function gen_notifications(){
		
		$re = array('s'=>0,'m'=>'');
		$this->set('re',$re);
		
		$this->layout = 'ajax';
		$data = $this->request->data;
		
		if(empty($data) || empty($data['pcode']) || empty($data['pid'])){
			return;
		}
		$has_access = $this->ProdAPI->has_api_or_python_access($data['pcode']);
		if(!$has_access){return;}
		
		$users_tracking = $data['users_tracking'];
	
		$user_ids = split(",", $users_tracking);
		$notf_info = array();
		$notf_info['pid'] = $data['pid'];
		$notf_info['pimage'] = $data['pimage'];
		$notf_info['plink'] = $data['plink'];
		$notf_info['new_price'] = $data['new_price'];
		$notf_info['old_price'] = $data['old_price'];
		
		$all_saved = true;
		
		$total_user_ids = count($user_ids);
		for($i=0;$i<$total_user_ids;$i++){
			$user_id = $user_ids[$i];
			$notf_info['uid'] = $user_id;
			$saved = $this->PriceNotification->add_new($notf_info);
			if(empty($saved)){
				$all_saved = false;
				break;
			}
		}
		
		if ($all_saved){
			$re['s'] = 1;
		}
		$this->set('re',$re);
		return $re;
	}
	
	public function get_tracking_users(){
	
		$this->layout = 'ajax';
		$user_ids = "";
		$this->set('user_ids', $user_ids);
		
		$data = $this->request->data;
		if(empty($data) || empty($data['pcode']) || empty($data['pid'])){
			return;
		}
		$has_access = $this->ProdAPI->has_api_or_python_access($data['pcode']);
		if(!$has_access){return;}
		
		$pid = $data['pid'];
		
		//$pid = '369';
		
		$user_products = $this->UserProduct->getAllUserIdFromProductId($pid);
		
		// Build comma separated user ids string
		$total_up=count($user_products);
		for($i=0;$i<$total_up;$i++){
			$another_user_id = $user_products[$i]['UserProduct']['user_id'];
			if (!$user_ids){
				$user_ids = $another_user_id;
			}else{
				$user_ids = $user_ids . "," . $another_user_id;
			}
		}
		$this->set('user_ids', $user_ids);
	}
	
	public function get_product_info(){
		
		$this->layout = 'ajax';
		$product_info = array();
		$this->set('product_info',$product_info);
		
		$data = $this->request->data;
		if(empty($data) || empty($data['pcode']) || empty($data['pid'])){
			return;
		}
		
		$has_access = $this->ProdAPI->has_api_or_python_access($data['pcode']);
		if(!$has_access){return;}
		
		$pid = $data['pid'];
		
		//$pid='138';
		$product_info = $this->Product->getProductInfoForPriceNotf($pid);
		$this->set('product_info',$product_info);
	}
	
	
	
	public function send_next_notification(){
		
		$re = array('s'=>0,'m'=>'','nomore'=>0);
		
		$this->layout = 'ajax';
		$data = $this->request->data;
		if(empty($data) || empty($data['pcode'])){
			return;
		}
		
		$has_access = $this->ProdAPI->has_api_or_python_access($data['pcode']);
		if(!$has_access){return;}
		
		// Loop until either you find a notification to a valid user.
		// If no-more pending notifications left in the process, then return.
		// Otherwise, break out of the loop once you find a pending notification
		// to a valid user.
		while(true){
			$notification = $this->PriceNotification->find_first();
			if (empty($notification)){
				$re['nomore'] = 1;
				$re['m'] = 'Did not find anymore notifications';
				$this->set('re', $re);
				return $re;
			}
			
			$notf_user_id = $notification['PriceNotification']['uid'];
			if(empty($notf_user_id)){
				$this->PriceNotification->delete_by_user_ids(array(0));
			}
			// If found a pending notification to a valid user, then break.
			else{
				break;
			}
		}
		
		$notifications = $this->PriceNotification->find_group_by_userid_and_size($notf_user_id,10);
		if(empty($notifications)){
			$re['nomore'] = 1;
			$re['m'] = 'This must *NOT* happen. Did not find anymore notifications by same user.';
			$this->set('re', $re);
			return $re;
		}
		
		$notf_ids = array();
		$product_list = "";
		foreach($notifications as $index=>$notf){
			$pid = $notf['PriceNotification']['pid'];
			$prod = $this->Product->findRawProductInfoById($pid);
			if (empty($prod)){
				continue;
			}
			$plink = $prod['Product']['purl'];
			$pname = trim($prod['Product']['name']);
			$old_price = $prod['Product']['high_price'];
			$new_price = $prod['Product']['cur_price'];
			
			if(empty($pname)){
				$parsed_plink = parse_url($plink);
				$host = strtolower($parsed_plink['host']);
				$pname = $host . "...";
			}
			
			$product_list = $product_list . "<a href=\"{$plink}\">{$pname}</a> new:{$new_price} old:{$old_price} <br/>";
			
			// Build notification id array, as we'll delete these notifications later on 
			// after emailing the user.
			$notf_ids[] = $notf['PriceNotification']['id'];
		}
		
		App::uses('User','Model');
		$this->User = new User();
		
		$user = $this->User->findUserById($notf_user_id);
		if (empty($user)){
			$re['m']="Error finding user. This is dangerous, as it may cause notification system to hang.";
			$this->set('re',$re);
			return $re;
		}
		$user_name = $user['firstname'];
		$user_email = $user['username'];
		
		$email_params = array();
		$email_params['user_email'] = $user_email;
		$email_params['user_name'] = $user_name;
		$email_params['product_list'] = $product_list;
		
		// If we're in testing mode, then set format only
		if(!empty($data['testing'])){
			$this->EmailAccess->set_format_only();
		}
		
		$email_sent = $this->EmailAccess->shoot_price_notifications($email_params);
		
		if (!empty($email_sent)){
			// If we're not in testing mode,
			// then delete notf ids for the already sent notifications
			if(empty($data['testing'])){ 
				$this->PriceNotification->delete_by_notf_ids($notf_ids);
			}else{
				$re['m'] = $email_sent;
				$re['product_list'] = $product_list;
			}
			$re['s'] = true;
		}else{
			$re['m']='Error sending out email.';
		}
		
		$this->set('re',$re);
		return $re;
	}
	
}
?>