<?php

class ItemCategory extends AppModel {
    var $name = 'ItemCategory';
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

	function get_category_name($id) {
		$this->id = $id;
		return $this->field('name');
	}
}