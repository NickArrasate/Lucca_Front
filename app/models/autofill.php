<?php

class Autofill extends AppModel {

    var $name = 'Autofill';
	
	var $validate = array(
		'content' => array(
			'maxlength' => array(
				'rule' => array('maxLength', 500),
				'message' => 'There is a maximum of 500 characters. Please use fewer characters'
			),
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter in an email'
			)
		)

	);
	
}