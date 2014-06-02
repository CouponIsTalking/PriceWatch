<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class PcardCustsController extends AppController {

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
	
	/*
	public function lookup(){
	}
	*/
	
	public function lookup(){
		$r = array('s'=>false,'m'=>'');
		$this->set('is_post', false);
		
		//$this->set('list_cust_cards', false);
		$company_id = $this->UserData->getCompanyId();
		
		$is_post = $this->RequestHandler->isPost();
		
		//$is_ajax = $this->RequestHandler->isAjax();
		if ($is_post){
			$this->set('is_post', true);
			
			$email = $this->request->data['email'];
			$phone = $this->request->data['phone'];
			$cons = array('PcardCust.company_id' => $company_id);
			$search_op = false;
			if (!empty($email) || !empty($phone)){
				$cons = array('AND' =>$cons);				
			}
			if (!empty($email)){
				$cons['AND']['PcardCust.email'] = $email;
				$search_op = true;
			}
			if (!empty($phone)){
				$cons['AND']['PcardCust.phone'] = $phone;
				$search_op = true;
			}
			//debug($cons);
			if ($search_op){
				
				$this->paginate = array('recursive'=>-1,'conditions'=>$cons);
				
				$cust_cards = $this->Paginator->paginate('PcardCust');
				//$cust_cards = $this->PcardCust->findby($cons);
				//$r['cust_cards'] = $cust_cards;
				$this->set('cust_cards', $cust_cards);
				$card_ids = array();
				foreach($cust_cards as $index=>$cust_card){
					$crdid = $cust_card['PcardCust']['card_id'];
					$card_ids[$crdid] = $crdid;
				}
				
				$unique_card_ids = array();
				foreach($card_ids as $crdid => $crdid){
					$unique_card_ids[] = $crdid;
				}
				
				$cards = $this->Pcard->find_multiple_cards_by_id($unique_card_ids);
				$cards_key_on_id = array();
				if (!empty($cards)){
					foreach($cards as $index=>$card){
						$cards_key_on_id[$card['Pcard']['id']] = $card;
					}
				}
				$this->set('cards_key_on_id', $cards_key_on_id);
				
				//$this->set('list_cust_cards', true);
			}
			else{
				$this->Session->setFlash("Please enter customer email or phone.");
			}
		}
		
		$this->set('r',$r);
	}
	
}

?>