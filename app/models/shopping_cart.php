<?php

class ShoppingCart extends AppModel {
    var $name = 'ShoppingCart';
	
	var $useTable = false;
	
	var $validate = array(
		'quantity' => array(
			'numeric' => array(
				'rule' => '/^[1-9]{0,2}$/',
				'message' => 'Requested quantities limited to 1-99 items'
			),
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter in an email'
			)
		)
	);
	
}