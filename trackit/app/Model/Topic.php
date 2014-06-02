<?php
App::uses('AppModel', 'Model');
/**
 * Topic Model
 *
 */
class Topic extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	
	public function getTopicList()
	{
		$topics = $this->find('all');
		$topicdata = array();
		
		foreach ($topics as $k => $topic)
		{
			$topicdata[$topic['Topic']['id']] = array ('name' => $topic['Topic']['name']);
		}
		
		return $topicdata;
	}
}
