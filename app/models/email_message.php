<?php

class EmailMessage extends AppModel {
    var $name = 'EmailMessage';
	
	var $useTable = false;
	
	var $validate = array(
		'address' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please enter in a valid email'
			),
			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter in an email'
			)
		),
		'subject' => array(
			'subject' => array(
				'rule' => array('between', 0, 140),
				'message' => 'Please make sure your email subject is between 5 and 140 characters'
			)
		)
		/*
		,
		'message' => array(
			'subject' => array(
				'rule' => array('maxLength', 500),
				'message' => 'Please make sure your email message is under 500 characters'
			)
		)
		*/

	);
	
}