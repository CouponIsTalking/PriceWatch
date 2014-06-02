<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 * @property PaginatorComponent $Paginator
 */
class ProductsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	
	var $uses = array('Product', 'Company', 'Topic', 'UserProduct');

	public function show_shops()
	{
		
	}
}