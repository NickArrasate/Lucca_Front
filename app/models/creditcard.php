<?php

class CreditCard extends AppModel {

    var $name = 'Creditcard';
	var $belongsTo = array('Person');
	var $hasMany = 'Order';
	
	var $validate = array(
		'type' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		
		'number' => array(
			
			'ccNumber' => array(
				'rule' => 'cc',
				'message' => 'The credit card number you supplied was invalid.'
			),
			
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
			
		),
		
		'expiration_date_month' => array(
			'expirationDate' => array(
				'rule' => '/^[0-9]{2}$/i',
				
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		'expiration_date_year' => array(
			'expirationDate' => array(
				'rule' => '/^[0-9]{4}$/i',
				
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		),
		
		'security_code' => array(
			'threeNumbers' => array(
				'rule' => '/^[0-9]{3}$/i',
				'message' => 'Can only be 3 digit numbers'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank'
			)
		)
	);
	
	function create() {
	
		for($i=2009; $i<2019; $i++) {
			$years[] = $i;
		}
	
		$cc = array(
			'type' => array(
				'Visa', 'MasterCard', 'AMEX'
			), 
			'month' => array(
				'01' => 'January',
				'02' => 'February',
				'03' => 'March',
				'04' => 'April',
				'05' => 'May',
				'06' => 'June',
				'07' => 'July',
				'08' => 'August',
				'09' => 'September',
				'10' => 'October',
				'11' => 'November',
				'12' => 'December',
			),
			'year' => array(
				$years
			)
		);
	
		return $cc;
	}
	
}



