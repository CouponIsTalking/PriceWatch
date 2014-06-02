<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class PcardsController extends AppController {

/**
 * Components
 *
 * @var array
 */

	//public $components = array('RequestHandler', 'UserData');
	public $components = array(
		'Paginator',
		'RequestHandler',
		'Session',
		'UserData',
		'TimeManagement',
		'EmailAccess'
		);
		
	var $helpers = array('Html');//,'Javascript');
    
	public $uses = array ('Pcard', 'User','Company', 'PcardCust', 'PcardCvisit');
	
	public function view($card_id){
		
		$this->set('card',false);
		$company_id = $this->UserData->getCompanyId();
		if (empty($company_id)){
			$this->Session->setFlash("Please login as a business to see your punchcard.");
		}
		
		if(!empty($card_id)){
			$card = $this->Pcard->find_by_cardid($card_id);
			if (!empty($card['Pcard'])){
				if ($company_id != $card['Pcard']['company_id']){
					$this->redirect(SITE_NAME . "pcards/index");
				}
				else{
					$this->set('card',$card['Pcard']);
				}
			}
		}
	
	}
	
	public function create(){
	}
	
	public function addin(){
		
		$r = array('s'=>false,'m'=>'');
		
		$is_ajax=$this->RequestHandler->isAjax();
		$is_post=$this->RequestHandler->isPost();
		
		if ($is_ajax && $is_post){
			$this->layout='ajax';
			$this->set('show_form',false);
					
			$user_id = $this->UserData->getUserId();
			$company_id = $this->UserData->getCompanyId();
			$data = $this->request->data;
			$title = $data['title'];
			$desc = $data['desc'];
			$mv = $data['max_visit'];
			
			if (empty($user_id) && empty($company_id)){
				$r['m'] = 'Please login as a business to create your punchcard.';
			}else if (!empty($user_id) && empty($company_id)){
				$r['m'] = 'You appear to be loggedin but not using your business account.<br/>Please login using your business account to create your punchcard.';
			}else if (empty($title)){
				$r['m']='Please choose a title for your punchcard';
			}else if (empty($desc)){
				$r['m']='Please write one or two line of description for your punchcard';
			}else{
				$date = TimeManagement::TSTodayDate();
				$r = $this->Pcard->add_card($title, $desc, $mv, $company_id, $date);
			}
		}
		else{
			$this->set('show_form',true);
		}
		$this->set('r',$r);
		return $r;
	}
	
	public function update(){
	}
	
	public function give(){
		
		$r = array('s'=>false,'m'=>'');
		
		$is_ajax=$this->RequestHandler->isAjax();
		$is_post=$this->RequestHandler->isPost();
		$company_id = $this->UserData->getCompanyId();
		
		if ($is_ajax && $is_post){
			$this->layout = 'ajax';
			$data = $this->request->data;
			$data['user_id'] = 0;
			$data['firstname']=$data['fn'];
			$data['lastname']=$data['ln'];
			$email=$data['email'];
			$phone=$data['phone'];
			$card_id=$data['card_id'];
			$card_detail = $this->Pcard->find_by_cardid($card_id);
			
			if (empty($company_id)){
				$r['m'] = 'Please login as a business owner to give this card to your customers.';
			}
			else if ($company_id != $card_detail['Pcard']['company_id']){
				$r['m'] = 'Please login as a business owner to give this card to your customers.';
			}
			else{
				$user=$this->User->findUserByEmail($email);
				$company = $this->UserData->getCompanyData();
				$company_name = $company['Company']['name'];
				
				if (!empty($user['id'])){
					$data['user_id']=$user['id'];
				}
				
				$data['company_id'] = $company_id;
				$r = $this->PcardCust->add_cust($data);
				if ($r['s']){
					$cust_card_link = SITE_NAME . "pcards/customer_card/{$r['id']}";
					$r['link'] = $cust_card_link;
					
					$subject = "You have got a punchcard from '{$company_name}'";
					$template = 'send_punchcard';
					
					$data_for_email['to'] = $email;
					if (!empty($user['firstname'])){
						$data_for_email['to_firstname'] = $user['firstname'];
					}
					$data_for_email['formatting_vars']['user_name'] = $user['firstname'];
					$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
					
					$data_for_email['subject'] = $subject;
					$data_for_email['formatting_vars']['company_name'] = $company_name;
					$data_for_email['formatting_vars']['cust_card_link'] = $cust_card_link;
					$data_for_email['formatting_vars']['card_title'] = $card_detail['Pcard']['title'];
					$data_for_email['formatting_vars']['card_detail'] = $card_detail['Pcard']['desc'];
					$data_for_email['formatting_vars']['subject'] = $subject;
					$successfully_mail_sent = $this->EmailAccess->shoot_email($template, $data_for_email);
				}
			}
		}
		
		$this->set('r',$r);
	}
	
	public function index(){
		
		$company_id = $this->UserData->getCompanyId();
		
		$this->paginate = array(
			'recursive'=>-1,
			'conditions'=>array('company_id'=>$company_id)
		);
		
		$cards = $this->Paginator->paginate();
		$this->set('cards', $cards);
	}
	
	public function customer_card($cust_card_id){
		
		$this->set('company_watch', false);
		$this->set('member_info_open', false);
		
		$cust_card = $this->PcardCust->find_by_cardid($cust_card_id);
		if (!empty($cust_card)){
			$card = $this->Pcard->find_by_cardid($cust_card['PcardCust']['card_id']);
			$visits = $this->PcardCvisit->find_visits_by_custcardid($cust_card_id);
			
			$company_id = $this->UserData->getCompanyId();
			if (!empty($company_id) && $company_id == $card['Pcard']['company_id']){
				$this->set('company_watch', true);
				$this->set('member_info_open', true);
			}
			$user_id = $this->UserData->getUserId();
			if ($user_id == $cust_card['PcardCust']['user_id']){
				$this->set('member_info_open', true);
			}
		}
		
		
		$this->set('cust_card', $cust_card);
		$this->set('card', $card);
		$this->set('visits', $visits);
	}
	
	public function set_ccard_visit(){
		
		$r = array('s'=>false,'m'=>'');
		$this->set('r', $r);
		
		$is_ajax=$this->RequestHandler->isAjax();
		$is_post=$this->RequestHandler->isPost();
		$company_id = $this->UserData->getCompanyId();
		
		if ($is_ajax && $is_post){
			$this->layout = 'ajax';
			$user_id = $this->UserData->getUserId();
			$company_id = $this->UserData->getCompanyId();
			$data = $this->request->data;
			$cust_card_id = $data['ccard_id'];
			$visit_val = $data['visit_val'];
			$visit_num = $data['visit_num'];
			
			if (empty($user_id) && empty($company_id)){
				$r['m'] = 'Please login as a business to create your punchcard.';
			}else if (!empty($user_id) && empty($company_id)){
				$r['m'] = 'You appear to be loggedin but not using your business account.<br/>Please login using your business account to create your punchcard.';
			}else{
				$cust_card = $this->PcardCust->find_by_cardid($cust_card_id);
				if (empty($cust_card)){
					$r['m'] = "Couldn't find customer's punchcard.";
					$this->set('r',$r);
					return;
				}
				$card = $this->Pcard->find_by_cardid($cust_card['PcardCust']['card_id']);
				if (empty($card)){
					$r['m'] = "Couldn't find punchcard.";
					$this->set('r',$r);
					return;
				}
				
				if ($company_id != $card['Pcard']['company_id']){
					$r['m'] = "Please login as the business that created this punchcard.";
					$this->set('r',$r);
					return;
				}
				
				if (0==$visit_val){
					$r=$this->PcardCvisit->mark_unvisited($cust_card_id, $visit_num);
				}else if (1==$visit_val){
					$r=$this->PcardCvisit->mark_visited($cust_card_id, $visit_num);
				}
				
			}
			
		}
		
		$this->set('r', $r);
		return $r;
	}
	
	public function set_ccard_note(){
		
		$r = array('s'=>false,'m'=>'');
		$this->set('r', $r);
		
		$is_ajax=$this->RequestHandler->isAjax();
		$is_post=$this->RequestHandler->isPost();
		$company_id = $this->UserData->getCompanyId();
		
		if ($is_ajax && $is_post){
			$this->layout = 'ajax';
			$user_id = $this->UserData->getUserId();
			$company_id = $this->UserData->getCompanyId();
			$data = $this->request->data;
			$cust_card_id = $data['ccard_id'];
			$note_val = $data['note_val'];
			$visit_num = $data['visit_num'];
			
			if (empty($user_id) && empty($company_id)){
				$r['m'] = 'Please login as a business to create your punchcard.';
			}else if (!empty($user_id) && empty($company_id)){
				$r['m'] = 'You appear to be loggedin but not using your business account.<br/>Please login using your business account to create your punchcard.';
			}else{
				$cust_card = $this->PcardCust->find_by_cardid($cust_card_id);
				if (empty($cust_card)){
					$r['m'] = "Couldn't find customer's punchcard.";
					$this->set('r',$r);
					return;
				}
				$card = $this->Pcard->find_by_cardid($cust_card['PcardCust']['card_id']);
				if (empty($card)){
					$r['m'] = "Couldn't find punchcard.";
					$this->set('r',$r);
					return;
				}
				
				if ($company_id != $card['Pcard']['company_id']){
					$r['m'] = "Please login as the business that created this punchcard.";
					$this->set('r',$r);
					return;
				}
				
				$r=$this->PcardCvisit->set_note($cust_card_id, $visit_num, $note_val);
				
			}
			
		}
		
		$this->set('r', $r);
		return $r;
	}
	
	
}

?>