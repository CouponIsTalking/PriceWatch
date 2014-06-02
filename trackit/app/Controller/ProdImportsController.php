<?php
App::uses('AppController', 'Controller');
/**
 * ProdImports Controller
 * Import products from any where, a shopping site, from someone else's collection,
 * from global product collection
 *
 * @property Product $Product
 * @property PaginatorComponent $Paginator
 */
class ProdImportsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('RequestHandler', 'UserData');
	
	var $uses = array('Product');
	
	public function get_prod_info(){
		
		$this->layout = 'ajax';
		$result =array('s'=>false,'m'=>'','uinfo'=>array(),'pinfo'=>array());
		$this->set('result',$result);
		
		$is_ajax = $this->RequestHandler->isAjax();
		$is_post = $this->RequestHandler->isPost();
		if (!$is_ajax || !$is_post){
			return $result;
		}
		
		$data = $this->request->data;
		if (empty($data['way']) || empty($data['val'])){
			return $result;
		}
		
		$way = $data['way'];
		$val = $data['val'];
		
		/* Required info by JS
			var $uemail = $d['uinfo']['email'];
			var $pid = $d['pinfo']['prodid'];
			var $title = $d['pinfo']['title'];
			var $prodlink = $d['pinfo']['prodlink'];
			var $image_link1 = $d['pinfo']['image_link1'];
			var $image_link2 = $d['pinfo']['image_link2'];
			var $cur_price = $d['pinfo']['cur_price'];
			var $recent_pricing_info = $d['pinfo']['recent_pricing_info'];
		*/
			
		if ('prodid' == $way){
			$product = $this->Product->findRawProductInfoById($val);
			if (empty($product)){
				return $result;
			}
			$result['uinfo']['email'] = $this->UserData->getUserEmail();
			$result['pinfo']['prodid'] = $product['Product']['id'];
			$result['pinfo']['title'] = $product['Product']['name'];
			$result['pinfo']['prodlink'] = $product['Product']['purl'];
			$result['pinfo']['image_link1'] = $product['Product']['image1'];
			$result['pinfo']['image_link2'] = $product['Product']['image2'];
			$result['pinfo']['cur_price'] = $product['Product']['cur_price'];
			$result['pinfo']['recent_pricing_info'] = $product['Product']['price_date_history'];
			$result['s'] = true;
			$this->set('result', $result);
		}
		
		return $result;
	}
	
}?>