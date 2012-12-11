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
	
	private $main_menu_limit = 6;

	function get_name($item_type_id) {
		$type = $this->find('all', array(
			'conditions' => array(
				'ItemType.id' => $item_type_id
			)
		));

		return $type[0]['ItemType']['name'];
	}

	function get_menu_items(){
		$all_types = $this->find('all', array('order' => 'ItemType.sort asc', 'fields' => array('ItemType.id', 'ItemType.name'), 'recursive' => 0));
		$base_types = array();
		$over_base_types = array();
		$i = 0;
		foreach($all_types as $type){
			if($i < $this->main_menu_limit){
				$base_types[] = $type['ItemType'];
			}
			else{
				$over_base_types[] = $type['ItemType'];
			}
			$i++;
		}

		return array('base_types' => $base_types, 'over_base_types' => $over_base_types);
	}
}
