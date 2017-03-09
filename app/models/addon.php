<?php

class Addon extends AppModel {
    var $name = 'Addon';
	var $hasMany = array('Option');
	var $recursive = -1;
	var $hasOne = 'Item';
	
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The name field cannot be left blank'
			)
	));
	
	function get_name($addon_id) {
		
		$addon_name = $this->find('all', array(
			'fields' => array('name'),
			'conditions' => array(
				'Addon.id' => $addon_id
			)
		));
		
		return $addon_name[0]['Addon']['name'];
	}
}