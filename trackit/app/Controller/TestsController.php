<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class TestsController extends AppController {

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
		'TwitterResultProcessor',
		'Vid'
		);
		
	var $helpers = array('Html');//,'Javascript');
    
	public $uses = array ('User', 'Company', 'Product', 'NewsletterSignup', 'UserEmail');
	
	public function test_fb_resp_update_and_show_coupon_one()
	{
	}
	
	public function test_fb_resp_update_and_show_coupon_two()
	{
	}
	
	public function session_write_test($val)
	{
		$this->Session->write('test_val', $val);
	}
	
	public function session_read_test($v_val)
	{
		$val = $this->Session->read('test_val');
		$re = ($v_val == $val);
		$this->set('re', $re);
		
	}
	
	public function userdata_write_test($val)
	{
		$this->UserData->test_write($val);
		//$this->UserData->cleanLoginData();
	}
	
	public function userdata_read_test($v_val)
	{
		$val = $this->UserData->test_read();
		$this->set('re', $val);
		
	}
	
	public function clean_login_data()
	{
		$this->UserData->cleanLoginData();
	}
	
	public function read_login_data()
	{
		$user = $this->UserData->getLoginData();
		$email = 'fail';
		if (!empty($user['email']) && $user['email'] == 'testemail@')
		{
			$email = $user['email'];
		}
		$this->set('email', $email);
	}
	
	public function set_login_data()
	{
		$user = array('role' => 'blogger', 'username' => 'testuser', 'email' => 'testemail@', 'id' => 'testid', 'twitter_compact_info' => 'no-twitter-info');
		$this->UserData->setLoginData($user);
	}
	
	public function logout_ajax() 
	{
		$is_ajax = $this->RequestHandler->isAjax();
		$this->UserData->cleanLoginData();
		
		if (!$is_ajax)
		{
			$next = false;
			if (!empty($this->request->query['next']))
			{
				$next = $this->request->query['next'];
			}
			if ($next)
			{
				$this->redirect(urldecode($next));
			}
		}		
		
		$result = array('errors' => 0, 'msg' => "Thanks for visiting us. Please come back again as and when you like.");
		$this->set('result', $result);
		return $result;
	}
	
	public function build_graph_page_url()
	{
		$pairs = array(
			array('url' => 'facebook.com/loft',
					'graph_page_url' => ''
			),
			array('url' => 'http://facebook.com/loft',
					'graph_page_url' => ''
			),
			array('url' => 'www.facebook.com/loft',
					'graph_page_url' => ''
			),
			array('url' => 'https://facebook.com/loft',
					'graph_page_url' => ''
			),
			array('url' => 'unavailablepage',
					'graph_page_url' => ''
			),
		);
		
		$this->FBInf = $this->Components->load('FBInf');
		foreach ($pairs as $index => $p)
		{
			$pairs[$index]['graph_page_url'] = $this->FBInf->get_graph_uri_from_page_uri($pairs[$index]['url']);
		}
		
		$this->set('pairs', $pairs);
	}
	
	public function test_get_fbid_from_page()
	{
		$url = "http://graph.facebook.com/loft";
		$this->FBInf = $this->Components->load('FBInf');
		$page_id = $this->FBInf->get_fb_page_id($url);
		$page_props = $this->FBInf->get_fb_page_props($url);
		$this->set('page_id', $page_id);
		$this->set('page_props', $page_props);
				
	}
	
	public function test_vdo_import()
	{
	
	}
	
	public function test_post_video()
	{
	
	}
	
	public function test_email_signup()
	{
		$email = 'testemail@usemenot.com';
		$company_id = 1232323231212;
		$user_id = 1;
		
		$slug = $this->NewsletterSignup->build_slug($email, $company_id);
		$this->set('slug', $slug);
		
		$emails = array('testemail+1@usemenot.com', 'testemail+2@usemenot.com');
		$result = $this->NewsletterSignup->signup_emails($emails, $company_id, $user_id);
		$this->set('signedup', $result);
		
		$are_emails_signed_up = array();
		foreach($emails as $i=>$email)
		{
			$are_emails_signed_up[] = $this->NewsletterSignup->is_email_signedup($email, $company_id);
		}
		$this->set('are_emails_signed_up', $are_emails_signed_up);
		
		$this->NewsletterSignup->remove_signups($emails, $company_id);
		
		$are_emails_signed_up_after_remove = array();
		foreach($emails as $i=>$email)
		{
			$are_emails_signed_up_after_remove[] = $this->NewsletterSignup->is_email_signedup($email, $company_id);
		}
		$this->set('are_emails_signed_up_after_remove', $are_emails_signed_up_after_remove);
		
	}
	
	public function test_add_user_email()
	{
		$email = 'testemail@usemenot.com';
		$user_id = 1;
		$confirmation_code = "CcOoNnFfIiRrMmCode1234";
		
		$slug = $this->UserEmail->build_slug($email, $user_id);
		$this->set('slug', $slug);
		
		$entry_id = $this->UserEmail->add_email($email, $user_id, $confirmation_code);
		$this->set('entry_id', $entry_id);
		
		$entry = $this->UserEmail->get_from_entry_id($entry_id);
		$this->set('entry_from_entry_id', $entry);
		
		$user_owns_email = $this->UserEmail->user_owns_email($email, $user_id);
		$this->set('user_owns_email', $user_owns_email);
		
		$is_email_confirmed = $this->UserEmail->is_email_confirmed($email, $user_id);
		$this->set('is_email_confirmed', $is_email_confirmed);
		
		$set_email_confirmed = $this->UserEmail->set_email_confirmed($email, $user_id);
		$this->set('set_email_confirmed', $set_email_confirmed);
		
		$is_email_confirmed_now = $this->UserEmail->is_email_confirmed($email, $user_id);
		$this->set('is_email_confirmed_now', $is_email_confirmed_now);
		
		$user_owns_email_after_confirmation = $this->UserEmail->user_owns_email($email, $user_id);
		$this->set('user_owns_email_after_confirmation', $user_owns_email_after_confirmation);
		
		$remove_email = $this->UserEmail->remove_email($email, $user_id);
		$this->set('remove_email', $remove_email);
		
		$is_email_confirmed_after_remove = $this->UserEmail->is_email_confirmed($email, $user_id);
		$this->set('is_email_confirmed_after_remove', $is_email_confirmed_after_remove);
		
		$user_owns_email_after_remove = $this->UserEmail->user_owns_email($email, $user_id);
		$this->set('user_owns_email_after_remove', $user_owns_email_after_remove);
	}
	
	public function test_add_eemail_js()
	{
	}
	
	public function get_thumbnails()
	{
		$urls = array(
			array('url'=>'https://www.youtube.com/watch?v=iDkslMXUC1M'),
			array('url'=>'https://www.youtube.com/watch?v=xFQ25x-RS50'),
			array('url'=>'https://www.youtube.com/watch?v=MGNDyYIPdfk'),
			array('url'=>'http://vimeo.com/83910533'),
			array('url'=>'http://vimeo.com/61252928'),
			array('url'=>'')
			
		);
		foreach($urls as $k=>$url)
		{
			$urls[$k]['thumbnail'] = $this->Vid->get_large_thumbmail_src($url['url']);
		}
		$this->set('urls', $urls);
	}
	
	
}
?>