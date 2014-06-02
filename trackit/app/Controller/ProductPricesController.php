<?php
App::uses('AppController', 'Controller');
/**
 * ProductPrices Controller
 *
 * @property ProductPrice $ProductPrice
 * @property PaginatorComponent $Paginator
 */
class ProductPricesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler');

	public $uses = array('Product');
	
	public function test_get_pdata($prodid){
		
		$prices = array();
		$product = $this->Product->findRawProductInfoById($prodid);
		if (empty($product)){
			return $prices;
		}
		
		$price_date_history = $product['Product']['price_date_history'];
		
		$prices_exploded = explode(',',$price_date_history);
		
		$i = 0;
		$total = count($prices);
		while ($i < $total){
			$prices[$prices_exploded[$i+1]] = $prices_exploded[$i];
			$i = $i+2;
		}
		
		$this->set('prices', $prices);
		$this->set('price_date_history', $price_date_history);
		
		return $prices;
		
	}
	
	public function test_get_pdata_ajax(){
	}
	
	public function get_pdata(){
		
		$isajax = $this->RequestHandler->isAjax();
		$ispost = $this->RequestHandler->isPost();
		$this->layout='ajax';
		
		$price_date_history = "";
		$this->set('price_date_history', $price_date_history);
			
		if (!$isajax || !$ispost){
			return $price_date_history;
		}
		
		$data = $this->request->data;
		if (empty($data['prodid'])){
			return $price_date_history;
		}
		
		$prodid = $data['prodid'];
		$product = $this->Product->findRawProductInfoById($prodid);
		if (empty($product)){
			return $price_date_history;
		}
		
		$price_date_history = $product['Product']['price_date_history'];
		
		$prices_exploded = explode(',',$price_date_history);
		$te = count($prices_exploded);
		if ($te > 1){
			$last_price = $prices_exploded[$te-2];
			$timenow = time();
			$price_date_history = $price_date_history . ",{$last_price},{$timenow}";
		}
		/*
		$i = 0;
		$total = count($prices);
		while ($i < $total){
			$prices[$prices_exploded[$i+1]] = $prices_exploded[$i];
			$i = $i+2;
		}
		
		$this->set('prices', $prices);
		*/
		$this->set('price_date_history', $price_date_history);
		
		return $price_date_history;
		
	}
}
