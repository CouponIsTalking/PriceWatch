<?php
App::uses('AppModel', 'Model');
/**
 * Queue Model
 *
 */
class Queue extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'queue';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'ocr_id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ocr_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'created' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'processed' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	public function queueit($ocr_id)
	{
		$queued_item = $this->find('first', array(
				'conditions' => array(
					'ocr_id' => $ocr_id
					),
				'recursive' => -1
			));
		if ( !empty($queued_item['Queue']) )
		{
			return $queued_item;
		}
		
		$this->create();
		$qitem = array('Queue' => array());
		$qitem['Queue']['created'] = date("Y-m-d H:i:s");
		$qitem['Queue']['ocr_id'] = $ocr_id;
		$qitem['Queue']['processed'] = 0;
		if ($this->save($qitem))
		{
			$qitem['Queue']['id'] = $this->id;
			return $qitem;
		}
		
		return null;
	}
	
	public function clearit($ocr_id)
	{
		$queued_item = $this->find('first', array(
				'conditions' => array(
					'ocr_id' => $ocr_id
					),
				'recursive' => -1
			));
		
		if ( !empty($queued_item['Queue']) )
		{
			$this->id = $queued_item['Queue']['id'];
			$this->delete();
			return true;
		}
		return false;
	}
	
	public function getRawQueuedItems()
	{
		$qitems = $this->find('all', 
					array(
						'recursive'=>-1
						)
				);
		return $qitems;
	}
}