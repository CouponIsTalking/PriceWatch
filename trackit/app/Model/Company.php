<?php
App::uses('AppModel', 'Model');
/**
 * Company Model
 *
 */
class Company extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	
	
	public function compactData($companies)
	{
		$companydata = array();
		
		foreach ($companies as $k => $company)
		{
			$companydata[$company['Company']['id']] = array ('name' => $company['Company']['name'], 'website' => $company['Company']['website']);
		}
		
		return $companydata;
	}
	
	public function getCompanyList()
	{
		$companies = $this->find('all');
		$companydata = $this->compactData($companies);
		
		return $companydata;
	}
	
	public function getCompanyListByIds($ids){
	
		if (empty($ids)){return array();}
		
		if(1==count($ids)){
			$conditions = array('id'=>$ids[0]);
		}else{
			$conditions = array('id IN'=>$ids);
		}
		$companies = $this->find('all', array(
			'recursive'=>-1,
			'conditions'=>$conditions,
			'fields' => array('id','name','website','enabled')
		));
		$companydata = $this->compactData($companies);
		
		return $companydata;
	}
	
	public function getBrosableCompaniesList($company_ids = null)
	{
		if (!empty($company_ids))
		{
			$companies = $this->find('all', array(
					'recursive' => -1,
					'conditions' => array('id IN' => $company_ids, 'enabled' => 1)
				));
		}
		else
		{
			$companies = $this->find('all', array(
					'recursive' => -1,
					'conditions' => array('enabled' => 1)
				));
		}
		$companydata = $this->compactData($companies);
		
		return $companydata;
	}
	
	public function getRawCompanyInfo($company_id)
	{
		
		if (empty($company_id)) return null;
	
		$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $company_id)));
		
		return $company;	
	
	}
	
	public function getRawCompanyInfoByEmail($email)
	{
		
		if (empty($email)) return null;
	
		$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('email' => $email)));
		
		return $company;	
	
	}
	
	public function getCompanyNameById($company_id)
	{
		
		if (empty($company_id)) return "";
	
		$company = $this->getRawCompanyInfoById($company_id);
		
		if(!empty($company['Company']))
		{
			return $company['Company']['name'];
		}
		
		return "";
	
	}
	
	public function getRawCompanyInfoById($company_id)
	{
		
		if (empty($company_id)) return null;
	
		$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $company_id)));
		
		return $company;	
	
	}
	
	public function getRawCompanyInfoBySiteName($website)
	{
		
		if (empty($website)) return null;
		$website=trim($website);
		
		$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('website' => $website)));
		
		if (empty($company)){
			//$has_www = strpos(strtolower($website), "www.");
			$website_parts = split("[.]", strtolower($website));
			$has_www = ($website_parts[0] == "www");
			
			if (!$has_www){
				$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('website' => "www.".$website)));
				if (empty($company)){
					$website_parts = split("[.]", $website);
					$total_parts = count($website_parts);
					if ($total_parts == 3 && strtolower($website_parts[2]) == "com")
					{
						$website_without_subdomain = $website_parts[1] . "." . $website_parts[2];
						$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('website' => $website_without_subdomain)));
					}
				}
			}
			else if($has_www){
				$website_without_www = substr($website, 4);
				$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('website' => $website_without_www)));
			}
			
		}
		
		return $company;	
		
	}
	
	public function AddFromWebsiteName($website)
	{
		$this->create();
		$company_info = array('Company'=>array());
		
		$pos = strpos($website, "www."); // usa.tommy.com was not being added because of this rule.
		if ($pos === 0)
		{
			$company_name = substr($website, $post+4);
		}
		else
		{
			$company_name = $website;
		}
		
		$company_info['Company']['name'] = $company_name;
		$company_info['Company']['website'] = $website;
		$company_info['Company']['email'] = 'abc@abc.com';
		$company_info['Company']['phone'] = '000-000-0000';
		$company_info['Company']['topic1'] = 0;
		$company_info['Company']['topic2'] = 0;
		
		if($this->save($company_info))
		{
			return true;
		}
		
		return false;
		
	}
	
	public function FindCompanyByWebsite($website)
	{
		$company = $this->find('first', array('recursive' => -1, 'conditions' => array ('website' => $website)));
		
		return $company;
	}
	
	public function AddCompany($name, $website, $email, $phone)
	{
		$this->create();
		$company_info = array('Company'=>array());
		
		$company_info['Company']['name'] = $name;
		$company_info['Company']['website'] = $website;
		$company_info['Company']['email'] = $email;
		$company_info['Company']['phone'] = $phone;
		$company_info['Company']['topic1'] = 0;
		$company_info['Company']['topic2'] = 0;
		$company_info['Company']['enabled'] = 0;
		
		$company = $this->save($company_info);
		
		if (!empty($company['Company']['id']))
		{
			return $company;
		}
		
		return false;
	
	}
	
	public function GetOrAddCompany($name, $website, $email, $phone)
	{
		$company = $this->FindCompanyByWebsite($website);
		
		//debug($company);
		
		if (empty($company))
		{
			$company = $this->AddCompany($name, $website, $email, $phone);
			
			//debug($company);
		}
		else
		{
			$company['Company']['name'] = $name;
			$company['Company']['email'] = $email;
			$company['Company']['phone'] = $phone;
			
			$this->id = $company['Company']['id'];
			$company = $this->save($company);
			//debug($company);
		}
		
		return $company;
	}
}
