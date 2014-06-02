<?php
App::uses('AppController', 'Controller');
/**
 * FcparserInfos Controller
 *
 * @property FcparserInfo $FcparserInfo
 * @property PaginatorComponent $Paginator
 // FC STANDS for FOREIGN COUPON, i.e., Coupon obtained from Foreign Sources
 */
class CouponInfsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'UserData');
	
	public $uses = array('FcparserInfo','Company', 'OpenCampaign', 'OcCondition');
	
	
	public function test_get_fcp_info(){
		//$this->set('cid', 31);
		//$this->set('pcode', Configure::read('PYTHON_VERIFICATION_CODE'));
	}
	
	public function scr_update_coupon_info(){
		
		$this->layout = 'ajax';
		$this->set('result', 0);
		
		$data = $this->request->data;
		$company_id = $data['company_id'];
		
		if(empty($data['company_id'])){return;}
        if(empty($data['coupon_code'])){return;}
        if(empty($data['coupon_cur_code'])){return;}
        if(empty($data['coupon_worth'])){return;}
        if(empty($data['coupon_title'])){return;}
        if(empty($data['coupon_detail'])){return;}
        if(empty($data['coupon_source'])){return;}
        if(empty($data['valid_until_date'])){return;}
        if(empty($data['coupon_type'])){return;}
        if(empty($data['pcode'])){return;}

		$pcode = $data['pcode'];
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_access = $this->ProdAPI->has_python_access($pcode);
		if (!$has_access){
			return;
		}
		
		list($year, $month, $day) = split('-',$data['valid_until_date']);
		$valid_until_datetime = $this->OcCondition->makeValidUntilDateTime($year, $month, $day);
		$coupon_valid_until_date = $this->OcCondition->makeValidUntilDate($year, $month, $day);
		
		$has_same_code = $this->OpenCampaign->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'coupon_code' => $data['coupon_code'],
					'company_id' => $data['company_id'],
					'coupon_valid_until_date >=' => $coupon_valid_until_date
				)
			));
		
		if (!empty($has_same_code)){
			return;
		}
		
		$ocdata_tosave = array();
		$ocdata_tosave['OpenCampaign'] = array();
		$ocdata_tosave['OpenCampaign']['company_id'] = $data['company_id'];
		$ocdata_tosave['OpenCampaign']['product_id'] = 0;
		$ocdata_tosave['OpenCampaign']['type'] = 'giveaway';
		$ocdata_tosave['OpenCampaign']['end_date'] = $valid_until_datetime; //date("Y-m-d H:i:s", $valid_until_datetime);
		$ocdata_tosave['OpenCampaign']['active'] = 0;
		$ocdata_tosave['OpenCampaign']['coupon_code'] = $data['coupon_code'];
		$ocdata_tosave['OpenCampaign']['coupon_worth'] = $data['coupon_worth'];
		$ocdata_tosave['OpenCampaign']['coupon_line'] = $data['coupon_title'];
		$ocdata_tosave['OpenCampaign']['coupon_details'] = $data['coupon_detail'];
		$ocdata_tosave['OpenCampaign']['coupon_valid_until_date'] = $coupon_valid_until_date;
		$ocdata_tosave['OpenCampaign']['coupon_worth_cur'] = $data['coupon_cur_code'];
		$ocdata_tosave['OpenCampaign']['coupon_type'] = $data['coupon_type'];
		$ocdata_tosave['OpenCampaign']['coupon_source'] = $data['coupon_source'];
		$ocdata_tosave['OpenCampaign']['approved_content_ids'] = "";
		$ocdata_tosave['OpenCampaign']['default_title'] = "";
		$ocdata_tosave['OpenCampaign']['default_link'] = "";
		$ocdata_tosave['OpenCampaign']['default_desc'] = "";
		//debug($ocdata_tosave);
		//die();
		$saved = $this->OpenCampaign->save($ocdata_tosave);
		if (!empty($saved)){
			$result = 1;
		}
		
		$this->set('result', $result);
		return $result;
	}
	
	
}