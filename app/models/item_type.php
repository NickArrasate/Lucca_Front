<?php

class ItemType extends AppModel {
    var $name = 'ItemType';
	var $hasMany = 'Item';
	var $cacheQueries = true;
	
	
	function get_name($item_type_id) {
		$type = $this->find('all', array(
			'conditions' => array(
				'ItemType.id' => $item_type_id 
			)
		));
		
		return $type[0]['ItemType']['name'];
	}
}
