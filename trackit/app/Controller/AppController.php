<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	//public $components = array('Session', 'DebugKit.Toolbar', 'Paginator', 'UserData');
	public $components = array('Session', 'Paginator', 'UserData');
	public $helpers = array ('CommonFunc');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$debug_level = Configure::read('debug');
		if ( 0 < $debug_level)
		{
			$this->Toolbar = $this->Components->load('DebugKit.Toolbar');
		}
	}
	
	public function beforeRender(){
		
		$preset_var_logged_in_user_id = $this->UserData->getUserId();
		$preset_var_logged_in_user_email = $this->UserData->getUserEmail();
		$preset_var_welcome_name = $this->UserData->getWelcomeName();
		//debug($this->UserData->getUserID());
		$this->set('preset_var_logged_in_user_id', $preset_var_logged_in_user_id );
		$this->set('preset_var_logged_in_user_email', $preset_var_logged_in_user_email );
		$this->set('preset_var_welcome_name', $preset_var_welcome_name );
	}
	
	public function isAdmin($user) {
		// Admin can access every action
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		}
		
		// Default deny
		return false;
	}
	
	public function getUser($user) {
		if (isset($user['id'])) {
			return $user['id'];
		}
		
		// Default deny
		return false;
	}
	
	public function getUserType($user) {
		if (isset($user['role'])){
			return $user['role'];
		}
		
		// Default deny
		return false;
	}
	
	public function show_modal_msg_on_blank_page($msg, $msg_type)
	{
		$this->redirect(array(
					'controller'=>'pages',
					'action'=>'show_message',
					'msg' => urlencode($msg),
					'msg_type' => urlencode($msg_type)
				));
	}
	
	public function only_admin_can_see(){
		$is_admin = $this->UserData->isAdmin();
		if (empty($is_admin)){
			$this->goto_restricted_page();
		}
	}
	
	public function goto_restricted_page(){
		$this->redirect(array(
			'controller' => 'pages',
			'action' => 'restricted'
		));
	}
	
}