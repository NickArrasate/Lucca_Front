<?php

class ItemVariation extends AppModel {

    var $name = 'ItemVariation';
	var $belongsTo = 'Item';
	var $cacheQueries = true;
	
	var $validate = array(
		'price' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The price field cannot be left blank'
			),
			'number' => array(
				'rule' => '/^[0-9]+(\.[0-9]{1,2})?$/i',
				'message' => 'Use only numbers and decimals for the price'
			)
		),
		'sku' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The price field cannot be left blank'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Please try again, SKU was not unique'
			)
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The name field cannot be left blank'
			)
		),
		/*
		'quantity' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The price field cannot be left blank'
			),
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please use a number for the quantity'
			)
		)
		*/
	);
	
	// get an accurate price from the database to add to the order.
		
	function get_price($item_variation_id) {
	
		$item = $this->find('all', array(
				'fields' => array('ItemVariation.price'),
				'conditions' => array(
					'ItemVariation.id' => $item_variation_id
				)
		));
		
		return $item[0]['ItemVariation']['price'];
	}
	
	function get_name($item_variation_id) {
	
		//return $item_variation_id;
		
		$item = $this->find('all', array(
				'fields' => array('ItemVariation.name'),
				'conditions' => array(
					'ItemVariation.id' => $item_variation_id
				)
		));
		
		//return count($item);
		return $item[0]['ItemVariation']['name'];
		
	}
	
}