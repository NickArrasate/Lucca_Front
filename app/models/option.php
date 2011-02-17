<?php

class Option extends AppModel {
    var $name = 'Option';
	var $belongsTo  = 'Addon';
	
	
	var $validate = array(
		'price' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The price field cannot be left blank'
			)
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The name field cannot be left blank'
			)
		),
		'sku' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The sku field cannot be left blank'
			),
			'isUnique' => array(
				'rule' => 'notEmpty',
				'message' => 'This SKU has been taken. Please try again'
			),
		)
	);
	
	function get_details($option_id) {
	
		$option_details = $this->find('all', array(
			'fields' => array('price', 'sku', 'name', 'addon_id'),
			'conditions' => array(
				'Option.id' => $option_id
			)
		));
		
		return $option_details[0]['Option'];
	
	}
	
	function get_price($option_id) {
	
		$option_details = $this->find('all', array(
			'fields' => array('price'),
			'conditions' => array(
				'Option.id' => $option_id
			)
		));
		
		return $option_details[0]['Option']['price'];
	}
	
}