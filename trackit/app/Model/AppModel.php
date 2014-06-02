<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	/*
	 $options is like array(
		'cat_name'=><cat_name> // like model name,
		'strip_cat_name'=><true/false> // should cat name or model name be stripped 
							// in re-indexed array
	 );
	*/
	public function ReindexOn($data, $index_field, $options){
		
		if(empty($data)){return array();}
		
		if(!empty($options['cat_name'])){
			$cat_name=$options['cat_name']; 
			// should strip cat name ?
			if(!empty($options['strip_cat_name'])){$strip_cat_name=true;}
			else{$strip_cat_name=false;}
		}
		else{$cat_name="";}
		
		$to_ret = array();		
		if (""!=$cat_name){$has_cat = true;} else{$has_cat = false;}
		
		foreach($data as $index=>$per_data){
			if ($has_cat){
				if ($strip_cat_name){
					$to_ret[$per_data[$cat_name][$index_field]] = $per_data[$cat_name];
				}else{
					$to_ret[$per_data[$cat_name][$index_field]] = $per_data;
				}
			}else{
				$to_ret[$per_data[$index_field]] = $per_data;
			}
		}
		
		return $to_ret;
	}
	
	public function getUniqueFields($data, $field_name, $cat_name=""){
		
		$field_vals = array();
		
		if(empty($data)){return $field_vals;}
		
		$to_ret = array();		
		if (empty($cat_name)){$has_cat = false;} else{$has_cat = true;}
		
		foreach($data as $index=>$per_data){
			if($has_cat){
				$field_vals[] = $per_data[$cat_name][$field_name];
			}else{
				$field_vals[] = $per_data[$field_name];
			}
		}
		$field_vals = array_unique($field_vals);
		
		return $field_vals;
	}
}
