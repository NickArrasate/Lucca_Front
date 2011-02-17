<?php

class Item extends AppModel {

    var $name = 'Item';
	var $hasMany = array('ItemVariation', 'ItemImage');
	var $belongsTo = array('ItemType', 'ItemCategory', 'InventoryLocation');
	var $cacheQueries = true;

	
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The name field cannot be left blank'
			)
		),
		'item_type_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please choose an item category'
			)
		),
		'item_category_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please choose an item subcategory'
			)
		),
		'inventory_location_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please choose a location'
			)
		)
		
	);
	
	function get_status_count($item_type_id ='', $status) {
		
		$count = $this->find('count', array(
			'conditions' => array(
				'Item.status' => $status
			)
		));
			
		
		return $count;
	}

}