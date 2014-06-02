<?php
App::uses('AppModel', 'Model');
/**
 * OpenCampaign Model
 *
 */
class OpenCampaign extends AppModel {
	
	
	public function isActive($oc_id)
	{
		$ocdata = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $oc_id)));
		
		if ( !empty($ocdata['OpenCampaign']) && 
			 ($ocdata['OpenCampaign']['active'] == 1)
			)
		{
			return true;			
		}
		return false;
	}
	
	public function deactivate($oc_id)
	{
		$re = $this->activate($oc_id, false);
		return $re;
	}
	
	public function activate($oc_id, $value, $company_id = false, $is_admin = false)
	{
	
		if (empty($company_id) && empty($is_admin))
		{
			return false;
		}
		
		$ocdata = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $oc_id)));
		
		if (empty($ocdata['OpenCampaign']))
		{
			return false;			
		}
		
		if ($ocdata['OpenCampaign']['company_id'] != $company_id && !$is_admin)
		{
			return false;
		}
		
		if(true == $value)
		{
			$ocdata['OpenCampaign']['active'] = 1;
			if (empty($ocdata['OpenCampaign']['start_date']))
			{
				$ocdata['OpenCampaign']['start_date'] = date("Y-m-d H:i:s");
			}
		}
		else if(false == $value)
		{
			$ocdata['OpenCampaign']['active'] = 0;
		}

		$this->id = $ocdata['OpenCampaign']['id'];
		$result = $this->save($ocdata);
		if (!empty($result))
		{
			return true;
		}
		
		return false;
	}
	
	public function extractOCIds($ocs)
	{
		$oc_ids = array();
		if (!empty($ocs))
		{
			foreach ($ocs as $index => $oc)
			{
				$oc_ids[] = $oc['OpenCampaign']['id'];
			}
		}
		return $oc_ids;
	}
	
	public function filterOCDataOnOCIds($ocs, $oc_ids)
	{
		$ocsdata = array();
		$ocs = $this->IndexOnOCId($ocs);
		
		if (!empty($ocs))
		{
			foreach ($ocs as $oc_id => $oc)
			{
				if (!empty($oc_ids[$oc_id]))
				{
					$ocsdata[$oc_id] = $oc;
				}
			}
		}
		
		return $ocsdata;
	}
	
	public function IndexOnOCId($ocs)
	{
		$ocsdata = array();
		
		if (!empty($ocs))
		{
			foreach ($ocs as $k => $oc)
			{
				$ocsdata[$oc['OpenCampaign']['id']] = $oc;
			}
		}
		
		return $ocsdata;
	}
	
	public function getCampaignsByCompanyId($company_id)
	{
		
		if (empty($company_id)) return null;
	
		$open_campaigns = $this->find('all', array('recursive' => -1, 'conditions' => array ('company_id' => $company_id)));
		
		return $open_campaigns;	
	
	}
	
	public function getActiveCampaigns()
	{
		$now_date_time = date("Y-m-d H:i:s");
		
		$ocs = $this->find('all', array('recursive' => -1, 'order' => array('id' => 'DESC'), 'conditions' => array ('end_date >=' => $now_date_time, 'active' => 1)));
		return $ocs;
	}
	
	public function getActiveCampaignsByCompanyId($company_id)
	{
		
		if (empty($company_id)) return null;
		
		$now_date_time = date("Y-m-d H:i:s");
	
		$open_campaigns = $this->find('all', array('recursive' => -1, 'conditions' => array ('company_id' => $company_id, 'end_date >=' => $now_date_time, 'active'=>1)));
		
		return $open_campaigns;	
	
	}

	/*
	public function getActiveCampaignsByOCIds($oc_ids)
	{
		
		if (empty($oc_ids)) return null;
		
		$now_date_time = date("Y-m-d H:i:s");
	
		$open_campaigns = $this->find('all', array('recursive' => -1, 'conditions' => array ('id IN' => $oc_ids, 'end_date >=' => $now_date_time, 'active'=>1)));
		
		return $open_campaigns;	
	
	}
	*/
	
	public function getCampaignsByOCIds($oc_ids)
	{
		
		if (empty($oc_ids)) return null;
		
		if (!is_array($oc_ids))
		{
			$oc_ids = array($oc_ids);
		}
		
		if (1 == count($oc_ids))
		{
			$open_campaign = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $oc_ids[0])));
			$open_campaigns = array(0 => $open_campaign);
		}
		else
		{
			$open_campaigns = $this->find('all', array('recursive' => -1, 'conditions' => array ('id' => $oc_ids)));
		}
		
		return $open_campaigns;	
	
	}
	
	public function doesCompanyOwnCampaign($company_id, $oc_id)
	{
	
		if (empty($company_id) || empty($oc_id)) return null;
	
		$open_campaign = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $oc_id, 'company_id'=>$company_id)));
		
		if (!empty($open_campaign['OpenCampaign']))
		{
			return true;
		}
		else
		{
			return false;
		}		
	
	}
	
	public function coupon_exists($coupon_code, $company_id)
	{
		if (empty($coupon_code) || empty($company_id)){return 0;}
		
		$entry = $this->find('first', array(
			'recursive' => -1,
			'conditions'=>array(
				'coupon_code'=>$coupon_code,
				'company_id' => $company_id
				)
			));
		if (empty($entry)){return 0;}
		else {return $entry['OpenCampaign']['id'];}
	}
}