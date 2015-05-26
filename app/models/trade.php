<?php
// file: /app/models/trade.php
class Trade extends AppModel {
	var $name = 'Trade';
	var $validate = array(
		'name'=>array(
			'rule' => 'alphaNumeric',
			'required'=>true,
			'allowEmpty'=>false,
			'message'=>'Please enter your Username'
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter your Password',
			'allowEmpty' => false,
			'required' => true,
			),
			'isConfirmed' => array(
				'rule' => array('isConfirmed'),
				'message' => 'Password not equal with Password Confirm',
				'required' => true,
			),
		),
		'password_confirm' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Password Confirm is required',
			'allowEmpty' => false,
			'required' => true,
			),
			'isConfirmed' => array(
				'rule' => array('isConfirmed'),
				'message' => 'Password Confirm not equal with Password',
				'required' => true,
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Email is required',
			),
			'email' => array(
				'rule' => array('email', true),
				'message' => 'Email is invalid',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This email has already been taken',
			),
		),
		'company_name' => array(
			'rule' => 'notEmpty',
			'message' => 'Please enter company name'
		),
		'address' => array(
			'rule' => 'notEmpty',
			'message' => 'Please enter address'
		),
		'phone' => array(
			'rule' => array('phone', null, 'us'),
		),
		'resale' => array(
			'rule' => 'notEmpty',
			'message' => 'Please enter resale #'
		)
	);

	public function isConfirmed($check) {
		if (array_key_exists('password', $this->data[$this->alias]) && array_key_exists('password_confirm', $this->data[$this->alias]) &&
			$this->data[$this->alias]['password'] === $this->data[$this->alias]['password_confirm']
		) {
			return true;
		}
		return false;
	}

}
