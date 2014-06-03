<?php
App::uses('AppModel', 'Model');
/**
 * CacheData Model
 Set or Get stuff from cached_data table in database. 
 When we're retrieving cached data then we should look for data in memcache 
 first before querying cached data in the table. The table should be 
 queried if we don't find what we're looking for in memcache. 
 
 A management script should ensure that memcache contains what is in 
 the cached_data table, so we don't look up this table unless we need
 to set updata data, or we've queried after the memcache is expired but
 before it is re-populated. For now, when we update the cached data in 
 the table, it would update the memcache aswell with new values.
 *
 */
class CacheData extends AppModel {

	public $primaryKey = 'id';
	public $useTable = 'cached_data';
	
	public function is_valid_key_type($type)
	{
		if ( 'comps_select_list_with_fast_tracker'== $type
			|| 'company_ids_with_fast_tracker' == $type
			|| 'prod_map_for_recent_pdrop_list1'== $type
			|| 'prod_map_for_recent_pdrop_list2'== $type
			|| 'prod_map_for_recent_pdrop_list3'== $type
			|| 'prod_map_for_recent_pdrop_list4'== $type
			|| 'prod_map_for_recent_pdrop_list5'== $type
			|| 'prod_map_for_recent_pdrop_list6'== $type
			|| 'comp_map_for_recent_pdrop_list1' == $type
			|| 'comp_map_for_recent_pdrop_list2' == $type
			|| 'comp_map_for_recent_pdrop_list3' == $type
			|| 'comp_map_for_recent_pdrop_list4' == $type
			|| 'comp_map_for_recent_pdrop_list5' == $type
			|| 'comp_map_for_recent_pdrop_list6' == $type
			|| 'prodids_for_recent_pdrop_list1' == $type
			|| 'prodids_for_recent_pdrop_list2' == $type
			|| 'prodids_for_recent_pdrop_list3' == $type
			|| 'prodids_for_recent_pdrop_list4' == $type
			|| 'prodids_for_recent_pdrop_list5' == $type
			|| 'prodids_for_recent_pdrop_list6' == $type
			|| 'condition_list' == $type
		){
			return true;
		}
		return false;
	}
	
	/*	What it does - Sets a cached data associated to a key in DB's cache table, as
			well as in the file/mem cache. File/mem cache is updated only if the DB's cache
			table is updated successfully.
		$key - Key for the data to be written.
		$data - Data to write for the key given.
		$is_json - Is input data JSON formatted or not ?
		$cache_group - Name of file/mem cache, we should look for the key in.
	*/
	public function set_data_by_key($key, $data, $is_json, $cache_group)
	{		
		// If it is not a json data, encode in json format
		if(!$is_json){
			$data = json_encode($data);
		}
		
		$this->id = $key;
		$entry=array(
			'CacheData'=>array('id'=>$key,'val'=>$data, 'update_timestamp'=>time())
			);
		$saved = $this->save($entry, true, array('val','update_timestamp'));
		if (!empty($saved)){
			Cache::write($key, $data, $cache_group);
			return true;
		}
		return false;
	}
	
	/*	What it does - Looks for a key in file/mem cache first. If it is not found, then
			looks for the key in DB's cache table. Returns data if found, or else it will
			return false.
		$key - Key to look for
		$return_json - Should we return data in json format or not ? We write json data in
		    DB's cache table and file/mem-cache. If $return_json is set to true, it will
			json decode in associative array format before returning the data. If the written
			data was an object instead of an assoc array, json_decode in assoc array format 
			may not work right.				
		$cache_group - Name of file/mem cache, we should look for the key in.
		$update_file_mem_cache - Should we update the file/mem cache if data is not found
			there and we retrieve it from DB's cache table.
	*/
	public function get_data_by_key($key, $return_json, $cache_group, $update_file_mem_cache)
	{
		
		$json_data = Cache::read($key, $cache_group);
		
		if(!$json_data){
			$entry = $this->find('first', array(
				'recursive'=>-1,
				'conditions'=>array('id'=>$key)
			));
			if (!empty($entry)){
				$json_data = $entry['CacheData']['val'];
				if($update_file_mem_cache){
					Cache::write($key, $json_data, $cache_group);
				}
			}
		}
		
		if (!$json_data){
			return "";
		}
		
		if ($return_json){
			return $json_data;
		}else{
			$data = json_decode($json_data, true);
			return $data;
		}
		
	}

}