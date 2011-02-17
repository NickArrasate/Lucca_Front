<?php

class Person extends AppModel {

    var $name = 'Person';
	//var $hasMany = 'Order';
	var $hasOne = array('Order','Creditcard');
	
	var $validate = array(
		'first_name' => array(
			'onlyLetters' => array(
				'rule' => '/^[a-z]*$/i',
				'message' => 'First name must only contain letters.'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		'last_name' => array(
			'onlyLetters' => array(
				'rule' => '/^[a-z]*$/i',
				'message' => 'Last names must only contain letters .'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		'address_1' => array(
			'address' => array(
				'rule' => '/^.{2,60}$/i',
				'message' => 'Addresses can only be between 2 and 60  characters.'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		'address_2' => array(
			'address' => array(
				'rule' => '/^.{0,60}$/i',
				'message' => 'Addresses can only be between 2 and 60 characters.'
			)
		),
		'city' => array(
			'onlyLetters' => array(
				'rule' => '/^([a-z]| )*$/i',
				'message' => 'Cities can only contain letters and spaces.'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		'state' => array(
			'onlyLetters' => array(
				'rule' => '/^[a-z]{2}$/i',
				'message' => 'For state names, only use the two-letter abbreviation.'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		'zipcode' => array(
			'zipcodeCheck' => array(
				'rule' => array('postal', null, 'us'),
				'message' => 'Zipcode is not a valid US zipcode.'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		/*
		'phone_number' => array(
			'phonenumberCheck' => array(
				'rule' => array('phone', null, 'us'),
				'message' => 'The phone number provided is not a valid US number'
			)
		),
		*/
		'email' => array(
			'emailCheck' => array(
				'rule' => array('email'),
				'message' => 'Email provided is not valid.'
				
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		)
	);
	
	function get_customer_name($person_id) {
	
		$customer_name = $this->find('all', array(
			'fields' => array('Person.first_name', 'Person.last_name'),
			'conditions' => array(
				'Person.id' => $person_id
			)
		));
		return $customer_name[0]['Person'];
	}
	
}
