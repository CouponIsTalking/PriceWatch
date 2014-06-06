<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class AopsController extends AppController {

	
	public $ADMIN_PASS_CODE = 'ADa89AD#SWS@S';
	
/**
 * Components
 *
 * @var array
 */

	//public $components = array('RequestHandler', 'UserData');
	public $components = array(
		'Paginator',
		'RequestHandler',
	/*	'Auth' => array(
            'loginRedirect' => array('controller' => 'companies', 'action' => 'user_view'),
            'logoutRedirect' => array('controller' => 'companies', 'action' => 'user_view')
			),
	*/	'Session',
		'UserData',
		'RedirectUrl',
		'TimeManagement',
		'EmailAccess',
		'TwitterResultProcessor'
		);
		
	var $helpers = array('Html');//,'Javascript');
    
	public $uses = array ('User', 'Company', 'Product');
	
	
	public function clearamode()
	{
		$this->UserData->set_admin_mode(0);
	}
	
	public function menu(){
		$this->only_admin_can_see();
		$menus = array();
		$menus['Users'] = array();
		$menus['Users']['List' ] = SITE_NAME . "users/view_all";
		$menus['Users']['Find By Email' ] = SITE_NAME . "users/find_by_email";
		$menus['Users']["To \"Logout and Login As\", find user by email first" ] = "";
		$menus['Tracker'] = array();
		$menus['Tracker']['List'] = SITE_NAME . "tracker_infos/index";
		$menus['Tracker']['Add'] = SITE_NAME . "tracker_infos/add";
		$menus['Tracker']['Search by Company Site'] = SITE_NAME . "tracker_infos/search_by_company";
		$menus['Companies'] = array();
		$menus['Companies']['List' ] = SITE_NAME . "companies/index";
		$menus['Companies']['Add' ] = SITE_NAME . "companies/add";
		$menus['Companies']['Search By Website' ] = SITE_NAME . "companies/search_by_website";
		$menus['UserTrackedProducts'] = array();
		$menus['UserTrackedProducts']['List' ] = SITE_NAME . "user_products/view_all";
		$menus['Products'] = array();
		$menus['Products']['List' ] = SITE_NAME . "products/view_all";
		$menus['Campaigns'] = array();
		$menus['Campaigns']['List'] = SITE_NAME . "open_campaigns/index";
		$menus["Users' Products"] = array();
		$menus["Users' Products"]['List'] = SITE_NAME . "user_products/view_all";
		$menus["Users' Products"]['Update empty user-id from user-email'] = SITE_NAME . "fixer_userproducts/userid_useremail";
		$menus['Caching'] = array();
		$menus['Caching']['Cache Recent Price Drop Data'] = SITE_NAME . "cache_data/cache_recent_pdrop_data";
		$menus['Caching']['Cache Discount Coupons Page'] = SITE_NAME . "cache_data/cache_discount_page";
		$menus['Caching']["Cache CompanyIDs with Fast Tracker for 'Track an item' page"] = SITE_NAME . "cache_data/cache_company_ids_with_fast_tracker";
		
		$this->set('menus', $menus);
	}
	public function setamode()
	{
		$re = array ('success' => false, 'msg' => 'undefined');
				
		$is_ajax = $this->RequestHandler->isAjax();
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		
		$user_id = $this->UserData->getUserId();
		
		if (empty($user_id))
		{
			$re['msg'] = 'Please log in first.';
		}
		else
		{
			$is_admin = $this->UserData->isAdminInDB();
			if ($is_admin)
			{
				$this->UserData->set_admin_mode(1);
				$re['success'] = true;
				$re['msg'] = 'Mode set';
			}
			else
			{
				$is_post = $this->request->is('post');
				
				if ( ($is_post && !$is_ajax)
					|| (!$is_post && $is_ajax)
					)
				{
					$re['msg'] = 'Bad request.';
				}
				else if ($is_post && $is_ajax)
				{
					$data = $this->request->data;
					if (empty($data['code']))
					{
						//$this->set('show_form', true);
					}
					else if ($this->ADMIN_PASS_CODE == $data['code'])
					{
						$this->UserData->set_admin_mode(1);
						$re['success'] = true;
						$re['msg'] = 'Mode set';
					}
					else
					{
						$re['msg'] = 'Bad Request.';
					}
				}
			}
		}
		
		if (!empty($re['msg']) && !$is_ajax)
		{
			$this->Session->setFlash($re['msg']);
		}
		
		$this->set('is_ajax', $is_ajax);
		$this->set('result', $re);
		return $re;
		
	}
}

?>