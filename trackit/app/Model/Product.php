<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 */
class Product extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	public function compactProductData($products)
	{
		$productdata = array();
		
		foreach ($products as $k => $product)
		{
			$productdata[$product['Product']['id']] = array ('name' => $product['Product']['name'], 'company_id' => $product['Product']['company_id']);
		}
		
		return $productdata;
	}
	
	public function indexOnId($products)
	{
		$productdata = array();
		
		foreach ($products as $k => $product)
		{
			$productdata[$product['Product']['id']] = $product['Product'];
		}
		
		return $productdata;
	}
	
	public function getProductList()
	{
		$products = $this->find('all');
		
		$productdata = $this->compactProductData($products);
		return $productdata;
	
	}
	
	public function getRawProductsByCompanyId($company_id = null)
	{
		if (empty($company_id)) return null;
	
		$products = $this->find('all', array('recursive' => -1, 'conditions' => array ('company_id' => $company_id)));
		
		return $products;
	}
	
	public function findRawProductInfoByUrl($purl = null)
	{
		if (empty($purl)) return null;
	
		$product = $this->find('first', array('recursive' => -1, 'conditions' => array ('purl' => $purl)));
		
		return $product;
	}
	
	public function findRawProductInfoById($pid = null)
	{
		if (empty($pid)) return null;
	
		$product = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $pid)));
		
		return $product;
	}
	
	public function getProductInfoForPriceNotf($pid){
		
		$product_info = array();
		$product = $this->findRawProductInfoById($pid);
		if (!empty($product)){
			$product_info['pid'] = $pid;
			$product_info['plink'] = $product['Product']['purl'];
			$product_info['pimage'] = $product['Product']['image1'];
			$product_info['new_price'] = $product['Product']['cur_price'];
			$product_info['old_price'] = $product['Product']['high_price'];
		}
		return $product_info;
	}
	
	public function doesCompanyOwnsProduct($company_id, $product_id)
	{
		if ($product_id == 0)
		{
			return true;
		}
		
		$product = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $product_id)));
		
		if (empty($product) || empty($product['Product']))
		{
			return false;
		}
		
		if ($company_id == $product['Product']['company_id'])
		{
			return true;
		}
		
		return false;
		
	}
	
	
	// assumes that a product with id $p_id already exists
	// assumes a valid vote type
	public function update_lwo_vote($p_id, $vote_type, $dir){
		
		if (empty($p_id)){return false;}
		
		$updated = false;
		
		$this->id = $p_id;
		if ('up'==$dir){
			$updated = $this->updateAll(
				array("Product.{$vote_type}" => "Product.{$vote_type}+1"),
				array('Product.id' => $p_id)
			);
		}else if('down'==$dir){
			$updated = $this->updateAll(
				array("Product.{$vote_type}" => "Product.{$vote_type}-1"),
				array('Product.id' => $p_id)
			);
		}
		
		if(!empty($updated)){return true;}
		return false;
		
	}

}
