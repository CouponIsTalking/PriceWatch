<?php
App::uses('AppController', 'Controller');
/**
 * Companies Controller
 *
 * @property Company $Company
 * @property PaginatorComponent $Paginator
 */
class GPlusController extends AppController {
	
	public $components = array( 'GPlus' );
	
	public $uses = '';//array ('GPlus');
	
	public get_access_token_from_code()
	{
		
		$access_token = $this->GPlus->get_access_token_from_code();
		$this->GPlus->set_access_token($access_token);
	  
	}
	
	
	public login()
	{
	}
	
	public get_token()
	{
	}
	
	
}