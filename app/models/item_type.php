<?php

class ItemType extends AppModel {
    var $name = 'ItemType';
	var $hasMany = 'Item';
	var $cacheQueries = true;
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The name field cannot be left blank',
				'required' => true,
			),
		),
	);


	function get_name($item_type_id) {
		$type = $this->find('all', array(
			'conditions' => array(
				'ItemType.id' => $item_type_id
			)
		));

		return $type[0]['ItemType']['name'];
	}
}