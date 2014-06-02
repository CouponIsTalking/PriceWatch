<?php
App::uses('GPlusComponent', 'Component');

require_once App::path('Vendor') . DS. 'GoogleClientApi' . DS. 'src'  . DS. 'Google_Client.php';
require_once App::path('Vendor') . DS. 'GoogleClientApi' . DS. 'src' . DS. 'contrib' . DS. 'Google_PlusService.php';

/**
 * Companies Controller
 *
 * @property Company $Company
 * @property PaginatorComponent $Paginator
 */
class GPlusComponent extends AppComponent {

	public $components = array ('Session', 'UserData');
	
	$controller = null;
	$client = null;
	$plus = null;
	
	public initialize (Controller $controller)
	{
		$this->controller = $controller;
		
		$this->client = new Google_Client();
		$this->client->setApplicationName(GENERIC_APPNAME);

		// Visit https://code.google.com/apis/console?api=plus to generate your
		// client id, client secret, and to register your redirect uri.
		// $client->setClientId('insert_your_oauth2_client_id');
		// $client->setClientSecret('insert_your_oauth2_client_secret');
		// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
		// $client->setDeveloperKey('insert_your_developer_key');
		$this->plus = new Google_PlusService($client);
	}
	
	public get_access_token_from_code()
	{
		$this->client->authenticate();
		$access_token = $this->client->getAccessToken();
		return $access_token;
	}
	
	public set_access_token($access_token)
	{
		$this->client->setAccessToken($access_token);
	}
	
	public get_auth_step1_url()
	{
		$state = mt_rand();
		$client->setState($state);
		
		$authUrl = $this->client->createAuthUrl();
		return $authUrl
	}
	
	public get_me()
	{
		$me = $this->plus->people->get('me');	
		return $me
	}
	

	public get_email()
	{
	}
	
	public get_user()
	{
	}
	

}